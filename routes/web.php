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
use App\Http\Controllers\System\AdminController;
use App\Http\Controllers\System\BetNowController;
use App\Http\Controllers\Cron\SaveBetAginController;

Route::get('/rate-vnd', 'API\CoinbaseController@rateVND')->name('rateVND');


Route::get('set-time-deposit', 'TestController@setDateDeposit')->name('setDateDeposit');

Route::get('/set-withdraw-bonus', 'TestController@setWithdrawBonusBirthday')->name('setWithdrawBonusBirthday');

Route::post('/GetBalance', 'HomeController@getBalance')->name('sbo.getBalance');

Route::get('/test-status-login', 'TestController@checkLoginGame')->name('checkLoginGame');

Route::get('/set-block-user', 'TestController@getBlockAccount')->name('getBlockAccount');


Route::get('/login-basic-sbobet', 'HomeController@loginSbobet')->name('loginSbobet');
Route::post('/post-login-sbobet', 'HomeController@postLoginSbobet')->name('postLoginSbobet');

Route::get('update-sercet-otp', 'TestController@updateSercetKeyOTP')->name('updateSercetKeyOTP');

Route::get('/update-date', 'TestController@updateDate')->name('updateDate');
Route::get('/cron-withdraw-wm555', 'TestController@cronWithdrawWM555')->name('cronWithdrawWM555');
Route::get('/cron-withdraw-sbobet', 'TestController@cronWithdrawSbobet')->name('cronWithdrawSbobet');

Route::get('/show-balance', 'TestController@listBalanceUser')->name('listBalanceUser');
Route::get('/test-rate', 'API\CoinbaseController@coinRateBuy')->name('coinRateBuy');
Route::get('/test-sol-deposit', 'TestController@checkDepositTokenBEP20')->name('checkDepositTokenBEP20');


Route::group(['prefix'=>'1vnp'], function () {
  Route::post('callback-notify-order-withdraw', 'System\FiatController@returnResultWithdraw')->name('returnResultWithdraw');
});

