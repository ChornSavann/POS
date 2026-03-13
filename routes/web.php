<?php

use App\Http\Controllers\BankController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpenseTypeController;
use App\Http\Controllers\ItemExpenseController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\PurchaseController;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TableController;

// Route::get('login', [UserController::class, 'index'])->name('user.index');
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/test', [TestController::class, 'index'])->name('test.index');

// Route::middleware(['auth'])->group(function () {

//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
//     Route::middleware(['role:admin'])->group(function () {
//         // រាល់ URL ក្នុងនេះ ចូលបានតែ Admin ប៉ុណ្ណោះ
//         Route::resource('units', UnitController::class);
//     });


//     // Route ផ្សេងៗទៀត...
// });


// បង្ហាញបញ្ជី User ទាំងអស់
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
Route::get('/login', [UserController::class, 'login'])->name('users.login');
// Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('/login', [UserController::class, 'authenticate'])->name('login.post');
// Register Routes (បើបងចង់ឱ្យមានការចុះឈ្មោះ)
Route::post('/register', [UserController::class, 'register'])->name('register.post');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');


//category
Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
Route::get('/category/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
Route::put('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
//unit
Route::get('/units', [UnitController::class, 'index'])->name('units.index');
Route::get('/units/create', [UnitController::class, 'create'])->name('units.create');
Route::post('/units', [UnitController::class, 'store'])->name('units.store');
Route::get('/units/{id}/edit', [UnitController::class, 'edit'])->name('units.edit');
Route::put('/units/{id}', [UnitController::class, 'update'])->name('units.update');
Route::delete('/units/{id}', [UnitController::class, 'destroy'])->name('units.destroy');
//brand
Route::get('/brands', [BrandController::class, 'index'])->name('brand.index');
Route::get('/brands/create', [BrandController::class, 'create'])->name('brand.create');
Route::post('/brands', [BrandController::class, 'store'])->name('brand.store');
Route::get('/brands/{id}/edit', [BrandController::class, 'edit'])->name('brand.edit');
Route::put('/brands/{id}', [BrandController::class, 'update'])->name('brand.update');
Route::delete('/brands/{id}', [BrandController::class, 'destroy'])->name('brand.destroy');

//store
Route::get('/store', [StoreController::class, 'index'])->name('store.index');
Route::get('/store/create', [StoreController::class, 'create'])->name('store.create');
Route::post('/store', [StoreController::class, 'store'])->name('store.store');
Route::get('/store/{id}/edit', [StoreController::class, 'edit'])->name('store.edit');
Route::put('/store/{id}', [StoreController::class, 'update'])->name('store.update');
Route::delete('/store/{id}', [StoreController::class, 'destroy'])->name('store.destroy');

// List and Create
Route::get('/table', [TableController::class, 'index'])->name('table.index');
Route::post('/table', [TableController::class, 'store'])->name('table.store');
Route::put('/table/{id}', [TableController::class, 'update'])->name('table.update');
Route::delete('/table/{id}', [TableController::class, 'destroy'])->name('table.destroy');

//supplier
Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
Route::get('/suppliers/{id}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
Route::put('/suppliers/{id}', [SupplierController::class, 'update'])->name('suppliers.update');
Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

//customer
Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
Route::get('/customer/create', [CustomerController::class, 'create'])->name('customer.create');
Route::post('/customer', [CustomerController::class, 'store'])->name('customer.store');
Route::get('/customer/{id}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
Route::put('/customer/{id}', [CustomerController::class, 'update'])->name('customer.update');
Route::delete('/customer/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');

///seller
Route::get('/seller', [SellerController::class, 'index'])->name('seller.index');
Route::get('/seller/create', [SellerController::class, 'create'])->name('seller.create');
Route::post('/seller', [SellerController::class, 'store'])->name('seller.store');
Route::get('/seller/{id}/edit', [SellerController::class, 'edit'])->name('seller.edit');
Route::put('/seller/{id}', [SellerController::class, 'update'])->name('seller.update');
Route::delete('/seller/{id}', [SellerController::class, 'destroy'])->name('seller.destroy');


// ដាក់ក្នុង Group ដើម្បីងាយស្រួលគ្រប់គ្រង (Optional)
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/{id}/update', [ProductController::class, 'update'])->name('products.update');
    Route::get('/get-sub-units/{base_unit_id}', [ProductController::class, 'getSubUnits']);
   // កែត្រង់នេះ៖ លុប /products ចេញ ទុកតែ /suggestions
    Route::get('/generateBarcode', [ProductController::class, 'generateBarcode'])->name('products.generateBarcode');
    Route::get('/suggestions', [ProductController::class, 'getSuggestions'])->name('products.suggestions');
});
// ក្នុង routes/web.php ត្រូវតែមានសញ្ញាសួរ ? បែបនេះ
Route::delete('/products/{id?}/delete', [ProductController::class, 'destroy'])->name('products.destroy');
// ឬបែប Manual (បើចង់កំណត់ខ្លួនឯង)
//Purchase
Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
Route::get('/purchase/create',[PurchaseController::class,'create'])->name('purchases.create');
Route::post('/purchase',[PurchaseController::class,'store'])->name('purchases.store');
Route::post('/purchases/update-status/{id}', [PurchaseController::class, 'updateStatusToReceived'])->name('purchases.updateStatus');
Route::get('purchase/edit/{id}',[PurchaseController::class,'edit'])->name('purchases.edit');
Route::put('/purchase/update/{id}', [PurchaseController::class, 'update'])->name('purchases.update');
Route::delete('/purchase/delete/{id}',[PurchaseController::class,'destroy'])->name('purchase.delete');



//Order
Route::get('/pos',[OrderController::class,'index'])->name('order.index');
Route::post('/orders/update-table-status', [OrderController::class, 'updateTableStatus'])->name('orders.update-table-status');
Route::post('/orders/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
Route::get('/order/invoice/{id}', [OrderController::class, 'showInvoice'])->name('order.invoice');
Route::get('/list/sale/',[OrderController::class,'listOrder'])->name('order.list');
// បន្ថែម Route សម្រាប់បង់លុយជំពាក់
Route::post('/orders/pay-debt', [OrderController::class, 'payDebt'])->name('orders.pay-debt');
Route::get('/orders/print/{id}', [OrderController::class, 'printInvoice'])->name('orders.print');
Route::get('/orders/print-all', [OrderController::class, 'printAll'])->name('orders.printAll');
// Bank Routes
Route::prefix('bank')->group(function () {

    Route::get('/', [BankController::class, 'index'])->name('bank.index');          // list banks
    Route::get('/create', [BankController::class, 'create'])->name('bank.create');   // show create form
    Route::post('/', [BankController::class, 'store'])->name('bank.store');          // save new bank
    Route::get('/edit/{id}', [BankController::class, 'edit'])->name('bank.edit');    // show edit form
    Route::put('/{id}', [BankController::class, 'update'])->name('bank.update');     // update bank
    Route::delete('/{id}', [BankController::class, 'destroy'])->name('bank.destroy'); // delete bank
});

//ItemExpens
Route::prefix('item-expenses')->name('item_expense.')->group(function () {
    Route::get('/', [ItemExpenseController::class, 'index'])->name('index');
    Route::get('/create', [ItemExpenseController::class, 'create'])->name('create');
    Route::post('/store', [ItemExpenseController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ItemExpenseController::class, 'edit'])->name('edit');
    Route::put('/{id}/update', [ItemExpenseController::class, 'update'])->name('update');
    Route::delete('/{id}/delete', [ItemExpenseController::class, 'destroy'])->name('destroy');
});
// របៀបទី២៖ សរសេរដាច់ៗពីគ្នា (បើបងចង់ប្តូរឈ្មោះ Route តាមចិត្ត)
       Route::get('/expense-types', [ExpenseTypeController::class, 'index'])->name('expense_types.index');
       Route::get('/expense-types/create', [ExpenseTypeController::class, 'create'])->name('expense_types.create');
       Route::post('/expense-types', [ExpenseTypeController::class, 'store'])->name('expense_types.store');
       Route::get('/expense-types/{id}/edit', [ExpenseTypeController::class, 'edit'])->name('expense_types.edit');
       Route::put('/expense-types/{id}', [ExpenseTypeController::class, 'update'])->name('expense_types.update');
       Route::delete('/expense-types/{id}', [ExpenseTypeController::class, 'destroy'])->name('expense_types.destroy');

// Group សម្រាប់របាយការណ៍ទាំងអស់
    Route::prefix('reports')->name('reports.')->group(function () {

        // Route សម្រាប់របាយការណ៍ប្រចាំថ្ងៃ
        Route::get('/daily', [ReportController::class, 'Daily'])->name('daily');
      // Route របស់បង
        Route::get('/invoice/{id}', [ReportController::class, 'printInvoice'])->name('invoice');

        // បងអាចបន្ថែមរបាយការណ៍ផ្សេងទៀតនៅទីនេះនាពេលក្រោយ
        // Route::get('/monthly', [ReportController::class, 'Monthly'])->name('monthly');
    });
