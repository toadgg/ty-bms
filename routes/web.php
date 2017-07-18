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
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');


Auth::routes();

// 系统权限，角色，用户管理
Route::resource('users', 'UserController', [
    'middleware' => ['auth']
]);
Route::resource('roles', 'RoleController', [
    'middleware' => ['auth']
]);
Route::resource('permissions', 'PermissionController', [
    'middleware' => ['auth']
]);

// 系统管理
Route::middleware(['auth'])->prefix('settings')->group(function () {
    Route::get('sync', 'SettingController@settingSync')->name('settings.sync.index');
    Route::post('sync/all', 'SettingController@syncAll')->name('settings.sync.all');
    Route::post('sync/attachments', 'SettingController@syncAttachments')->name('settings.sync.attachments');

});

// 基础档案
Route::resource('projects', 'ProjectController', [
    'only' => ['index', 'show', 'update'],
    'middleware' => ['auth']
]);

Route::resource('sections', 'ProjectSectionController', [
    'middleware' => ['auth']
]);

Route::resource('contracts', 'ContractController', [
    'only' => ['index', 'show', 'edit', 'update'],
    'middleware' => ['auth']
]);

// 产值申报（应收）
Route::resource('outputs', 'ProjectOutputValueController', [
    'middleware' => ['auth']
]);

// 回款登记（实收）
Route::resource('receipts', 'ProjectReceiptController', [
    'middleware' => ['auth']
]);


// 资金计划（支出）
Route::group(['prefix' => 'plans'], function () {
    Route::get('capital/{capital}/category/{category}', 'CapitalPlanDetailController@index')
        ->name('plans.capital.details.index');
    Route::post('capital/{capital}/category/{category}', 'CapitalPlanDetailController@store')
        ->name('plans.capital.details.store');
    Route::resource('capital', 'CapitalPlanController', [
        'as' => 'plans',
        'middleware' => ['auth'],
    ]);
});

// 报表
Route::group(['prefix' => 'charts'], function () {
    Route::resource('balance', 'BalanceSheetController', [
        'middleware' => ['auth'],
        'as' => 'charts'
    ]);
});


