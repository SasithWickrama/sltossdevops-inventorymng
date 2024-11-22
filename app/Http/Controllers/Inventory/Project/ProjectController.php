<?php

namespace App\Http\Controllers\Inventory\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Workgroup;
use App\Models\Projects_status;
use App\Models\Area;
use App\Models\Item;
use App\Models\Project_attribute;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Project_inventory_status;
use App\Models\Project_depot;

class ProjectController extends Controller
{
    /**
     * Show the Pending Projecyy details.
     *
     * @return \Illuminate\View\View
     */
    public function projects(Request $request)
    {

        $user = Session::get('user');
        $result = null;
        $items = null;
        $invstatus = null;
        if ($request->input('project') != null) {
            $items = Item::get();
            $projectId = $request->input('project');
            $result = Project::where('pros_id', $projectId)->get();
            $invstatus = DB::select("SELECT *  FROM PROJECT_DEPOT,PROJECTS_INVENTORY_STATUS WHERE PD_PROJECT_ID = '$projectId'
            AND TRIM(PD_STATUS) = PI_STATUS");
            $contractor = Project_attribute::where('pa_project_id', $projectId)->where('pa_name', 'CONTRACTOR')->first();
            $depot = Project_depot::where('pd_project_id', $projectId)->first();
            if (strcmp($user->USERWG, 'SLT') == 0 || strcmp($user->USERWG, $contractor->pa_value) == 0) {
                return view('Inventory.Project.project', compact('result', 'contractor', 'user', 'invstatus', 'items' ,'depot'));
            } else {
                return view('Inventory.Project.project', compact('result', 'user', 'items', 'invstatus'));
            }
        } else {
            return view('Inventory.Project.project', compact('result', 'user', 'invstatus', 'items'));
        }
    }

    public function projectInbox(Request $request)
    {
        $user = Session::get('user');
        $contractors = Workgroup::where('WGTYPE', 'EXTERNAL')->where('WGSTATUS', 'ACTIVE')->groupBy('wgname')->pluck('wgname');
        $projectstatus = Projects_status::groupBy('ps_status')->pluck('ps_status');
        $invstatus = Project_inventory_status::whereNotNull('pi_order')->orderby('pi_order')->pluck('pi_status');
        $lea = Area::groupBy('lea')->orderBy('lea', 'ASC')->pluck('lea');
        $result = null;
        return view('Inventory.Project.projectInbox', compact('contractors', 'invstatus', 'lea', 'user'));
    }


    public function projectsItems(Request $request)
    {
        if ($request->input('project') != null) {
            $projectId = $request->input('project');
            $status = $request->input('status');

            $result = DB::select("SELECT A.* ,NVL(ASSIGNED,0)-NVL(ALLUSED,0)-NVL(ALLWASTE,0) REMAINING  FROM (SELECT DISTINCT DI_ID , DI_ITEM_CODE \"Item Code\",DI_LOT_NO \"Lot Number\",  ITEM_DISCRIPTION Discription, ITEM_MESSUREMENT Measuerment ,  
            (select  SUM(DIL_QTY) from DEPOT_ITEM_RESEV x where (X.DIL_PROJECT_ID = '$projectId'
                        OR X.DIL_PROJECT_ID IN (select PARENT_ID  from PROJECT_HIERARCHY where CHILD_ID =  '$projectId')) AND X.DIL_DI_ID = DI_ID)  ASSIGNED,
                        (SELECT SUM(DIL_QTY)  FROM DEPOT_ITEM_LOG X WHERE X.DIL_PROJECT_ID = '$projectId' AND X.DIL_DI_ID = DI_ID AND DIL_REC_TYPE = 'USED') USED,
                        (SELECT SUM(DIL_QTY)  FROM DEPOT_ITEM_LOG X WHERE X.DIL_PROJECT_ID = '$projectId' AND X.DIL_DI_ID = DI_ID AND DIL_REC_TYPE = 'WASTE') WASTE,
                        (SELECT SUM(DIL_QTY)  FROM DEPOT_ITEM_LOG X WHERE (X.DIL_PROJECT_ID = '$projectId'
                        OR X.DIL_PROJECT_ID IN (select PARENT_ID  from PROJECT_HIERARCHY where CHILD_ID =  '$projectId')) AND X.DIL_DI_ID = DI_ID AND DIL_REC_TYPE = 'USED') ALLUSED,
                        (SELECT SUM(DIL_QTY)  FROM DEPOT_ITEM_LOG X WHERE (X.DIL_PROJECT_ID = '$projectId'
                        OR X.DIL_PROJECT_ID IN (select PARENT_ID  from PROJECT_HIERARCHY where CHILD_ID =  '$projectId')) AND X.DIL_DI_ID = DI_ID AND DIL_REC_TYPE = 'WASTE') ALLWASTE
                        FROM DEPOT_ITEMS , ITEMS
                        WHERE DI_ID IN (SELECT DIL_DI_ID FROM DEPOT_ITEM_RESEV  WHERE (DIL_PROJECT_ID = '$projectId'
                        OR DIL_PROJECT_ID IN (select PARENT_ID  from PROJECT_HIERARCHY where CHILD_ID =  '$projectId')))
                        and ITEM_CODE = DI_ITEM_CODE) A");

            if (strcmp($status, "COMPLEATED") == 0) {
                return Datatables::of($result)
                    ->addIndexColumn()
                    ->addColumn('update', function ($row) {
                        $btn = '';
                        return $btn;
                    })
                    ->rawColumns(['update'])
                    ->make(true);
            }
            if (strcmp($status, "INITIATED") == 0) {
                return Datatables::of($result)
                    ->addIndexColumn()
                    ->addColumn('update', function ($row) {
                        $btn = '<button  class="edit btn btn-primary btn-sm">Update</button>';
                        return $btn;
                    })
                    ->rawColumns(['update'])
                    ->make(true);
            }
        } else {
            $result = null;
            return Response()->json($result);
        }
    }


    public function projectRecords(Request $request)
    {
        $contractor = "";
        $lea = "";
        $status = "";
        $startdate = "";
        $enddate = "";
        if ($request->input('contractor') != null) {
            $contractor = $request->input('contractor');
            $queary = "SELECT *  FROM (SELECT A.*  ,
                        (SELECT PA_VALUE  FROM PROJECT_ATTRIBUTES WHERE PA_NAME = 'CONTRACTOR' AND PA_PROJECT_ID = PROS_ID) CONTRACTOR
                        FROM PROJECTS A), PROJECT_DEPOT
                        WHERE CONTRACTOR = '$contractor' 
                        AND PD_PROJECT_ID = PROS_ID";
        }
        if ($request->input('lea') != null) {
            $lea = $request->input('lea');
            $queary = $queary . " AND PROS_LEA ='$lea' ";
        }
        if ($request->input('status') != null) {
            $status = $request->input('status');
            $queary = $queary . " AND PD_STATUS ='$status' ";
        }
        if ($request->input('startdate') != null) {
            $startdate = $request->input('startdate');
            $queary = $queary . " AND PROS_CREATEDATE > TO_DATE('$startdate 00:00:00', 'YYYY/MM/DD HH24:MI:SS ') ";
        }
        if ($request->input('enddate') != null) {
            $enddate = $request->input('enddate');
            $queary = $queary . " AND PROS_CREATEDATE < TO_DATE('$enddate 23:59:59', 'YYYY/MM/DD HH24:MI:SS ') ";
        }


        $result = DB::select($queary);
        return Datatables::of($result)
            ->addIndexColumn()
            ->addColumn('view', function ($row) {
                if ($row->pros_status == 'INITIATED') {
                    $btn = '<form action="' . route('project') . '" method="POST"> 
                        <input type="hidden" name="_token" value=" ' . csrf_token() . ' ">
                        <input type="hidden" name="project"  value="' . $row->pros_id . '" >
                        <button type="submit"  class="edit btn btn-primary btn-sm">View</button>
                        </form>';
                } else {
                    $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">xxxxxx</a>';
                }
                return $btn;
            })
            ->rawColumns(['view'])
            ->make(true);
    }



