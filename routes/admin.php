<?php

use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Admin_panel_settingsController;
use App\Http\Controllers\Admin\TreasuresController;
use App\Http\Controllers\Admin\Sales_material_typesController;
use App\Http\Controllers\Admin\StoreControllerstores;
use App\Http\Controllers\Admin\Inv_ums_UomController;
use App\Http\Controllers\Admin\Inv_itemcard_categoryController;
use App\Http\Controllers\Admin\Inv_itemcardController;
use App\Http\Controllers\Admin\Account_typesController;
use App\Http\Controllers\Admin\AccountsController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SuppliersController;
use App\Http\Controllers\Admin\Suppliers_orderControllers;
use App\Http\Controllers\Admin\Suppliers_categoriesController;
use App\Http\Controllers\Admin\Admins_shiftsController;
use App\Http\Controllers\Admin\AdminsControllers;
use App\Http\Controllers\Admin\CollectController;
use App\Http\Controllers\Admin\ExchangeController;
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

define('PAGINATEION_COUNT',10);

Route::group(['prefix' =>'admin', 'middleware'=>'auth:admin'],function(){
    Route::get('/dashboard',[DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('logout',[LoginController::class, 'logout'])->name('admin.logout');
    Route::get('/adminpanelsetting/index',[Admin_panel_settingsController::class, 'index'])->name('admin.adminPanelSettings.index');
    Route::get('/adminpanelsetting/edit',[Admin_panel_settingsController::class, 'edit'])->name('admin.adminPanelSettings.edit');
    Route::post('/adminpanelsetting/update',[Admin_panel_settingsController::class, 'update'])->name('admin.adminPanelSettings.update');

    // start treasures
    Route::get('/treasures/index',[TreasuresController::class, 'index'])->name('admin.treasures.index');
    Route::get('/treasures/create',[TreasuresController::class, 'create'])->name('admin.treasures.create');
    Route::get('/treasures/edit/{id}',[TreasuresController::class, 'edit'])->name('admin.treasures.edit');
    Route::get('/treasures/details/{id}',[TreasuresController::class, 'details'])->name('admin.treasures.details');
    Route::get('/treasures/Add_treasures_delivery/{id}',[TreasuresController::class, 'add_treasures_delivery'])->name('admin.treasures.add_treasures_delivery');
    Route::get('/treasures/delete_treasures_delivery/{id}',[TreasuresController::class, 'delete_treasures_delivery'])->name('admin.delete_treasures_delivery');


    Route::post('/treasures/store',[TreasuresController::class, 'store'])->name('admin.treasures.store');
    Route::post('/treasures/update/{id}',[TreasuresController::class, 'update'])->name('admin.treasures.update');
    Route::post('/treasures/ajax_search', [TreasuresController::class, 'ajax_search'])->name('admin.treasures.ajax_search');
    Route::post('/treasures/store_treasures_delivery/{id}', [TreasuresController::class, 'store_treasures_delivery'])->name('admin.treasures.store_treasures_delivery');

    // end treasures

    // start sales_material_types

    Route::get('/sales_material_types/index',[Sales_material_typesController::class, 'index'])->name('admin.sales_material_types.index');
    Route::get('/sales_material_types/create',[Sales_material_typesController::class, 'create'])->name('admin.sales_material_types.create');
    Route::get('/sales_material_types/edit/{id}',[Sales_material_typesController::class, 'edit'])->name('admin.sales_material_types.edit');
    Route::get('/treasures/delete_sales_material_types/{id}',[Sales_material_typesController::class, 'delete'])->name('admin.delete_sales_material_types');


    Route::post('/sales_material_types/store',[Sales_material_typesController::class, 'store'])->name('admin.sales_material_types.store');
    Route::post('/sales_material_types/update/{id}',[Sales_material_typesController::class, 'update'])->name('admin.sales_material_types.update');
    // end sales_material_types


    // start store 
    Route::get('/store/index',[StoreControllerstores::class, 'index'])->name('admin.stores.index');
    Route::get('/store/create',[StoreControllerstores::class, 'create'])->name('admin.stores.create');
    Route::get('/store/edit/{id}',[StoreControllerstores::class, 'edit'])->name('admin.stores.edit');
    Route::get('/store/delete_stores/{id}',[StoreControllerstores::class, 'delete'])->name('admin.stores.delete');


    Route::post('/store/store',[StoreControllerstores::class, 'store'])->name('admin.stores.store');
    Route::post('/store/update/{id}',[StoreControllerstores::class, 'update'])->name('admin.stores.update');


    // end store


    // start uoms

    Route::get('/uoms/index',[Inv_ums_UomController::class, 'index'])->name('admin.uoms.index');
    Route::get('/uoms/create',[Inv_ums_UomController::class, 'create'])->name('admin.uoms.create');
    Route::get('/uoms/edit/{id}',[Inv_ums_UomController::class, 'edit'])->name('admin.uoms.edit');
    Route::get('/uoms/delete_stores/{id}',[Inv_ums_UomController::class, 'delete'])->name('admin.uoms.delete');


    Route::post('/uoms/store',[Inv_ums_UomController::class, 'store'])->name('admin.uoms.store');
    Route::post('/uoms/update/{id}',[Inv_ums_UomController::class, 'update'])->name('admin.uoms.update');
    Route::post('/uoms/ajax_search', [Inv_ums_UomController::class, 'ajax_search'])->name('admin.uoms.ajax_search');


    // end uoms


    // start categories

    Route::resource('/inv_itemcard_categories', Inv_itemcard_categoryController::class);
    Route::get('/inv_itemcard_categories/delete_stores/{id}',[Inv_itemcard_categoryController::class, 'delete'])->name('inv_itemcard_categories.delete');

    // end categories

    // start item card

    Route::resource('/inv_itemcard', Inv_itemcardController::class);
    Route::post('/inv_itemcard/ajax_search', [Inv_itemcardController::class, 'ajax_search'])->name('admin.inv_itemcard.ajax_search');
    Route::get('/inv_itemcard/delete/{id}',[Inv_itemcardController::class, 'delete'])->name('inv_itemcard.delete');

    // end item card

    // start account types

    Route::get('/account_types/index',[Account_typesController::class, 'index'])->name('admin.account_types.index');


    // end account types


    // start Accounts 

    Route::get('/accounts/index',[AccountsController::class, 'index'])->name('admin.accounts.index');
    Route::get('/accounts/create',[AccountsController::class, 'create'])->name('admin.accounts.create');
    Route::get('/accounts/edit/{id}',[AccountsController::class, 'edit'])->name('admin.accounts.edit');
    Route::get('/accounts/delete_stores/{id}',[AccountsController::class, 'delete'])->name('admin.accounts.delete');


    Route::post('/accounts/store',[AccountsController::class, 'store'])->name('admin.accounts.store');
    Route::post('/accounts/update/{id}',[AccountsController::class, 'update'])->name('admin.accounts.update');
    Route::post('/accounts/ajax_search', [AccountsController::class, 'ajax_search'])->name('admin.accounts.ajax_search');

    
    // end Accounts 
    
    
    
    // start customer 
    Route::get('/customer/index',[CustomerController::class, 'index'])->name('admin.customer.index');
    Route::get('/customer/create',[CustomerController::class, 'create'])->name('admin.customer.create');
    Route::get('/customer/edit/{id}',[CustomerController::class, 'edit'])->name('admin.customer.edit');
    Route::get('/customer/delete_stores/{id}',[CustomerController::class, 'delete'])->name('admin.customer.delete');


    Route::post('/customer/store',[CustomerController::class, 'store'])->name('admin.customer.store');
    Route::post('/customer/update/{id}',[CustomerController::class, 'update'])->name('admin.customer.update');
    Route::post('/customer/ajax_search', [CustomerController::class, 'ajax_search'])->name('admin.customer.ajax_search');
    // end customer
    
    
    // start suppliers  categories
    Route::get('/suppliers_categories/index',[Suppliers_categoriesController::class, 'index'])->name('admin.suppliers_categories.index');
    Route::get('/suppliers_categories/create',[Suppliers_categoriesController::class, 'create'])->name('admin.suppliers_categories.create');
    Route::get('/suppliers_categories/edit/{id}',[Suppliers_categoriesController::class, 'edit'])->name('admin.suppliers_categories.edit');
    Route::get('/suppliers_categories/delete_stores/{id}',[Suppliers_categoriesController::class, 'delete'])->name('admin.suppliers_categories.delete');


    Route::post('/suppliers_categories/store',[Suppliers_categoriesController::class, 'store'])->name('admin.suppliers_categories.store');
    Route::post('/suppliers_categories/update/{id}',[Suppliers_categoriesController::class, 'update'])->name('admin.suppliers_categories.update');
    
    // end suppliers categories


        // start suppliers 
        Route::get('/suppliers/index',[SuppliersController::class, 'index'])->name('admin.suppliers.index');
        Route::get('/suppliers/create',[SuppliersController::class, 'create'])->name('admin.suppliers.create');
        Route::get('/suppliers/edit/{id}',[SuppliersController::class, 'edit'])->name('admin.suppliers.edit');
        Route::get('/suppliers/delete_stores/{id}',[SuppliersController::class, 'delete'])->name('admin.suppliers.delete');
        
    
    
        Route::post('/suppliers/store',[SuppliersController::class, 'store'])->name('admin.suppliers.store');
        Route::post('/suppliers/update/{id}',[SuppliersController::class, 'update'])->name('admin.suppliers.update');
        Route::post('/suppliers/ajax_search', [SuppliersController::class, 'ajax_search'])->name('admin.suppliers.ajax_search');
        // end suppliers

        // start suppliers orders  purchases
        Route::get('/suppliers_orders/index',[Suppliers_orderControllers::class, 'index'])->name('admin.suppliers_orders.index');
        Route::get('/suppliers_orders/create',[Suppliers_orderControllers::class, 'create'])->name('admin.suppliers_orders.create');
        Route::get('/suppliers_orders/edit/{id}',[Suppliers_orderControllers::class, 'edit'])->name('admin.suppliers_orders.edit');
        Route::get('/suppliers_orders/delete_stores/{id}',[Suppliers_orderControllers::class, 'delete'])->name('admin.suppliers_orders.delete');
        Route::get('/suppliers_orders/show/{id}',[Suppliers_orderControllers::class, 'show'])->name('admin.suppliers_orders.show');
        Route::get('/suppliers_orders/delete_details/{id}/{id_parent}',[Suppliers_orderControllers::class, 'delete_details'])->name('admin.suppliers_orders.delete_details');
        Route::get('/suppliers_orders/delete/{id}',[Suppliers_orderControllers::class, 'delete'])->name('admin.suppliers_orders.delete');
        Route::get('/suppliers_orders/do_approve/{id}',[Suppliers_orderControllers::class, 'do_approve'])->name('admin.suppliers_orders.do_approve');
        
        Route::post('/suppliers_orders/store',[Suppliers_orderControllers::class, 'store'])->name('admin.suppliers_orders.store');
        Route::post('/suppliers_orders/update/{id}',[Suppliers_orderControllers::class, 'update'])->name('admin.suppliers_orders.update');
        Route::post('/suppliers_orders/add_new_details', [Suppliers_orderControllers::class, 'add_new_details'])->name('admin.suppliers_orders.ajax_add_new_details');
        Route::post('/suppliers_orders/get_item_uoms', [Suppliers_orderControllers::class, 'get_item_uoms'])->name('admin.suppliers_orders.get_item_uoms');
        Route::post('/suppliers_orders/reload_itemsdetails', [Suppliers_orderControllers::class, 'reload_itemsdetails'])->name('admin.suppliers_orders.reload_itemsdetails');
        Route::post('/suppliers_orders/reload_parent_pill', [Suppliers_orderControllers::class, 'reload_parent_pill'])->name('admin.suppliers_orders.reload_parent_pill');
        Route::post('/suppliers_orders/load_edit_item_details', [Suppliers_orderControllers::class, 'load_edit_item_details'])->name('admin.suppliers_orders.load_edit_item_details');
        Route::post('/suppliers_orders/load_model_add_details', [Suppliers_orderControllers::class, 'load_model_add_details'])->name('admin.suppliers_orders.load_model_add_details');
        Route::post('/suppliers_orders/edit_item_details', [Suppliers_orderControllers::class, 'edit_item_details'])->name('admin.suppliers_orders.edit_item_details');
        Route::post('/suppliers_orders/load_model_approve_invoice', [Suppliers_orderControllers::class, 'load_model_approve_invoice'])->name('admin.suppliers_orders.load_model_approve_invoice');
        Route::post('/suppliers_orders/load_usershiftDiv', [Suppliers_orderControllers::class, 'load_usershiftDiv'])->name('admin.suppliers_orders.load_usershiftDiv');
        Route::post('/suppliers_orders/do_approve/{id}', [Suppliers_orderControllers::class, 'do_approve'])->name('admin.suppliers_orders.do_approve');
        Route::post('/suppliers_orders/ajax_search', [Suppliers_orderControllers::class, 'ajax_search'])->name('admin.suppliers_orders.ajax_search');

        
        
        // end suppliers orders purchases


         // start Admin treasures
        Route::get('/admins_accounts/index',[AdminsControllers::class, 'index'])->name('admin.admins_accounts.index');
        Route::get('/admins_accounts/create',[AdminsControllers::class, 'create'])->name('admin.admins_accounts.create');
        Route::get('/admins_accounts/edit/{id}',[AdminsControllers::class, 'edit'])->name('admin.admins_accounts.edit');
        Route::get('/admins_accounts/details/{id}',[AdminsControllers::class, 'details'])->name('admin.admins_accounts.details');
        Route::get('/admins_accounts/Add_treasures_delivery/{id}',[AdminsControllers::class, 'admins_accounts'])->name('admin.treasures.add_treasures_delivery');
        Route::get('/admins_accounts/delete_treasures_delivery/{id}',[AdminsControllers::class, 'admins_accounts'])->name('admin.delete_treasures_delivery');


        Route::post('/admins_accounts/store',[AdminsControllers::class, 'store'])->name('admin.admins_accounts.store');
        Route::post('/admins_accounts/update/{id}',[AdminsControllers::class, 'update'])->name('admin.admins_accounts.update');
        Route::post('/admins_accounts/ajax_search', [AdminsControllers::class, 'ajax_search'])->name('admin.admins_accounts.ajax_search');
        Route::post('/admins_accounts/store_treasures_to_admin/{id}', [AdminsControllers::class, 'store_treasures_to_admin'])->name('admin.admins_accounts.store_treasures_to_admin');

    // end Admin treasures

    // start admins shifts
        Route::get('/admin_shift/index',[Admins_shiftsController::class, 'index'])->name('admin.admin_shift.index');
        Route::get('/admin_shift/create',[Admins_shiftsController::class, 'create'])->name('admin.admin_shift.create');
        
        Route::post('/admin_shift/store',[Admins_shiftsController::class, 'store'])->name('admin.admin_shift.store');
      

    // end admins shifts
 
 
    // start collect_tranaction
        Route::get('/collect_tranaction/index',[CollectController::class, 'index'])->name('admin.collect_tranaction.index');
        Route::get('/collect_tranaction/create',[CollectController::class, 'create'])->name('admin.collect_tranaction.create');
        
        Route::post('/collect_tranaction/store',[CollectController::class, 'store'])->name('admin.collect_tranaction.store');
      

    // end collect_tranaction


    
    // start Exchange_tranaction
        Route::get('/exchange_tranaction/index',[ExchangeController::class, 'index'])->name('admin.exchange_tranaction.index');
        Route::get('/exchange_tranaction/create',[ExchangeController::class, 'create'])->name('admin.exchange_tranaction.create');
        
        Route::post('/exchange_tranaction/store',[ExchangeController::class, 'store'])->name('admin.exchange_tranaction.store');
      

    // end EExchange_tranaction
} );


Route::group(['namespace'=>'Admin' , 'prefix' =>'admin', 'middleware'=>'guest:admin'],function(){
    Route::get('login',[LoginController::class, 'showLogin'])->name('admin.showlogin');
    Route::post('login',[LoginController::class, 'login'])->name('admin.login');
} );


