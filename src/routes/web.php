<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BarberController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WalkInController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeViewController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\CloseDayController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ShopAddressController;
use App\Http\Controllers\BarberTimeController;

Route::get('/',[LoginController::class,'login'])->name('login')->middleware('guest');


Route::post('authenticate',[LoginController::class,'authenticate'])->name('authenticate');
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('roleCheck');
    Route::get('add-user',[UserController::class,'index'])->middleware('roleCheck');
    Route::get('user-list',[UserController::class,'userList'])->middleware('auth');
    Route::post('user-store',[UserController::class,'userStore'])->name('userStore')->middleware('roleCheck');
    Route::get('user-edit/{id}',[UserController::class,'edit'])->name('userEdit');
    Route::put('user-update/{id}',[UserController::class,'update'])->name('userUpdate');
    Route::delete('user-delete/{id}',[UserController::class,'destroy'])->name('user.delete')->middleware('roleCheck');

Route::resource('/home_views',HomeViewController::class);
    //barbers CRUD
    Route::resource('barbers', BarberController::class);
    Route::delete('barbers/{id}/delete',[BarberController::class,'destroy'])->name('user.delete');
    Route::resource('absence',AbsenceController::class);

    //Walk In Customers CRUD
    Route::resource('walk_in', WalkInController::class);

    Route::resource('close-days', CloseDayController::class);

    //Reports
    Route::get('salary_report',[ReportController::class,'salaryReport']);
    Route::get('services_report',[ReportController::class,'servicesReport'])->middleware('roleCheck');
    Route::get('products_report', [ReportController::class, 'productReport'])->middleware('roleCheck');
    Route::get('expenses_report', [ReportController::class, 'expenseIncomeReport'])->middleware('roleCheck');
    Route::get('performance_report',[ReportController::class,'performanceReport']);

    Route::resource('shop_addresses', ShopAddressController::class);

    //Appointments
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('booking.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('booking.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('booking.store');
    Route::get('/appointments/edit/{id}', [AppointmentController::class, 'edit'])->name('booking.edit');
    Route::put('/appointments/update', [AppointmentController::class, 'update'])->name('booking.update');
    Route::delete('/appointments', [AppointmentController::class, 'destroy'])->name('booking.delete');

Route::get('/appointments/customerPaginationAjax',[AppointmentController::class,'customerPaginationAjax'])->name('booking.customerPaginationAjax');

Route::get('/appointments/customerAjax',[AppointmentController::class,'customerAjax'])->name('booking.customerAjax');
    Route::get('logout',[LoginController::class, 'logout'])->name('logout')->middleware('auth');

    Route::get('/calendar', [AppointmentController::class, 'showCalendar']);

    //Services CRUD
    Route::get('services',[ServiceController::class,'index']);
    Route::get('service/create',[ServiceController::class,'create'])->middleware('roleCheck');
    Route::post('service/store',[ServiceController::class,'store'])->middleware('roleCheck');
    Route::get('service/edit/{id}',[ServiceController::class,'edit'])->middleware('roleCheck');
    Route::put('service/update/{id}',[ServiceController::class,'update'])->middleware('roleCheck');
    Route::delete('service/delete/{id}',[ServiceController::class,'destroy'])->middleware('roleCheck');

    //Products CRUD
    Route::get('products',[ProductController::class,'index'])->middleware('roleCheck');
    Route::get('products/create',[ProductController::class,'create'])->middleware('roleCheck');
    Route::post('products/store',[ProductController::class,'store'])->middleware('roleCheck');
    Route::get('products/edit/{id}',[ProductController::class,'edit'])->middleware('roleCheck');
    Route::put('products/update/{id}',[ProductController::class,'update'])->middleware('roleCheck');
    Route::delete('products/delete/{id}',[ProductController::class,'destroy'])->middleware('roleCheck');
    Route::get('products/details/',[ProductController::class,'productDetails'])->middleware('roleCheck');
    Route::post('products/sold',[ProductController::class,'soldForm'])->middleware('roleCheck');

    //Expense CRUD
    Route::resource('expense', ExpenseController::class)->middleware('roleCheck');
    Route::resource('expense_category', ExpenseCategoryController::class)->middleware('roleCheck');

    // Audit Logs
    Route::get('/audit_logs', [AuditLogController::class, 'index'])->middleware('roleCheck');

    //Customers CRUD
    Route::get('customers/create',[CustomerController::class,'create'])->name('customer.create');
    Route::get('customers', [CustomerController::class, 'index'])->name('customer.index');
    Route::post('customers/store',[CustomerController::class,'store'])->name('customer.store');
    Route::get('customers/{id}', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::put('customer', [CustomerController::class, 'update'])->name('customer.update');
    Route::delete('customers', [CustomerController::class, 'destroy'])->name('customer.destroy');

    // Gallery
    Route::get('gallery', [GalleryController::class, 'index'])->name('gallery')->middleware('roleCheck');
    Route::get('gallery/create',[GalleryController::class,'create'])->middleware('roleCheck');
    Route::post('gallery/store',[GalleryController::class,'store'])->middleware('roleCheck');
    Route::get('gallery/edit/{id}',[GalleryController::class,'edit'])->middleware('roleCheck');
    Route::put('gallery/{id}',[GalleryController::class,'update'])->middleware('roleCheck');
    Route::delete('gallery/delete/{id}',[GalleryController::class,'destroy'])->middleware('roleCheck');
    //pay slip
    Route::get('/pay-slip', function() {
        return view('pay_slip');
    });
    //Barber Time
    Route::get('barber/time', [BarberTimeController::class, 'index'])->name('barbertime');

});




// Route::get('/dashboard', [DashboardController::class, 'index']);

