<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BannerController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\SbobetController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('/test-mail-deposit', 'TestController@testMailDeposit')->name('testMailDeposit');
Route::get('withdraw-brand', 'TestController@withdrawBrand')->name('withdrawBrand');

Route::post('/test-post', 'TestController@postTest')->name('postTest');
Route::get('/test-search/{id}', 'API\TestSaController@testsearch')->name('testsearch');
Route::group(['prefix' => 'sagame','middleware' => ['langAPI']], function ($router) {
  Route::get('register', 'SAGameController@register')->name('api.register');
  Route::get('login', 'SAGameController@postLoginRequest')->name('api.postLoginRequest');
  Route::get('balance', 'SAGameController@checkBalance')->name('api.checkBalance');
  Route::get('deposit', 'SAGameController@depositSA')->name('api.depositSA');
  Route::get('withdraw', 'SAGameController@postCreditSA')->name('api.postCreditSA');
  Route::get('win-loss', 'SAGameController@GetUserWinLost')->name('api.GetUserWinLost');
  Route::get('history', 'SAGameController@gamehistory')->name('api.gamehistory');
  Route::get('callmethod', 'SAGameController@callmethod')->name('api.method');
  Route::get('all-transaction', 'SAGameController@getAllTransaction')->name('api.getAllTransaction');
  Route::post('search-history', 'SAGameController@searchDetailhistory')->name('api.sasearchDetailhistory');
});

Route::group(['prefix' => 'test-mdw', 'middleware' => ['auth:api']], function ($router) {
  Route::group(['middleware' => ['testDeposit','langAPI']], function ($router) {
    Route::get('get', 'TestController@getTestMiddw')->name('getTestMiddw');
  });
});

Route::group(['prefix' => 'v1/auth/address','middleware' => ['langAPI']], function ($router) {
  Route::post('/register', 'API\AuthAddressController@postRegisterAddress')->name('postRegisterAddress');
  Route::post('/login', 'API\AuthAddressController@postLoginAddress')->name('postLoginAddress');
});

