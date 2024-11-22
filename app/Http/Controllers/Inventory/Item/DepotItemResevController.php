<?php

namespace App\Http\Controllers\Inventory\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Models\Depot_item_resev;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Inventory\Project\ProjectDepotComController;
use App\Enums\CommentTypes;


class DepotItemResevController extends Controller
{
    // protected $commentService;
    // public function __construct(ProjectDepotComController $commentService)
    // {
    //     $this->commentService = $commentService;
    // }

    public function reservedItemList(Request $request)
    {
        if ($request->input('dil_item_code') != null) {
            $itemid = $request->input('dil_item_code');
            $projectId = $request->input('project');
            $result = Depot_item_resev::where('dil_project_id', $projectId)
                ->where('dil_rec_type', 'ASSIGNED')
                ->join('depot_items', 'depot_items.di_id', '=', 'Depot_item_resev.dil_di_id')
                ->where('di_item_code', $itemid)->get(['Depot_item_resev.*', 'depot_items.*']);

            return Datatables::of($result)
                ->addIndexColumn()
                ->addColumn('update', function ($row) {
                    $btn = '<button class="btn btn-primary btn-sm update"><i class="tim-icons icon-cloud-upload-94"></i></button >';
                    return $btn;
                })
                ->addColumn('delete', function ($row) {
                    $btn = '<button class="btn btn-primary btn-sm delete" onclick="requested_model_table_deleteItem(this)"><i class="tim-icons icon-trash-simple"></i></button >';
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
        $input['dil_rec_type'] = 'ASSIGNED';

        try {
            Depot_item_resev::create($input);

            $notify_msg = array(
                'responce' => true,
                'message' => 'Update Successfully!',
                'alert-type' => 'success'
            );

            // $item =$request()->input('DIL_DI_ID');
            // $qty = $request()->input('DIL_QTY');
            // $project = $request()->input('DIL_PROJECT_ID');
            // $request->merge(['comtype' => CommentTypes::ITEMRES,
            //                     'item' =>  $item ,
            //                     'qty' => $qty,
            //                     'projectid' => $project
            // ]);

          //  $tasks_controller = new ProjectDepotComController;

            // Access method in TasksController
         //   $tasks_controller->store($request);

        }  catch (\Exception $ex) {
            if ($ex->getCode() == 1) {
                $msg = "Cannot Insert Duplicate Record.";
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


    public function delete(Request $request)
    {
        $itemid = $request->input('dil_item_code');
        $projectId = $request->input('project');

        try {
            $return = Depot_item_resev::where('dil_project_id', $projectId)->where('dil_di_id', $itemid)->delete();

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
        } catch (\Exception $ex) {
            if ($ex->getCode() == 1) {
                $msg = "Cannot Insert Duplicate Record.";
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



    public function update(Request $request)
    {
        $itemid = $request->input('dil_item_code');
        $projectId = $request->input('project');
        $qty = $request->input('qty');
        try {
            $return = Depot_item_resev::where('dil_project_id', $projectId)->where('dil_di_id', $itemid)->update(['dil_qty' => $qty]);

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
        } catch (\Exception $ex) {
            if ($ex->getCode() == 1) {
                $msg = "Cannot Insert Duplicate Record.";
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
}
