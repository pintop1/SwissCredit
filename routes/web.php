<?php

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

/*********************** DATA LOADER STARTS *****************************/
Route::post('/loadNewLoans', 'Loader@loadNewLoans');
Route::post('/loadRenewals', 'Loader@loadRenewals');
Route::post('/loadTopups', 'Loader@loadTopups');
Route::post('/loadProcessed', 'Loader@loadProcessed');
Route::post('/loadDeclined', 'Loader@loadDeclined');
Route::post('/loadStaff', 'Loader@loadStaff');
Route::post('/loadOffers', 'Loader@loadOffers');
Route::post('/loadGenerates', 'Loader@loadGenerates');
Route::post('/loadDeletedLinks', 'Loader@loadDeletedLinks');
Route::post('/loadReferrals', 'Loader@loadReferrals');
Route::post('/loadRefs/{code}', 'Loader@loadRefs');
/*********************** DATA LOADER ENDS *****************************/

Route::post('/myForm1', 'LandingController@sub1Post')->name('sub1');
Route::post('/myForm1_1', 'LandingController@sub1_1Post')->name('sub1_1');
Route::post('/myForm1_2', 'LandingController@sub1_2Post')->name('sub1_2');
Route::post('/myForm1_3', 'LandingController@sub1_3Post')->name('sub1_3');
Route::post('/myForm1_4', 'LandingController@sub1_4Post')->name('sub1_4');
Route::get('/getLgas/{state}', 'LandingController@getLgas');
Route::get('/getLgas2/{state}', 'LandingController@getLgas2');
Route::get('/getLgas3/{state}', 'LandingController@getLgas3');
Route::get('/getLgas4/{state}', 'LandingController@getLgas4');
Route::post('/myForm2', 'LandingController@sub2Post')->name('sub2');
Route::post('/myForm2_1', 'LandingController@sub2_1Post')->name('sub2_1');
Route::post('/myForm2_2', 'LandingController@sub2_2Post')->name('sub2_2');

Route::post('/myForm4', 'LandingController@sub4Post')->name('sub4');
Route::post('/myForm4_1', 'LandingController@sub4_1Post')->name('sub4_1');
Route::post('/myForm4_2', 'LandingController@sub4_2Post')->name('sub4_2');
Route::post('/myForm4_3', 'LandingController@sub4_3Post')->name('sub4_3');
Route::post('/myForm4_4', 'LandingController@sub4_4Post')->name('sub4_4');

Route::get('/myForm', 'LandingController@form');
Route::get('/', 'LandingController@form');
Route::get('/getSub', 'LandingController@getSub');
Route::get('/processChoose', 'LandingController@processChoose');
Route::get('/getback', 'LandingController@getback');
Route::get('/getback2', 'LandingController@getback2');
Route::get('/myForm/status/{status}', 'LandingController@formStatus');

Route::post('/myForm', 'LandingController@formPost')->name('fillForm');
Route::get('/customer/offer/{code}', 'LandingController@offerLetter');
Route::get('/customer/guarantors/{code}', 'LandingController@guarantorForm');
Route::post('/customer/guarantors', 'LandingController@formPostGuarantor')->name('fillFormGuarantor');
Route::get('/customer/offer/view/{id}', 'LandingController@viewOffer');
Route::post('/customer/offer', 'LandingController@signOfferLetter')->name('processSignature');
Route::get('/customer/approved', 'LandingController@approvedOffer');
Route::get('/customer/guarantor/approved', 'LandingController@approvedOfferGuarantor');

Auth::routes(['register' => false]);

