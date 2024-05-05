<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [HomeController::class, 'index'])->name('/');
Route::get('/jobs', [JobsController::class, 'index'])->name('jobs');
Route::get('/jobs/detail/{id}', [JobsController::class, 'detail'])->name('jobsDetail');
Route::post('/apply-job', [JobsController::class, 'applyjob'])->name('applyJob');
Route::post('/save-job', [JobsController::class, 'saveJob'])->name('saveJob');



Route::group(['prefix' => 'admin','middleware'=>'checkRole'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/users/{id}', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users', [UserController::class, 'destroy'])->name('admin.users.destroy');
});



Route::group(['account'], function () {

    //Guest Route
    Route::group(['middleware' => 'guest'], function () {
        Route::get('/account/register', [AccountController::class, 'register'])->name('account.register');
        Route::post('/account/process-register', [AccountController::class, 'processRegistration'])->name('account.processRegistration');
        Route::post('/account/authenticate', [AccountController::class, 'authenticate'])->name('account.authenticate');
        Route::get('/account/login', [AccountController::class, 'login'])->name('account.login');


    });

    //Authentocate Route
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/account/profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::get('/account.logout', [AccountController::class, 'logout'])->name('account.logout');
        Route::put('/account/update-profile', [AccountController::class, 'updateProfile'])->name('account.updateProfile');
        Route::post('/account/update-profile-pic', [AccountController::class, 'updateProfilePic'])->name('account.updateProfilePic');
        Route::get('/account/post-Job', [AccountController::class, 'postJob'])->name('account.job.postJob');
        Route::post('/account/process-PostJob', [AccountController::class, 'processPostJob'])->name('account.job.processPostJob');
        Route::get('/my-jobs',[AccountController::class,'myjobs'])->name('account.job.myjobs');
        Route::get('/my-jobs/edit-job/{jobId}',[AccountController::class,'editJob'])->name('account.job.editJob');
        Route::post('/process-EditJob/{jobId}', [AccountController::class, 'processEditJob'])->name('account.processEditJob');
        Route::post('/delete-job', [AccountController::class, 'deleteJob'])->name('account.job.deleteJob');
        Route::get('/my-job-applications',[AccountController::class,'myJobApplications'])->name('account.job.myJobApplications');
        Route::post('/delete-Applied-job', [AccountController::class, 'deleteAppliedJob'])->name('account.job.deleteAppliedJob');
        Route::get('/saved-job', [AccountController::class, 'savedJob'])->name('account.job.savedJob');
        Route::post('/delete-Saved-job', [AccountController::class, 'deleteSavedJob'])->name('account.job.deleteSavedJob');
        Route::post('/update-password', [AccountController::class, 'updatePassword'])->name('account.updatePassword');
    });
});