Route::group(['prefix' => 'v1','middleware' => ['langAPI']], function ($router) {

  Route::get('list-game-v2', [DashboardController::class, 'listGameV2']);

  Route::get('/status-login-game', 'API\UserV2Controller@checkLoginGame')->name('checkLoginGame');

  Route::get('v1/test-withdraw-bonus', 'API\GameController@postTestWithdrawBonusBirthday')->name('postTestWithdrawBonusBirthday');
  //banner
  Route::get('/banner', [BannerController::class, 'getBanner'])->name('getBanner');

  Route::get('top-trending','API\DashboardController@topTrending')->name('topTrending');
  Route::get('rencently-game','API\DashboardController@recentlyGame')->name('recentlyGame');
  Route::get('top-game','API\DashboardController@topGame')->name('topGame');
  Route::get('top-game-week','API\DashboardController@topGameOfWeek')->name('topGameOfWeek');
  Route::get('user-top-game','API\DashboardController@userTopGame')->name('userTopGame');

  Route::get('/dashboard/list-game-type', [DashboardController::class, 'getGameType'])->name('getGameType');
  Route::get('/dashboard/list-all-live-casino', [DashboardController::class, 'getLiveCasino'])->name('getLiveCasino');
  Route::get('/dashboard/list-top-winner', [DashboardController::class, 'topListWinner'])->name('topListWinner');
  Route::get('/dashboard/list-history-latest-bet', [DashboardController::class, 'historyLatestBets'])->name('historyLatestBets');
  Route::get('/dashboard/high-rollers', [DashboardController::class, 'highRollers'])->name('highRollers');

  Route::post('/cooperation-contact', 'API\UserController@postCooperationContact')->name('postCooperationContact');
  Route::get('/liquid-partners', 'API\UserController@getLiquidPartner')->name('getLiquidPartner');

  Route::group(['prefix' => 'license'], function ($router) {
    Route::get('list-currency', 'API\LicenseController@listCurrency')->name('listCurrency');
    Route::post('send', 'API\LicenseController@postComplaints')->name('postComplaints');
  });

  Route::post('game-portal/change-password', 'API\EvolutionController@postChangePass')->name('game.portal.postChangePass');

  Route::group(['prefix' => 'agin-sportbook', 'middleware' => ['auth:api']], function ($router) {
    Route::get('list-member', 'API\AGINController@listmember')->name('agin.listmember');
    Route::get('balance', 'API\AGINController@getBalance')->name('agin.getBalance');
    Route::post('deposit', 'API\AGINController@deposit')->name('agin.deposit');
    Route::post('withdraw', 'API\AGINController@withdraw')->name('agin.withdraw');
    Route::post('create-member', 'API\AGINController@CreateMember')->name('agin.CreateMember');
    Route::post('change-password', 'API\AGINController@postChangePass')->name('agin.postChangePass');

    Route::post('login', 'API\AGINController@login')->name('agin.login');
    Route::get('best-history', 'API\AGINController@saveHistoryBest')->name('agin.saveHistoryBest');
    Route::get('list-bet-history', 'API\AGINController@listHistoryBest')->name('agin.listHistoryBest');
  });
  //Route::group(['prefix' => 'evolution'], function ($router) {
  Route::group(['prefix' => 'evolution', 'middleware' => ['auth:api']], function ($router) {
    Route::get('list-member', 'API\EvolutionController@listmember')->name('evolution.listmember');
    Route::get('balance', 'API\EvolutionController@getBalance')->name('evolution.getBalance');
    Route::post('deposit', 'API\EvolutionController@deposit')->name('evolution.deposit')->middleware('captchav3');
    Route::post('withdraw', 'API\EvolutionController@withdraw')->name('evolution.withdraw')->middleware('captchav3');

    Route::post('withdraw-v2', 'API\EvolutionController@withdrawV2');
    //Route::post('create-member', 'API\EvolutionController@CreateMember')->name('evolution.CreateMember');

    Route::post('login', 'API\EvolutionController@login')->name('evolution.login');
    Route::get('best-history', 'API\EvolutionController@saveHistoryBest')->name('evolution.saveHistoryBest');
    Route::get('list-bet-history', 'API\EvolutionController@listHistoryBest')->name('evolution.listHistoryBest');



  });



  Route::group(['prefix' => '1vnp'], function ($router) {
    Route::post('order-deposit', 'API\Integration1VNPController@postOrderDeposit');
    Route::post('callback-notify-order', 'API\Integration1VNPController@returnResultDeposit');

    Route::get('check-order', 'API\Integration1VNPController@orderStatusQuery');

    Route::post('order-payout', 'API\Integration1VNPController@postOrderPayout');

    Route::get('history-order', 'API\Integration1VNPController@getHistoryOrder');

    Route::get('list-bank', 'API\Integration1VNPController@listBank');
    Route::get('list-channel', 'API\Integration1VNPController@getChannel');
  });

  // Sbobet
  Route::group(['prefix' => 'promotion', 'middleware' => ['auth:api']], function ($router) {
    Route::post('input-code', 'API\WalletV2Controller@inputCodeBonus')->name('promotion.inputCodeBonus');
  });

  // Sbobet
  Route::group(['prefix' => 'promotion', 'middleware' => ['auth:api']], function ($router) {
    Route::post('input-code', 'API\WalletV2Controller@inputCodeBonus')->name('promotion.inputCodeBonus');
  });

  // Sbobet
  Route::post('block-agent', [SbobetController::class, 'BlockAgent'])->name('sbobet.BlockAgent');
  Route::get('sbobet/update-bet-member', [SbobetController::class, 'updateMaxbetMember'])->name('sbobet.updateMaxbetMember');
  Route::group(['prefix' => 'sbobet', 'middleware' => ['auth:api']], function ($router) {

    Route::post('create-member-agent', [SbobetController::class, 'CreateMemberAgent'])->name('sbobet.CreateMemberAgent');
    Route::post('deposit', [SbobetController::class, 'deposit'])->name('sbobet.deposit')->middleware('captchav3');
    Route::post('withdraw', [SbobetController::class, 'withdraw'])->name('sbobet.withdraw')->middleware('captchav3');
    Route::post('create-member', [SbobetController::class, 'CreateMember'])->name('sbobet.CreateMember');
    Route::post('change-password', [SbobetController::class, 'postChangePass'])->name('sbobet.postChangePass');
    Route::post('login', [SbobetController::class, 'login'])->name('sbobet.login');
    Route::get('list-game', [SbobetController::class, 'getListGameOfSbobet'])->name('sbobet.getListGameOfSbobet');

    Route::post('login-v2', [SbobetController::class, 'loginV2']);
    Route::post('deposit-v2', [SbobetController::class, 'depositV2'])->name('sbobet.depositV2');//->middleware('captchav3')
    Route::post('withdraw-v2', [SbobetController::class, 'withdrawV2'])->name('sbobet.withdrawV2');//->middleware('captchav3')
  });

  Route::get('sbobet/deposit-test', [SbobetController::class, 'depositTestSbobet'])->name('sbobet.depositTestSbobet');

  Route::get('sbobet/best-history', [SbobetController::class, 'saveHistoryBest'])->name('sbobet.saveHistoryBest');
  Route::get('sbobet/best-history/sportbook-sbolive', [SbobetController::class, 'saveHistorySportbookSbolive'])->name('sbobet.saveHistorySportbookSbolive');
  Route::get('sbobet/best-history/sportbook-sbolive/V2', [SbobetController::class, 'saveHistorySportbookSboliveV2'])->name('sbobet.saveHistorySportbookSboliveV2');
  Route::get('sbobet/best-history/sportbook-sbolive/V3', [SbobetController::class, 'saveHistorySportbookSboliveV3'])->name('sbobet.saveHistorySportbookSboliveV3');
  Route::get('sbobet/best-history/sportbook-sbolive/V4', [SbobetController::class, 'saveHistorySportbookSboliveV4'])->name('sbobet.saveHistorySportbookSboliveV4');
  Route::get('sbobet/best-history/sportbook-sbolive/V5', [SbobetController::class, 'saveHistorySportbookSboliveV5'])->name('sbobet.saveHistorySportbookSboliveV5');
  Route::get('sbobet/best-history/casino', [SbobetController::class, 'saveHistoryCasino'])->name('sbobet.saveHistoryCasino');
  Route::get('sbobet/best-history/seamless', [SbobetController::class, 'saveHistorySeamlessGame'])->name('sbobet.saveHistorySeamlessGame');
  Route::get('sbobet/best-history/virtual', [SbobetController::class, 'saveHistoryVirtual'])->name('sbobet.saveHistoryVirtual');

  //AE Sexy AWC 
  Route::group(['prefix' => 'ae-sexy', 'middleware' => ['auth:api']], function ($router) {
    //Route::get('list-member', 'API\AGINController@listmember')->name('agin.listmember');
    //Route::get('balance', 'API\AGINController@getBalance')->name('agin.getBalance');
    Route::post('deposit', 'API\AWCController@deposit')->name('ae.deposit');
    Route::post('withdraw', 'API\AWCController@withdraw')->name('ae.withdraw');
    Route::post('create-member', 'API\AWCController@CreateMember')->name('ae.CreateMember');
    Route::post('change-password', 'API\AWCController@postChangePass')->name('ae.postChangePass');

    Route::post('login', 'API\AWCController@login')->name('ae.login');

  });
  Route::get('ae-sexy/best-history', 'API\AWCController@saveHistoryBest')->name('ae.saveHistoryBest');
  Route::get('ae-sexy/list-bet-history', 'API\AWCController@listHistoryBest')->name('ae.listHistoryBest');


  Route::group(['prefix' => 'auth/address', 'middleware' => ['auth:api']], function ($router) {
    Route::post('/update-email', 'API\AuthAddressController@postUpdateEmail')->name('postUpdateEmail');
    Route::post('/update-address', 'API\AuthAddressController@postUpdateAddress')->name('postUpdateAddress');
    Route::post('/update-password', 'API\AuthAddressController@postUpdatePassword')->name('postUpdatePassword');
  });

  Route::group(['prefix' => 'test-sportbook', 'middleware' => ['auth:api']], function ($router) {
    Route::post('create-member', 'API\SportBookController@CreateMember')->name('CreateMember');
  });

  Route::group(['prefix' => 'tournament', 'middleware' => ['auth:api']], function ($router) {
    Route::get('list', 'API\TicketController@getTournament')->name('getTournament');
    Route::post('buy', 'API\TicketController@postBuyTournamentTicket')->name('postBuyTournamentTicket');
  });
  Route::group(['prefix' => 'top-leader', 'middleware' => ['auth:api']], function ($router) {
    Route::get('get-info', 'API\TopLeaderController@getInforTopLeader')->name('getInforTopLeader');
    Route::get('get-list', 'API\TopLeaderController@getListTopLeader')->name('getListTopLeader');
  });
  Route::group(['prefix' => 'top-trade', 'middleware' => ['auth:api']], function ($router) {
    Route::get('get-info', 'API\TopLeaderController@getInforTopTrader')->name('getInforTopTrader');
    Route::get('get-list', 'API\TopLeaderController@getListTopTrader')->name('getListTopTrader');
  });

  Route::get('posts-notifi', 'API\NotificationController@getNotiPost')->name('notifi.getNotifi');
  Route::get('document', 'API\NotificationController@getDocument')->name('notifi.getDocument');

  Route::get('check-deposit-platform', 'API\WalletController@getDepositPlatform')->name('getDepositPlatform');

  Route::post('otp-sendmail', 'API\WalletController@sendMailOTP')->name('sendMailOTP')->middleware('auth:api');

  Route::get('notification', 'System\NotificationImageController@getAPINotification')->name('getAPINotification');

  Route::get('noti-laning', 'System\NotificationImageController@getApiNotificationLanding')->name('getApiNotificationLanding')->middleware(['throttle:3,1']);
  Route::post('register', 'API\UserController@postRegister')->name('postRegister');
  Route::post('add-member', 'API\UserController@postAddMember')->name('postAddMember');

  Route::post('login', 'API\UserController@postLogin')->name('postLogin');


  Route::post('login-v2', 'API\UserV2Controller@postLogin');
  Route::post('register-v2', 'API\UserV2Controller@postRegister');
  Route::post('add-member-v2', 'API\UserV2Controller@postAddMember');



  Route::post('login-metamask', 'API\MetamaskController@postLoginMetamask')->name('postLoginMetamask');
  Route::post('forget', 'API\UserController@postForgetPassword')->name('postForgetPassword');
  Route::group(['prefix' => 'spin'], function ($router) {
    Route::get('info', 'API\SpinController@getInfoSpin')->name('getInfoSpin');
    Route::post('play', 'API\SpinController@postSpin')->name('postSpin');
    Route::post('buy', 'API\SpinController@postBuyVoucher')->name('postBuyVoucher');
    Route::post('withdraw', 'API\SpinController@postWithdrawVoucher')->name('postWithdrawVoucher');
  });
  Route::group(['prefix' => 'sagame'], function ($router) {
    Route::post('login', 'API\SAGameController@postLoginRequest');
  });
  Route::group(['prefix' => 'skygame'], function ($router) {
    Route::post('login', 'API\SkyGameController@postLoginRequest');
  });
  Route::group(['prefix' => 'bc'], function ($router) {
    Route::post('login', 'API\BCSportController@postLoginRequest');
  });
  Route::group(['prefix' => 'game', 'middleware' => ['auth:api']], function ($router) {
    Route::get('agency', 'API\GameController@getAgencyIB')->name('getAgencyIB');
    Route::post('withdraw-bonus', 'API\GameController@postWithdrawBonus')->name('postWithdrawBonus');
    Route::post('buy-agency', 'API\GameController@postBuyAgency')->name('postBuyAgency');
    Route::post('deposit', 'API\GameController@depositGame')->name('depositGame');
    Route::post('withdraw', 'API\GameController@withdrawGame')->name('withdrawGame');
    Route::get('list', 'API\GameController@getListGame')->name('getListGame');

    Route::post('test-withdraw-bonus-birthday', 'API\GameController@postTestWithdrawBonusBirthday')->name('postTestWithdrawBonusBirthday');

    Route::post('withdraw-bonus-birthday', 'API\GameController@postWithdrawBonusBirthday')->name('postWithdrawBonusBirthday');
  });
  Route::get('statictical-home', 'API\GameController@getStaticticalHome')->name('getStaticticalHome');
  Route::get('list-game-new', 'API\GameController@getListGameNew')->name('getListGameNew');
  Route::get('list-game', 'API\GameController@getListGameTest')->name('getListGameTest');

  Route::group(['prefix' => 'auth', 'middleware' => ['auth:api']], function ($router) {
    Route::get('dashboard', 'API\DashboardController@getDashboard')->name('getDashboard');
    Route::post('update-email', 'API\UserV2Controller@updateEmail');
    Route::get('active-email', 'API\UserV2Controller@resentMail');
  });
  //Ticket
  Route::group(['prefix' => 'ticket', 'middleware' => ['auth:api']], function () {
    Route::get('/', 'API\TicketController@getTicket')->name('Ticket');
    Route::post('post-ticket', 'API\TicketController@postTicket')->name('postTicket');
    Route::get('get-ticket-detail/{id}', 'API\TicketController@getTicketDetail')->name('getTicketDetail');//
  });
  //Member
  Route::group(['prefix' => 'member', 'middleware' => ['auth:api']], function ($router) {
    Route::post('member-list', 'API\MemberController@memberList')->name('memberList');
    Route::post('member-list-agency', 'API\MemberController@memberListAgency')->name('memberListAgency');
    Route::get('member-tree', 'API\MemberController@memberTree')->name('memberTree');
    Route::post('add-member', 'API\MemberController@postAddMember')->name('postAddMember')->middleware('captchav3');
    Route::get('get-member-detail/{id}', 'API\MemberController@getMemberDetail')->name('getMemberDetail');
    Route::get('check-email', 'API\MemberController@checkEmail')->name('checkEmail');
    Route::post('post-kyc', 'API\MemberController@postKYC')->name('postKYC');

    Route::get('block-user-member', 'API\MemberController@getBlockUserMember');
  });
  //profile
  Route::group(['prefix' => 'profile', 'middleware' => ['auth:api']], function ($router) {
    Route::post('/', 'API\ProfileController@getProfile')->name('getProfile');
    Route::post('/change-password', 'API\ProfileController@postChangePassword')->name('postChangePassword');
  });


  //Wallet
  Route::group(['prefix' => 'wallet', 'middleware' => ['auth:api']], function ($router) {
    Route::get('deposit', 'API\WalletController@getDeposit')->name('getDeposit');
    Route::post('deposit-member-add', 'API\WalletController@postDepositMemberAdd');
    Route::post('withdraw-member-add', 'API\WalletController@postWithdrawMemberAdd');
    Route::post('transfer', 'API\WalletController@postTransfer')->name('postTransfer')->middleware('captchav3');
    Route::post('withdraw', 'API\WalletController@postWithdraw')->name('postWithdraw')->middleware('captchav3');
    Route::get('swap', 'API\WalletController@postSwap')->name('postSwap');
    Route::get('coin', 'API\UserController@getCoin')->name('getCoin');
    Route::post('update', 'API\UserController@updateWithdrawAddress')->name('update');
    Route::post('trasaction-detail', 'API\UserController@getTransactionDetail')->name('getTransactionDetail');

    //test
    Route::post('swap-ebp', 'API\TestDepositEBPController@postSwapEBP')->name('postSwapEBP');
    Route::post('withdraw-ebp', 'API\TestDepositEBPController@postWithdrawEBP')->name('postWithdrawEBP');
    Route::post('withdraw-user', 'API\WalletV2Controller@postWithdrawNew')->name('postWithdrawNew');

    //Route::post('withdraw-auto-pay', 'System\PayautoController@postWithdrawAuto')->name('postWithdrawAuto');
  });

  Route::group(['prefix' => 'lucky'], function ($router) {
    Route::post('check-email', 'API\WalletV2Controller@postCheckEmail')->name('postCheckEmail');
    Route::get('balance', 'API\WalletV2Controller@getBalanceLucky')->name('getBalanceLucky');
    Route::get('list-pool', 'API\WalletV2Controller@listPool')->name('listPool');
    Route::post('withdraw', 'API\WalletV2Controller@postWithdrawToLuckyHero')->name('postWithdrawToLuckyHero')->middleware('captchav3');
    Route::get('history-deposit-lucky', 'API\WalletV2Controller@getHistoryDepositLucky')->name('getHistoryDepositLucky');
  });

  //Insurance
  Route::group(['prefix' => 'insurance', 'middleware' => ['auth:api']], function ($router) {
    Route::get('/', 'API\InsuranceController@getInsurance')->name('getInsurance');
    Route::post('/', 'API\InsuranceController@postInsurance')->name('postInsurance');
    Route::post('increament', 'API\InsuranceController@postIncreaAmount')->name('postIncreaAmount');
    Route::get('history', 'API\InsuranceController@getHistoryInsurance')->name('getHistoryInsurance');
  });

  //History
  Route::group(['prefix' => 'report', 'middleware' => ['auth:api']], function ($router) {
    Route::get('login', 'API\ReportController@getHistoryLogin')->name('getHistoryLogin');
    Route::get('wallet', 'API\ReportController@getHistoryWallet')->name('getHistoryWallet');
    Route::get('wallet/v2', 'API\ReportController@getHistoryWalletNew')->name('getHistoryWalletNew');
    Route::get('game', 'API\ReportController@getHistoryGame')->name('getHistoryGame');
  });

  //Auth
  Route::group(['prefix' => 'auth', 'middleware' => ['auth:api']], function ($router) {
    //Route::get('logout', 'API\UserController@getLogout')->name('getLogout');
    //Route::post('change-password', 'API\UserController@postChangePassword')->name('postChangePassword');
    //Route::post('register', 'API\UserController@postRegister')->name('postRegister');
    //Route::post('login', 'API\UserController@postLogin')->name('postLogin');
    //Route::post('forget-password', 'API\UserController@postForgetPassword')->name('postForgetPassword');
    //Route::get('info', 'API\UserController@getInfo')->name('getInfo');
    Route::get('get-auth', 'API\UserController@getAuth')->name('getAuth');
    Route::post('confirm-auth', 'API\UserController@postConfirmAuth')->name('postConfirmAuth');

    //Route::get('dashboard', 'API\DashboardController@getDashboard')->name('getDashboard');

    //Route::get('search-history-login', 'API\DashboardController@searchHistoryLogin')->name('searchHistoryLogin');
    //Route::get('search-transaction-history', 'API\DashboardController@searchTransactionHistory')->name('searchTransactionHistory');
  });

  //WM555
  Route::group(['prefix' => 'wm555'], function ($router) {
    Route::get('list-member', 'API\WM555Controller@listMemberWM')->name('listMemberWM');
    Route::get('balance', 'API\WM555Controller@getBalance')->name('getBalance');
    Route::post('register', 'API\WM555Controller@registerWM')->name('registerWM');
    Route::post('change-password', 'API\WM555Controller@postChangePass')->name('postChangePass');
    Route::post('deposit', 'API\WM555Controller@depositWM')->name('depositWM');
    Route::post('withdraw', 'API\WM555Controller@withdrawWM')->name('withdrawWM');
    Route::post('transaction-history', 'API\WM555Controller@transactionHistoryWM')->name('transactionHistoryWM');
    Route::get('bet-history', 'API\WM555Controller@betHistoryWM')->name('betHistoryWM');
    Route::get('save-bet-history', 'API\WM555Controller@saveBetHistoryWM')->name('saveBetHistoryWM');
    Route::get('get-balance', 'API\WM555Controller@getBalance')->name('getBalance');
  });
  Route::group(['prefix' => 'wm555-agency'], function ($router) {
    Route::get('list-member', 'API\WM555AgencyController@listMemberWM')->name('agency.listMemberWM');
    Route::get('balance', 'API\WM555AgencyController@getBalance')->name('agency.getBalance');
    Route::post('register', 'API\WM555AgencyController@registerWM')->name('agency.registerWM');
    Route::post('change-password', 'API\WM555AgencyController@postChangePass')->name('agency.postChangePass');
    Route::post('deposit', 'API\WM555AgencyController@depositWM')->name('agency.depositWM');
    Route::post('withdraw', 'API\WM555AgencyController@withdrawWM')->name('agency.withdrawWM');
    Route::post('transaction-history', 'API\WM555AgencyController@transactionHistoryWM')->name('agency.transactionHistoryWM');
    Route::get('bet-history', 'API\WM555AgencyController@betHistoryWM')->name('agency.betHistoryWM');
    Route::get('save-bet-history', 'API\WM555AgencyController@saveBetHistoryWM')->name('agency.saveBetHistoryWM');
    Route::get('get-balance', 'API\WM555AgencyController@getBalance')->name('agency.getBalance');
  });
  Route::group(['prefix' => '789api'], function ($router) {
    Route::post('login', 'API\G789APIController@login')->name('login');
    Route::get('balance', 'API\G789APIController@balance')->name('balance');
    Route::post('list-member', 'API\G789APIController@listMember789API')->name('listMember789API');
    Route::post('bet-history', 'API\G789APIController@betHistory789API')->name('betHistory789API');
    Route::post('deposit', 'API\G789APIController@deposit789API')->name('deposit789API');
    Route::post('withdraw', 'API\G789APIController@withdraw789API')->name('withdraw789API');
    Route::post('status', 'API\G789APIController@status')->name('status');
    Route::post('history-wallet', 'API\G789APIController@historyWallet789API')->name('historyWallet789API');
  });
  //Metamask
  Route::group(['prefix' => 'metamask', 'middleware' => ['auth:api']], function () {
    Route::get('info', 'API\MetamaskController@getInfoMetamask')->name('getInfoMetamask');
    Route::post('confirm-connect', 'API\MetamaskController@postConfirmConnectMetamask')->name('postConfirmConnectMetamask');
    Route::post('disconnect-connect', 'API\MetamaskController@postDisConnectMetamask')->name('postDisConnectMetamask');
  });
});


