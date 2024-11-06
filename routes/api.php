<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductBatchController;
use App\Http\Controllers\ProductSupplierController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleDetailController;
use App\Http\Controllers\WarehouseTransferController;
use App\Http\Controllers\WarehouseTransferDetailController;
use App\Http\Controllers\RestockRequestController;
use App\Http\Controllers\RestockRequestDetailController;
use App\Http\Controllers\BatchController;

Route::apiResource('category', CategoryController::class);
Route::apiResource('supplier', SupplierController::class);
Route::apiResource('product', ProductController::class);
Route::apiResource('warehouse', WarehouseController::class);
Route::apiResource('store', StoreController::class);
Route::apiResource('user', UserController::class);
Route::apiResource('inventory', InventoryController::class);
Route::apiResource('batch', BatchController::class);
Route::apiResource('product_batch', ProductBatchController::class);
Route::apiResource('product_supplier', ProductSupplierController::class);
Route::apiResource('sale', SaleController::class);
Route::apiResource('sale_detail', SaleDetailController::class);
Route::apiResource('warehouse_transfer', WarehouseTransferController::class);
Route::apiResource('warehouse_transfer_detail', WarehouseTransferDetailController::class);
Route::apiResource('restock_request', RestockRequestController::class);
Route::apiResource('restock_request_detail', RestockRequestDetailController::class);

