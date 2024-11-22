<?php

namespace App\Http\Controllers\Inventory\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Models\Depot_item_log;
use App\Models\Depot_item_request;
use Illuminate\Support\Facades\Session;


class DepotItemReqController extends Controller
{
    public function itemRequestList(Request $request)
    {
        if ($request->input('project') != null) {
            $projectId = $request->input('project');

            $result = Depot_item_request::where('dil_project_id', $projectId)->join('items', 'depot_item_requests.dil_item_code', '=', 'items.item_code')
                ->get(['depot_item_requests.*', 'items.*']);

            return Datatables::of($result)
                ->addIndexColumn()
                ->addColumn('delete', function ($row) {
                    $btn = '<button class="btn btn-primary btn-sm delete"><i class="tim-icons icon-trash-simple"></i></button >';
                    return $btn;
                })
                ->addColumn('update', function ($row) {
                    $btn = '<button class="btn btn-primary btn-sm update"><i class="tim-icons icon-cloud-upload-94"></i></button >';
                    return $btn;
                })
                ->addColumn('reserve', function ($row) {
                    $resev = Depot_item_log::where('dil_project_id', $row['dil_project_id'])
                        ->where('dil_rec_type', 'ASSIGNED')
                        ->join('depot_items', 'depot_items.di_id', '=', 'depot_item_log.dil_di_id')
                        ->where('di_item_code', $row['dil_item_code'])->sum('dil_qty');
                        return $resev;
                })          
                ->rawColumns(['update', 'delete','reserve'])
                ->make(true);
        } else {
            $result = null;
            return Response()->json($result);
        }
    }



    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'dil_item_code' => 'required',
            'dil_project_id' => 'required',
            'dil_qty' => 'required'
        ]);

        $input = $request->all();
        $user = Session::get('user');

        //var_dump($user);
        $input['dil_update_by'] = $user->USERLOGINNAME;
        $input['dil_update_date'] = DB::raw("SYSDATE");

        Depot_item_request::create($input);

        $notify_msg = array(
            'message' => 'CREATED Successfully!',
            'alert-type' => 'success'
        );

        return Response()->json($notify_msg);
    }



    public function delete(Request $request)
    {
        $itemid = $request->input('dil_item_code');
        $projectId = $request->input('project');
        $return = Depot_item_request::where('dil_project_id', $projectId)->where('DIL_ITEM_CODE', $itemid)->delete();

        if ($return) {
            $notify_msg = array(
                'responce' => true,
                'message' => 'Delete Success!',
                'alert-type' => 'success'
            );
        } else {
            $notify_msg = array(
                'responce' => false,
                'message' => "Delete Failed.",
                'alert-type' => 'fail'
            );
        }
        return Response()->json($notify_msg);
 
    }



    public function update(Request $request)
    {
        $itemid = $request->input('dil_item_code');
        $projectId = $request->input('project');
        $qty = $request->input('qty');
        $return = Depot_item_request::where('dil_project_id', $projectId)->where('DIL_ITEM_CODE', $itemid)->update(['dil_qty'=>$qty]);

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
    
}