Route::post('v1/provide/create', 'APIProvide\UserProvideController@postCreateProvide')->name('provide.postCreateProvide');

Route::group(['prefix' => 'v1/provide' , 'middleware' => ['checkParentProvide']], function ($router) {
  Route::post('register', 'APIProvide\AginProvideController@register')->name('agin.provideRegister');
  Route::group(['prefix' => 'agin'], function ($router) {
    Route::post('create-member', 'APIProvide\AginProvideController@CreateMember')->name('agin.provideCreateMember');
    Route::post('deposit', 'APIProvide\AginProvideController@deposit')->name('agin.provideDeposit');
    Route::post('withdraw', 'APIProvide\AginProvideController@withdraw')->name('agin.provideWithdraw');
    Route::post('change-password', 'APIProvide\AginProvideController@postChangePass')->name('agin.provideChangePass');

    Route::post('login', 'APIProvide\AginProvideController@login')->name('agin.provideLogin');
    Route::get('best-history-spotbook', 'APIProvide\AginProvideController@listHistoryBestSport')->name('agin.provideListHistoryBestSport');
    Route::get('best-history-slot', 'APIProvide\AginProvideController@listHistoryBestSlot')->name('agin.provideListHistoryBestSlot');
    Route::get('best-history-hunter', 'APIProvide\AginProvideController@listHistoryBestHunter')->name('agin.provideListHistoryBestHunter');
  });

  Route::group(['prefix' => 'evolution'], function ($router) {
    Route::post('create-member', 'APIProvide\AvolProvideController@CreateMember')->name('evol.provideCreateMember');
    Route::post('login', 'APIProvide\AvolProvideController@login')->name('evol.provideLogin');
    Route::post('deposit', 'APIProvide\AvolProvideController@deposit')->name('evol.provideDeposit');
    Route::post('withdraw', 'APIProvide\AvolProvideController@withdraw')->name('evol.provideWithdraw');

    Route::post('change-password', 'APIProvide\AvolProvideController@postChangePass')->name('evol.provideChangePass');
    Route::get('best-history', 'APIProvide\AvolProvideController@listHistoryBest')->name('evol.provideListHistoryBest');
  });
  Route::group(['prefix' => 'wm555'], function ($router) {
    Route::post('create-member', 'APIProvide\VMProvideController@CreateMember')->name('wm555.provideCreateMember');
    Route::post('deposit', 'APIProvide\VMProvideController@deposit')->name('wm555.provideDeposit');
    Route::post('withdraw', 'APIProvide\VMProvideController@withdraw')->name('wm555.provideWithdraw');

    Route::post('change-password', 'APIProvide\VMProvideController@postChangePass')->name('wm555.provideChangePass');
    Route::get('best-history', 'APIProvide\VMProvideController@listHistoryBest')->name('wm555.provideListHistoryBest');
  });

  Route::group(['prefix' => 'ae-sexy'], function ($router) {
    Route::post('create-member', 'APIProvide\AWCProvideController@CreateMember')->name('aesexy.provideCreateMember');
    Route::post('login', 'APIProvide\AWCProvideController@login')->name('aesexy.provideLogin');
    Route::post('deposit', 'APIProvide\AWCProvideController@deposit')->name('aesexy.provideDeposit');
    Route::post('withdraw', 'APIProvide\AWCProvideController@withdraw')->name('aesexy.provideWithdraw');

    Route::post('change-password', 'APIProvide\AWCProvideController@postChangePass')->name('aesexy.provideChangePass');
    Route::get('best-history', 'APIProvide\AWCProvideController@listHistoryBest')->name('aesexy.listHistoryBest');
  });

});

