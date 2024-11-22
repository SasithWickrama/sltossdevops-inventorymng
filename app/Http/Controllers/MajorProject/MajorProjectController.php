<?php

namespace App\Http\Controllers\MajorProject;

use App\Http\Controllers\Controller;
// use App\Models\Project;
use App\Models\Workgroup;
use App\Models\Major_project;
use App\Models\Major_projects_status;
use App\Models\Area;
// use App\Models\Item;
// use App\Models\Project_types;
// use App\Models\Project_hierarchy;
// use App\Models\Project_attributes;
// use App\Models\Project_depot;
use App\Models\Service_types;
// use App\Models\Depot;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class MajorProjectController extends Controller
{

    public function inbox()
    {
        $user = Session::get('user');
        $contractors = Workgroup::where('WGTYPE', 'EXTERNAL')->where('WGSTATUS', 'ACTIVE')->groupBy('wgname')->pluck('wgname');
        $proStatus =  Major_projects_status::groupBy('mps_status')->pluck('mps_status');
        $serviceType = Service_types::groupBy('svtype')->pluck('svtype');
        $docProsId = Major_project::whereNotNull('major_doc_pros_id')->orderBy('major_doc_pros_id')->pluck('major_doc_pros_id');
        $result = null;

        return view('majorproject.inboxMajorProject', compact('contractors', 'proStatus', 'serviceType', 'docProsId', 'user'));
        
    }

    public function majorProjectRecords(Request $request)
    {
        $project_status = "";
        $service_type = "";
        $doc_pros_id = "";
        $startdate = "";
        $enddate = "";
        $whereclause = false;

        $queary = "SELECT * FROM MAJOR_PROJECTS";

        if ($request->input('project_status') != null) {
            $project_status = $request->input('project_status');
            $whereclause = true;
            $queary = $queary. " WHERE MAJOR_PROS_STATUS = '$project_status'";
        }
        if ($request->input('service_type') != null) {
            if($whereclause){
                $service_type = $request->input('service_type');
                $queary = $queary . " AND MAJOR_PROS_SVTYPE = '$service_type' ";
            }else{
                $service_type = $request->input('service_type');
                $whereclause = true;
                $queary = $queary . " WHERE MAJOR_PROS_SVTYPE = '$service_type' ";
            }
            
        }
        if ($request->input('doc_pros_id') != null) {
            if($whereclause){
                $doc_pros_id = $request->input('doc_pros_id');
                $queary = $queary . " AND MAJOR_DOC_PROS_ID = '$doc_pros_id' ";
            }else{
                $whereclause = true;
                $doc_pros_id = $request->input('doc_pros_id');
                $queary = $queary . " WHERE MAJOR_DOC_PROS_ID = '$doc_pros_id' ";
            }
            
        }
        if ($request->input('startdate') != null) {
            if($whereclause){
                $startdate = $request->input('startdate');
                $queary = $queary . " AND MAJOR_PROS_CREATEDATE > TO_DATE('$startdate 00:00:00', 'YYYY/MM/DD HH24:MI:SS ') ";
            }else{
                $whereclause = true;
                $startdate = $request->input('startdate');
                $queary = $queary . " WHERE MAJOR_PROS_CREATEDATE > TO_DATE('$startdate 00:00:00', 'YYYY/MM/DD HH24:MI:SS ') ";
            }
            
        }
        if ($request->input('enddate') != null) {
            if($whereclause){
                $enddate = $request->input('enddate');
                $queary = $queary . " AND MAJOR_PROS_CREATEDATE < TO_DATE('$enddate 23:59:59', 'YYYY/MM/DD HH24:MI:SS ') ";
            }else{
                $whereclause = true;
                $enddate = $request->input('enddate');
                $queary = $queary . " WHERE MAJOR_PROS_CREATEDATE < TO_DATE('$enddate 23:59:59', 'YYYY/MM/DD HH24:MI:SS ') ";
            }
            
        }

        $result = DB::select($queary);
        return Datatables::of($result)
            ->addIndexColumn()
            ->addColumn('view', function ($row) {
                
                $btn = '<form action="' . route('updateMajorProjectView') . '" method="POST"> 
                    <input type="hidden" name="_token" value=" ' . csrf_token() . ' ">
                    <input type="hidden" name="major_pros_id"  value="' . $row->major_pros_id . '" >
                    <button type="submit"  class="edit btn btn-primary btn-sm">View</button>
                    </form>';
               
                return $btn;
            })
            ->rawColumns(['view'])
            ->make(true);
    }

    public function createMajorProject()
    {
        // $ProjectStatus = Major_projects_status::groupBy('ps_status')->pluck('ps_status');
        $serviceType = Service_types::groupBy('svtype')->pluck('svtype');
        // $contractors = Workgroup::where('WGTYPE', 'EXTERNAL')->where('WGSTATUS', 'ACTIVE')->groupBy('wgname')->pluck('wgname');
        $lea = Area::groupBy('lea')->orderBy('lea', 'ASC')->pluck('lea');
        $user = Session::get('user');
        return view('majorproject.createMajorProject', compact('serviceType','lea','user'));
        
    }

    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'MAJOR_DOC_PROS_ID' => 'required',
            'MAJOR_PROS_SVTYPE' => 'required',
            'MAJOR_PROS_TARGET_ENDDATE' => 'required',
            'MAJOR_PROS_NAME' => 'required'
        ]);

        $test_key = DB::select('SELECT MAJOR_PROJECTS_SEQ.NEXTVAL as seq_gen FROM DUAL');

        $input = $request->all();

        $input['MAJOR_PROS_CREATEDATE'] = DB::raw("SYSDATE") ;
        $input['MAJOR_PROS_STATUSDATE'] = DB::raw("SYSDATE") ;
        $input['MAJOR_PROS_STATUS'] = 'INITIATED' ;
        $input['MAJOR_PROS_STATUS_REASON'] = 'New Major Project' ;
        $input['MAJOR_PROS_ID']  = $test_key[0]->seq_gen;

        try{

            $major_project = Major_project::create($input);

            $notify_msg = array(
                'message' => 'Project CREATED Successfully!',
                'alert-type' => 'success'
            );

        } catch (\Exception $ex) {
            if($ex->getCode() == 1){
                $msg = "Cannot Insert Duplicate Record.";
            }else{
                $msg = "Error Code :".$ex->getCode();
            }
            $notify_msg = array(
                'responce' => false ,
                'message' => $msg,
                'alert-type' => 'fail'
            );
        }
        return Response()->json($notify_msg);
    }

    public function updateMajorProject(Request $request)
    {
        if(! $request->input('major_pros_id') == null){
            $majorProjectId = (int)$request->input('major_pros_id');
            $majorProject = Major_project::where('MAJOR_PROS_ID', $majorProjectId)->get();
            $projectList =  Major_project::orderBy('major_pros_id')->pluck('major_pros_id','major_pros_name');
            $ProjectStatus = Major_projects_status::groupBy('mps_status')->pluck('mps_status');
            $serviceType = Service_types::groupBy('svtype')->pluck('svtype');
            $lea = Area::groupBy('lea')->pluck('lea');
            $user = Session::get('user');
            return view('majorproject.updateMajorProject', compact('projectList','ProjectStatus','serviceType','lea','user','majorProject'));
        }else{
            $majorProject = null;
            $projectList =  Major_project::orderBy('major_pros_id')->pluck('major_pros_id','major_pros_name');
            $ProjectStatus = Major_projects_status::groupBy('mps_status')->pluck('mps_status');
            $serviceType = Service_types::groupBy('svtype')->pluck('svtype');
            $lea = Area::groupBy('lea')->pluck('lea');
            $user = Session::get('user');
            return view('majorproject.updateMajorProject', compact('projectList','ProjectStatus','serviceType','lea','user','majorProject'));
        }
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'MAJOR_PROS_SVTYPE' => 'required',
            'MAJOR_PROS_TARGET_ENDDATE' => 'required',
            'MAJOR_PROS_NAME' => 'required',
            'MAJOR_PROS_STATUS' => 'required'
        ]);

        $majorProsId = (int)$request->input('MAJOR_PROSS_ID');

        $input['MAJOR_PROS_SVTYPE'] =$request->input('MAJOR_PROS_SVTYPE');
        $input['MAJOR_PROS_TARGET_ENDDATE'] =$request->input('MAJOR_PROS_TARGET_ENDDATE');
        $input['MAJOR_DOC_PROS_ID'] =$request->input('MAJOR_DOC_PROS_ID');
        $input['MAJOR_PROS_NAME'] =$request->input('MAJOR_PROS_NAME');
        if($request->input('MAJOR_PROS_STATUS') != null){
            $input['MAJOR_PROS_STATUS'] = $request->input('MAJOR_PROS_STATUS');
            $input['MAJOR_PROS_STATUSDATE'] = DB::raw("SYSDATE") ;
            $input['MAJOR_PROS_STATUS_REASON'] = 'In-progress Project' ;
        }


        try{

            $majorProject = Major_project::where('MAJOR_PROS_ID', $majorProsId)->update($input);
           
            $notify_msg = array(
                'message' => 'Project UPDATED Successfully!',
                'alert-type' => 'success'
            );

        } catch (\Exception $ex) {
            if($ex->getCode() == 1){
                $msg = "Cannot Insert Duplicate Record.";
            }else{
                $msg = "Error Code :".$ex->getCode();
            }
            $notify_msg = array(
                'responce' => false ,
                'message' => $msg,
                'alert-type' => 'fail'
            );
        }
        return Response()->json($notify_msg);

    }

    public function childJobs(Request $request){
        $majorProjectId = $request->input('major_project');
        $result = DB::select("SELECT *  FROM PROJECTS WHERE PROS_ID IN (SELECT JOB_ID FROM MAIN_PROJECT_HIERARCHY WHERE PROJECT_ID = '$majorProjectId' AND JOB_TYPE = 'CHILD')");
        return Datatables::of($result)
            ->addIndexColumn()
            ->addColumn('view', function ($row) {
               
                    $btn = '<form action="' . route('updateProjectView') . '" method="POST"> 
                        <input type="hidden" name="_token" value=" ' . csrf_token() . ' ">
                        <input type="hidden" name="pros_id"  value="' . $row->pros_id . '" >
                        <button type="submit"  class="edit btn btn-primary btn-sm">View</button>
                        </form>';
                
                return $btn;
            })
            ->rawColumns(['view'])
            ->make(true);
    }

    public function childJobsForParentJob(Request $request){
        $parentProjectId = $request->input('parent_project');
        $result = DB::select("  SELECT *  FROM PROJECTS,PROJECT_HIERARCHY
                                WHERE PROS_ID = CHILD_ID
                                AND PARENT_ID = '$parentProjectId'");
        return Datatables::of($result)
            ->addIndexColumn()
            ->addColumn('view', function ($row) {
               
                    $btn = '<form action="' . route('updateProjectView') . '" method="POST"> 
                        <input type="hidden" name="_token" value=" ' . csrf_token() . ' ">
                        <input type="hidden" name="pros_id"  value="' . $row->pros_id . '" >
                        <button type="submit"  class="edit btn btn-primary btn-sm">View</button>
                        </form>';
                
                return $btn;
            })
            ->rawColumns(['view'])
            ->make(true);
    }

    public function parentJobs(Request $request){
        $majorProjectId = $request->input('major_project');
        $result = DB::select("  SELECT P.* , (select count(CHILD_ID) from PROJECT_HIERARCHY 
                                WHERE PARENT_ID = P.PROS_ID
                                GROUP BY PARENT_ID) CHILD_COUNT
                                FROM PROJECTS P
                                WHERE PROS_ID IN (SELECT JOB_ID FROM MAIN_PROJECT_HIERARCHY WHERE PROJECT_ID = '$majorProjectId' AND JOB_TYPE = 'PARENT')");
        return Datatables::of($result)
            ->addIndexColumn()
            ->addColumn('view', function ($row) {
               
                    $btn = '<form action="' . route('updateProjectView') . '" method="POST"> 
                        <input type="hidden" name="_token" value=" ' . csrf_token() . ' ">
                        <input type="hidden" name="pros_id"  value="' . $row->pros_id . '" >
                        <button type="submit"  class="edit btn btn-primary btn-sm">View</button>
                        </form>';
                
                return $btn;
            })
            ->rawColumns(['view'])
            ->make(true);
    }

    public function complete(Request $request)
    {

        $majorProsId = (int)$request->input('MAJOR_PROSS_ID');

        $input['MAJOR_PROS_STATUSDATE'] = DB::raw("SYSDATE") ;
        $input['MAJOR_PROS_STATUS'] = 'COMPLETED' ;
        $input['MAJOR_PROS_STATUS_REASON'] = 'Complete Project' ;

        $queary = " SELECT * from PROJECTS
                    WHERE PROS_ID IN (SELECT JOB_ID FROM MAIN_PROJECT_HIERARCHY WHERE PROJECT_ID = '$majorProsId' AND JOB_TYPE = 'PARENT')
                    MINUS
                    SELECT * from PROJECTS 
                    WHERE PROS_ID IN (SELECT JOB_ID FROM MAIN_PROJECT_HIERARCHY WHERE PROJECT_ID = '$majorProsId' AND JOB_TYPE = 'PARENT')
                    AND PROS_STATUS = 'COMPLETED'";
        $result = DB::select($queary);

        try{
            if(count($result) == 0){
                $gh = "can complete";
                $majorProject = Major_project::where('MAJOR_PROS_ID', $majorProsId)->update($input);

                $notify_msg = array(
                    'message' => 'Project Completed Successfully!',
                    'alert-type' => 'success'
                );

            }else{
                $gh = "cannot complete";

                $notify_msg = array(
                    'message' => 'Project Completion process is Fail.There are Parents Jobs which are not completed!',
                    'alert-type' => 'fail complete'
                );
            }
           
            

        } catch (\Exception $ex) {
            $msg = "Error Code :".$ex->getCode();

            $notify_msg = array(
                'responce' => false ,
                'message' => $msg,
                'alert-type' => 'fail'
            );
        }
        return Response()->json($notify_msg);
    }

}