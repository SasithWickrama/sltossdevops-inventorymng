<?php

namespace App\Http\Controllers\Inventory\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Models\Depot_item_allocation;
use App\Models\Depot_item_request;
use Illuminate\Support\Facades\Session;


class DepotItemAllocateController extends Controller
{
    public function itemRequestList(Request $request)
    {
        if ($request->input('project') != null) {
            $projectId = $request->input('project');

            // $invstatus = DB::select("SELECT *  FROM PROJECT_DEPOT,PROJECTS_INVENTORY_STATUS WHERE PD_PROJECT_ID = '$projectId'
            // AND TRIM(PD_STATUS) = PI_STATUS");
            

            $result = DB::select("SELECT B.* ,ITEM_DISCRIPTION , (nvl(PIA_QTY,'0') -nvl(PIA_RESV_QTY,'0')) ALQTY, DIA_ITEM_CODE
            DIA_PROJECT_ID ,  nvl(DIA_INITQTY,'0') DIA_INITQTY , nvl(DIA_REQQTY,'0') DIA_REQQTY,
            nvl(DIA_FINALQTY,'0') DIA_FINALQTY , nvl(DIA_TOTUSED,'0') DIA_TOTUSED , nvl(DIA_TOTWASTE,'0') DIA_TOTWASTE,
            nvl(DIA_TOTCOILED,'0') DIA_TOTCOILED            
            FROM MAIN_PROJECT_HIERARCHY A, MAIN_PROJECT_ITEM_ALLOCATIONS B , ITEMS C ,DEPOT_ITEM_ALLOCATIONS d
            WHERE JOB_ID = '$projectId'
            AND PROJECT_ID = PIA_PROJECT_ID
            AND ITEM_CODE = PIA_ITEM_CODE
            and PIA_ITEM_CODE = DIA_ITEM_CODE(+)");

            return Datatables::of($result)
                ->addIndexColumn()
                ->addColumn('delete', function ($row) {
                    $btn = '<button class="btn btn-primary btn-sm delete"><i class="tim-icons icon-trash-simple"></i></button >';
                    return $btn;
                })
                ->addColumn('save', function ($row) {
                    $btn = '<button class="btn btn-primary btn-sm update" ><i class="tim-icons icon-cloud-upload-94"></i></button >';
                    return $btn;
                })             
                ->rawColumns(['save', 'delete'])
                ->make(true);
        } else {
            $result = null;
            return Response()->json($result);
        }
    }



    public function storeReserve(Request $request)
    {

        $input = $request->all();
        $user = Session::get('user');

        //var_dump($user);
        $input['dia_reqqty'] = $request->input('dia_initqty');
        $input['dia_finalqty'] = $request->input('dia_initqty');
        $input['dia_update_by'] = $user->USERLOGINNAME;
        try {
            Depot_item_allocation::create($input);
            $notify_msg = array(
                'responce' => true,
                'message' => 'Insert Successfully!',
                'alert-type' => 'success'
            );
        } catch (\Exception $ex) {
            if ($ex->getCode() == 1) {
                $msg = "Cannot Insert Duplicate Record.";

                $return = Depot_item_allocation::where('dia_project_id', $request->input('dia_project_id'))
                    ->where('dia_item_code', $request->input('dia_item_code'))
                    ->update([
                        'dia_initqty' => $request->input('dia_initqty'),
                        'dia_reqqty' => $request->input('dia_initqty'),
                        'dia_finalqty' => $request->input('dia_initqty'),
                        'dia_update_by' => Session::get('user')->USERLOGINNAME                        
                    ]);

                if ($return) {
                    $notify_msg = array(
                        'responce' => true,
                        'message' => 'Update Success!',
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
            if ($ex->getCode() == 20002) {
                $msg = "Cannot Reserve More than Available Quantity.";
            } else {
                $msg = "Error Code :" . $ex->getCode();
            }
            $notify_msg = array(
                'responce' => false,
                'message' => $msg,
                'alert-type' => 'fail'
            );
        }

        return Response()->json($notify_msg);
    }


    public function updateRequest(Request $request)
    {

        $return = Depot_item_allocation::where('dia_project_id', $request->input('dia_project_id'))
            ->where('dia_item_code', $request->input('dia_item_code'))
            ->update([
                'dia_reqqty' => $request->input('dia_reqqty'),
                'dia_update_by' => Session::get('user')->USERLOGINNAME
            ]);

        if ($return) {
            $notify_msg = array(
                'responce' => true,
                'message' => 'Update Success!',
                'alert-type' => 'success'
            );
        } else {
            $notify_msg = array(
                'responce' => false,
                'message' => 'Insert Successfully!',
                'alert-type' => 'fail'
            );
        }



        return Response()->json($notify_msg);
    }


    public function updateConfirm(Request $request)
    {

        $return = Depot_item_allocation::where('dia_project_id', $request->input('dia_project_id'))
            ->where('dia_item_code', $request->input('dia_item_code'))
            ->update([
                'dia_finalqty' => $request->input('dia_finalqty'),
                'dia_update_by' => Session::get('user')->USERLOGINNAME
            ]);

        if ($return) {
            $notify_msg = array(
                'responce' => true,
                'message' => 'Update Success!',
                'alert-type' => 'success'
            );
        } else {
            $notify_msg = array(
                'responce' => false,
                'message' => 'Insert Successfully!',
                'alert-type' => 'fail'
            );
        }



        return Response()->json($notify_msg);
    }



}
