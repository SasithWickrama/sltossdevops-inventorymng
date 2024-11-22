<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });



//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes();

//Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');
Route::post('login.custom', [App\Http\Controllers\Auth\CustomLoginController::class, 'doLogin'])->name('login.custom'); 
Route::get('/', [App\Http\Controllers\Auth\CustomLoginController::class, 'index'])->name('login'); 
Route::get('signout', [App\Http\Controllers\Auth\CustomLoginController::class, 'signOut'])->name('signout');

Route::group(['middleware' => 'auth'], function () {
		Route::get('icons', ['as' => 'pages.icons', 'uses' => 'App\Http\Controllers\PageController@icons']);
		Route::get('maps', ['as' => 'pages.maps', 'uses' => 'App\Http\Controllers\PageController@maps']);
		Route::get('notifications', ['as' => 'pages.notifications', 'uses' => 'App\Http\Controllers\PageController@notifications']);
		Route::get('rtl', ['as' => 'pages.rtl', 'uses' => 'App\Http\Controllers\PageController@rtl']);
		Route::get('tables', ['as' => 'pages.tables', 'uses' => 'App\Http\Controllers\PageController@tables']);
		Route::get('typography', ['as' => 'pages.typography', 'uses' => 'App\Http\Controllers\PageController@typography']);
		Route::get('upgrade', ['as' => 'pages.upgrade', 'uses' => 'App\Http\Controllers\PageController@upgrade']);
});

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});