    public function changeInvStatus(Request $request)
    {
        $projectId = $request->input('project');
        $status = $request->input('invstatus');
        $fozen = $request->input('fozen');
        $reject = $request->input('reasign');
        $type = $request->input('type');

        if (!$reject == "") {
            $result = DB::update("UPDATE PROJECT_DEPOT SET PD_STATUS = (SELECT  Pd_OLD_STATUS  FROM PROJECT_DEPOT WHERE PD_PROJECT_ID = '$projectId' ) , PD_OLD_STATUS = trim(PD_STATUS) , PD_CHECK_COUNT = PD_CHECK_COUNT +1  WHERE PD_PROJECT_ID = '$projectId'");
                
        }else if (!$fozen == "") {
            $result = DB::update("UPDATE PROJECT_DEPOT SET PD_OLD_STATUS = PD_STATUS  , PD_STATUS = 'FORZEN' WHERE PD_PROJECT_ID = '$projectId'");

            $result2 = DB::update("UPDATE PROJECT_DEPOT SET PD_OLD_STATUS = PD_STATUS  , PD_STATUS = 'FORZEN' WHERE PD_PROJECT_ID IN ( SELECT CHILD_ID FROM PROJECT_HIERARCHY WHERE PARENT_ID ='$projectId')");
                 
        } else {

            if (strcmp($status, "FORZEN") == 0) {
                $result = DB::update("UPDATE PROJECT_DEPOT SET PD_STATUS = (SELECT  Pd_OLD_STATUS  FROM PROJECT_DEPOT WHERE PD_PROJECT_ID = '$projectId' ) , PD_OLD_STATUS = trim(PD_STATUS)  WHERE PD_PROJECT_ID = '$projectId'");
                
                $result2 = DB::update("UPDATE PROJECT_DEPOT SET PD_STATUS = (SELECT  Pd_OLD_STATUS  FROM PROJECT_DEPOT WHERE PD_PROJECT_ID = '$projectId' ) , PD_OLD_STATUS = trim(PD_STATUS)  WHERE PD_PROJECT_ID  IN ( SELECT CHILD_ID FROM PROJECT_HIERARCHY WHERE PARENT_ID ='$projectId')");
            } else {

                $result = DB::update("UPDATE PROJECT_DEPOT SET PD_STATUS = (SELECT  PI_STATUS  FROM PROJECTS_INVENTORY_STATUS WHERE PI_ORDER = (SELECT PI_ORDER +1 FROM  PROJECTS_INVENTORY_STATUS WHERE PI_STATUS = '$status') ) , PD_OLD_STATUS = trim(PD_STATUS)  WHERE PD_PROJECT_ID = '$projectId'");
            }

            if($type == "PARENT" && $status == "SYN_ERP" ){
                $result2 = DB::update("UPDATE PROJECT_DEPOT SET  PD_OLD_STATUS = trim(PD_STATUS) ,PD_STATUS = 'COMPLEATED'  WHERE PD_PROJECT_ID  IN ( SELECT CHILD_ID FROM PROJECT_HIERARCHY WHERE PARENT_ID ='$projectId')");
            }
        }

        

        if ($result) {
            $notify_msg = array(
                'responce' => true,
                'message' => "Success",
                'alert-type' => 'success'
            );
        } else {
            $notify_msg = array(
                'responce' => false,
                'message' => "Update Failed.",
                'alert-type' => 'fail'
            );
           
        }
        return Response()->json($notify_msg);
    }
}
