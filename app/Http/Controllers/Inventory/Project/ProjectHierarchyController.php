<?php

namespace App\Http\Controllers\Inventory\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Workgroup;
use App\Models\Projects_status;
use App\Models\Area;
use App\Models\Item;
use App\Models\Project_attribute;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Models\Project_hierarchy;

class ProjectHierarchyController extends Controller
{
    public function childProjects(Request $request){
        $projectId = $request->input('project');
        $result = DB::select("SELECT *  FROM PROJECTS WHERE PROS_ID IN (SELECT CHILD_ID FROM PROJECT_HIERARCHY WHERE PARENT_ID = '$projectId')");
        return Datatables::of($result)
            ->addIndexColumn()
            ->addColumn('view', function ($row) {
               
                    $btn = '<form action="' . route('project') . '" method="POST"> 
                        <input type="hidden" name="_token" value=" ' . csrf_token() . ' ">
                        <input type="hidden" name="project"  value="' . $row->pros_id . '" >
                        <button type="submit"  class="edit btn btn-primary btn-sm">View</button>
                        </form>';
                
                return $btn;
            })
            ->rawColumns(['view'])
            ->make(true);
    }



    public function parentProjects(Request $request){
        $projectId = $request->input('project');
        $result = DB::select("SELECT *  FROM PROJECTS WHERE PROS_ID IN (SELECT PARENT_ID  FROM PROJECT_HIERARCHY WHERE CHILD_ID= '$projectId')");
        return Datatables::of($result)
            ->addIndexColumn()
            ->addColumn('view', function ($row) {
               
                    $btn = '<form action="' . route('project') . '" method="POST"> 
                        <input type="hidden" name="_token" value=" ' . csrf_token() . ' ">
                        <input type="hidden" name="project"  value="' . $row->pros_id . '" >
                        <button type="submit"  class="edit btn btn-primary btn-sm">View</button>
                        </form>';
                
                return $btn;
            })
            ->rawColumns(['view'])
            ->make(true);
    }
}
