<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileUploadController;


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

// Public routes - add authentication if these routes are actually used
// Currently these routes appear to be placeholders - review and secure if needed
Route::get('/public/client/{id}', [\App\Http\Controllers\PublicController::class, 'publicClientEdit'])->middleware('auth');
Route::post('/public/client/{id}/save', [\App\Http\Controllers\PublicController::class, 'publicClientSave'])->middleware('auth');

Auth::routes(['register' => false]);
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
Route::resource('companies', \App\Http\Controllers\CompanyController::class);
Route::resource('orders', \App\Http\Controllers\OrderController::class);
Route::resource('persons', \App\Http\Controllers\PersonController::class);
Route::get('/invoices/paid', [\App\Http\Controllers\InvoiceController::class, 'paidInvoices'])->name('paidInvoices');
Route::get('/invoices/unpaid', [\App\Http\Controllers\InvoiceController::class, 'unpaidInvoices'])->name('unpaidInvoices');
Route::get('/invoices/{payerName}', [\App\Http\Controllers\InvoiceController::class, 'searchByPayer'])->name('searchByPayer');
Route::resource('invoices', \App\Http\Controllers\InvoiceController::class);
Route::get('/services/createCategory', [App\Http\Controllers\ServiceController::class, 'createCategory'])->name('createCategory');
Route::post('/services/createCategory', [App\Http\Controllers\ServiceController::class, 'storeCategory'])->name('storeServiceCategory');
Route::resource('services', \App\Http\Controllers\ServiceController::class);
Route::resource('settings', \App\Http\Controllers\SettingsController::class);
Route::resource('users', \App\Http\Controllers\UsersController::class);
Route::resource('orderService', \App\Http\Controllers\OrderServiceController::class);

Route::get('/renewals', [App\Http\Controllers\OrderController::class, 'renewals'])->name('renewals.index');
Route::get('/renewals/test', [App\Http\Controllers\OrderController::class, 'renewalsTest']);

Route::get('/documents', [App\Http\Controllers\FileUploadController::class, 'showDocuments'])->name('documents.index');

Route::get('/services/category/{id}', [App\Http\Controllers\ServiceController::class, 'showCategory']);
Route::delete('/services/category/{id}', [App\Http\Controllers\ServiceController::class, 'destroyCategory']);
Route::put('/services/category/{id}', [App\Http\Controllers\ServiceController::class, 'updateCategory']);

Route::post('/notes/person/{id}', [App\Http\Controllers\NoteController::class, 'newPersonNote']);
Route::post('/notes/company/{id}', [App\Http\Controllers\NoteController::class, 'newCompanyNote']);
Route::post('/notes/order/{id}', [App\Http\Controllers\NoteController::class, 'newOrderNote']);
Route::post('/notes/delete/{id}', [App\Http\Controllers\NoteController::class, 'deleteNote']);
Route::post('/notes/update/{id}', [App\Http\Controllers\NoteController::class, 'updateNote']);

// KYC Routes
Route::post('/kyc/store', [App\Http\Controllers\KycController::class, 'store'])->name('kyc.store');
Route::put('/kyc/update/{id}', [App\Http\Controllers\KycController::class, 'update'])->name('kyc.update');
Route::delete('/kyc/delete/{id}', [App\Http\Controllers\KycController::class, 'destroy'])->name('kyc.destroy');

Route::post('/order/contact/{id}', [App\Http\Controllers\OrderContactController::class, 'newOrderContact']);
Route::post('/order/contact/update/{id}', [App\Http\Controllers\OrderContactController::class, 'updateOrderContact']);
Route::post('/order/contact/delete/{id}', [App\Http\Controllers\OrderContactController::class, 'deleteOrderContact']);

