<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Workgroup;
use App\Models\Projects_status;
use App\Models\Area;
use App\Models\Item;
use App\Models\Project_types;
use App\Models\Project_hierarchy;
use App\Models\Project_attributes;
use App\Models\Project_depot;
use App\Models\Service_types;
use App\Models\Depot;
use App\Models\Major_project;
use App\Models\Main_project_hierarchy;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class ProjectController extends Controller
{
    public function inbox()
    {
        $user = Session::get('user');
        $contractors = Workgroup::where('WGTYPE', 'EXTERNAL')->where('WGSTATUS', 'ACTIVE')->groupBy('wgname')->pluck('wgname');
        $projectstatus = Projects_status::groupBy('ps_status')->pluck('ps_status');
        $lea = Area::groupBy('lea')->orderBy('lea', 'ASC')->pluck('lea');
        $serviceType = Service_types::groupBy('svtype')->pluck('svtype');
        $jobType = Project_types::groupBy('protype')->pluck('protype');
        $result = null;
        
        return view('Project.inboxProject',compact('contractors', 'projectstatus', 'lea', 'serviceType','jobType','user'));
        
    }

    public function projectRecords(Request $request)
    {
        $contractor = "";
        $lea = "";
        $status = "";
        $service_type = "";
        $type = "";
        $startdate = "";
        $enddate = "";
        if ($request->input('contractor') != null) {
            $contractor = $request->input('contractor');
            $queary = " SELECT *  FROM (SELECT A.*  ,
                        (SELECT PA_VALUE  FROM PROJECT_ATTRIBUTES WHERE PA_NAME = 'CONTRACTOR' AND PA_PROJECT_ID = PROS_ID) CONTRACTOR
                        FROM PROJECTS A),
                        (SELECT *
                        FROM MAIN_PROJECT_HIERARCHY B,MAJOR_PROJECTS M WHERE MAJOR_PROS_ID = PROJECT_ID)
                        WHERE CONTRACTOR = '$contractor'
                        AND PROS_ID = JOB_ID";
        }
        if ($request->input('lea') != null) {
            $lea = $request->input('lea');
            $queary = $queary . " AND PROS_LEA ='$lea' ";
        }
        if ($request->input('status') != null) {
            $status = $request->input('status');
            $queary = $queary . " AND PROS_STATUS ='$status' ";
        }
        if ($request->input('service_type') != null) {
            $service_type = $request->input('service_type');
            $queary = $queary . " AND PROS_SVTYPE ='$service_type' ";
        }
        if ($request->input('type') != null) {
            $type = $request->input('type');
            $queary = $queary . " AND PROS_TYPE ='$type' ";
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
            ->addColumn('projectName', function ($row) {
               
                $btn = '<form action="' . route('updateMajorProjectView') . '" method="POST"> 
                        <input type="hidden" name="_token" value=" ' . csrf_token() . ' ">
                        <input type="hidden" name="major_pros_id"  value="' . $row->major_pros_id . '" >
                        <button type="submit"  class="edit btn btn-link btn-sm">'. $row->major_pros_name.'</button>
                        </form>';
            
                return $btn;
            })
            ->addColumn('view', function ($row) {
                $btn = '<form action="' . route('updateProjectView') . '" method="POST"> 
                    <input type="hidden" name="_token" value=" ' . csrf_token() . ' ">
                    <input type="hidden" name="pros_id"  value="' . $row->pros_id . '" >
                    <button type="submit"  class="edit btn btn-primary btn-sm">View</button>
                    </form>';
            
                return $btn;
            })
            ->rawColumns(['projectName','view'])
            ->make(true);
    }


    public function createProject()
    {
        $ProjectStatus = Projects_status::groupBy('ps_status')->pluck('ps_status');
        $projectType = Project_types::groupBy('protype')->pluck('protype');
        $serviceType = Service_types::groupBy('svtype')->pluck('svtype');
        $contractors = Workgroup::where('WGTYPE', 'EXTERNAL')->where('WGSTATUS', 'ACTIVE')->groupBy('wgname')->pluck('wgname');
        $lea = Area::groupBy('lea')->orderBy('lea', 'ASC')->pluck('lea');
        $majorProjects = Major_project::select(DB::raw("CONCAT(major_doc_pros_id,CONCAT(' - ',major_pros_name)) AS pros_name_id"),'major_pros_id')->where('MAJOR_PROS_STATUS','INPROGRESS')->get()->pluck('pros_name_id','major_pros_id');
        $user = Session::get('user');
        return view('Project.createProject', compact('ProjectStatus','projectType','serviceType','lea','contractors','user','majorProjects'));
        
    }

    public function createProjectdropdown(Request $request)
    {
        if(! $request->input('con_name') == null){
            $contractor = $request->input('con_name');
            $query_projects = "  SELECT pros_id,pros_name  FROM (SELECT A.*  ,
                                (SELECT PA_VALUE  FROM PROJECT_ATTRIBUTES WHERE PA_NAME = 'CONTRACTOR' AND PA_PROJECT_ID = PROS_ID) CONTRACTOR,
                                (SELECT PD_STATUS  FROM PROJECT_DEPOT WHERE PD_PROJECT_ID = PROS_ID) Depot_status
                                FROM PROJECTS A)
                                WHERE CONTRACTOR = '$contractor'
                                AND Depot_status = 'UPD_MATERIAL' ";
            $parentProjects = DB::select($query_projects);  
            $inventoryDepot = Depot::where('DEPOT_STATUS', 'ACTIVE')->where('DEPOT_USER_NAME', $contractor)->pluck('depot_id','depot_user_name');

            return response()->json([
                'parentProjects' => $parentProjects,
                'inventoryDepot' => $inventoryDepot
            ]);   
        }else{
            $parentProjects = null;
            $inventoryDepot = null;

            return response()->json([
                'parentProjects' => $parentProjects,
                'inventoryDepot' => $inventoryDepot
            ]);  
        } 
    }

    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'PROS_SVTYPE' => 'required',
            'PROS_TYPE' => 'required',
            'PROS_TARGET_ENDDATE' => 'required',
            'PROS_LEA' => 'required',
            'PROS_NAME' => 'required',
            'MAJOR_PROJECT_ID' => 'required'
        ]);

        $test_key = DB::select('SELECT PROJECTS_SEQ.NEXTVAL as seq_gen FROM DUAL');

        $input = $request->all();
        $user = Session::get('user');

        $input['PROS_CREATEDATE'] = DB::raw("SYSDATE") ;
        $input['PROS_STATUSDATE'] = DB::raw("SYSDATE") ;
        $input['PROS_ID']  = $test_key[0]->seq_gen;

        try{

            $project = Project::create($input);

            $input['PA_PROJECT_ID'] = $test_key[0]->seq_gen; 
            $input['PA_LAST_UPDATE'] = DB::raw("SYSDATE") ;
            $input['PA_UPDATE_BY'] = $user->USERLOGINNAME ;
            $project_attributes = Project_attributes::create($input);

        
            $input['CHILD_ID'] =$test_key[0]->seq_gen;
            $project_hierarchy = Project_hierarchy::create($input);

            // if($request->input('PROS_TYPE') == 'PARENT'){
            //     $input['PD_STATUS'] = 'REQ_MATERIAL';
            // }else{
            //     $input['PD_STATUS'] = 'RES_MATERIAL';
            // }
            $input['PD_STATUS'] = 'NEW';
            $input['PD_PROJECT_ID'] =  $test_key[0]->seq_gen;
            $input['PD_STATUS_DATE'] = DB::raw("SYSDATE") ;
            $input['PD_CREATE_DATE'] = DB::raw("SYSDATE") ;
            $project_depot = Project_depot::create($input);

            $input['PROJECT_ID'] = $request->input('MAJOR_PROJECT_ID');
            $input['JOB_ID'] =  $test_key[0]->seq_gen;
            $input['JOB_TYPE'] = $request->input('PROS_TYPE');
            $main_project_hierarchy = Main_project_hierarchy::create($input);

    
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

    public function updateProject(Request $request)
    {
            if(! $request->input('pros_id') == null){
                $projectId = $request->input('pros_id');
                $query_projects = " SELECT pros_id,pros_name,contractor,pros_type  
                                FROM    (SELECT A.*  ,
                                        (SELECT PA_VALUE  FROM PROJECT_ATTRIBUTES WHERE PA_NAME = 'CONTRACTOR' AND PA_PROJECT_ID = PROS_ID) CONTRACTOR
                                        FROM PROJECTS A)
                                ORDER BY pros_id DESC";
                $project_list_all =  DB::select($query_projects);
                $ProjectStatus = Projects_status::groupBy('ps_status')->pluck('ps_status');
                $projectType = Project_types::groupBy('protype')->pluck('protype');
                $serviceType = Service_types::groupBy('svtype')->pluck('svtype');
                $contractors = Workgroup::where('WGTYPE', 'EXTERNAL')->where('WGSTATUS', 'ACTIVE')->groupBy('wgname')->pluck('wgname');
                $lea = Area::groupBy('lea')->pluck('lea');
                $majorProjects = Major_project::select(DB::raw("CONCAT(major_doc_pros_id,CONCAT(' - ',major_pros_name)) AS pros_name_id"),'major_pros_id')->where('MAJOR_PROS_STATUS','INPROGRESS')->get()->pluck('pros_name_id','major_pros_id');
                $user = Session::get('user');
                $query_projects = "SELECT *  FROM (SELECT A.*  ,
                                (SELECT PA_VALUE  FROM PROJECT_ATTRIBUTES WHERE PA_NAME = 'CONTRACTOR' AND PA_PROJECT_ID = PROS_ID) CONTRACTOR,
                                (SELECT PD_DEPOT_ID  FROM PROJECT_DEPOT WHERE PD_PROJECT_ID = PROS_ID) DEPOT,
                                (SELECT PARENT_ID  FROM PROJECT_HIERARCHY WHERE CHILD_ID = PROS_ID) PARENT,
                                (SELECT PD_STATUS  FROM PROJECT_DEPOT WHERE PD_PROJECT_ID = PROS_ID) DEPOT_STATUS,
                                (SELECT PROJECT_ID  FROM MAIN_PROJECT_HIERARCHY WHERE JOB_ID = PROS_ID) MAJOR_PROJECT
                                FROM PROJECTS A)
                                WHERE PROS_ID = $projectId";
                $project = DB::select($query_projects); 
                return view('Project.updateProject', compact('project_list_all','ProjectStatus','projectType','serviceType','lea','contractors','user','majorProjects','project'));
            
            }else{

                $query_projects = " SELECT pros_id,pros_name,contractor,pros_type  
                                FROM    (SELECT A.*  ,
                                        (SELECT PA_VALUE  FROM PROJECT_ATTRIBUTES WHERE PA_NAME = 'CONTRACTOR' AND PA_PROJECT_ID = PROS_ID) CONTRACTOR
                                        FROM PROJECTS A)
                                ORDER BY pros_id DESC";
                $project_list_all =  DB::select($query_projects);
                $ProjectStatus = Projects_status::groupBy('ps_status')->pluck('ps_status');
                $projectType = Project_types::groupBy('protype')->pluck('protype');
                $serviceType = Service_types::groupBy('svtype')->pluck('svtype');
                $contractors = Workgroup::where('WGTYPE', 'EXTERNAL')->where('WGSTATUS', 'ACTIVE')->groupBy('wgname')->pluck('wgname');
                $lea = Area::groupBy('lea')->pluck('lea');
                $majorProjects = Major_project::select(DB::raw("CONCAT(major_doc_pros_id,CONCAT(' - ',major_pros_name)) AS pros_name_id"),'major_pros_id')->where('MAJOR_PROS_STATUS','INPROGRESS')->get()->pluck('pros_name_id','major_pros_id');
                $user = Session::get('user');
                $project = null;
                return view('Project.updateProject', compact('project_list_all','ProjectStatus','projectType','serviceType','lea','contractors','user','majorProjects','project'));
            
            }
           
    }

    // public function getProjectDetails(Request $request)
    // {
    //     if(! $request->input('PROJECT') == null){
    //         $projectId = $request->input('PROJECT');
    //         $query_projects = "SELECT *  FROM (SELECT A.*  ,
    //                             (SELECT PA_VALUE  FROM PROJECT_ATTRIBUTES WHERE PA_NAME = 'CONTRACTOR' AND PA_PROJECT_ID = PROS_ID) CONTRACTOR,
    //                             (SELECT PD_DEPOT_ID  FROM PROJECT_DEPOT WHERE PD_PROJECT_ID = PROS_ID) DEPOT,
    //                             (SELECT PARENT_ID  FROM PROJECT_HIERARCHY WHERE CHILD_ID = PROS_ID) PARENT,
    //                             (SELECT PROJECT_ID  FROM MAIN_PROJECT_HIERARCHY WHERE JOB_ID = PROS_ID) MAJOR_PROJECT
    //                             FROM PROJECTS A)
    //                             WHERE PROS_ID = $projectId";
    //         $Project = DB::select($query_projects); 

    //         return response()->json([
    //             $Project
    //         ]);   
    //     }else{
    //         $Project= null;

    //         return response()->json([
    //             $Project
    //         ]);  
    //     } 
    // }

    public function update(Request $request)
    {
        // dd($request);
        $validatedData = $request->validate([
            'PROS_SVTYPE' => 'required',
            'PROS_TYPE' => 'required',
            'PROS_TARGET_ENDDATE' => 'required',
            'PROS_LEA' => 'required',
            'PROS_NAME' => 'required',
            'MAJOR_PROJECT_ID' => 'required'
        ]);

        $pros_id = (int)$request->input('PROSS_ID');
        // $input = $request->all();
        $input['PROS_SVTYPE'] =$request->input('PROS_SVTYPE');
        $input['PROS_TYPE'] =$request->input('PROS_TYPE');
        $input['PROS_TARGET_ENDDATE'] =$request->input('PROS_TARGET_ENDDATE');
        $input['PROS_LEA'] =$request->input('PROS_LEA');
        $input['PROS_NAME'] =$request->input('PROS_NAME');
        $input['PROS_STATUS'] =$request->input('PROS_STATUS');
        $input['PROS_STATUSDATE'] = DB::raw("SYSDATE") ;

        $user = Session::get('user');

        $input_attributes['PA_VALUE'] =$request->input('PA_VALUE');
        $input_attributes['PA_OLD_VALUE'] =$request->input('PA_OLD_VALUE');
        $input_attributes['PA_LAST_UPDATE'] = DB::raw("SYSDATE") ;
        $input_attributes['PA_UPDATE_BY'] = $user->USERLOGINNAME ;

        $input_hierarchy['PARENT_ID'] = $request->input('PARENT_ID');

        // if($request->input('PROS_TYPE') == 'PARENT' && ($request->input('PD_STATUS') == 'REQ_MATERIAL' || $request->input('PD_STATUS') == 'RES_MATERIAL')){
        //     $input_depot['PD_STATUS'] = 'REQ_MATERIAL';
        //     $input_depot['PD_STATUS_DATE'] =DB::raw("SYSDATE");
        //     $input_depot['PD_OLD_STATUS'] = $request->input('PD_STATUS');
        // }
        // if($request->input('PROS_TYPE') == 'CHILD' && ($request->input('PD_STATUS') == 'REQ_MATERIAL' || $request->input('PD_STATUS') == 'RES_MATERIAL')){
        //     $input_depot['PD_STATUS'] = 'RES_MATERIAL';
        //     $input_depot['PD_STATUS_DATE'] =DB::raw("SYSDATE");
        //     $input_depot['PD_OLD_STATUS'] = $request->input('PD_STATUS');
        // }
        if($request->input('PROS_TYPE') == 'PARENT' && $request->input('PROS_STATUS') == 'IN-PROGESS'){
            $input_depot['PD_STATUS'] = 'RES_MATERIAL';
            $input_depot['PD_STATUS_DATE'] =DB::raw("SYSDATE");
            $input_depot['PD_OLD_STATUS'] = $request->input('PD_STATUS');
        }
        if($request->input('PROS_TYPE') == 'CHILD' && $request->input('PROS_STATUS') == 'IN-PROGESS'){
            $input_depot['PD_STATUS'] = 'UPD_MATERIAL';
            $input_depot['PD_STATUS_DATE'] =DB::raw("SYSDATE");
            $input_depot['PD_OLD_STATUS'] = $request->input('PD_STATUS');
        }
        $input_depot['PD_DEPOT_ID'] = $request->input('PD_DEPOT_ID');

        $input_main_project['PROJECT_ID'] = $request->input('MAJOR_PROJECT_ID');
        $input_main_project['JOB_TYPE'] = $request->input('PROS_TYPE');

        try{

            $project = Project::where('PROS_ID',$pros_id)->update($input);
           
            $project_attributes = Project_attributes::where('PA_PROJECT_ID',$pros_id)->where('PA_NAME','CONTRACTOR')->update($input_attributes);

            $project_hierarchy = Project_hierarchy::where('CHILD_ID',$pros_id)->update($input_hierarchy);

            $project_depot = Project_depot::where('PD_PROJECT_ID',$pros_id)->update($input_depot);

            $main_project_hierarchy = Main_project_hierarchy::where('JOB_ID',$pros_id)->update( $input_main_project);
    
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

    public function jobList(Request $request){
        $ProjectId = $request->input('project');
        $ProjectType = $request->input('project_type');
        if($ProjectType == 'CHILD'){
            $querry = " SELECT *  FROM PROJECTS,PROJECT_HIERARCHY
                        WHERE PROS_ID = PARENT_ID
                        AND CHILD_ID = '$ProjectId'";
        }else{
            $querry = " SELECT *  FROM PROJECTS,PROJECT_HIERARCHY
                        WHERE PROS_ID = CHILD_ID
                        AND PARENT_ID = '$ProjectId'";
        }
        
        $result = DB::select($querry);
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

        $pros_id = (int)$request->input('PROSS_ID');
        $Pros_type = (int)$request->input('PROS_TYPE');

        $input['PROS_STATUSDATE'] = DB::raw("SYSDATE") ;
        $input['PROS_STATUS'] = 'COMPLETED' ;
        $input['PROS_STATUS_REASON'] = 'Complete Job' ;

        if($Pros_type == 'PARENT'){
            $queary = " SELECT *  FROM PROJECTS,PROJECT_HIERARCHY
                        WHERE PROS_ID = CHILD_ID
                        AND PARENT_ID = ' $pros_id'
                        MINUS
                        SELECT * FROM PROJECTS,PROJECT_HIERARCHY
                        WHERE PROS_ID = CHILD_ID
                        AND PARENT_ID = ' $pros_id'
                        AND PROS_STATUS = 'COMPLETED'";
            $result = DB::select($queary);
        }

        try{
            if($Pros_type == 'PARENT'){
                if(count($result) == 0){
                    $project = Project::where('PROS_ID',$pros_id)->update($input);
    
                    $notify_msg = array(
                        'message' => 'Job Completed Successfully!',
                        'alert-type' => 'success'
                    );
    
                }else{
    
                    $notify_msg = array(
                        'message' => 'Job Completion process is Fail.There are Child Jobs which are not completed!',
                        'alert-type' => 'fail complete'
                    );
                }    
            }else{
                    $project = Project::where('PROS_ID',$pros_id)->update($input);
    
                    $notify_msg = array(
                        'message' => 'Job Completed Successfully!',
                        'alert-type' => 'success'
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