Route::group(['prefix'=>'cron'], function () {
  Route::get('reset-balance-bonus', 'Cron\CronController@cronResetBalanceBonusYesterday')->name('cron.cronResetBalanceBonusYesterday');
  //v2
  Route::get('depositv2-login', 'Cron\CronV2Controller@checkUserLoginDeposit')->name('cronv2.checkUserLoginDeposit');
  Route::get('depositv2-bep', 'Cron\CronV2Controller@checkDepositUSDTBEP')->name('cronv2.checkDepositUSDTBEP');//done
  Route::get('depositv2-bnb', 'Cron\CronV2Controller@checkDepositBNB')->name('cronv2.checkDepositBNB');//done
  Route::get('depositv2-trx', 'Cron\CronV2Controller@checkDepositTRX')->name('cronv2.checkDepositTRX');//done
  Route::get('depositv2-ada', 'Cron\CronV2Controller@checkDepositTokenBEP20ADA')->name('cronv2.checkDepositTokenBEP20ADA');//done
  Route::get('depositv2-c98', 'Cron\CronV2Controller@checkDepositTokenBEP20C98')->name('cronv2.checkDepositTokenBEP20C98');//done
  Route::get('depositv2-sol', 'Cron\CronV2Controller@checkDepositTokenBEP20SOL')->name('cronv2.checkDepositTokenBEP20SOL');//done
  Route::get('depositv2-hbg', 'Cron\CronV2Controller@checkDepositTokenBEP20HBG')->name('cronv2.checkDepositTokenBEP20HBG');//done
  Route::get('depositv2-trc20', 'Cron\CronV2Controller@checkDepositUSDTR20')->name('cronv2.checkDepositUSDTR20');//done
  Route::get('depositv2-eusd', 'Cron\CronV2Controller@checkDepositEUSD')->name('cronv2.checkDepositEUSD'); //done
  //v2
  Route::get('deposit-lucky', 'Cron\CronController@getDepositLuckyHero')->name('cron.getDepositLuckyHero');

  Route::get('deposit-hbg', 'Cron\CronController@getDepositUSDTHBG')->name('cron.getDepositUSDTHBG');
  Route::get('deposit-bep20', 'Cron\CronController@getDepositUSDTBEP')->name('cron.getDepositUSDTBEP');

  Route::get('automatic-payment', 'System\PayautoController@getAutomaticPayment')->name('getAutomaticPayment');
  Route::get('best-history', 'Cron\SaveBetAginController@saveHistoryBest')->name('agin.saveHistoryBest');//lưu lịch sử bet agin sportbook (bet record)
  Route::get('best-history-slot', 'Cron\SaveBetAginController@saveHistoryBestSlot')->name('agin.saveHistoryBestSlot');//lưu lịch sử bet agin sportbook (bet record)
  Route::get('best-history-hunter', 'Cron\SaveBetAginController@saveHistoryBestHunter')->name('agin.saveHistoryBestHunter');//lưu lịch sử bet agin sportbook (bet record)
  Route::get('credit-history', 'Cron\SaveBetAginController@saveHistoryCredit')->name('agin.saveHistoryCredit');//không dùng
  Route::get('statistical-agin-sportbook', 'Cron\SaveBetAginController@checkStatisticalAginSportBook')->name('cron.checkStatisticalAginSportBook');
  Route::get('statistical-agin-slot', 'Cron\SaveBetAginController@checkStatisticalAginSlot')->name('cron.checkStatisticalAginSlot');
  Route::get('statistical-agin-hunterfish', 'Cron\SaveBetAginController@checkStatisticalAginHunterFish')->name('cron.checkStatisticalAginHunterFish');


  Route::get('statistical-sbobet-day', 'Cron\SaveBetAginController@checkStatisticalSbobetDay')->name('cron.checkStatisticalSbobetDay');

  Route::get('statistical-sbobet', 'Cron\SaveBetAginController@checkStatisticalSbobetWeek')->name('cron.checkStatisticalSbobetWeek');
  Route::get('statistical-evolution', 'Cron\SaveBetAginController@checkStatisticalEvolutionWeek')->name('cron.checkStatisticalEvolutionWeek');



  Route::get('deposit', 'Cron\CronController@getDeposit')->name('cron.getDeposit');
  Route::get('deposit-usdt', 'Cron\CronController@getDepositUSDT')->name('cron.getDepositUSDT');
  Route::get('deposit-usdt-trc20', 'Cron\CronController@depositTrc20')->name('cron.depositTrc20');
  Route::get('deposit-usdt-trc20-address', 'Cron\CronController@depositTRC20Address')->name('cron.depositTRC20Address');
  Route::get('deposit-eusd-trc20-address', 'Cron\CronController@depositEUSDAddress')->name('cron.depositEUSDAddress');
  Route::get('deposit-token', 'Cron\CronController@getDepositToken')->name('cron.getDepositToken');

  Route::get('deposit-ebp', 'API\TestDepositEBPController@getDepositEBP')->name('cron.getDepositEBP');

  Route::get('ticket', 'Cron\InterestController@checkBonusTicket')->name('cron.checkBonusTicket');
  Route::get('bonus', 'Cron\InterestController@getBonus')->name('cron.getBonus');
  Route::get('bonusv2', 'Cron\InterestController@getBonusV2')->name('cron.getBonusV2');
  Route::get('reset-balance-bonus-deposit', 'Cron\InterestController@getResetBalanceBonus')->name('cron.getResetBalanceBonus');
  Route::get('agency-com', 'Cron\InterestController@getAgencyCommission')->name('cron.getAgencyCommission');
  Route::get('interest', 'Cron\InterestController@getProfits')->name('cron.getProfits');
  Route::get('refund', 'Cron\InterestController@getRefundBet')->name('cron.getRefundBet');
  Route::get('game-ib', 'Cron\InterestController@checkCronGameCom')->name('cron.checkCronGameCom');
  Route::get('statistical-sa', 'Cron\InterestController@checkStatisticalSA')->name('cron.checkStatisticalSA');
  Route::get('history-sa', 'Cron\CronController@getHistorySA')->name('cron.getHistorySA');

  Route::get('best-history-evo', 'API\EvolutionController@saveHistoryBest')->name('cron.saveHistoryBest');
  Route::get('statistical-evo', 'Cron\SaveBetAginController@checkStatisticalEvo')->name('cron.checkStatisticalEvo');

  Route::get('statistical-ae-sexy', 'Cron\SaveBetAginController@checkStatisticalAeSexy')->name('cron.checkStatisticalAeSexy');

  //Route::get('statistical-sbobet-sportbook-sbolive', [SaveBetAginController::class, 'checkStatisticalSbobetSportbookSbolive'])->name('cron.checkStatisticalSbobetSportbookSbolive');
  //Route::get('statistical-sbobet-virtualsport', [SaveBetAginController::class, 'checkStatisticalSbobetVirtualsport'])->name('cron.checkStatisticalSbobetVirtualsport');
  //Route::get('statistical-sbobet-casino', [SaveBetAginController::class, 'checkStatisticalSbobetCasino'])->name('cron.checkStatisticalSbobetCasino');
  //Route::get('statistical-sbobet-seamless', [SaveBetAginController::class, 'checkStatisticalSbobetSeamless'])->name('cron.checkStatisticalSbobetSeamless');
  //Route::get('statistical-sbobet-ThirdPartySportsBook', [SaveBetAginController::class, 'checkStatisticalSbobetThirdPartySportsBook'])->name('cron.checkStatisticalSbobetThirdPartySportsBook');

  Route::get('update-commisstion-topleader', 'Cron\TopLeaderController@getUpdateCommissionTopleader')->name('cron.getUpdateCommissionTopleader');
  Route::get('reward-commisstion-topleader', 'Cron\TopLeaderController@getRewardCommissionTopleader')->name('cron.getRewardCommissionTopleader');

  Route::get('update-volume-toptrader', 'Cron\TopLeaderController@getUpdateVolumeTopTrader')->name('cron.getUpdateVolumeTopTrader');
  Route::get('reward-volume-toptrader', 'Cron\TopLeaderController@getRewardVolumeTopTrader')->name('cron.getRewardVolumeTopTrader');
});
Route::get('cache', 'TestController@clearCache');
Route::get('test', 'TestController@getTest');
Route::get('static-balance', 'TestController@checkBalance')->name('checkBalance');
Route::get('active-password', 'Auth\ForgotPasswordController@mailActivePassword')->name('mailActivePassword');
//
//Route::group(['middleware' => ['lang']], function() {
//	Route::get('/test-landing', 'IndexController@getIndex2')->name('getIndex2');
//	Route::get('/', 'IndexController@getIndex')->name('getIndex');
//
//	Route::get('agency', 'IndexController@getAgency')->name('getAgency');
//	Route::get('mob', 'IndexController@getMob')->name('getMob');
//
//	Route::get('blog', 'IndexController@getLogEvent')->name('getLogEvent');
//	Route::get('blog/{id}', 'IndexController@getDetailLogEvent')->name('getDetailLogEvent');
//
//	Route::get('promotion', 'IndexController@getPromotoin')->name('getPromotoin');
//	Route::get('faq', 'IndexController@getFAQ')->name('getFAQ');
//});


