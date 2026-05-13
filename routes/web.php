<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\StaffTaskController;
use App\Http\Controllers\Admin\MedicineController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\CommitmentNoteController;
use App\Http\Controllers\Admin\ColumnSettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\TaskAssignmentController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\ReportController;


Route::get('/', function () {
    return view('welcome');
});

// Authentication
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    // Dashboards
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/staff/dashboard', [StaffDashboardController::class, 'index'])->name('staff.dashboard');

    // Staff-specific
    Route::get('/staff/my-tasks', [StaffTaskController::class, 'myTasks'])->name('staff.my-tasks');
    Route::get('/staff/complete-task/{staffTask}', [StaffTaskController::class, 'completeTaskForm'])->name('staff.complete-task.form');
    Route::post('/staff/complete-task/{staffTask}', [StaffTaskController::class, 'completeTask'])->name('staff.complete-task');

    // Profile & Settings
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::get('/settings', fn() => view('settings.index'))->name('settings.index');

    // Admin-only routes
    Route::middleware(['role:admin'])->group(function () {
        Route::prefix('admin')->name('admin.')->group(function () {

            Route::get('/users', [DashboardController::class, 'users'])->name('users.index');

            // ============= MEDICINE SEARCH ROUTE - MUST BE BEFORE RESOURCE ROUTES =============
            Route::get('medicines/search', [CommitmentNoteController::class, 'searchMedicines'])
                ->name('medicines.search');

            // ============= DYNAMIC PRODUCT UPDATES (NEW) =============
            // Route for supplier search suggestions
            Route::get('suppliers/search', [CommitmentNoteController::class, 'searchSuppliers'])->name('suppliers.search');

            // NEW ROUTE
Route::get('medicines/search-suppliers', [CommitmentNoteController::class, 'searchMedicineSuppliers'])
    ->name('medicines.search-suppliers');

            // Route for updating dynamic product details (Order Qty, Supplier ID, Remarks)
            Route::post('commitment-notes-product/{id}/update-details', [CommitmentNoteController::class, 'updateProductDetails'])->name('commitment-notes-product.update-details');
Route::post('commitment-notes-product/{id}/update-status', [CommitmentNoteController::class, 'updateProductStatus'])->name('commitment-notes-product.update-status');

        // ✅ NEW ROUTE - Get current stage for AJAX update
Route::get('commitment-notes-product/{id}/stage', [CommitmentNoteController::class, 'getProductStage'])
     ->name('commitment-notes-product.stage');

     Route::get('commitment-notes-product/{id}/edit-product',
    [CommitmentNoteController::class, 'editByProduct'])
    ->name('admin.commitment-notes.edit-product');

Route::get('commitment-notes-product/{id}/show-product',
    [CommitmentNoteController::class, 'showByProduct'])
    ->name('admin.commitment-notes.show-product');

Route::post('commitment-notes-product/{id}/update-details',
    [CommitmentNoteController::class, 'updateProductDetails'])
    ->name('admin.commitment-notes.update-product-details');

Route::post('commitment-notes-product/{id}/update-status',
    [CommitmentNoteController::class, 'updateProductStatus'])
    ->name('admin.commitment-notes.update-product-status');

Route::delete('commitment-notes-product/{id}',
    [CommitmentNoteController::class, 'destroyProduct'])
    ->name('admin.commitment-notes.destroy-product');

            // ============= MEDICINE ROUTES =============
            Route::get('medicines/import/form', [MedicineController::class, 'showImportForm'])->name('medicines.import.form');
            Route::post('medicines/import', [MedicineController::class, 'import'])->name('medicines.import');
            Route::get('medicines/import/template', [MedicineController::class, 'downloadTemplate'])->name('medicines.import.template');
            Route::get('medicines/categories', [App\Http\Controllers\Admin\MedicineController::class, 'getCategories'])->name('medicines.categories');
            Route::get('medicines/search-with-details', [App\Http\Controllers\Admin\MedicineController::class, 'searchWithDetails'])->name('medicines.search-with-details');

            // ============= MRP PRICE UPDATE ROUTE (NEW) =============
            Route::patch('medicines/{medicine}/update-price', [MedicineController::class, 'updatePrice'])->name('medicines.update-price');

            // RESOURCE ROUTES
            Route::resource('medicines', MedicineController::class);

            // ============= PRODUCTS ROUTES (Alias for Medicines) =============
            Route::get('products/import/form', [MedicineController::class, 'showImportForm'])->name('products.import.form');
            Route::post('products/import', [MedicineController::class, 'import'])->name('products.import');
            Route::get('products/import/template', [MedicineController::class, 'downloadTemplate'])->name('products.import.template');

            Route::resource('products', MedicineController::class)->parameters(['products' => 'medicine']);

            // ============= SUPPLIER ROUTES =============
            Route::resource('suppliers', SupplierController::class);

            // ============= COMMITMENT NOTES ROUTES =============
            // IMPORTANT: 'all' route MUST come before the resource route
            // ============= COMMITMENT NOTES ROUTES =============
           

            Route::get('commitment-notes/all', [CommitmentNoteController::class, 'allRecords'])
                ->name('commitment-notes.all');

            Route::get('commitment-notes/edit-product/{productId}', [CommitmentNoteController::class, 'editByProduct'])
                ->name('commitment-notes.edit-product');
            // ✅ CORRECT — group already adds 'admin.' prefix
Route::get('commitment-notes/show-product/{productId}',
    [CommitmentNoteController::class, 'showByProduct'])
    ->name('commitment-notes.show-product');

            Route::resource('commitment-notes', CommitmentNoteController::class);

            // ============= WORKFLOW STAGE UPDATE ROUTE =============
            Route::post('commitment-notes/{commitmentNote}/workflow-stage', [CommitmentNoteController::class, 'updateWorkflowStage'])
                ->name('commitment-notes.update-workflow-stage');

            Route::delete('commitment-notes-product/{id}', [CommitmentNoteController::class, 'destroyProduct'])
    ->name('commitment-notes-product.destroy');

            // ============= COLUMN SETTINGS ROUTES =============
            Route::get('/column-settings', [ColumnSettingController::class, 'index'])->name('column-settings.index');
            Route::post('/column-settings', [ColumnSettingController::class, 'update'])->name('column-settings.update');
            Route::delete('/column-settings/{id}', [ColumnSettingController::class, 'deleteCustomColumn'])->name('column-settings.delete');

            // ============= WORK LISTS ROUTES =============
            Route::resource('work-lists', \App\Http\Controllers\Admin\WorkListController::class);
            Route::post('/work-lists/{workList}/mark-complete', [\App\Http\Controllers\Admin\WorkListController::class, 'markTaskAsComplete'])->name('work-lists.mark-complete');
            Route::post('/work-lists/{workList}/mark-incomplete', [\App\Http\Controllers\Admin\WorkListController::class, 'markTaskAsIncomplete'])->name('work-lists.mark-incomplete');

            // ============= STAFF MANAGEMENT ROUTES =============
            Route::resource('staffs', StaffController::class);

            // ============= TASKS ROUTES =============
            Route::resource('tasks', \App\Http\Controllers\Admin\TaskController::class);

            // ============= TASK ASSIGNMENT ROUTES =============
            Route::resource('task-assignments', TaskAssignmentController::class);

            // ============= STAFF TASKS ROUTES =============
            Route::resource('staff-tasks', \App\Http\Controllers\Admin\StaffTaskController::class);
            Route::post('/staff-tasks/{staffTask}/complete', [\App\Http\Controllers\Admin\StaffTaskController::class, 'completeWithProof'])->name('staff-tasks.complete');

            // ============= STAFF TASKS API ROUTE =============
            Route::get('/staff/{id}/tasks', [StaffController::class, 'getStaffTasks'])->name('staff.tasks');

            // ============= REPORTS ROUTES =============
            Route::prefix('reports')->name('reports.')->group(function () {
                Route::get('/cs-sales', [ReportController::class, 'csSales'])->name('cs-sales');
                Route::get('/cs-return', [ReportController::class, 'csReturn'])->name('cs-return');
                Route::get('/cs-nil-stock', [ReportController::class, 'csNilStock'])->name('cs-nil-stock');
            });
        });
    });
});
