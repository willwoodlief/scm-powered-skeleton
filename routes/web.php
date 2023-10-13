<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContractorsController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

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





Route::get('/', [HomeController::class, 'root_page'])->name('root');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard',[HomeController::class,'dashboard'] )->name('dashboard');

    Route::prefix('employees')->group(function () {
        Route::get('/',[EmployeeController::class,'index'] )->name('employees');

        Route::get('/profile/{employee_id}',[EmployeeController::class,'employee_profile'] )
            ->where('employee_id', '[0-9]+')
            ->name('employee.profile');

        Route::get('/new',[EmployeeController::class, 'new_employee'] )->name('employee.new');

        Route::post('/new',[EmployeeController::class,'create_employee'] )->name('employee.create');

        Route::get('/update/{employee_id}',[EmployeeController::class,'update_employee_form'] )
            ->where('employee_id', '[0-9]+')
            ->name('employee.edit');

        Route::patch('/update/{employee_id}',[EmployeeController::class,'update_employee'] )
            ->where('employee_id', '[0-9]+')
            ->name('employee.update');

        Route::delete('/delete/{employee_id}',[EmployeeController::class,'delete_employee'] )
            ->where('employee_id', '[0-9]+')
            ->name('employee.delete');

        Route::prefix('hr')->middleware('human_resources')->group(function () {
            Route::post('/web_access/{employee_id}',[EmployeeController::class,'web_access'] )
                ->where('employee_id', '[0-9]+')
                ->name('employee.hr.web_access');
        });

    });

    Route::prefix('projects')->group(function () {
        Route::get('/',[ProjectController::class,'index'] )->name('projects');

        Route::get('/view/{project_id}',[ProjectController::class,'view_project'] )
            ->where('project_id', '[0-9]+')
            ->name('project.view');

        Route::get('/new',[ProjectController::class, 'new_project_form'] )->name('project.new');

        Route::post('/create',[ProjectController::class,'create_project'] )->name('project.create');

        Route::get('/edit/{project_id}',[ProjectController::class, 'edit_project'] )
            ->where('project_id', '[0-9]+')
            ->name('project.edit');

        Route::patch('/update/{project_id}',[ProjectController::class,'update_project'] )
            ->where('project_id', '[0-9]+')
            ->name('project.update');



        Route::delete('/delete/{project_id}',[ProjectController::class,'delete_project'] )
            ->where('project_id', '[0-9]+')
            ->name('project.delete');

        Route::prefix('files')->group(function () {

            Route::post('/new/{project_id}', [ProjectController::class, 'upload_file'])
                ->where('project_id', '[0-9]+')
                ->name('project.file.create');

            Route::delete('/delete_file/{project_id}', [ProjectController::class, 'delete_file'])
                ->where('project_id', '[0-9]+')
                ->name('project.file.delete');
        });


        Route::prefix('logs')->group(function () {

            Route::post('/new/{project_id}', [ProjectController::class, 'create_log'])
                ->where('project_id', '[0-9]+')
                ->name('project.log.create');

        });


    });



    Route::prefix('transactions')->middleware('accounting')->group(function () {
        Route::get('/',[ExpenseController::class,'index'] )->name('transactions');

        Route::get('/new',[ExpenseController::class,'new_expense'] )->name('transactions.new_form');

        Route::post('/new',[ExpenseController::class,'create_expense'] )->name('transactions.create');

        Route::get('/edit/{expense_id}',[ExpenseController::class, 'edit_expense'] )
            ->where('expense_id', '[0-9]+')
            ->name('transactions.edit');

        Route::patch('/update/{expense_id}',[ExpenseController::class,'update_expense'] )
            ->where('expense_id', '[0-9]+')
            ->name('transactions.update');

        Route::delete('/delete/{expense_id}',[ExpenseController::class,'delete_expense'] )
            ->where('expense_id', '[0-9]+')
            ->name('transactions.delete');

    });

    Route::prefix('contractors')->group(function () {
        Route::get('/',[ContractorsController::class,'index'] )->name('contractors');

        Route::get('/new',[ContractorsController::class,'add_contractor_form'] )->name('contractor.new');

        Route::post('/new',[ContractorsController::class,'create_contractor'] )->name('contractor.create');

        Route::get('/view/{contractor_id}',[ContractorsController::class, 'view_contractor'] )
            ->where('contractor_id', '[0-9]+')
            ->name('contractor.view');

        Route::get('/edit/{contractor_id}',[ContractorsController::class, 'edit_contractor'] )
            ->where('contractor_id', '[0-9]+')
            ->name('contractor.edit');

        Route::patch('/update/{contractor_id}',[ContractorsController::class,'update_contractor'] )
            ->where('contractor_id', '[0-9]+')
            ->name('contractor.update');

        Route::delete('/delete/{contractor_id}',[ContractorsController::class,'delete_contractor'] )
            ->where('contractor_id', '[0-9]+')
            ->name('contractor.delete');
    });

    Route::prefix('accounting')->middleware('accounting')->group(function () {
        Route::get('/',[InvoiceController::class,'index'] )->name('accounting');


        Route::prefix('invoice')->group(function () {

            Route::get('/list',[InvoiceController::class,'index'] )->name('invoice.list');

            Route::get('/new',[InvoiceController::class, 'new_invoice'] )->name('invoice.new');
            Route::post('/create', [InvoiceController::class, 'create_invoice'])->name('invoice.create');

            Route::patch('{invoice_id}/pay', [InvoiceController::class, 'pay_invoice'])
                ->where('invoice_id', '[0-9]+')
                ->name('invoice.pay');

            Route::delete('{invoice_id}/delete',[InvoiceController::class,'delete_invoice'] )
                ->where('invoice_id', '[0-9]+')
                ->name('invoice.delete');

        });


    });

    Route::prefix('notifications')->group(function () {
        Route::get('/',[NotificationController::class,'index'] )->name('notifications');
        Route::get('/list', [NotificationController::class, 'list_notifications'])->name('notifications.list');
        Route::get('/new', [NotificationController::class, 'new_notification'])->name('notifications.new');
        Route::post('/create', [NotificationController::class, 'create_notification'])->name('notifications.create');
        Route::delete('{notification_id}/delete',[NotificationController::class,'delete_notification'] )
            ->where('notification_id', '[0-9]+')
            ->name('notifications.delete');
    });


    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('tasks');
        Route::get('/list', [TaskController::class, 'list_tasks'])->name('tasks.list');
        Route::get('/new', [TaskController::class, 'new_task'])->name('tasks.new');
        Route::post('/create', [TaskController::class, 'create_task'])->name('tasks.create');
        Route::delete('/delete/{todo_id}', [TaskController::class, 'delete_task'])
            ->where('todo_id', '[0-9]+')
            ->name('tasks.delete');
    });

    Route::prefix('chat')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('chat');
        Route::get('/get_last_messages', [ChatController::class, 'get_last_messages'])->name('chat.messages');
        Route::post('/create', [ChatController::class, 'create_message'])->name('chat.create');
        Route::delete('/delete/{chat_id}', [ChatController::class, 'delete_chat'])
            ->where('chat_id', '[0-9]+')
            ->name('chat.delete');
    });

    Route::prefix('user_logs')->group(function () {
        Route::get('/', [\App\Http\Controllers\LogController::class, 'index'])->name('user_logs');
    });
});


Route::prefix('user')->middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::get('/logs', [AdminController::class, 'logs'])->name('admin.logs');
    Route::prefix('users')->middleware('auth')->group(function () {
        Route::get('/', [AdminUserController::class, 'list_users'])->name('admin.users');
        Route::get('/new',[AdminUserController::class, 'new_user'] )->name('admin.user.new');
        Route::post('/create', [AdminUserController::class, 'create_user'])->name('admin.user.create');


        Route::get('/edit/{user_id}', [AdminUserController::class, 'edit_user'])
            ->where('user_id', '[0-9]+')
            ->name('admin.user.edit');

        Route::patch('/update/{user_id}', [AdminUserController::class, 'update_user'])
            ->where('user_id', '[0-9]+')
            ->name('admin.user.update');

        Route::delete('/delete/{user_id}', [AdminUserController::class, 'delete_user'])
            ->where('user_id', '[0-9]+')
            ->name('admin.user.delete');
    });


});

Route::get('/test',function() {
    dd( app()->publicPath('plugins'));
} )->name('test');


require __DIR__.'/auth.php';
