<?php

namespace App\Http\Controllers\Inventory\MajorProject;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Major_project;
use App\Models\Depot;
use App\Models\Depot_item;
use \Yajra\Datatables\Datatables;
use App\Models\Main_project_item_allocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use App\Services\DepotComService;

class MajorProjectController extends Controller
{
    protected $depotComService;

    public function __construct(DepotComService $depotComService)
    {
        $this->depotComService = $depotComService;
    }


    public function index(Request $request)
    {
        if (!$request->input('project') == null) {
            $majorProjectId = (int)$request->input('project');
            $result = Major_project::where('MAJOR_PROS_ID', $majorProjectId)->get();
            $items = DB::select("SELECT *  FROM ITEMS WHERE ITEM_CODE NOT IN (SELECT PIA_ITEM_CODE FROM MAIN_PROJECT_ITEM_ALLOCATIONS WHERE PIA_PROJECT_ID = '$majorProjectId')");
            return view('Inventory.MainProject.project', compact('result', 'items'));
        } else {
            $result = null;
            return view('Inventory.MainProject.project', compact('result'));
        }
    }

    public function filltable(Request $request)
    {
        $majorProjectId = (int)$request->input('project');
        $result = DB::select("SELECT A.*,B.ITEM_DISCRIPTION  FROM MAIN_PROJECT_ITEM_ALLOCATIONS A, ITEMS B
        WHERE ITEM_CODE = PIA_ITEM_CODE
        AND PIA_PROJECT_ID = '$majorProjectId'");

        $project = Major_project::where('MAJOR_PROS_ID', $majorProjectId)->get();

        if(strcmp($project[0]->major_pros_status, "CONFIRMED") == 0 ){
        return Datatables::of($result)
            ->addIndexColumn()
            ->addColumn('update', function ($row) {
                $btn = '<button class="btn btn-primary btn-sm update"><i class="tim-icons icon-cloud-upload-94"></i></button >';
                return $btn;
            })
            ->addColumn('delete', function ($row) {
                $btn = '<button class="btn btn-primary btn-sm delete"><i class="tim-icons icon-trash-simple"></i></button >';
                return $btn;
            })
            ->addColumn('input', function ($row) {
                $btn = '<input type="number"  class="form-control qtyinput" min="0" value="'.$row->pia_qty.'" >';
                return $btn;
            })
            ->rawColumns(['update', 'input' ,'delete'])
            ->make(true);
        }else{
            return Datatables::of($result)
            ->addIndexColumn()
            ->addColumn('update', function ($row) {
                $btn = '';
                return $btn;
            })
            ->addColumn('delete', function ($row) {
                $btn = '';
                return $btn;
            })
            ->addColumn('input', function ($row) {
                $btn = $row->pia_qty;
                return $btn;
            })
            ->rawColumns(['update', 'input' ,'delete'])
            ->make(true);

        }
    }




    public function store(Request $request)
    {
        $input = $request->all();
        $user = Session::get('user');
        $input['pia_update_by'] = $user->USERLOGINNAME;
        $input['pia_update_date'] = DB::raw("SYSDATE");
        try {
            Main_project_item_allocation::create($input);

            $notify_msg = array(
                'responce' => true,
                'message' => 'Update Successfully!',
                'alert-type' => 'success'
            );            

        }  catch (\Exception $ex) {
            if ($ex->getCode() == 1) {
                $msg = "Cannot Insert Duplicate Record.";
            }
            if ($ex->getCode() == 20002) {
                $msg = "Cannot Reserve More than Available Quantity.";
            } else {
                $msg = "Error Code :" . $ex->getMessage();
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
        $itemid = $request->input('pia_item_code');
        $projectId = $request->input('project');

        try {
            $return = Main_project_item_allocation::where('pia_project_id', $projectId)->where('pia_item_code', $itemid)->delete();

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
        $itemid = $request->input('pia_item_code');
        $projectId = $request->input('project');
        $qty = $request->input('qty');
        try {
            $return = Main_project_item_allocation::where('pia_project_id', $projectId)->where('pia_item_code', $itemid)->update(['pia_qty' => $qty]);

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


    public function reqErp(Request $request)
    {

        $user = Session::get('user')->USERLOGINNAME;

        $return = Depot_item::where('di_depot_id', $request->input('id'))->delete();

        $client = new \GuzzleHttp\Client([
            'headers' => ['Content-Type' => 'application/json'],
        ]);

        $url = 'http://172.25.2.134:7650/ftthcore/erpget/';

        try {
            $response = $client->request('POST', $url, [
                \GuzzleHttp\RequestOptions::JSON => [
                    'contractor' => $request->input('contractor'),
                    'id' => $request->input('id')
                ],
            ]);

            $this->depotComService->add('SYSTEM', $request->input('id'),'DEPOTSYNC',$user,'','','','');

        } catch (ClientException  $e) {
            
            $this->depotComService->add('SYSTEM', $request->input('id'),'DEPOTSYNCSTATUS',$user,'','',"Client Exception Occured");
            
            $notify_msg = array(
                'responce' => false,
                'message' => 'Client Exception Occured. Please contact Admin',
                'alert-type' => 'danger'
            );
        } catch (ConnectException $e) {
            
            $this->depotComService->add('SYSTEM', $request->input('id'),'DEPOTSYNCSTATUS',$user,'','',"Connection Exception Occured");
            
            $notify_msg = array(
                'responce' => false,
                'message' => 'Connection Exception Occured. Please contact Admin',
                'alert-type' => 'danger'
            );
        }
        $array = json_decode($response->getBody()->getContents(), true);
        if ($array["ERROR"] == false) {
            
            $this->depotComService->add('SYSTEM', $request->input('id'),'DEPOTSYNCSTATUS',$user,'','','Success','');
            $input_depot['DEPOT_LAST_SNYC'] = DB::raw("SYSDATE") ;
            $depot = Depot::where('DEPOT_ID',$request->input('id'))->update($input_depot);
            
            $notify_msg = array(
                'responce' => true,
                'message' => 'Success',
                'alert-type' => 'success'
            );
        } else {
            
            $this->depotComService->add('SYSTEM', $request->input('id'),'DEPOTSYNCSTATUS',$user,'','','Error',$array["MSG"] ?: "Something went wrong. Please try again");
            
            $notify_msg = array(
                'responce' => false,
                'message' => $array["MSG"] ?: "Something went wrong. Please try again",
                'alert-type' => 'danger'
            );
        }



        return $notify_msg;
    }
    
}
