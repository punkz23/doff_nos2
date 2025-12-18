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


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\WaybillController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Newsletter;
use App\Http\Controllers\OtherPageController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ContactAddressController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\AccountController as AuthAccountController;

Route::get('/encrypt',function(){
    // echo Hash::make('Abc123456789');
    echo Crypt::encryptString('doffUser@123_*');
});

Route::post('/trace',[TestController::class, 'index']);
Route::post('/fbuser-data-deletion',[FacebookController::class, 'fb_data_deletion']);

Route::controller(ReferenceController::class)->group(function(){
    Route::get('/get-sector/{id}','sector');
    Route::get('/stocks','stocks');
    Route::get('/search-stocks/{desc}','search_stocks');
    Route::get('/get-references','get_references');
    Route::get('/get-sector/{id}','sector');
    Route::get('/get-city-data/{id}','getCity')->name('references.getCity');
});

Route::controller(HomeController::class)->group(function(){

    Route::get("/", 'welcome');
    Route::get("/landing-page", 'landing_page');
    Route::get("/auth-check", 'auth_check');
    Route::get('/branch-serviceable-map/{id}','branch_serviceable_map');
    Route::get('/sector-list/{id}','sector_list');
    Route::get('/get-sector-schedule/{id}/{date}/{action}','get_sector_schedule');
    Route::get('/get-sector-province/{action}','get_sector_province');
    Route::get('/get-sector-city/{action}/{province}','get_sector_city');
    Route::get('/get-sector-brgy/{action}/{province}/{city}','get_sector_brgy');
    Route::get('/get-sector-details/{id}','get_sector_details');
    Route::get('/get-address-details/{id}/{action}','get_address_details');
    Route::get('/get-qr-code-profile/{id}','get_qr_code_profile');
    Route::get('/profile-qr-code','profile_qr_code');
    Route::get('/get-customer-details/{id}','get_customer_details');
    Route::get('/get-qr-code-profile-list','get_qr_code_profile_list');
    Route::get('/deactivate-qr-code-profile/{id}','deactivate_qr_code_profile');
    Route::get('/save-sc-qrcode/{id}/{qrcode}','save_sc_qrcode');
    Route::get('/get-rebate-points/{shipper}/{consignee}/{sqrcode}/{cqrcode}/{qrcode_sid}/{qrcode_cid}','get_rebate_points');
    Route::get('/get-pasabox-branch-receiver','get_pasabox_branch_receiver');
    Route::get('/get-online-payment-exist/{ref_no}/{onl_ref}','get_online_payment_exist');
    Route::get('/get-gcash-info','get_gcash_info');
    Route::get('/get-gcash-cf-info/{ref_no}','get_gcash_cf_info');
    Route::get('/get-gcash-rp-qr/{ref_no}','get_gcash_rp_qr');
    Route::get('/validate-email/{email}','validate_email');
    Route::get('/get-id-type','get_id_type');
    Route::get('/track','track');
    Route::get('/claim/{transaction_code}/{action}','claim');
    Route::get('/branch-details/{id}','branch_details');
    Route::get('/previous','previous');
    Route::get('/terms-and-condition','terms_and_condition');
    Route::get('/faq/{dialect}','faq')->name('faqs.outside');
    Route::get('/data-privacy-policy','privacy_policy')->name('privacy-policy');
    Route::get('/home', 'index')->name('home');
    Route::get('/send-reg-otp/{action}/{type}/{email}','send_otp');
    Route::get('/validate-reg-otp/{email}/{otp}','validate_otp');
    Route::get('/login-validate-email/{pca}/{type}/{email}','login_validate_email');
    Route::get('/validate-user-pwd/{pca}/{type}/{email}/{pwd}','validate_user_pwd');
    Route::get('/check-web-otp','enable_disable_web_otp');

    Route::post('/save-apply-verification','save_apply_verification');
    Route::post('/create-qr-profile','create_qr_profile');
    Route::post('/save-gcash-reposting-payment','save_gcash_reposting_payment');


});