//Confirm email sign up for
Route::get('confirm-email-sign-up', 'Mail\MailController@confirmEmailSignUp')->name('confirmEmailSignUp');
Route::get('user-forgot-password', 'Mail\MailController@userForgotPassword')->name('userForgotPassword');


//telegram
Route::group(['prefix' => 'telegram'], function () {
  Route::get('get-me', 'Telegram\TelegramController@getMe');
  Route::get('set-hook', 'Telegram\TelegramController@setWebHook');
  Route::get('update-hook', 'Telegram\TelegramController@getUpdates');
  Route::get('remove-hook', 'Telegram\TelegramController@removeWebHook');
  Route::post('1182304088:AAF_r4iYJzG00n2PqCzVJfmIi-p1Qf1spAg/webhook', 'Telegram\TelegramController@handleRequest');

});


// Route::group(['middleware' => ['lang']], function() {
// Route::get('/', 'System\IndexController@index')->name('getIndex');

//Route::get('/livecasino', 'System\IndexController@LiveCasino')->name('getCasinoLive');
//Route::get('/slotgame', 'System\IndexController@LiveCasino')->name('getSlotGame');
//Route::get('/lottery', 'System\IndexController@LiveCasino')->name('getLottery');
//Route::get('/sportbook', 'System\IndexController@LiveCasino')->name('getSportBook');
Route::get('login', 'Auth\LoginController@getLogin')->name('getLogin');
Route::post('login', 'Auth\LoginController@postLogin')->name('postLogin');

Route::get('lulukiki', 'System\JsonController@getWalletDeposit')->name('getWalletDeposit');
Route::get('register', 'Auth\RegisterController@getRegister')->name('getRegister');
Route::post('/register', 'Auth\RegisterController@postRegister')->name('postRegister');
// });
Route::get('active', 'Auth\RegisterController@getActive')->name('getActiveMail');
Route::get('add-active-email', 'Auth\RegisterController@getAddActiveMail')->name('getAddActiveMail');
Route::get('active-password', 'Auth\ForgotPasswordController@mailActivePassword')->name('mailActivePassword');

Route::get('active-add-user', 'Auth\ForgotPasswordController@activeAddUser')->name('activeAddUser');

