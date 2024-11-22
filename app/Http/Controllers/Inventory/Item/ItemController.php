<?php

namespace App\Http\Controllers\Inventory\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Models\Depot_item;

class ItemController extends Controller
{
    public function itemHistory(Request $request)
    {
        if ($request->input('project') != null) {
            $projectId = $request->input('project');

            $result = DB::select("SELECT DISTINCT DI_ITEM_CODE \"Item Code\",DI_LOT_NO \"Lot Number\",  ITEM_DISCRIPTION DISCRIPTION, ITEM_MESSUREMENT MEASUERMENT ,  A.DIL_ID        
            ,  A.DIL_DI_ID   ,  A.DIL_QTY,  A.DIL_PROJECT_ID ,  A.DIL_UPDATE_DATE ,  A.DIL_UPDATE_BY  ,  A.DIL_REC_TYPE  
            FROM DEPOT_ITEMS , ITEMS , DEPOT_ITEM_LOG A
           WHERE DI_ID = DIL_DI_ID
           AND DIL_PROJECT_ID = '$projectId'
           AND ITEM_CODE = DI_ITEM_CODE");

            return Datatables::of($result)->addIndexColumn()
                ->make(true);
        } else {
            $result = null;
            return Response()->json($result);
        }
    }


    public function itemLotList(Request $request)
    {
        if ($request->input('dil_item_code') != null) {
            $itemid = $request->input('dil_item_code');
            $result = Depot_item::where('di_item_code', $itemid)->where('di_depot_id', $request->input('depot'))
            ->get();

            return Response()->json($result);
        } else {
            $result = null;
            return Response()->json($result);
        }
    }

    public function itemAvailableQty(Request $request)
    {
        if ($request->input('item_id') != null) {
            $itemid = $request->input('item_id');
            $result = Depot_item::where('di_id', $itemid)->where('di_depot_id', $request->input('depot'))
            ->get();

            return Response()->json($result);
        } else {
            $result = null;
            return Response()->json($result);
        }
    }

}
