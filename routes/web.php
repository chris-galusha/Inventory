<?php

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

Route::get('/', function () {
  return redirect('/items');
});

// Authentication Routes
Auth::routes();

// Group of Routes where user must be an admin
Route::middleware(['auth', 'admin'])->group(function () {
  // Admin Controller
  Route::get('/sql/backup', 'AdminController@backupSQL');
  Route::post('/sql/recover', 'AdminController@recoverSQL');
  Route::get('/sql/recover/upload', 'AdminController@recoverSQLUpload');
  Route::get('/sql/recover/select', 'AdminController@recoverSQLSelect');

  Route::get('/admin', 'AdminController@index');
  Route::get('/admin/deleted/restore', 'AdminController@restore');
  Route::get('/admin/deleted/delete', 'AdminController@delete');
  Route::post('/admin/deleted/restore/confirm', 'AdminController@confirmRestore');
  Route::post('/admin/deleted/delete/confirm', 'AdminController@confirmDelete');
  Route::get('/admin/deleted/restore/update', 'AdminController@restoreUpdate');

  // User Controller
  Route::resource('users', 'UserController');
  Route::post('users/confirmDelete/{user}', 'UserController@confirmDelete');

  // Column Controller
  Route::resource('columns', 'ColumnController');
  Route::patch('/columns/{column}/changeType', 'ColumnController@changeType');
  Route::get('/columns/{column}/confirm-delete', 'ColumnController@confirmDelete');

  // Items Controller
  Route::delete('/items/mass/delete', 'ItemsController@massDelete');
  Route::get('/items/mass/delete/confirm', 'ItemsController@confirmMassDelete');

});

// Group of Routes where user must be a normal user or better
Route::middleware(['auth', 'normal'])->group(function () {

  // Report Controller
  Route::resource('reports', 'ReportController');
  Route::get('/reports/run/{report}', 'ReportController@manualRun');
  Route::get('/reports/{report}/select', 'ReportController@selectFromReport');

  // Inventory Controller
  Route::get('/inventory', 'InventoryController@inventory');
  Route::get('/inventory/confirm', 'InventoryController@confirmInventory');
  Route::get('/inventory/upload', 'InventoryController@inventoryUpload');
  Route::post('/inventory/upload/update', 'InventoryController@inventoryUploadUpdate');

  // Decommission Controller
  Route::post('/decommission', 'DecommissionController@decommission');
  Route::get('/decommission/confirm', 'DecommissionController@confirmDecommission');
  Route::get('/decommission/recommission', 'DecommissionController@recommission');
  Route::get('/decommission/recommission/confirm', 'DecommissionController@confirmRecommission');
  Route::get('/decommission/upload', 'DecommissionController@decommissionUpload');
  Route::post('/decommission/upload/update', 'DecommissionController@decommissionUploadUpdate');

  // Import Controller
  Route::get('/import', 'ImportController@import');
  Route::post('/import/map', 'ImportController@map');
  Route::post('/import/upload', 'ImportController@upload');

  // Export Controller
  Route::get('/export', 'ExportController@export');
  Route::post('/export/download', 'ExportController@download');

  //Items Controller
  Route::get('/items/mass/mass-edit', 'ItemsController@massEdit');
  Route::patch('/items/mass/update', 'ItemsController@massUpdate');
  Route::get('/items/select/upload', 'ItemsController@selectUpload');
  Route::post('/items/select', 'ItemsController@selectByCSV');
});

// Items Controller
Route::resource('items', 'ItemsController');

// Filter Controlller
Route::get('/filter', 'FilterController@filter');
Route::post('/filter/advanced', 'FilterController@advancedFilter');

// Auth Controller
Route::get('/logout', 'AuthController@logout');