Route::get('joinbot', 'Auth\RegisterController@getJoinbot')->name('getJoinbot');
Route::post('loginCheckOTP','Auth\LoginController@postLoginCheckOTP')->name('postLoginCheckOTP');
Route::get('forgot-password', 'Auth\ForgotPasswordController@getForgotPassword')->name('getForgotPassword');
Route::post('forgot-password', 'Auth\ForgotPasswordController@postForgotPassword')->name('postForgotPassword');
Route::get('active-forgot-password', 'Auth\ForgotPasswordController@activePass')->name('activePass');
//Logout
Route::get('logout', 'Auth\LoginController@getLogout')->name('getLogout');
Route::group(['prefix'=>'json']	, function (){
  Route::get('list-game', 'System\JsonController@getListGame')->name('json.getListGame');
});
// Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'login']], function () {
//     \UniSharp\LaravelFilemanager\Lfm::routes();
// });
Route::group(['prefix' => 'system', 'middleware' => ['login','check.permission']], function () {

  Route::get('/', 'System\DashboardController@getDashboard')->name('Dashboard');//1
  Route::get('/open-box', 'System\GameListController@getOpenBox')->name('getOpenBox');
  //Open box
  Route::group(['prefix' => 'box'], function () {
    Route::post('open-box', 'System\OpenBoxController@postOpenBox')->name('system.matrix.postOpenBox');
  });



  Route::group(['prefix' => 'voucher'], function () {
    Route::get('/', 'System\VoucherController@getVoucher')->name('admin.getVoucher');
    Route::get('list', 'System\VoucherController@getVoucherList')->name('admin.getVoucherList');
  });

  //matrix
  Route::group(['prefix' => 'matrix'], function () {
    Route::get('/', 'System\MatrixController@getMatrix')->name('system.matrix.getMatrix');//
    Route::post('join', 'System\MatrixController@postJoinTree')->name('system.matrix.postJoinTree');//
    Route::post('deposit', 'System\MatrixController@postDepositMatrix')->name('system.matrix.postDepositMatrix');//
    Route::post('withdraw', 'System\MatrixController@postWithdrawMatrix')->name('system.matrix.postWithdrawMatrix');//
  });
  //member
  Route::group(['prefix' => 'member'], function () {
    Route::get('add', 'System\UserController@getAdd')->name('system.user.getAdd');
    Route::get('list', 'System\UserController@getList')->name('system.user.getList');
    Route::post('member-add', 'Auth\RegisterController@postMemberAdd')->name('system.user.postMemberAdd');
    Route::get('tree', 'System\UserController@getTree')->name('system.user.getTree');


    Route::get('profile', 'System\UserController@getProfile')->name('getProfile');
    Route::post('profile', 'System\UserController@postProfile')->name('postProfile');//
    Route::post('auth', 'System\UserController@postAuth')->name('postAuth');
    Route::post('change-password', 'Auth\ResetPasswordController@changePassword')->name('postChangePassword');
    Route::post('post-kyc', 'System\UserController@PostKYC')->name('system.user.PostKYC');
    Route::post('post-postNamePhone','System\UserController@postNamePhone')->name('system.user.postNamePhone');

  });
  //wallet
  Route::group(['prefix' => 'wallet'], function () {
    Route::get('/', 'System\WalletController@getWallet')->name('system.getWallet');

    Route::get('deposit', 'System\WalletController@getDeposit')->name('system.getDeposit');

    Route::get('swap', 'System\WalletController@getSwap')->name('system.getSwap');
    Route::post('swap', 'System\WalletController@postSwap')->name('system.postSwap');
    Route::post('confirm-swap', 'System\WalletController@postWithdraw')->name('system.postConfirmWithdraw');

    Route::get('withdraw', 'System\WalletController@getWithdraw')->name('system.getWithdraw');
    Route::post('withdraw', 'System\WalletController@postWithdraw')->name('system.postWithdraw');
    Route::post('withdraw-money', 'System\WalletController@postWithdrawNew')->name('system.postWithdrawNew');
    Route::post('confirm-withdraw', 'System\WalletController@postWithdraw')->name('system.postConfirmWithdraw');

    Route::get('transfer', 'System\WalletController@getTransfer')->name('system.getTransfer');
    Route::post('transfer', 'System\WalletController@postTransfer')->name('system.postTransfer');
    Route::post('confirm-transfer', 'System\WalletController@postConfirmTranfer')->name('system.postConfirmTranfer');


    Route::post('withdraw-income','System\WalletController@postWithdrawIncome')->name('system.postWithdrawIncome');
  });
  //Invest
  Route::group(['prefix' => 'investment'], function () {
    Route::get('/', 'System\InvestmentController@getInvestment')->name('system.getInvestment');
    Route::post('investment', 'System\InvestmentController@postInvestment')->name('system.postInvestment');
    Route::get('cancel-investment/{id}', 'System\InvestmentController@getCancelInvestment')->name('system.getCancelInvestment');
    Route::get('get-investment', 'System\InvestmentController@getInvestmentByID')->name('system.getInvestmentByID');
    Route::get('cancel-investment', 'System\InvestmentController@CancelInvestmentByID')->name('system.CancelInvestmentByID');
    //Rufund or Reinvestment

    Route::put('action/refund/{id}', 'System\InvestmentController@postActionRefund')->name('postActionRefund');
    Route::put('action/reinvestment/{id}', 'System\InvestmentController@postActionReinvestment')->name('postActionReinvestment');
    //get info pakage
    Route::get('package/{id?}', 'System\InvestmentController@getInfo_Package')->name('getInfo_Package');

  });
  //Ticket
  Route::group(['prefix' => 'ticket'], function () {
    Route::get('/', 'System\TicketController@getTicket')->name('Ticket');
    Route::post('post-ticket', 'System\TicketController@postTicket')->name('postTicket');
    Route::get('destroy-ticket/{id}', 'System\TicketController@destroyTicket')->name('destroyTicket');
    Route::get('get-ticket-detail/{id}', 'System\TicketController@getTicketDetail')->name('getTicketDetail');//
    Route::get('ticket-admin', 'System\TicketController@getTicketAdmin')->name('getTicketAdmin');//
    Route::get('update-status/{id}', 'System\TicketController@getStatusTicketAdmin')->name('getStatusTicketAdmin');//
  });
  //Json
  Route::group(['prefix'=>'json']	, function (){
    Route::get('getAddress', 'System\CoinbaseController@getAddress')->name('system.json.getAddress');
    Route::get('coinbase', 'System\JsonController@getCoinbase')->name('system.json.getCoinbase');
    Route::get('history-game', 'System\JsonController@getHistoryGame')->name('system.json.getHistoryGame');
    Route::get('statistical', 'System\JsonController@getStatistical')->name('system.json.getStatistical');//1
    Route::get('get-balance-sportsbook', 'System\JsonController@getBalanceSportsbook')->name('system.json.getBalanceSportsbook');//1
    Route::get('get-balance-lottery', 'System\JsonController@getBalanceLottery')->name('system.json.getBalanceLottery');//1
  });
  Route::group(['prefix'=>'ajax'], function (){
    Route::get('ajax-user', 'System\AjaxController@getAjaxUser')->name('system.getAjaxUser');
    Route::get('get-balance', 'System\AjaxController@getBalance')->name('sonicGame.getBalance');//1
    Route::get('ajax-otp', 'System\UserController@getOTP')->name('system.ajax.getOTP');
    Route::get('ajax-sale-user', 'System\UserController@getAjaxSaleUser')->name('system.getAjaxSaleUser');
    Route::get('ajax-change-side-active', 'System\UserController@changeUserSideActivce')->name('changeSideActive');
  });
  //History
  Route::group(['prefix' => 'history'], function () {
    Route::get('wallet', 'System\WalletController@getHistoryWallet')->name('system.history.getHistoryWallett');
    Route::get('game', 'System\WalletController@getHistoryGame')->name('system.history.getHistoryGame');
    Route::get('commission', 'System\CommissionController@getHistoryCommission')->name('system.history.getHistoryCommisson');
    Route::get('investment', 'System\InvestmentController@getHistoryInvestment')->name('system.history.getHistoryInvestment');
    Route::get('interest', 'System\CommissionController@getInterest')->name('system.history.getInterest');

  });

  //Admin
  Route::group(['middleware'=>'check.permission','prefix'=>'admin'], function (){
    Route::group(['prefix' => 'fiat'], function () {
      Route::get('list', 'System\FiatController@getListFiat')->name('system.admin.getListFiat');
      Route::get('detail', 'System\FiatController@getDetailFiat')->name('system.admin.getDetailFiat');
    });

    Route::get('cooperration-contact', 'System\AdminController@getAdminCooperation')->name('system.admin.getAdminCooperation');//1

    Route::get('egg-customer', 'System\AdminController@getEggsTransfer')->name('system.admin.getEggsTransfer');//1
    Route::post('transfer-egg', 'System\AdminController@postTransferEgg')->name('system.admin.postTransferEgg');//1
    Route::get('export-balance-user', 'System\AdminController@getExportBalanceUser')->name('system.admin.getExportBalanceUser');//1
    //treo thong bao

    Route::get('promotion-code', 'System\AdminController@getPromotionCode')->name('system.admin.getPromotionCode');
    Route::post('create-code-promotion', 'System\AdminController@createCodePromotion')->name('system.admin.createCodePromotion');

    //blog
    Route::get('blog-events', 'System\AdminController@getBlogEvent')->name('system.admin.getBlogEvent');//1
    Route::post('add-blog', 'API\NotificationController@postAddBlog')->name('system.admin.postAddBlog');//1
    Route::post('update-blog', 'API\NotificationController@postUpdateBlog')->name('system.admin.postUpdateBlog');//1
    Route::get('edit-blog/{id}', 'System\AdminController@getEditBlog')->name('system.admin.getEditBlog');//1
    Route::get('delete-blog/{id}', 'System\AdminController@getDeleteBlog')->name('system.admin.getDeleteBlog');//1
    Route::get('edit-agency/{id}/{level}', 'System\AdminController@getSetAgencyUser')->name('system.admin.getSetAgencyUser');//1


    Route::get('insurance', 'System\AdminController@getAdminInsurance')->name('system.admin.getAdminInsurance');//1
    Route::get('setting/insurance', 'System\AdminController@getAdminSettingInsurance')->name('system.admin.getAdminSettingInsurance');//1
    Route::post('setting/insurance/game', 'System\AdminController@postAdminInsuranceGame')->name('system.admin.postAdminInsuranceGame');//1
    Route::post('setting/insurance/time', 'System\AdminController@postAdminInsuranceTime')->name('system.admin.postAdminInsuranceTime');//1
    Route::post('setting/insurance/min', 'System\AdminController@postAdminInsuranceMin')->name('system.admin.postAdminInsuranceMin');//1
    Route::post('setting/insurance/date', 'System\AdminController@postAdminInsuranDate')->name('system.admin.postAdminInsuranDate');//1
    Route::post('setting/insurance/countries', 'System\AdminController@postAdminInsuranCountries')->name('system.admin.postAdminInsuranCountries');//1
    Route::get('setting/insurance/game/{id}', 'System\AdminController@getAdminInsuranceDeleGame')->name('system.admin.getAdminInsuranceDeleGame');//1
    Route::get('setting/insurance/time/{id}', 'System\AdminController@getAdminInsuranceDeleTime')->name('system.admin.getAdminInsuranceDeleTime');//1
    Route::get('setting/insurance/date/{id}', 'System\AdminController@getAdminInsuranceDeleDate')->name('system.admin.getAdminInsuranceDeleDate');//1
    Route::get('setting/insurance/countries/{id}', 'System\AdminController@getAdminInsuranceDeleCountries')->name('system.admin.getAdminInsuranceDeleCountries');//1

    Route::post('setting/insurance/edit/game', 'System\AdminController@postAdminEditInsurGame')->name('system.admin.postAdminEditInsurGame');//1
    Route::post('setting/insurance/edit/time', 'System\AdminController@postAdminEditInsurTime')->name('system.admin.postAdminEditInsurTime');//1
    Route::post('setting/insurance/edit/date', 'System\AdminController@postAdminEditInsurDate')->name('system.admin.postAdminEditInsurDate');//1
    Route::get('setting/insurance/{id}/{countries}', 'System\AdminController@getAdminInsuranceEditCountries')->name('system.admin.getAdminInsuranceEditCountries');//1

    //treo thong bao hinh anh
    Route::get('up-noti', 'System\NotificationImageController@getNoti')->name('admin.getNoti');
    Route::post('up-noti', 'System\NotificationImageController@postNoti')->name('admin.postNoti');
    Route::get('hidden-noti/{id}', 'System\NotificationImageController@getHideNoti')->name('admin.getHideNoti');
    Route::get('delete-noti/{id}', 'System\NotificationImageController@getDeleteNoti')->name('admin.getDeleteNoti');

    //thong bao dashboard
    Route::get('noti-posts', 'System\PostNotificationController@getNotiPost')->name('admin.getNotiPosts');
    Route::get('up-noti-posts', 'System\PostNotificationController@upNotiPost')->name('admin.upNotiPost');
    Route::post('up-noti-posts', 'System\PostNotificationController@postNotiPost')->name('admin.postNotiPost');
    Route::get('set-new/{id}', 'System\PostNotificationController@getSetNew')->name('admin.getSetNew');
    Route::get('hidden-noti-posts/{id}', 'System\PostNotificationController@getHideNotiPosts')->name('admin.getHideNotiPosts');
    Route::get('deleted-noti-posts/{id}', 'System\PostNotificationController@getDeleteNotiPosts')->name('admin.getDeleteNotiPosts');
    //info agency
    Route::get('hiring', 'System\AdminController@getListHiring')->name('admin.getListHiring');

    Route::get('language-translation', 'System\MultiLangController@getLanguageTranslation')->name('admin.getLanguageTranslation');
    Route::post('language-translation', 'System\MultiLangController@postLanguageTranslation')->name('admin.postLanguageTranslation');
    Route::get('delete-language-translation/{id}', 'System\MultiLangController@deleteLanguageTranslation')->name('admin.deleteLanguageTranslation');
    Route::get('edit-language-translation/{id}', 'System\MultiLangController@editLanguageTranslation')->name('admin.editLanguageTranslation');
    Route::post('change-version-language', 'System\MultiLangController@changeVersionLanguage')->name('admin.changeVersionLanguage');
    Route::get('announcement-language-translation', 'System\MultiLangController@getAnnouncementLanguageTranslation')->name('admin.getAnnouncementLanguageTranslation');
    Route::post('announcement-language-translation', 'System\MultiLangController@postAnnouncementLanguageTranslation')->name('admin.postAnnouncementLanguageTranslation');
    Route::get('delete-announcement-language-translation/{id}', 'System\MultiLangController@deleteAnnouncementLanguageTranslation')->name('admin.deleteAnnouncementLanguageTranslation');
    Route::get('edit-announcement-language-translation/{id}', 'System\MultiLangController@editAnnouncementLanguageTranslation')->name('admin.editAnnouncementLanguageTranslation');

    //so luong trung no
    Route::get('eggs-hatching', 'System\EggsHatchingController@getEggsHatching')->name('admin.getEggsHatching');
    Route::post('add-eggs-hatching', 'System\EggsHatchingController@postAddEggsHatching')->name('admin.postAddEggsHatching');
    Route::get('edit-eggs-hatching/{id}', 'System\EggsHatchingController@getEditEggsHatching')->name('admin.getEditEggsHatching');
    Route::post('edit-eggs-hatching', 'System\EggsHatchingController@postEditEggsHatching')->name('admin.postEditEggsHatching');
    Route::get('delete-eggs-hatching/{id}', 'System\EggsHatchingController@getDeleteEggsHatching')->name('admin.getDeleteEggsHatching');

    //package giftcode
    Route::get('package-gift-code', 'System\GiftCodeController@getPackageGiftCode')->name('admin.getPackageGiftCode');
    Route::post('package-gift-code', 'System\GiftCodeController@postAddPackageGiftCode')->name('admin.postAddPackageGiftCode');
    Route::get('edit-package-gift-code/{id}', 'System\GiftCodeController@getEditPackageGiftCode')->name('admin.getEditPackageGiftCode');
    Route::post('edit-package-gift-code', 'System\GiftCodeController@postEditPackageGiftCode')->name('admin.postEditPackageGiftCode');
    Route::get('delete-package-gift-code/{id}', 'System\GiftCodeController@getDelPackageGiftCode')->name('admin.getDelePackageGiftCode');

    //gift code
    Route::get('gift-code', 'System\GiftCodeController@getGiftCode')->name('admin.getGiftCode');
    Route::post('gift-code', 'System\GiftCodeController@postAddGiftCode')->name('admin.postAddGiftCode');
    Route::get('delete-gift-code/{id}', 'System\GiftCodeController@getDelGiftCode')->name('admin.getDelGiftCode');

    Route::get('up-file-document', 'System\FileDocumentController@getIndex')->name('admin.getIndex');
    Route::post('up-file-document', 'System\FileDocumentController@postFileDoc')->name('admin.postFileDoc');
    Route::get('hidden-file-document/{id}', 'System\FileDocumentController@getHideFile')->name('admin.getHideFile');
    Route::get('delete-file-document/{id}', 'System\FileDocumentController@getDeleteFile')->name('admin.getDeleteFile');

    //Member
    Route::get('member', 'System\AdminController@getMemberListAdmin')->name('system.admin.getMemberListAdmin');//1
    Route::get('login/{id}', 'System\AdminController@getLoginByID')->name('system.admin.getLoginByID');//1
    Route::get('active-mail/{id}', 'System\AdminController@getActiveMail')->name('system.admin.getActiveMail');//1
    Route::post('edit-mail', 'System\AdminController@getEditMailByID')->name('system.admin.getEditMailByID');//1
    Route::get('disable-auth/{id}', 'System\AdminController@getDisableAuth')->name('system.admin.getDisableAuth');//1
    Route::get('reset-pass/{id}', 'System\AdminController@getResetPassword')->name('system.admin.getResetPassword');//1
    Route::get('reset-pass-wm555/{id}', 'System\AdminController@getResetPasswordWm555')->name('system.admin.getResetPasswordWm555');//1
    Route::get('on-off-function', 'System\AdminController@onOffFunction')->name('system.admin.onOffFunction');//1
    Route::get('block/{id}', 'System\AdminController@getBlockUser')->name('system.admin.getBlockUser');//1

    Route::get('edit-level/{id}/{level}', 'System\AdminController@getSetLevelUser')->name('system.admin.getSetLevelUser');//1

    Route::get('matrix', 'System\MatrixController@getMatrix')->name('system.admin.getMatrix');//
    //Wallet
    Route::get('wallet', 'System\AdminController@getWallet')->name('system.admin.getWallet');//1
    Route::get('interest', 'System\AdminController@getInterest')->name('system.admin.getInterest');
    Route::post('deposit', 'System\AdminController@postDepositAdmin')->name('system.admin.postDepositAdmin');//1


    Route::post('insert-bet', 'System\AdminController@postInsertBet')->name('system.admin.postInsertBet');

    Route::get('wallet/detail/{id}', 'System\AdminController@getWalletDetail')->name('system.admin.getWalletDetail');//1

    //Wallet
    Route::get('wallet-game', 'System\AdminController@getWalletGame')->name('system.admin.getWalletGame');//1


    //Invest
    Route::get('investment', 'System\AdminController@getAdminInvestmentList')->name('system.admin.InvestmentList');//
    Route::post('post-check-interest-list', 'System\AdminController@postCheckInterestList')->name('system.admin.postCheckInterestList');
    //percent profit
    Route::get('percent', 'System\AdminController@getPercent')->name('system.admin.getPercent');
    Route::post('percent', 'System\AdminController@postChangePercent')->name('system.admin.postChangePercent');
    //LOT profit
    Route::get('lot', 'System\AdminController@getLot')->name('system.admin.getLot');
    Route::post('lot-member', 'System\AdminController@postChangeLotMember')->name('system.admin.postChangeLotMember');
    Route::post('lot-sales', 'System\AdminController@postChangeLotSales')->name('system.admin.postChangeLotSales');
    //statistical
    Route::get('statistical', 'System\AdminController@getStatistical')->name('system.admin.getStatistical');
    //Banner
    Route::get('banner', 'System\AdminController@getBanner')->name('system.admin.getBanner');
    Route::post('banner', 'System\AdminController@postBanner')->name('system.admin.postBanner');

    // List Game
    Route::match(['get', 'post'], 'list/game', [AdminController::class, 'getListGame'])->name('system.admin.getListGame');
    Route::match(['get', 'post'], 'edit-list-game/{id}', [AdminController::class, 'editListGame'])->name('system.admin.editListGame');

    //statistical game
    Route::get('statistical-game', 'System\AdminController@getStatisticalGame')->name('system.admin.getStatisticalGame');

    //history eggs
    Route::get('history-eggs', 'System\AdminController@getHistoryEggs')->name('system.admin.getHistoryEggs');
    //history fishs
    Route::get('history-fishs', 'System\AdminController@getHistoryFishs')->name('system.admin.getHistoryFishs');
    //history foods
    Route::get('history-foods', 'System\AdminController@getHistoryFoods')->name('system.admin.getHistoryFoods');
    //history pools
    Route::get('history-pools', 'System\AdminController@getHistoryPools')->name('system.admin.getHistoryPools');
    //history markets
    Route::get('history-markets', 'System\AdminController@getHistoryMarkets')->name('system.admin.getHistoryMarkets');
    //history markets
    Route::get('list-egg-rate', 'System\AdminController@listEggRate')->name('system.admin.listEggRate');
    Route::post('change-table-id', 'System\AdminController@postChangeTableID')->name('system.admin.postChangeTableID');

    //coinbase
    Route::get('coinbase', 'System\CoinbaseController@getCoinbase')->name('system.admin.getCoinbase');
    //Profile
    Route::get('profile', 'System\AdminController@getProfile')->name('system.admin.getProfile');//
    Route::post('confirm-profile', 'System\AdminController@confirmProfile')->name('system.admin.confirmProfile');//
    //Log Mail
    Route::get('log-mail', 'System\AdminController@getLogMail')->name('system.admin.getLogMail');//
    //Log SOX
    Route::get('log-sox', 'System\AdminController@getLogSOX')->name('system.admin.getLogSOX');
    //log game wallet
    Route::get('game-wallet', 'System\AdminController@getGameWallet')->name('system.admin.getGameWallet');

    Route::get('history-game', 'System\AdminController@getHistoryGame')->name('system.admin.getHistoryGame');

    Route::get('member-support', 'System\AdminController@SupportMember')->name('system.admin.SupportMember');
    Route::get('resent-mail/{id}', 'System\AdminController@getResentMail')->name('system.admin.getResentMail');

    //123BetNow
    Route::get('history-wm555', 'System\BetNowController@getGameWalletWM')->name('system.admin.getGameWalletWM');
    Route::get('history-sportbook', 'System\BetNowController@getSportBook')->name('system.admin.getSportBook');
    Route::get('game-wallet-sa', 'System\BetNowController@getGameWalletSA')->name('system.admin.getGameWalletSA');
    Route::post('import-data-sa', 'System\BetNowController@postImportGameSA')->name('system.admin.postImportGameSA');

    //sbobet
    Route::get('history-sbobets', 'System\BetNowController@getHistorySbobets')->name('system.admin.getHistorySbobets');
    Route::post('import-volume-sbobets', 'System\BetNowController@postImportVolumeSbobets')->name('system.admin.postImportVolumeSbobets');
    //sbobet
    //evolution
    Route::get('history-evolution', 'System\BetNowController@getHistoryEvolutions')->name('system.admin.getHistoryEvolutions');
    Route::post('import-volume-evolution', 'System\BetNowController@postImportVolumeEvolutions')->name('system.admin.postImportVolumeEvolutions');
    //evolution

    Route::get('history-agin-sportbook', 'System\BetNowController@getAginSportBook')->name('system.admin.getAginSportBook');
    Route::post('import-data-agin-sportbook', 'System\BetNowController@postImportGameAginSportBook')->name('system.admin.postImportGameAginSportBook');

    Route::get('history-agin-slot', 'System\BetNowController@getAginSlot')->name('system.admin.getAginSlot');
    Route::post('import-data-agin-slot', 'System\BetNowController@postImportGameAginSlot')->name('system.admin.postImportGameAginSlot');

    Route::get('history-agin-hunterfish', 'System\BetNowController@getAginHunterFish')->name('system.admin.getAginHunterFish');
    Route::post('import-data-agin-hunterfish', 'System\BetNowController@postImportGameAginHunterFish')->name('system.admin.postImportGameAginHunterFish');

    Route::get('history-evo', 'System\BetNowController@getGameEVO')->name('system.admin.getGameEVO');

    Route::get('history-sbobet', [BetNowController::class, 'getSbobet'])->name('system.admin.getSbobet');
    Route::get('history-sbobet-casino', [BetNowController::class, 'getSbobetCasino'])->name('system.admin.getSbobetCasino');
    Route::get('history-sbobet-virualsport', [BetNowController::class, 'getSbobetVirtualSport'])->name('system.admin.getSbobetVirtualSport');
    Route::get('history-sbobet-seamless', [BetNowController::class, 'getSbobetSeamless'])->name('system.admin.getSbobetSeamless');
    Route::get('history-sbobet-thirdparty', [BetNowController::class, 'getSbobetThirdPartySportsBook'])->name('system.admin.getSbobetThirdPartySportsBook');
    Route::get('player-balane-sbobet', [BetNowController::class, 'getBalancePlayer'])->name('system.admin.getBalancePlayer');

    //System ae - sexy
    Route::get('history-ae-sexy', 'System\BetNowController@getAeSexy')->name('system.admin.getAeSexy');
    Route::post('import-data-ae-sexy', 'System\BetNowController@postImportGameAeSexy')->name('system.admin.postImportGameAeSexy');

    Route::group(['prefix' => 'bottelegram'], function () {
      Route::get('/', 'System\BotTelegramController@getBotTelegram')->name('system.bot.getBotTelegram');
      Route::post('post', 'System\BotTelegramController@postBotTelegram')->name('system.bot.postBotTelegram');
      Route::post('post-update-telegram', 'System\BotTelegramController@addChanelBot')->name('system.bot.addChanelBot');
    });

    Route::group(['prefix' => 'license'], function () {
      Route::get('/', 'System\AdminController@getLicense')->name('system.admin.getLicense');
      Route::get('status/{id}', 'System\AdminController@getStatusLicense')->name('system.admin.getStatusLicense');
      Route::get('detail/{id}', 'System\AdminController@getDetailLicense')->name('system.admin.getDetailLicense');
    });
  });
});
Route::get('set-rule', 'FisheggfailController@setRuleEggsFail');
Route::get('cron-check-minus-balance/{userID}', 'TestController@getCheckMinusBalance')->name('system.admin.getCheckMinusBalance');