Route::group(['middleware' => 'auth'], function () {
	Route::get('maininventory',  [App\Http\Controllers\Inventory\Maininventory\MainInventoryController::class, 'show'])->name('maininventory');
	Route::post('maininventory',  [App\Http\Controllers\Inventory\Maininventory\MainInventoryController::class, 'show'])->name('maininventory');
	Route::post('createmaininventory',  [App\Http\Controllers\Inventory\Maininventory\MainInventoryController::class, 'store'])->name('createmaininventory');
	Route::put('updatemaininventory',  [App\Http\Controllers\Inventory\Maininventory\MainInventoryController::class, 'update'])->name('updatemaininventory');
	Route::get('getInventoryComment',  [App\Http\Controllers\Inventory\Maininventory\MainInventoryController::class, 'getComment'])->name('getInventoryComment');
	Route::post('storeInventoryComment',  [App\Http\Controllers\Inventory\Maininventory\MainInventoryController::class, 'storeComment'])->name('storeInventoryComment');
	// Route::get('maininventory', ['as' => 'maininventory.show', 'uses' => 'App\Http\Controllers\Inventory\Maininventory\MainInventoryController@show']);
	// Route::post('maininventory', ['as' => 'maininventory.show', 'uses' => 'App\Http\Controllers\Inventory\Maininventory\MainInventoryController@show']);
	// Route::get('newproject/{pro_id}', ['as' => 'newproject.show', 'uses' => 'App\Http\Controllers\NewProjectController@show']);
	//  Route::get('newprojectlist', ['as' => 'newproject.list', 'uses' => 'App\Http\Controllers\NewProjectController@list']);
	// Route::get('newprojectlistrecords', ['as' => 'newproject.newprojects_records', 'uses' => 'App\Http\Controllers\NewProjectController@newprojects_records']);
	
	Route::get('createProject',  [App\Http\Controllers\Project\ProjectController::class, 'createProject'])->name('createProjectView');
	Route::get('getProjectDropdown',  [App\Http\Controllers\Project\ProjectController::class, 'createProjectdropdown'])->name('getProjectDropdown');
	Route::post('createProject',  [App\Http\Controllers\Project\ProjectController::class, 'store'])->name('createProject');
	Route::get('updateProject',  [App\Http\Controllers\Project\ProjectController::class, 'updateProject'])->name('updateProjectView');
	Route::post('updateProject',  [App\Http\Controllers\Project\ProjectController::class, 'updateProject'])->name('updateProjectView');
	// Route::get('getProjectDetails',  [App\Http\Controllers\Project\ProjectController::class, 'getProjectDetails'])->name('getProjectDetails');
	Route::put('updateProject',  [App\Http\Controllers\Project\ProjectController::class, 'update'])->name('updateProject');
	Route::get('projectsInbox',  [App\Http\Controllers\Project\ProjectController::class, 'inbox'])->name('jobInbox');
	Route::get('projectsRecords',  [App\Http\Controllers\Project\ProjectController::class, 'projectRecords'])->name('jobRecords');
	Route::get('jobList',  [App\Http\Controllers\Project\ProjectController::class, 'jobList'])->name('jobList');
	Route::put('completeJob',  [App\Http\Controllers\Project\ProjectController::class, 'complete'])->name('completeJob');

	Route::get('createMajorProject',  [App\Http\Controllers\MajorProject\MajorProjectController::class, 'createMajorProject'])->name('createMajorProjectView');
	Route::post('createMajorProject',  [App\Http\Controllers\MajorProject\MajorProjectController::class, 'store'])->name('createMajorProject');
	Route::get('updateMajorProject',  [App\Http\Controllers\MajorProject\MajorProjectController::class, 'updateMajorProject'])->name('updateMajorProjectView');
	Route::post('updateMajorProject',  [App\Http\Controllers\MajorProject\MajorProjectController::class, 'updateMajorProject'])->name('updateMajorProjectView');
	Route::put('updateMajorProject',  [App\Http\Controllers\MajorProject\MajorProjectController::class, 'update'])->name('updateMajorProject');
	Route::get('MajorProjectChildJobs',  [App\Http\Controllers\MajorProject\MajorProjectController::class, 'ChildJobs'])->name('MajorProjectChildJobs');
	Route::get('MajorProjectParentChildJobs',  [App\Http\Controllers\MajorProject\MajorProjectController::class, 'childJobsForParentJob'])->name('MajorProjectParentChildJobs');
	Route::get('MajorProjectParentJobs',  [App\Http\Controllers\MajorProject\MajorProjectController::class, 'parentJobs'])->name('MajorProjectParentJobs');
	Route::get('majorProjectInbox',  [App\Http\Controllers\MajorProject\MajorProjectController::class, 'inbox'])->name('majorProjectInbox');
	Route::get('majorProjectRecords',  [App\Http\Controllers\MajorProject\MajorProjectController::class, 'majorProjectRecords'])->name('majorProjectRecords');
	Route::put('completeMajorProject',  [App\Http\Controllers\MajorProject\MajorProjectController::class, 'complete'])->name('completeMajorProject');



	Route::get('ongoingProject',  [App\Http\Controllers\ProjectController::class, 'pendingProjects'])->name('ongoingProject');
	Route::post('ongoingProject',  [App\Http\Controllers\ProjectController::class, 'pendingProjects'])->name('ongoingProject');
	//Route::get('projectItemRecords',  [App\Http\Controllers\ProjectController::class, 'projectsItems'])->name('projectItemRecords');
	Route::get('project',  [App\Http\Controllers\Inventory\Project\ProjectController::class, 'projects'])->name('project');
	Route::post('project',  [App\Http\Controllers\Inventory\Project\ProjectController::class, 'projects'])->name('project');
	Route::get('projectItemRecords',  [App\Http\Controllers\Inventory\Project\ProjectController::class, 'projectsItems'])->name('projectItemRecords');

	Route::get('inventory/project',  [App\Http\Controllers\Inventory\MajorProject\MajorProjectController::class, 'index'])->name('inventory/project');
	Route::post('inventory/project',  [App\Http\Controllers\Inventory\MajorProject\MajorProjectController::class, 'index'])->name('inventory/project');
	Route::get('inventory/projectTableData',  [App\Http\Controllers\Inventory\MajorProject\MajorProjectController::class, 'filltable'])->name('inventory/projectTableData');
	Route::post('inventory/projectstore',  [App\Http\Controllers\Inventory\MajorProject\MajorProjectController::class, 'store'])->name('inventory/projectstore');
	Route::post('inventory/projectupdate',  [App\Http\Controllers\Inventory\MajorProject\MajorProjectController::class, 'update'])->name('inventory/projectupdate');
	Route::delete('inventory/projectdelete',  [App\Http\Controllers\Inventory\MajorProject\MajorProjectController::class, 'delete'])->name('inventory/projectdelete');
	Route::get('inventory/erpdata',  [App\Http\Controllers\Inventory\MajorProject\MajorProjectController::class, 'reqErp'])->name('inventory/erpdata');
	Route::get('inventory/erpdatatable',  [App\Http\Controllers\Inventory\Maininventory\MainInventoryController::class, 'getItemtable'])->name('inventory/erpdatatable');
	Route::get('inventory/erpchildtable',  [App\Http\Controllers\Inventory\Maininventory\MainInventoryController::class, 'getItemchildtable'])->name('inventory/erpchildtable');


	Route::get('childProjects',  [App\Http\Controllers\Inventory\Project\ProjectHierarchyController::class, 'childProjects'])->name('childProjects');
	Route::get('parentProjects',  [App\Http\Controllers\Inventory\Project\ProjectHierarchyController::class, 'parentProjects'])->name('parentProjects');


	Route::get('projectInbox',  [App\Http\Controllers\Inventory\Project\ProjectController::class, 'projectInbox'])->name('projectInbox');
	Route::get('projectRecords',  [App\Http\Controllers\Inventory\Project\ProjectController::class, 'projectRecords'])->name('projectRecords');

	Route::get('itemHistory',  [App\Http\Controllers\Inventory\Item\ItemController::class, 'itemHistory'])->name('itemHistory');
	
	Route::get('itemRequestList',  [App\Http\Controllers\Inventory\Item\DepotItemReqController::class, 'itemRequestList'])->name('itemRequestList');
	Route::post('itemRequestSave',  [App\Http\Controllers\Inventory\Item\DepotItemReqController::class, 'store'])->name('itemRequestSave');
	Route::delete('deleteRequest',  [App\Http\Controllers\Inventory\Item\DepotItemReqController::class, 'delete'])->name('deleteReq');
	Route::put('updateRequest',  [App\Http\Controllers\Inventory\Item\DepotItemReqController::class, 'update'])->name('updateReq');

	Route::get('itemRequestListforReserv',  [App\Http\Controllers\Inventory\Item\DepotItemAllocateController::class, 'itemRequestList'])->name('itemRequestListforReserv');

	Route::get('itemLotList',  [App\Http\Controllers\Inventory\Item\ItemController::class, 'itemLotList'])->name('itemLotList');
	
	Route::get('reservedItemList',  [App\Http\Controllers\Inventory\Item\DepotItemResevController::class, 'reservedItemList'])->name('reservedItemList');

	Route::post('storeReserve',  [App\Http\Controllers\Inventory\Item\DepotItemAllocateController::class, 'storeReserve'])->name('storeReserve');
	Route::post('updateRequest',  [App\Http\Controllers\Inventory\Item\DepotItemAllocateController::class, 'updateRequest'])->name('updateRequest');
	Route::post('updateConfirm',  [App\Http\Controllers\Inventory\Item\DepotItemAllocateController::class, 'updateConfirm'])->name('updateConfirm');
	
	Route::delete('deleteReserve',  [App\Http\Controllers\Inventory\Item\DepotItemResevController::class, 'delete'])->name('deleteReserve');
	Route::put('updateReserve',  [App\Http\Controllers\Inventory\Item\DepotItemResevController::class, 'update'])->name('updateReserve');

	Route::post('storeUpdate',  [App\Http\Controllers\Inventory\Item\DepotItemLogController::class, 'store'])->name('storeUpdate');
	
	Route::post('changeInvStatus',  [App\Http\Controllers\Inventory\Project\ProjectController::class, 'changeInvStatus'])->name('changeInvStatus');
	Route::get('itemAvailableQty',  [App\Http\Controllers\Inventory\Item\ItemController::class, 'itemAvailableQty'])->name('itemAvailableQty');
	Route::get('usageSummary',  [App\Http\Controllers\Inventory\Item\DepotItemLogController::class, 'usageSummary'])->name('usageSummary');
	
	Route::post('setInvComment',  [App\Http\Controllers\Inventory\Project\ProjectDepotComController::class, 'store'])->name('setInvComment');
	Route::get('getInvComment',  [App\Http\Controllers\Inventory\Project\ProjectDepotComController::class, 'get'])->name('getInvComment');
	
	

	
});