Route::controller(WaybillController::class)->group(function(){

    Route::get('/request-qoutation','request_qoutation_as_guest')->name('request.qoutation');
    Route::get('/sending-pdf/{reference_no}','sendPDF');
    Route::get('/generated-pdf','generatePDF')->name('generate-pdf');
    Route::get('/link-to-pdf','other_link');
    Route::get('/create-booking','create_as_guest')->name('waybills.create_as_guest');
    Route::get('/waybills/printable-reference/{id}','print')->name('waybills.print');
    Route::get('/waybills/printable-label/{id}','print_label')->name('waybills.print_label');
    Route::get('/waybills/check-discount-coupon/{id}','verify')->name('waybills.verify');
    Route::get('/change-ol-reference/{action}','change_ol_reference');
    Route::get('/pc-account-application/{url_code}','pca_application')->name('pca.application');
    Route::get('/pc-account/{url_code}','pca_access')->name('pca.access');
    Route::get('/doff-pca-transactions','pca_transactions')->name('pca.transactions');
    Route::get('/doff-pca-accounts','pca_accounts')->name('pca.accounts');
    Route::get('/get-pca-ledger/{pca_no}/{date}/{from}/{to}/{type}', 'get_pca_ledger');
    Route::get('/pca-get-waybill-details/{tcode}', 'pca_get_waybill_details');
    Route::get('/pca-waybill-shipment-details/{tcode}', 'pca_waybill_shipment_details');
    Route::get('/pca-waybill-shipment-dimensions-details/{id}', 'pca_waybill_shipment_dimensions_details');
    Route::get('/pca-unpaid-transaction/{pca_no}/{action}/{month}', 'pca_unpaid_transaction');
    Route::get('/pca-deposit/{pca_no}/{tab}', 'pca_deposit');
    Route::get('/pca-cancel-deposit/{id}/{pca_no}', 'pca_cancel_deposit');
    Route::get('/pca-no-details/{pca_no}', 'pca_no_details');
    Route::get('/pca-account-check-ie/{pca_no}/{email}', 'pca_account_check_ie');
    Route::get('/pca-account-list/{pca_no}/{ie}/{status}', 'pca_account_list');
    Route::get('/pca-deactivate-account/{pca_no}/{id}/{status}', 'pca_deactivate_account');
    Route::get('/pca-account-access/{id}', 'pca_account_access');
    Route::get('/pca-account-selection-list', 'pca_account_selection_list');
    Route::get('/pca-account-selected-update/{pca_no}', 'pca_account_selected_update');
    Route::get('/get-pca-requirements/{pca_no}/{at}', 'get_pca_requirements');
    Route::get('/get-pca-deactivation-pending-count/{pca_no}/{action}', 'get_pca_deactivation_pending_count');
    Route::get('/get-pca-notif', 'get_pca_notif');
    Route::get('/pca-account-soa-range', 'pca_account_soa_range');
    Route::get('/on-off-notif-pca-account/{off_notif}/{notif_id}', 'on_off_notif_pca_account');
    Route::get('/get-wtax-atc', 'get_wtax_atc');
    Route::get('/get-pca-wtax-waybill/{pca_no}', 'get_pca_wtax_waybill');
    Route::get('/get-wtax-breakdown/{tcode}/{pca_no}', 'get_wtax_breakdown');
    Route::get('/cancel-wtax-application/{id}', 'cancel_wtax_application');
    Route::get('/pca-account-exp-date/{pca_no}', 'pca_account_exp_date');
    Route::get('/pca-account-check-apply-renewal/{pca_no}', 'pca_account_check_apply_renewal');
    Route::get('/get-pc-cities/{province}', 'get_pc_cities');
    Route::get('/get-pc-brgy/{city}', 'get_pc_brgy');
    Route::get('/pca-deposit-view-proof/{adv_id}', 'pca_deposit_view_proof');
    Route::get('/doff-set-password/{url_code}','set_doff_pwd')->name('doff.pwd');
    Route::get('/login-doff-check-pwd','login_check_pwd');
    Route::get('/pca-city','pca_city');
    Route::get('/pca-brgy/{city}','pca_brgy');
    Route::get('/pca-agent-list/{pca_no}/{id}','pca_agent_list');
    Route::get('/publication-csv-template-download/{pca_no}','pub_csv_templatye_download');
    Route::get('/pub-transaction-save-agent','pub_transaction_save_agent');
    Route::get('/pub-transaction-save-agent-address','pub_transaction_save_agent_address');
    Route::get('/get-pub-dr-list','get_pub_dr_list');
    Route::get('/remove-pub-dr','remove_pub_dr');
    Route::get('/get-pub-dr-transaction','get_pub_dr_transaction');
    Route::get('/pub-upload-view-proof', 'pub_upload_view_proof');



    Route::post('/check-discount-coupon','check_discount_coupon');
    Route::post('/request-qoutation-post','request_quotation_as_guest_post')->name('requests.quotation');
    Route::post('/track-and-trace','track_and_trace')->name('track_and_trace');
    Route::post('/create-booking-post','create_as_guest_post')->name('waybills.create_as_guest_post');
    Route::post('/discount-coupon-verification','verify_discount_coupon')->name('discount-coupon.verification');
    Route::post('/pc-activate-account','pc_activate_account');
    Route::post('/pca-save-deposit','pca_save_deposit');
    Route::post('/pca-save-account','pca_save_account');
    Route::post('/pca-update-account-access','pca_update_account_access');
    Route::post('/deactivate-pca-account', 'deactivate_pca_account');
    Route::post('/save-wtax-application','save_wtax_application');
    Route::post('/pca-save-application','pca_save_application');
    Route::post('/doff-set-pwd','doff_set_pwd');
    Route::post('/save-pca-agent','save_pca_agent');
    Route::post('/add-publication-transaction','add_publication_transaction');
    Route::post('/update-publication-transaction','update_publication_transaction');
    Route::post('/publication-import-delivery','publication_import_delivery');
    Route::post('/save-agent-sorting','save_agent_sorting');



});
Route::controller(ChatController::class)->group(function(){

    Route::get('/chat-guest','guestMessage');
    Route::get('/start-conversation/{session_key}','startConversation');
    Route::get('/get-messages/{id}','getMessages');
    Route::get('/chats/create/{id}','create')->name('chats.create');

    Route::post('/guest-request','guestRequest')->name('chats.guest_request');
    Route::post('/send-message','sendMessage');

});
Route::controller(IncidentController::class)->group(function(){
    Route::post('/incident-complain','store_complain')->name('incident.store_complain');
    Route::post('/customer-feedback','store_feedback')->name('incident.store_feedback');

});
Route::controller(AccountController::class)->group(function(){

    Route::get('/request-link','request_link_account');
    Route::get('/doff-transactions','doff_transaction')->name('doff-transactions')->middleware('has_doff_account');
    Route::get('/doff-transactions-data','doff_transaction_data')->name('doff-transaction-data');
    Route::get('/track-and-trace-data','track_and_trace_data')->name('track-and-trace-data');
    //Route::get('/pod-data','pod_data')->name('pod-data');

    Route::post('/upload-photo','upload_photo');


});
Route::controller(ContactController::class)->group(function(){
    Route::get('/chats','index')->name('chats.index');
});

