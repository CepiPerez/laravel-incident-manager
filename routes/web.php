<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

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

require __DIR__.'/auth.php';

Route::redirect('/', 'incidents');

Route::group(['middleware' => 'auth'], function () 
{
    Route::resource('incidents', Controllers\IncidentController::class)->except('show');
    
    Route::get('incidents/attachment/{incident_id}/{progress_id}/{attachment}', 
        [Controllers\AttachmentController::class, 'donwload'])->name('incidents.attachment');

    Route::post('/incident/{id}/progress', [Controllers\IncidentProgressController::class, 'store'])->name('incident.progress.store');
    Route::delete('/incident/{id}/progress/{progress}', [Controllers\IncidentProgressController::class, 'destroy'])->name('incident.progress.destroy');

    Route::get('user/{id}', [Controllers\UserProfileController::class, 'edit'])->name('user.profile');
    Route::put('user/{id}', [Controllers\UserProfileController::class, 'update'])->name('user.profile.update');
    
    Route::get('/utilities/get_data/{data}/{param}', [Controllers\DataController::class, 'getData']);

    Route::post('/upload', [Controllers\AttachmentController::class, 'store']);
    Route::post('/upload/delete', [Controllers\AttachmentController::class, 'destroy']);

    Route::get('/dashboard/{status?}', [Controllers\DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('reports', [Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/process', [Controllers\ReportController::class, 'process'])->name('reports.process');
    Route::get('reports/download', [Controllers\ReportController::class, 'download'])->name('reports.download');

});

Route::group(['middleware' => ['auth', 'internal'], 'prefix' => 'admin'], function () 
{
    Route::resource('users', Controllers\UserController::class)->except('show');
    Route::resource('clients', Controllers\ClientController::class)->except('show');
    Route::resource('groups', Controllers\GroupController::class)->except('show');
    Route::resource('roles', Controllers\RoleController::class)->except('show');
    Route::resource('areas', Controllers\AreaController::class)->except('show');
    Route::resource('modules', Controllers\ModuleController::class)->except('show');
    Route::resource('problems', Controllers\ProblemController::class)->except('show');
    Route::resource('servicetypes', Controllers\ServiceTypeController::class)->except('show');
    Route::resource('progresstypes', Controllers\ProgressTypeController::class)->except('show');
    Route::resource('priorities', Controllers\PriorityController::class)->only(['index', 'update', 'edit']);
    Route::resource('priorityrules', Controllers\PriorityRuleController::class)->except(['index', 'show']);
    Route::resource('sla', Controllers\SlaController::class)->only(['index', 'update']);
    Route::resource('slarules', Controllers\SlaRuleController::class)->except(['index', 'show']);
    Route::resource('assignation', Controllers\AssignationController::class)->except('show');

});
