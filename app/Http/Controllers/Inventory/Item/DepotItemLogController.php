<?php

namespace App\Http\Controllers\Inventory\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Models\Depot_item_log;
use App\Models\Depot_item_request;
use Illuminate\Support\Facades\Session;


class DepotItemLogController extends Controller
{
    public function reservedItemList(Request $request)
    {
        if ($request->input('dil_item_code') != null) {
            $itemid = $request->input('dil_item_code');
            $projectId = $request->input('project');
            $result = Depot_item_log::where('dil_project_id', $projectId)
                ->where('dil_rec_type', 'ASSIGNED')
                ->join('depot_items', 'depot_items.di_id', '=', 'depot_item_log.dil_di_id')
                ->where('di_item_code', $itemid)->get(['depot_item_log.*', 'depot_items.*']);

            return Datatables::of($result)
                ->addIndexColumn()
                ->addColumn('update', function ($row) {
                    $btn = '<button class="btn btn-primary btn-sm"><i class="tim-icons icon-cloud-upload-94"></i></button >';
                    return $btn;
                })
                ->addColumn('delete', function ($row) {
                    $btn = '<a href="javascript:void(0)" class="btn btn-primary btn-sm"><i class="tim-icons icon-trash-simple"></i></a>';
                    return $btn;
                })
                ->rawColumns(['update', 'delete'])
                ->make(true);
        } else {
            $result = null;
            return Response()->json($result);
        }
    }



    public function store(Request $request)
    {

        $input = $request->all();
        $user = Session::get('user');
        $input['dil_update_by'] = $user->USERLOGINNAME;
        $input['dil_update_date'] = DB::raw("SYSDATE");

        try {
            Depot_item_log::create($input);

            $notify_msg = array(
                'responce' => true,
                'message' => 'Insert Success!',
                'alert-type' => 'success'
            );
        } catch (\Exception $ex) {
            if ($ex->getCode() == 1) {
                $msg = "Cannot Insert Duplicate Record.";
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


    public function usageSummary(Request $request)
    {
        if ($request->input('item_code') != null) {
            $itemid = $request->input('item_code');
            $projectId = $request->input('project');
            $result = DB::select("SELECT * FROM
            ( select DI_LOT_NO , DI_DRUM_NO ,DIL_REC_TYPE , DIL_QTY from DEPOT_ITEM_LOG a, DEPOT_ITEMS b
            where a.DIL_DI_ID = b.DI_ID
            and DIL_PROJECT_ID = '$projectId'
            and b.DI_ITEM_CODE = '$itemid'
            )
            PIVOT
            (
            sum (DIL_QTY)
            FOR DIL_REC_TYPE
            IN ( 'USED' ,'WASTE' ,'COILED')) ");

            return Datatables::of($result)
                ->addIndexColumn()
                ->make(true);
        } else {
            $result = null;
            return Response()->json($result);
        }
    }
}