// Search routes with rate limiting
Route::get('/search', [App\Http\Controllers\SearchController::class, 'search'])->middleware('throttle:30,1');
Route::get('/search/detailed', [App\Http\Controllers\SearchController::class, 'showDetailedSearchForm'])->name('search.detailed.form');
Route::post('/search/detailed', [App\Http\Controllers\SearchController::class, 'detailedSearch'])->name('search.detailed')->middleware('throttle:30,1');
Route::get('/autocomplete', [App\Http\Controllers\SearchController::class, 'autoComplete'])->name('autocomplete')->middleware('throttle:60,1');
Route::get('/autocompleteModal', [App\Http\Controllers\SearchController::class, 'autoCompleteModal'])->name('autoCompleteModal')->middleware('throttle:60,1');
Route::get('/autocompleteModalUser', [App\Http\Controllers\SearchController::class, 'autoCompleteModalUser'])->name('autoCompleteModalUser')->middleware('throttle:60,1');
Route::get('/autocompleteModalPerson', [App\Http\Controllers\SearchController::class, 'autoCompleteModalPerson'])->name('autoCompleteModalPerson')->middleware('throttle:60,1');
Route::get('/search/documents', [App\Http\Controllers\SearchController::class, 'searchDocuments'])->middleware('throttle:30,1');

Route::post('/companies/{id}/client', [\App\Http\Controllers\CompanyController::class, 'storeRelatedPerson']);
Route::post('/orders/{id}/service', [\App\Http\Controllers\OrderController::class, 'storeRelatedService']);
Route::post('/orders/{id}/payment', [\App\Http\Controllers\OrderController::class, 'storeOrderPayment']);
Route::post('/orders/payment/delete/{paymentId}', [\App\Http\Controllers\OrderController::class, 'deleteOrderPayment']);
Route::post('/orders/payment/update/{paymentId}', [\App\Http\Controllers\OrderController::class, 'updateOrderPayment']);
Route::post('/orders/{id}/service/delete', [\App\Http\Controllers\OrderController::class, 'deleteRelatedService']);
Route::post('/companies/{id}/order', [\App\Http\Controllers\OrderController::class, 'storeRelatedCompany']);
Route::post('/companies/{id}/client/delete', [\App\Http\Controllers\CompanyController::class, 'deleteRelatedPerson']);
Route::post('/companies/{id}/client/update', [\App\Http\Controllers\CompanyController::class, 'updateRelatedPerson']);
Route::post('/companies/{id}/company/delete', [\App\Http\Controllers\CompanyController::class, 'deleteRelatedCompany']);

Route::get('/proformas/', [\App\Http\Controllers\InvoiceController::class, 'proformas']);
Route::get('/proformas/{id}', [\App\Http\Controllers\InvoiceController::class, 'showProforma']);
Route::post('/invoices/storeInvoice', [\App\Http\Controllers\InvoiceController::class, 'storeInvoice'])->name('storeInvoice')->middleware('throttle:10,1');


Route::get('/invoice/pdf/{id}', [\App\Http\Controllers\InvoiceController::class, 'createPDF']);
Route::get('/view/pdf/{id}', [\App\Http\Controllers\InvoiceController::class, 'viewPDF']);

// File upload routes with rate limiting
Route::post('storeFile', [FileUploadController::class, 'store'])->middleware('throttle:10,1');

Route::get('/file/company/{companyId}/{file}', [FileUploadController::class, 'viewUploadedCompanyFile'])->middleware('throttle:30,1');
Route::get('/file/person/{personId}/{file}', [FileUploadController::class, 'viewUploadedPersonFile'])->middleware('throttle:30,1');
Route::get('/file/order/{orderId}/{file}', [FileUploadController::class, 'viewUploadedOrderFile'])->middleware('throttle:30,1');

Route::get('/file/download/company/{companyId}/{file}', [FileUploadController::class, 'downloadUploadedCompanyFile'])->middleware('throttle:30,1');
Route::get('/file/download/person/{personId}/{file}', [FileUploadController::class, 'downloadUploadedPersonFile'])->middleware('throttle:30,1');
Route::get('/file/download/order/{orderId}/{file}', [FileUploadController::class, 'downloadUploadedOrderFile'])->middleware('throttle:30,1');

Route::delete('/file/delete/company/{companyId}/{file}', [FileUploadController::class, 'deleteUploadedCompanyFile'])->middleware('throttle:10,1');
Route::delete('/file/delete/person/{personId}/{file}', [FileUploadController::class, 'deleteUploadedPersonFile'])->middleware('throttle:10,1');
Route::delete('/file/delete/order/{orderId}/{file}', [FileUploadController::class, 'deleteUploadedOrderFile'])->middleware('throttle:10,1');

