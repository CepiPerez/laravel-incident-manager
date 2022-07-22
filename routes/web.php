<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Livewire;

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

Route::controller(Controllers\IncidentsController::class)->middleware(['auth', 'internal'])->group( function()
{
    Route::get('cargamasiva', 'cargaMasiva')->name('cargamasiva');
    Route::get('cargamasiva/descargar_plantilla', 'descargarExcel')->name('cargamasiva.descargarexcel');
    Route::post('cargamasiva', 'procesar')->name('cargamasiva.guardar');
});

Route::group(['middleware' => 'auth'], function () 
{
    #Route::get('/', Livewire\IncidentsTable::class)->name('incidents.index');
    #Route::get('/incidents/{id}', Livewire\IncidentEdit::class)->name('incidents.edit');
    #Route::get('/create', [Controllers\IncidentsController::class, 'create'])->name('incidents.create');
    #Route::post('/incidents', [Controllers\IncidentsController::class, 'store'])->name('incidents.store');

    Route::resource('incidents', Controllers\IncidentsController::class)->except('show');
    
    Route::get('incidents/attachment/{incident_id}/{progress_id}/{attachment}', 
        [Controllers\IncidentsController::class, 'donwloadAttachment'])->name('incidents.attachment');

    Route::get('reports', [Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/process', [Controllers\ReportController::class, 'process'])->name('reports.process');
    Route::get('reports/download', [Controllers\ReportController::class, 'download'])->name('reports.download');
});

Route::group(['middleware' => ['auth', 'internal'], 'prefix' => 'admin'], function () 
{
    Route::resource('users', Controllers\UsersController::class)->except('show');
    Route::resource('clients', Controllers\ClientsController::class)->except('show');
    Route::resource('groups', Controllers\GroupsController::class)->except('show');
    Route::resource('roles', Controllers\RolesController::class)->except('show');
    Route::resource('areas', Controllers\AreasController::class)->except('show');
    Route::resource('modules', Controllers\ModulesController::class)->except('show');
    Route::resource('problems', Controllers\ProblemsController::class)->except('show');
    Route::resource('servicetypes', Controllers\ServiceTypesController::class)->except('show');
    Route::resource('progresstypes', Controllers\ProgressTypesController::class)->except('show');
    Route::resource('priorities', Controllers\PrioritiesController::class)->only(['index', 'update', 'edit']);
    Route::resource('priorityrules', Controllers\PriorityRulesController::class)->except(['index', 'show']);
    Route::resource('sla', Controllers\SlaController::class)->only(['index', 'update']);
    Route::resource('slarules', Controllers\SlaRulesController::class)->except(['index', 'show']);
    Route::resource('assignation', Controllers\AssignationsController::class)->except('show');

});

Route::middleware('auth')->group( function()
{
    Route::post('/incident/{id}/progress', [Controllers\IncidentProgressController::class, 'store'])->name('incident.progress.store');
    Route::delete('/incident/{id}/progress/{progress}', [Controllers\IncidentProgressController::class, 'destroy'])->name('incident.progress.destroy');

    Route::get('user/{id}', [Controllers\UserProfileController::class, 'edit'])->name('user.profile');
    Route::put('user/{id}', [Controllers\UserProfileController::class, 'update'])->name('user.profile.update');
    
    Route::get('/utilities/get_data/{data}/{param}', [Controllers\DataController::class, 'getData']);

    Route::post('/upload', [Controllers\UploadsController::class, 'store']);
    Route::post('/upload/delete', [Controllers\UploadsController::class, 'destroy']);

    Route::get('/dashboard/{status?}', [Controllers\DashboardController::class, 'index'])->name('dashboard.index');

});



/* Route::controller(Controllers\PriorityRulesController::class)->middleware(['auth', 'internal'])->group( function()
{
    Route::get('reglas', 'index')->name('reglas');
    Route::get('reglas/crear', 'crearRegla')->name('reglas.crear');
    Route::post('reglas', 'guardarRegla')->name('reglas.guardar');
    Route::get('reglas/{id}/editar', 'editarRegla')->name('reglas.editar');
    Route::put('reglas/{id}', 'modificarRegla')->name('reglas.modificar');
    Route::get('reglas/{id}/habilitar', 'habilitarRegla')->name('reglas.habilitar');
    Route::delete('reglas/{id}', 'eliminarRegla')->name('reglas.eliminar');
}); */