Route::controller(Newsletter::class)->group(function(){
    Route::get('/confirm/{id}','confirm')->name('newsletter.confirm');
    Route::get('/unsubscribe/{id}','unsubscribe')->name('newsletter.unsubscribe');
    Route::post('/subscribe','subscribe')->name('newsletter.subscribe');
    Route::post('/subscribe-customer','subscribeCustomer')->name('newsletter.subscribe-customer');
    Route::get('/get_news_update', 'getNewsUpdate');
    Route::get('/stream_news_update/{path}', 'streamNewsUpdate')->where('path', '.*');
});

Route::controller(FacebookController::class)->group(function(){
    Route::get('auth/facebook', 'redirectToFacebook');
    Route::get('auth/facebook/callback', 'handleFacebookCallback');
});
Route::controller(GoogleController::class)->group(function(){
    Route::get('auth/google', 'redirectToGoogle');
    Route::get('auth/google/callback', 'handleGoogleCallback');
});

Route::controller(AuthAccountController::class)->group(function(){
    Route::get('/account','index')->name('account');
    Route::get('/account/address','account_address')->name('accounts.addresses');
    Route::get('/get-cities/{id}','getCities')->name('getCities');

    Route::post('/account/update-password','update_password')->name('accounts.update_password');
    Route::post('/account/update-em','update_em')->name('accounts.update_em');
});