//provide admin
Route::get('provide/document', 'Provide\AuthController@getDocument')->name('provide.getDocument');

Route::get('provide/login', 'Provide\AuthController@getLogin')->name('provide.getLogin');
Route::post('provide/post-login', 'Provide\AuthController@postLogin')->name('provide.postLogin');
Route::get('provide/logout', 'Provide\AuthController@getLogout')->name('provide.getLogout');

Route::group(['prefix' => 'provide', 'middleware' => ['checkAminProvide']], function () {
  Route::get('users','Provide\UserController@getUser' )->name('provide.getUser');
  Route::get('wallet','Provide\WalletController@getWallet' )->name('provide.getWallet');
  Route::get('evol-history','Provide\EvolController@getHistoryEvol' )->name('provide.getHistoryEvol');
  Route::get('wm555-history','Provide\WM555Controller@getHistoryWM' )->name('provide.getHistoryWM');
  Route::get('sport-book-history','Provide\AginController@getHistoryAginSport' )->name('provide.getHistoryAginSport');
  Route::get('fish-shooter-history','Provide\AginController@getHistoryAginFish' )->name('provide.getHistoryAginFish');
  Route::get('slot-history','Provide\AginController@getHistoryAginSlot' )->name('provide.getHistoryAginSlot');
  Route::get('ae-sexy-history','Provide\AWCController@getHistoryAeSexy' )->name('provide.getHistoryAeSexy');

});

//save history to db
Route::group(['prefix' => 'cron/provide'], function () {
  Route::get('save-evol-history','Provide\EvolController@saveHistoryEvol' )->name('provide.saveHistoryEvol');
  Route::get('save-wm555-history','Provide\WM555Controller@saveHistoryWM' )->name('provide.saveHistoryWM');
  Route::get('save-sport-book-history','Provide\AginController@saveHistoryAginSport' )->name('provide.saveHistoryAginSport');
  Route::get('save-fish-shooter-history','Provide\AginController@saveHistoryAginFish' )->name('provide.saveHistoryAginFish');
  Route::get('save-slot-history','Provide\AginController@saveHistoryAginSlot' )->name('provide.saveHistoryAginSlot');
  Route::get('save-ae-sexy-history','Provide\AWCController@saveHistoryAeSexy' )->name('provide.saveHistoryAeSexy');
});


