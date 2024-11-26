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
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleDetailController;
use App\Http\Controllers\WarehouseTransferController;
use App\Http\Controllers\WarehouseTransferDetailController;
use App\Http\Controllers\RestockRequestController;
use App\Http\Controllers\RestockRequestDetailController;
use App\Http\Controllers\BatchController;
use App\Models\Batch;
use App\Models\Product;
use App\Models\WarehouseTransfer;
use App\Http\Controllers\StoreTransferController;
use App\Http\Controllers\StoreTransferDetailController;

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
Route::post('/create_batch', [BatchController::class, 'store']);
Route::patch('/batch/update-status/{id}', [BatchController::class, 'patchUpdate']);
Route::post('/batch/update-status', [BatchController::class, 'updateStatus']);
Route::get('/supplier/{supplier_id}/deliveries', [ProductBatchController::class, 'getSupplierDeliveries']);
Route::get('product/sku/{sku}', [ProductController::class, 'getProductWithCategories']);
Route::get('products/list-names', [ProductController::class, 'getProductNames']);

// Product Images Routes
Route::get('product/{product_id}/images', [ProductImageController::class, 'index']); // Listar imágenes de un producto
Route::post('product/{product_id}/images', [ProductImageController::class, 'store']); // Subir imágenes adicionales
Route::delete('product/image/{image_id}', [ProductImageController::class, 'destroy']); // Eliminar una imagen adicional

//Transfer orders
Route::get('/stores/labels', [StoreController::class, 'store_labels']);
Route::get('/products/labels', [ProductController::class, 'product_names']);
Route::post('/transfer_stock/{orderID}', [WarehouseTransferController::class, 'updateStoreStock']);
Route::post('/transfer_stock_status/{orderID}', [WarehouseTransferController::class, 'updStatusOrder']);
//batches
Route::put('batches/bulk_update', [BatchController::class, 'bulkUpdate']);
Route::get('batches_details/{batchID}', [ProductBatchController::class, 'getBatchWithProducts']);

//STORE
Route::get('inventories/store/{storeID}', [StoreController::class, 'store_inventory']);
Route::get('/user/firebase/{firebase_user_ID}',[UserController::class, 'index']);

//STORE TRANSFER
// Rutas para StoreTransfer
Route::get('store-transfers', [StoreTransferController::class, 'index']);
Route::get('store-transfers/{id}', [StoreTransferController::class, 'show']);
Route::post('store-transfers', [StoreTransferController::class, 'store']);
Route::put('store-transfers/{id}', [StoreTransferController::class, 'update']);
Route::delete('store-transfers/{id}', [StoreTransferController::class, 'destroy']);
Route::post('store-transfers/update-status', [StoreTransferController::class, 'updateStatus']);
Route::post('store-transfers/bulk-update', [StoreTransferController::class, 'bulkUpdate']);
Route::patch('store-transfers/{id}', [StoreTransferController::class, 'patchUpdate']);
Route::post('create-transfer', [StoreTransferController::class, 'createTransfer']); // Nueva ruta para crear transferencia con detalles

// Rutas para StoreTransferDetail
Route::get('store-transfer-details', [StoreTransferDetailController::class, 'index']);
Route::get('store-transfer-details/{id}', [StoreTransferDetailController::class, 'show']);
Route::post('store-transfer-details', [StoreTransferDetailController::class, 'store']);
Route::put('store-transfer-details/{id}', [StoreTransferDetailController::class, 'update']);
Route::delete('store-transfer-details/{id}', [StoreTransferDetailController::class, 'destroy']);