Auth::routes();
//Route::resource('chats','ChatController');
Route::group(['middleware' => ['role:Client']], function () {

    Route::controller(AuthAccountController::class)->group(function(){
        Route::get('/get-useraddress/{id}','get_useraddress')->name('getAddress');
        Route::get('/get-alluseraddress/{id}','get_Alluseraddress')->name('getAlluseraddress');

        Route::post('/account/update-profile','update_profile')->name('accounts.update_profile');
        Route::post('/account/save-address','save_address')->name('accounts.save_address');
        Route::post('/set-default/{contact_id}','setDefault')->name('setDefault');
        Route::post('/account/set-default-address','setDefault')->name('accounts.setDefault');

    });

    Route::controller(HomeController::class)->group(function(){

        Route::get('track-trace','track_trace')->name('track-trace');
        Route::get('pod-data/{pod_month}','pod_data')->name('pod-data');

        Route::post('track-trace-data','track_trace_data')->name('track-trace-data');

    });

    Route::resource('waybills','WaybillController');
    Route::controller(WaybillController::class)->group(function(){

        Route::get('/get-waybills','getWaybills');
        Route::get('/get-pendings','get_pendings')->name('waybills.get_pendings');
        Route::get('/get-transacted/{fmonth}','get_transacted')->name('waybills.get_transacted');
        Route::get('/get-recent-transacted','get_recent_transaction')->name('waybills.get_recent_transaction');
        Route::get('/get-booking-track-trace/{ref_no}','get_booking_track_traces')->name('waybills.get_booking_track_traces');
        Route::get('/get-pasabox-uploaded-file/{ref_no}','get_pasabox_uploaded_file')->name('waybills.get_pasabox_uploaded_file');
        Route::get('/get-waybill-details/{tcode}','get_waybill_details')->name('waybills.get_waybill_details');

        Route::post('/waybill-update/{id}','update');
        Route::post('/waybill-track-by-reference','track_by_reference');
        Route::post('/waybills/search','search')->name('waybills.search');

    });
    Route::resource('contacts','ContactController');
    Route::controller(ContactController::class)->group(function(){

        Route::get('/contacts-default/{id}','save_default');
        Route::get('/get-contacts/{status}','getContacts');
        Route::get('/get-contacts-deactivated','get_contacts_deactivated');
        Route::get('/update-contacts-deactivated','update_contacts_deactivated');

        Route::post('/contact-update','update');
        Route::post('/contacts/save-address','save_address')->name('contacts.save_address');

    });

    Route::get('/contacts/addresses-list/{id}',[ContactAddressController::class, 'index']);
    Route::controller(OtherPageController::class)->group(function(){
        Route::get('/contact-us/complain','complain')->name('contact-us.complain');
        Route::get('/contact-us/feedback','feedback')->name('contact-us.feedback');
        Route::get('/contact-us/request-quote','request_quote')->name('contact-us.request-quote');
    });


});
Route::resource('guides','GuideController');
Route::controller(GuideController::class)->group(function(){

    Route::get('/get-categories-via-dialect/{id}','get_category_via_dialect');
    Route::get('/get-questions-via-category/{id}','get_questions_via_category');
    Route::post('/question-store','question_store')->name('guides.question_store');

});

Route::get('/branches-list',[BranchController::class, 'list'])->name('branches.list');

Route::group(['middleware' => ['role:Admin']], function () {

    Route::resource('branches','BranchController');
    Route::controller(BranchController::class)->group(function(){

        Route::get('/branch-contact-show/{id}','contact_no_show')->name('branches.contact_no_show');
        Route::get('/branch-schedule-show/{id}','schedule_show')->name('branches.schedule_show');

        Route::post('/branch-contact-store','contact_no_store')->name('branches.contact_no_store');
        Route::post('/branch-schedule-store','schedule_store')->name('branches.schedule_store');

        Route::delete('/branch-schedule-delete/{id}','schedule_delete')->name('branches.schedule_delete');
        Route::delete('/branch-contact-delete/{id}','contact_no_delete')->name('branches.contact_no_delete');

    });

    Route::controller(OtherPageController::class)->group(function(){
        Route::get('/others/faq','faq')->name('others.faq');
        Route::get('/others/faq-create','faq_create')->name('others.faq_create');
        Route::post('/others/faq-post','faq_post')->name('others.faq_post');

        Route::get('/terms','terms_and_condition')->name('terms.maintenance');
        Route::post('/terms-post','terms_and_condition_post')->name('terms.maintenance_post');
        Route::get('/terms/{type}','term')->name('terms.show');
    });
    Route::post('/update-to-done/{id}',[ChatController::class, 'setDone']);
});
// Route::get('/terms','OtherPageController@terms_and_condition')->name('terms.maintenance');
// Route::post('/terms-post','OtherPageController@terms_and_condition_post')->name('terms.maintenance_post');
// Route::get('/terms/{type}','OtherPageController@term')->name('terms.show');











