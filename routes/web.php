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

Route::get('/public/client/{id}', [\App\Http\Controllers\PublicController::class, 'publicClientEdit']);
Route::post('/public/client/{id}/save', [\App\Http\Controllers\PublicController::class, 'publicClientSave']);

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

Route::get('/renewals', [App\Http\Controllers\OrderController::class, 'renewals']);
Route::get('/renewals/test', [App\Http\Controllers\OrderController::class, 'renewalsTest']);

Route::get('/documents', [App\Http\Controllers\FileUploadController::class, 'showDocuments']);

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

Route::get('/search', [App\Http\Controllers\SearchController::class, 'search']);
Route::get('/search/detailed', [App\Http\Controllers\SearchController::class, 'showDetailedSearchForm'])->name('search.detailed.form');
Route::post('/search/detailed', [App\Http\Controllers\SearchController::class, 'detailedSearch'])->name('search.detailed');
Route::get('/autocomplete', [App\Http\Controllers\SearchController::class, 'autoComplete'])->name('autocomplete');
Route::get('/autocompleteModal', [App\Http\Controllers\SearchController::class, 'autoCompleteModal'])->name('autoCompleteModal');
Route::get('/autocompleteModalUser', [App\Http\Controllers\SearchController::class, 'autoCompleteModalUser'])->name('autoCompleteModalUser');
Route::get('/autocompleteModalPerson', [App\Http\Controllers\SearchController::class, 'autoCompleteModalPerson'])->name('autoCompleteModalPerson');
Route::get('/search/documents', [App\Http\Controllers\SearchController::class, 'searchDocuments']);

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
Route::post('/invoices/storeInvoice', [\App\Http\Controllers\InvoiceController::class, 'storeInvoice'])->name('storeInvoice');


Route::get('/invoice/pdf/{id}', [\App\Http\Controllers\InvoiceController::class, 'createPDF']);
Route::get('/view/pdf/{id}', [\App\Http\Controllers\InvoiceController::class, 'viewPDF']);

Route::get('/test/pdf/{id}', [\App\Http\Controllers\InvoiceController::class, 'testPDF']);

Route::post('storeFile', [FileUploadController::class, 'store']);

Route::get('/file/company/{companyId}/{file}', [FileUploadController::class, 'viewUploadedCompanyFile']);
Route::get('/file/person/{personId}/{file}', [FileUploadController::class, 'viewUploadedPersonFile']);
Route::get('/file/order/{orderId}/{file}', [FileUploadController::class, 'viewUploadedOrderFile']);

Route::get('/file/download/company/{companyId}/{file}', [FileUploadController::class, 'downloadUploadedCompanyFile']);
Route::get('/file/download/person/{personId}/{file}', [FileUploadController::class, 'downloadUploadedPersonFile']);
Route::get('/file/download/order/{orderId}/{file}', [FileUploadController::class, 'downloadUploadedOrderFile']);

Route::delete('/file/delete/company/{companyId}/{file}', [FileUploadController::class, 'deleteUploadedCompanyFile']);
Route::delete('/file/delete/person/{personId}/{file}', [FileUploadController::class, 'deleteUploadedPersonFile']);
Route::delete('/file/delete/order/{orderId}/{file}', [FileUploadController::class, 'deleteUploadedOrderFile']);

Route::post('/file/archivenr/{fileId}', [FileUploadController::class, 'updateArchiveNumber']);
Route::post('/file/archivenr/generate/{fileId}', [FileUploadController::class, 'generateArchiveNumber']);


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

/*TEST ROUTES*/

Route::get('/test/email', [FileUploadController::class, 'sendTestEmail']);
Route::get('/changelog', [\App\Http\Controllers\HomeController::class, 'changelog']);


Route::get('/importData', [\App\Http\Controllers\HomeController::class, 'importData']);
Route::get('/addMissingCompanies', [\App\Http\Controllers\HomeController::class, 'addMissingCompanies']);


/* DROPZONE */

Route::get('dropzone', [FileUploadController::class, 'dropZoneIndex']);

Route::post('dropzone/upload', [FileUploadController::class, 'store'])->name('dropzone.upload');

Route::post('dropzone/upload-virtual-document', [FileUploadController::class, 'storeVirtualOfficeDocument'])->name('dropzone.upload-virtual-document');

Route::get('dropzone/fetch', [FileUploadController::class, 'fetch'])->name('dropzone.fetch');

Route::get('dropzone/delete', [FileUploadController::class, 'delete'])->name('dropzone.delete');


/* CRON */

Route::get('cron/last-updated-orders', [\App\Http\Controllers\CronController::class, 'lastUpdatedOrders']);
Route::get('cron/check-kyc-expirations', [\App\Http\Controllers\CronController::class, 'checkKycExpirations']);


/* TEST */
Route::get('test/manualSQL', [\App\Http\Controllers\HomeController::class, 'manualSQL']);

Route::get('/report', [\App\Http\Controllers\HomeController::class, 'report']);

Route::get('/kyc', [\App\Http\Controllers\HomeController::class, 'getKYC'])->name('getKYC');
