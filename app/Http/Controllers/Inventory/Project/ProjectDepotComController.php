<?php

namespace App\Http\Controllers\Inventory\Project;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enums\CommentTypes;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Project_depot_comment;
use App\Models\Depot_item;
use \Yajra\Datatables\Datatables;

class ProjectDepotComController extends Controller
{
   // public function store($project, $comtype, $item, $qty, $usetype, $status, $wg, $text)
   public function store(Request $request)
    {
        $comtype = $request->input('comtype');
        $item = $request->input('item');
        $qty = $request->input('qty');
        $wg = $request->input('wg');
        $status = $request->input('status');
        $text = $request->input('text');
        $usetype = $request->input('usetype');      
        $user = Session::get('user')->USERLOGINNAME;
        $comuser = "SYSTEM";
        $project = $request->input('projectid');
       

        switch ($comtype) {
            case CommentTypes::ITEMREQ:
                $msg = "Request $qty of $item by $user";
                break;
            case CommentTypes::ITEMREQUPD:
                $msg = "Update $item request ammount to $qty by $user";
                break;
            case CommentTypes::ITEMREQDEL:
                $msg = "Deleted $item request by $user";
                break;

            case CommentTypes::ITEMRES:
                $itemdetails = Depot_item::where('di_id', $item)->get();
                $item = $itemdetails[0]->DI_ITEM_CODE . '[' . $itemdetails[0]->DI_LOT_NO . ']';
                $msg = "Allocated $qty of $item by $user";
                break;
            case CommentTypes::ITEMRESUPD:
                $itemdetails = Depot_item::where('di_id', $item)->get();
                $item = $itemdetails[0]->DI_ITEM_CODE . '[' . $itemdetails[0]->DI_LOT_NO . ']';
                $msg = "Update $item allocated ammount to $qty by $user";
                break;
            case CommentTypes::ITEMRESDEL:
                $itemdetails = Depot_item::where('di_id', $item)->get();
                $item = $itemdetails[0]->DI_ITEM_CODE . '[' . $itemdetails[0]->DI_LOT_NO . ']';
                $msg = "Deleted $item allocated by $user";
                break;


            case CommentTypes::WGCHANGE:
                $msg = "WorkGroup Changed to $wg by $user";
                break;
            case CommentTypes::STATUSCHANGE:
                $msg = "Status changed to $status by $user";
                break;
            case CommentTypes::USERCOM:
                $msg = $text;
                $comuser = $user;
                break;

            case CommentTypes::ITEMUPD:
                $itemdetails = Depot_item::where('di_id', $item)->get();
                $item = $itemdetails[0]->DI_ITEM_CODE . '[' . $itemdetails[0]->DI_LOT_NO . ']';
                $msg = "Update item $usetype to $qty by $user";
                break;

            default:
                $msg = "";
        }

       // $msg = $text;
        $seq_key = DB::select('SELECT PCOM_DEPOT_SEQ.NEXTVAL as seq_gen FROM DUAL');

        $input['PDC_USER'] = $comuser;
        $input['PDC_DATE'] = DB::raw("SYSDATE");
        $input['PDC_ID'] = $seq_key[0]->seq_gen;
        $input['PDC_PROJECT'] = $project;
        $input['PDC_TEXT'] = $msg;

        try {
            Project_depot_comment::create($input);

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


    public function get(Request $request)
    {
        $project = $request->input('project');
        $result =  Project_depot_comment::where('PDC_PROJECT', $project)->get();
        return Datatables::of($result)->make(true);

    }

    public function storeComment(Request $request)
    {
        $text = $request->input('text');    
        $user = Session::get('user')->USERLOGINNAME;
        $project = $request->input('projectid');

        $seq_key = DB::select('SELECT PCOM_DEPOT_SEQ.NEXTVAL as seq_gen FROM DUAL');

        $input['PDC_USER'] = $user;
        $input['PDC_DATE'] = DB::raw("SYSDATE");
        $input['PDC_ID'] = $seq_key[0]->seq_gen;
        $input['PDC_PROJECT'] = $project;
        $input['PDC_TEXT'] = $text;
        
    }
}
