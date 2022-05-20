<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'users', 'middleware' => 'auth'], function() {
    Route::get('/profile', 'HomeController@myProfile');
    Route::put('/{id}', 'HomeController@updateUser');
    Route::get('/process/{id}', 'ProcessUserController@getProcessUsers');
    Route::post('/process', 'ProcessUserController@newProcessUser');
    Route::put('/{id}/process/{idProceso}', 'ProcessUserController@editProcessUser');
    Route::delete('/{id}/process/{idProceso}', 'ProcessUserController@deleteProcessUser');
    Route::get('/', 'HomeController@getUsers');
    Route::get('/role/{role}', 'ProcessUserController@getUsersRole');
    Route::get('/process/{id}/role/{role}', 'ProcessUserController@getProcessUsersRole');
});

Route::group(['prefix' => 'issues', 'middleware' => 'auth'], function() {
    Route::get('/', 'IssueController@getIssues');
    Route::get('/type/{type}', 'IssueController@getIssuesType');
    Route::post('/', 'IssueController@newIssue');
    Route::put('/{id}', 'IssueController@editIssue');
    Route::delete('/{id}', 'IssueController@deleteIssue');
    Route::post('/tasks', 'IssueTaskController@newIssueTask');
    Route::get('/{id}/tasks', 'IssueTaskController@getIssueTasks');
    Route::delete('/{id}/tasks', 'IssueTaskController@deleteIssueTasks');
    Route::get('/{id}/issueRegister', 'IssueRegisterController@getIssueRegister');
    Route::post('/issueRegister', 'IssueRegisterController@newIssueRegister');
    Route::put('/issueRegister/{id}', 'IssueRegisterController@editIssueRegister');
    Route::get('/{id}/fiveWhys', 'IssueController@getFiveWhys');
    Route::get('/{id}/fiveWhysBean', 'FiveWhyController@getFiveWhys');
    Route::post('/{id}/fiveWhys', 'FiveWhyController@newFiveWhys');
    Route::get('/{id}', 'IssueController@getIssue');
    Route::get('/{id}/subproblems', 'IssueController@getSubproblems');
    Route::get('/root/elements', 'IssueController@getRootProblems');
    Route::get('/{id}/ishikawa', 'IssueController@getIshikawa');
});

Route::group(['prefix' => 'processes', 'middleware' => 'auth'], function() {
    Route::get('/', 'ProcessController@getProcessesView');
    Route::get('/all', 'ProcessController@getAllProcesses');
    Route::post('/', 'ProcessController@newProcess');
    Route::put('/{id}', 'ProcessController@editProcess');
    Route::delete('/{id}', 'ProcessController@deleteProcess');
    Route::get('/bean/{id}', 'ProcessController@getProcess');
    Route::get('/{id}', 'ProcessController@getProcessView');
    Route::get('/{id}/valueAdded', 'ProcessController@getValueAddedView');
});

Route::group(['prefix' => 'tasks', 'middleware' => 'auth'], function() {
    Route::get('/process/{id}', 'TaskController@getProcessTasks');
    Route::post('/', 'TaskController@newTask');
    Route::put('/{id}', 'TaskController@editTask');
    Route::delete('/{id}', 'TaskController@deleteTask');
});

Route::group(['prefix' => 'documentations', 'middleware' => 'auth'], function() {
    Route::get('/process/{id}', 'DocumentationController@getProcessDocumentations');
    Route::post('/', 'DocumentationController@newDocument');
    Route::delete('/{id}', 'DocumentationController@deleteDocument');
    Route::get('/issueRegister/{id}', 'DocumentationController@getIssueRegisterDocumentations');
    Route::get('/process/{id}/cut', 'DocumentationController@getProcessDocumentationCut');
});

Route::group(['prefix' => 'objectives', 'middleware' => 'auth'], function() {
    Route::get('/process/{id}', 'ObjectiveController@getProcessObjectives');
    Route::post('/', 'ObjectiveController@newObjective');
    Route::put('/{id}', 'ObjectiveController@editObjective');
    Route::delete('/{id}', 'ObjectiveController@deleteObjective');
});

Route::group(['prefix' => 'fiveWhys', 'middleware' => 'auth'], function() {
    Route::post('/why', 'WhyController@newWhy');
    Route::put('/changeRootCause/{id}', 'WhyController@changeRootCause');
});

Route::group(['prefix' => 'notifications', 'middleware' => 'auth'], function() {
    Route::get('/', 'NotificationController@getNotificationsView');
    Route::get('/{id}', 'NotificationController@getNotifications');
    Route::post('/', 'NotificationController@newNotification');
    Route::delete('/{id}', 'NotificationController@deleteNotification');
    Route::post('/sameType', 'NotificationController@deleteNotificationsSameElement');
});

Route::group(['prefix' => 'analysis', 'middleware' => 'auth'], function() {
    Route::post('/', 'ValueAddedAnalysisController@newAnalysis');
    Route::put('/{id}', 'ValueAddedAnalysisController@editAnalysis');
    Route::post('/task', 'TaskValueAddedAnalysisController@newTaskAnalysis');
    Route::put('/task/{id}', 'TaskValueAddedAnalysisController@editTaskAnalysis');
    Route::get('/{id}/tasks', 'TaskValueAddedAnalysisController@getTasksAnalysis');
    Route::get('/{id}', 'ValueAddedAnalysisController@getAnalysisBean');
    Route::get('/wastes/{id}', 'WasteController@getWastes');
    Route::post('/wastes', 'WasteController@newWaste');
    Route::delete('/wastes/{id}', 'WasteController@deleteWaste');
});

Route::group(['prefix' => 'incidences', 'middleware' => 'auth'], function() {
    Route::get('/', 'IssueController@getIncidences');
});