Route::post('/file/archivenr/{fileId}', [FileUploadController::class, 'updateArchiveNumber'])->middleware('throttle:20,1');
Route::post('/file/archivenr/generate/{fileId}', [FileUploadController::class, 'generateArchiveNumber'])->middleware('throttle:20,1');


Route::post('/entitycontact/get/', [\App\Http\Controllers\EntityContactController::class, 'getEntityContacts']);
Route::post('/entitycontact/update/{contactId}', [\App\Http\Controllers\EntityContactController::class, 'updateEntityContact']);
Route::post('/entitycontact/new/{entityId}', [\App\Http\Controllers\EntityContactController::class, 'addNewEntityContact']);
Route::post('/entitycontact/delete/{contactId}', [\App\Http\Controllers\EntityContactController::class, 'deleteEntityContact']);

// Tax Residency routes
Route::post('/taxresidency/person/{personId}', [\App\Http\Controllers\TaxResidencyController::class, 'store']);
Route::put('/taxresidency/{id}', [\App\Http\Controllers\TaxResidencyController::class, 'update']);
Route::delete('/taxresidency/{id}', [\App\Http\Controllers\TaxResidencyController::class, 'destroy']);
Route::get('/taxresidency/person/{personId}', [\App\Http\Controllers\TaxResidencyController::class, 'getByPerson']);

Route::post('/entityaddress/new/{entityId}', [\App\Http\Controllers\EntityAddressController::class, 'addNewEntityAddress']);
Route::post('/entityaddress/delete/{contactId}', [\App\Http\Controllers\EntityAddressController::class, 'deleteEntityAddress']);
Route::post('/entityaddress/update/{contactId}', [\App\Http\Controllers\EntityAddressController::class, 'updateEntityAddress']);


//Company/person Risk Update
Route::post('/company/risk/update', [\App\Http\Controllers\CompanyController::class, 'updateCompanyRisk']);
Route::post('/person/risk/update', [\App\Http\Controllers\PersonController::class, 'updatePersonRisk']);

Route::delete('invoices/{id}', [\App\Http\Controllers\InvoiceController::class, 'destroy']);

/*TEST ROUTES - Protected by environment check*/

// Only allow test routes in non-production environments
if (!app()->environment('production')) {
    Route::get('/test/email', [FileUploadController::class, 'sendTestEmail']);
    Route::get('/test/manualSQL', [\App\Http\Controllers\HomeController::class, 'manualSQL']);
    Route::get('/test/pdf/{id}', [\App\Http\Controllers\InvoiceController::class, 'testPDF']);
    Route::get('/importData', [\App\Http\Controllers\HomeController::class, 'importData']);
    Route::get('/addMissingCompanies', [\App\Http\Controllers\HomeController::class, 'addMissingCompanies']);
}

Route::get('/changelog', [\App\Http\Controllers\HomeController::class, 'changelog']);


/* DROPZONE */

Route::get('dropzone', [FileUploadController::class, 'dropZoneIndex']);

Route::post('dropzone/upload', [FileUploadController::class, 'store'])->name('dropzone.upload')->middleware('throttle:10,1');

Route::post('dropzone/upload-virtual-document', [FileUploadController::class, 'storeVirtualOfficeDocument'])->name('dropzone.upload-virtual-document')->middleware('throttle:10,1');

Route::get('dropzone/fetch', [FileUploadController::class, 'fetch'])->name('dropzone.fetch');

Route::get('dropzone/delete', [FileUploadController::class, 'delete'])->name('dropzone.delete');


/* CRON */

Route::get('cron/last-updated-orders', [\App\Http\Controllers\CronController::class, 'lastUpdatedOrders']);
Route::get('cron/check-kyc-expirations', [\App\Http\Controllers\CronController::class, 'checkKycExpirations']);


/* TEST routes moved to environment check above */

Route::get('/report', [\App\Http\Controllers\HomeController::class, 'report']);

Route::get('/kyc', [\App\Http\Controllers\HomeController::class, 'getKYC'])->name('getKYC');
