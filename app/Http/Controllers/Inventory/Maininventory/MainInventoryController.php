<?php

namespace App\Http\Controllers\Inventory\Maininventory;

use App\Http\Controllers\Controller;
use App\Models\Workgroup;
use App\Models\Depot;
use App\Models\Depot_status;
use App\Models\Depot_types;
use App\Models\Depot_comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Session;
use App\Services\DepotComService;

// use Illuminate\Support\Facades\Hash;
// use App\Http\Requests\ProfileRequest;
// use App\Http\Requests\PasswordRequest;

class MainInventoryController extends Controller
{
    protected $depotComService;

    public function __construct(DepotComService $depotComService)
    {
        $this->depotComService = $depotComService;
    }

    /**
     * Show the Main inventory details.
     *
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        if ($request->input('workGroupSelect') != null) {
            $wgName = $request->input('workGroupSelect');
            $depot_tb_query = " SELECT DISTINCT DI.DI_ITEM_CODE,concat(it.ITEM_DISCRIPTION,concat(' (',concat(it.ITEM_MESSUREMENT,')'))) AS ITEM_DISCRIPTION,DI.DI_DATE_TRANSFERED,DI.DI_LOT_NO, DI.DI_DRUM_NO,DI.DI_TOT_QTY,DI.DI_RESERVED_QTY,DI.DI_TOT_QTY - DI.DI_RESERVED_QTY AS AVAILABLE_QTY
                                FROM DEPOT_ITEMS di, ITEMS it
                                WHERE DI.DI_ITEM_CODE = IT.ITEM_CODE
                                AND DI.DI_DEPOT_ID = (SELECT DEPOT_ID FROM DEPOT WHERE DEPOT_USER_NAME = '$wgName')";
            $contractors = Workgroup::where('WGTYPE', 'EXTERNAL')->where('WGSTATUS', 'ACTIVE')->groupBy('wgname')->orderBy('wgname')->pluck('wgname');
            $depotStatus = Depot_status::groupBy('ds_status')->pluck('ds_status');
            $depotType = Depot_types::groupBy('dt_type')->pluck('dt_type');
            $user = Session::get('user');
            $result =Depot::where('DEPOT_USER_NAME', $wgName)->get(); 
            $tb_result = DB::select($depot_tb_query);
            $depotTable= $tb_result ;
            return view('Inventory.Maininventory.show', compact('contractors','depotStatus','depotType','result','depotTable','wgName','user'));
        }else{
            $contractors = Workgroup::where('WGTYPE', 'EXTERNAL')->where('WGSTATUS', 'ACTIVE')->groupBy('wgname')->orderBy('wgname')->pluck('wgname');
            $depotStatus = Depot_status::groupBy('ds_status')->pluck('ds_status');
            $depotType = Depot_types::groupBy('dt_type')->pluck('dt_type');
            $result = null;
            $depotTable= null;
            $user = Session::get('user');
            $wgName = null;
            return view('Inventory.Maininventory.show', compact('contractors','depotStatus','depotType','result','depotTable','wgName','user'));
        }
    }

    public function getItemtable(Request $request){
        $wgName = $request->input('workGroupSelect');
        $result = DB::select(" SELECT DISTINCT DI.DI_ITEM_CODE,concat(it.ITEM_DISCRIPTION,concat(' (',concat(it.ITEM_MESSUREMENT,')'))) AS ITEM_DISCRIPTION,SUM(DI.DI_TOT_QTY) DI_TOT_QTY
        FROM DEPOT_ITEMS di, ITEMS it
        WHERE DI.DI_ITEM_CODE = IT.ITEM_CODE
        AND DI.DI_DEPOT_ID = (SELECT DEPOT_ID FROM DEPOT WHERE DEPOT_USER_NAME = '$wgName')
        GROUP BY DI.DI_ITEM_CODE,concat(it.ITEM_DISCRIPTION,concat(' (',concat(it.ITEM_MESSUREMENT,')'))) ");        

        return Datatables::of($result)
            ->addIndexColumn()            
            ->make(true);
        
           // return Response()->json($result);
    }


    public function getItemchildtable(Request $request){
        $wgName = $request->input('workGroupSelect');
        $itemCode = $request->input('itemCode');
        $result = DB::select(" SELECT DISTINCT DI.DI_ITEM_CODE,concat(it.ITEM_DISCRIPTION,concat(' (',concat(it.ITEM_MESSUREMENT,')'))) AS ITEM_DISCRIPTION,DI.DI_DATE_TRANSFERED,DI.DI_LOT_NO, DI.DI_DRUM_NO,DI.DI_TOT_QTY,DI.DI_RESERVED_QTY,DI.DI_TOT_QTY - DI.DI_RESERVED_QTY AS AVAILABLE_QTY
        FROM DEPOT_ITEMS di, ITEMS it
        WHERE DI.DI_ITEM_CODE = IT.ITEM_CODE
        AND DI_ITEM_CODE = '$itemCode'
        AND DI.DI_DEPOT_ID = (SELECT DEPOT_ID FROM DEPOT WHERE DEPOT_USER_NAME = '$wgName')");        

        return Datatables::of($result)
            ->addIndexColumn()            
            ->make(true);
        
           // return Response()->json($result);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'DEPOT_STATUS' => 'required',
            'DEPOT_USER_NAME' => 'required',
            'DEPOT_ERP_REF' => 'required',
            'DEPOT_TYPE'  => 'required',     
        ]);

        $user = Session::get('user')->USERLOGINNAME;

        $seq_key = DB::select('SELECT DEPOT_SEQ.NEXTVAL as seq_gen FROM DUAL');

        $input = $request->all();

        $input['DEPOT_CRESTE_DATE'] = DB::raw("SYSDATE") ;
        $input['DEPOT_STATUS_DATE'] = DB::raw("SYSDATE") ;
        $input['DEPOT_ID']  = $seq_key[0]->seq_gen;

        $wgname = $request->input('DEPOT_USER_NAME');
        $input_workgroup['WGINVENTORY_ID']  = $seq_key[0]->seq_gen;

        try{
            $this->depotComService->add('SYSTEM', $seq_key[0]->seq_gen,'DEPOTCRE',$user,'','','','');
           
            $depot = Depot::create($input);

            $workgroup = Workgroup::where('WGNAME',$wgname)->update($input_workgroup);
    
            $notify_msg = array(
                'message' => 'Inventory CREATED Successfully!',
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

    public function update(Request $request)
    {
        // $validatedData = $request->validate([
        //     'DEPOT_STATUS' => 'required',
        //     'DEPOT_USER_NAME' => 'required',
        //     'DEPOT_ERP_REF' => 'required',
        //     'DEPOT_TYPE'  => 'required',     
        // ]);

        $user = Session::get('user')->USERLOGINNAME;
        $depot_id = (int)$request->input('DEPOT_ID');

        $input = $request->all();

        if($request->input('DEPOT_STATUS')){
            $input['DEPOT_STATUS_DATE'] = DB::raw("SYSDATE") ;
        }
        
        try{

            foreach ($input as $key => $value) {
                if(!in_array($key, ["DEPOT_ID","DEPOT_USER_NAME"], true)){
                    $this->depotComService->add('SYSTEM', $depot_id,'DEPOTUPD',$user,$key,$value,'','');
                }
            }


            $depot = Depot::where('DEPOT_ID',$depot_id)->update($input);
    
            $notify_msg = array(
                'message' => 'Inventory UPDATED Successfully!',
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

    public function getComment(Request $request)
    {
        $depot = $request->input('depot');
        $result =  Depot_comment::where('DC_DEPOT', $depot)->get();
        return Datatables::of($result)->make(true);

    }

    public function storeComment(Request $request)
    {
        $text = $request->input('text');    
        $user = Session::get('user')->USERLOGINNAME;
        $depot = $request->input('depot');

        try {
            $this->depotComService->add($user, $depot,'DEPOTUSERCOM','','','','',$text);

            $notify_msg = array(
                'responce' => true,
                'message' => 'Update Successfully!',
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
}