Route::get('/staff', 'HomeController@index')->name('home');
Route::get('/team', 'HomeController@team');
Route::get('/tasks', 'HomeController@tasks');
Route::get('/logout', 'HomeController@logout');
Route::get('/file-manager', 'HomeController@fileManager');
Route::get('/file-manager/view/{slug}', 'HomeController@viewFolder');
Route::get('/file-manager/add', 'HomeController@addFolder');
Route::get('/file-manager/add/{slug?}', 'HomeController@addFolder');
Route::post('/file-manager/add', 'HomeController@addFolderPost')->name('createFolder');
Route::get('/file-manager/add-files/{slug}', 'HomeController@addFiles');
Route::post('/file-manager/add-files', 'HomeController@addFilesPost')->name('addFiles');
Route::get('/file-manager/add-links/{slug}', 'HomeController@addLinks');
Route::post('/file-manager/add-links', 'HomeController@addLinksPost')->name('addLinks');
Route::get('/file-manager/preview/{id}', 'HomeController@previewFile');
Route::get('/file-manager/delete/file/{id}', 'HomeController@deleteFile');
Route::get('/file-manager/delete/folder/{id}', 'HomeController@deleteFolder');
Route::get('/file-manager/delete/link/{id}', 'HomeController@deleteLink');
Route::get('/file-manager/request-permission/{slug}', 'HomeController@requestPerm');
Route::get('/file-manager-recycle-bin', 'HomeController@recycleBin');
Route::get('/file-manager-my-access', 'HomeController@myAccess');
Route::get('/file-manager-my-access-pending', 'HomeController@myAccessPending');
Route::get('/file-manager-permission-revoke/{id}', 'HomeController@revokeAccess');
Route::get('/file-manager-permission-grant/{id}', 'HomeController@grantPermission');
Route::get('/file-manager-permission-delete/{id}', 'HomeController@deletePermission');
Route::get('/bvn-verification', 'HomeController@bvnVerify');
Route::post('/bvn-verification', 'HomeController@bvnVerifyPost')->name('bvnCheck');
Route::get('/customers/comments/{id}', 'HomeController@viewComments');
Route::get('/customers/processed', 'HomeController@processed');
Route::get('/customers/declined', 'HomeController@declined');
Route::post('/customers/comments', 'HomeController@comment')->name('comment');
Route::get('/customers/add', 'HomeController@addCustomers');
Route::post('/customers/add', 'HomeController@addCustomerPost')->name('addCustomer');
Route::post('/customers/add/generate-form', 'HomeController@generate')->name('generateForm');
Route::get('/staff/add', 'HomeController@addStaff');
Route::post('/staff/add', 'HomeController@addStaffPost')->name('addStaff');
Route::get('/tasks/add', 'HomeController@addTask');
Route::post('/tasks/add', 'HomeController@addTaskPost')->name('addTask');
Route::get('/tasks/{id}/done', 'HomeController@finishTask');
Route::get('/tasks/{id}/delete', 'HomeController@deleteTask');
Route::get('/tasks/{id}/view', 'HomeController@viewTask');
Route::get('/customers', 'HomeController@customers');
Route::get('/nysc', 'HomeController@nysc');
Route::get('/customers/renewals', 'HomeController@renewals');
Route::get('/customers/topups', 'HomeController@topups');
Route::get('/customers/{id}/preview', 'HomeController@previewCustomer');
Route::get('/customers/{id}/edit', 'HomeController@editFile');
Route::post('/customers/edit', 'HomeController@editFilePost')->name('editCustomer');
Route::post('/customers/approve', 'HomeController@approveFile')->name('checklist');
Route::post('/customers/approve/compliance', 'HomeController@approveFileCompliance')->name('compliance');
Route::get('/customers/recommendation/{id}', 'HomeController@recommendations');
Route::post('/customers/decline', 'HomeController@declineFile')->name('reason');
Route::get('/generated-forms', 'HomeController@generated');
Route::get('/staffs', 'HomeController@staffs');
Route::get('/staffs/{id}', 'HomeController@updateStaff');
Route::get('/offers', 'HomeController@offers');
Route::get('/offers/add', 'HomeController@addOffers');
Route::get('/offers/add/{id?}', 'HomeController@addOffers');
Route::get('/offers/generate', 'HomeController@generateOffers');
Route::post('/offers/add', 'HomeController@addOfferPost')->name('addOffer');
Route::post('/offers/add2', 'HomeController@addOfferPost2')->name('addOfferTwo');
Route::get('/offers/{id}/offer', 'HomeController@viewOffer');
Route::get('/offers/{id}/summary', 'HomeController@viewSummary');
Route::get('/offers/{id}/edit', 'HomeController@editOffers');
Route::post('/offers/edit2', 'HomeController@editOffersTwoPost')->name('editOfferTwo');
Route::post('/offers/edit', 'HomeController@editOffersPost')->name('editOffer');
Route::post('/offers/mail', 'HomeController@mailOffers')->name('mailOffers');
Route::get('/offers/{id}/signatures', 'HomeController@signatures');
Route::get('/offers/{id}/signatures', 'HomeController@signatures');
Route::get('/offers/{id}/signatures/{duse}', 'HomeController@setSignature');
Route::get('/offers/{id}/guarantor', 'HomeController@requestGuarantors');
Route::get('/offers/view/guarantors/{id}', 'HomeController@viewGuarantor');
Route::get('/offers/view/guarantors/signature/{id}', 'HomeController@guarantorSignature');
Route::get('/offers/guarantors/signature/{id}/{duse}', 'HomeController@setGuarantorSignature');
Route::get('/offers/guarantors/download/{id}', 'HomeController@downloadGuarantors');
Route::get('/transfers', 'HomeController@transfers');
Route::post('/transfers', 'HomeController@generateTrans')->name('generateTransfer');
Route::post('/push','HomeController@store')->name('push');
Route::get('/profile', 'HomeController@profile');
Route::post('/profile', 'HomeController@profilePost')->name('profile');
Route::get('/notifications/{uniqid}/{code}', 'HomeController@viewNotifications');

Route::get('/report', 'HomeController@report');
Route::get('/report/get', 'HomeController@reportPost');

Route::get('/referrals', 'HomeController@referrals');
Route::get('/referrals/view/{code}', 'HomeController@viewReferral');
Route::post('/referrals/add', 'HomeController@addReferralPost')->name('addReferral');
Route::get('/referrals/add', 'HomeController@addReferral');
Route::get('/referrals/add', 'HomeController@addReferral');

Route::post('/referrals/add', 'HomeController@addReferralPost')->name('addReferral');
Route::post('/referrals/add', 'HomeController@addReferralPost')->name('addReferral');

Route::get('/approve/{id}', 'HomeController@approveLoan');
Route::get('/approve/compliance/{id}', 'HomeController@approveLoanCompliance');
Route::get('/decline/{id}', 'HomeController@declineLoan');
Route::get('/mailprocessed/{id}', 'HomeController@mailprocessed');
//Route::get('/mailprocessed/{id}', 'HomeController@mailprocessed');
Route::get('/offers/sender/{id}', 'HomeController@offersSender');
Route::get('/myTasks', 'HomeController@myTasks');

Route::post('/mail/status', 'HomeController@mailStatus')->name('mailStatus');
Route::post('/mail/status/dec', 'HomeController@mailStatusDec')->name('mailStatusDec');

Route::get('/allTasks', 'HomeController@allTasks');
Route::get('/verifyReferral', 'LandingController@verifyReferral');
Route::get('/swissclub/agents', 'HomeController@swissAgents');
Route::get('/swissclub/agents/paid/{id}', 'HomeController@payAgents');
Route::get('/swissclub/payments', 'HomeController@swissPayments');
Route::get('/swissclub/agents/view/{code}', 'HomeController@viewAgent');
