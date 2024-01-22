<div class="left side-menu">
  <div class="sidebar-inner slimscrollleft">
    <div class="user-details">
      <div class="image_lg text-center">
        <img src="https://system.123betnow.net/img/logo.5a2851eb.png" alt="" width="90%">
      </div>
      <div class="img_user">
        <img src="img/user-2.png" alt="" width="90%">
      </div>
      <div class="user-info">
        <a href="javascript:void(0);">#
          {{ Session('user')->User_ID}} </a>
      </div>
    </div>
    <!--- Divider -->
    <div id="sidebar-menu">
      <ul>
        @if(session('user')->User_Level == 3)
        <li>
          <a href="{{route('system.admin.getMemberListAdmin')}}"><i class="fa fa-user"
                                                                    aria-hidden="true"></i> <span
                                                                                                  class="menu__text">Member</span></a>
        </li>
        <li>
          <a href="{{route('system.admin.getWallet')}}"><i class="fa fa-credit-card-alt"
                                                           aria-hidden="true"></i> <span
                                                                                         class="menu__text">Wallet</span></a>
        </li>
        <li>
          <a href="{{route('admin.getNoti')}}">
            <div class="pull-left pull-left-nav">
              <i class="fa fa-bar-chart" aria-hidden="true"></i><span class="right-nav-text"> Up Notification</span>
            </div>
            <div class="clearfix"></div>
          </a>
        </li>

        <li>
          <a href="{{route('system.admin.getProfile')}}"><i class="fa fa-picture-o"
                                                            aria-hidden="true"></i> <span
                                                                                          class="menu__text">KYC</span></a>
        </li>
        <li>
          <a href="{{route('getTicketAdmin')}}"><i class="fa fa-picture-o" aria-hidden="true"></i> <span
                                                                                                         class="menu__text">Ticket</span></a>
        </li>
        <li>
          <a href="{{route('system.admin.getLicense')}}"><i class="fa fa-picture-o" aria-hidden="true"></i> <span
                                                                                                                  class="menu__text">License</span></a>
        </li>
        @endif
        @if(session('user')->User_Level == 1 && session('user')->User_Active_BotTelegram == 1)
        <li>
          <a href="{{route('system.bot.getBotTelegram')}}"><i class="fa fa-picture-o" aria-hidden="true"></i> <span
                                                                                                                    class="menu__text">Set Bot Telegram</span></a>
        </li>
        @endif
        @if((session('user')->User_Level == 1 || session('user')->User_Level == 2 ||  session('user')->User_Level == 10) && session('user')->User_Active_BotTelegram != 1)
        <li>
          <a href="{{route('system.admin.getPromotionCode')}}">
            <div class="pull-left pull-left-nav">
              <i class="fa fa-credit-card-alt" aria-hidden="true"></i><span class="right-nav-text"> Promotion Code</span></div>
            <div class="clearfix"></div>
          </a>
        </li>
        <li>
          <a href="{{route('system.admin.getBlogEvent')}}">
            <div class="pull-left pull-left-nav">
              <i class="fa fa-bar-chart" aria-hidden="true"></i><span
                                                                      class="right-nav-text"> Blogs</span></div>
            <div class="clearfix"></div>
          </a>
        </li>
        <li>
          <a href="{{route('system.admin.getAdminCooperation')}}">
            <div class="pull-left pull-left-nav">
              <i class="fa fa-bar-chart" aria-hidden="true"></i><span
                                                                      class="right-nav-text"> Cooperration Contact</span></div>
            <div class="clearfix"></div>
          </a>
        </li>
        <li>
          <a href="{{route('admin.getNoti')}}">
            <div class="pull-left pull-left-nav">
              <i class="fa fa-bar-chart" aria-hidden="true"></i><span class="right-nav-text"> Up Notification</span>
            </div>
            <div class="clearfix"></div>
          </a>
        </li>
        <li>
          <a href="{{route('admin.getIndex')}}"><i class="fa fa-user" aria-hidden="true"></i> <span
                                                                                                    class="menu__text">Up Document</span></a>
        </li>
        <li>
          <a href="{{route('admin.getNotiPosts')}}"><i class="fa fa-user" aria-hidden="true"></i> <span
                                                                                                        class="menu__text">Notification New</span></a>
        </li>
        <li>
          <a href="{{route('system.admin.getAdminInsurance')}}"><i class="fa fa-user"
                                                                   aria-hidden="true"></i> <span
                                                                                                 class="menu__text">Insurance</span></a>
        </li>
        <li>
          <a href="{{route('system.admin.getAdminSettingInsurance')}}"><i class="fa fa-user"
                                                                          aria-hidden="true"></i> <span
                                                                                                        class="menu__text"> Setting Insurance</span></a>
        </li>
        <li>
          <a href="{{route('system.admin.getMemberListAdmin')}}"><i class="fa fa-user"
                                                                    aria-hidden="true"></i> <span
                                                                                                  class="menu__text">Member</span></a>
        </li>
        <li>
          <a href="{{route('system.admin.getWallet')}}"><i class="fa fa-credit-card-alt"
                                                           aria-hidden="true"></i> <span
                                                                                         class="menu__text">Wallet</span></a>
        </li>
        <li>
          <a href="{{route('system.admin.getWalletGame')}}"><i class="fa fa-credit-card-alt"
                                                               aria-hidden="true"></i> <span
                                                                                             class="menu__text">Wallet Game</span></a>
        </li>
        <li>
          <a href="{{route('system.admin.getListFiat')}}"><i class="fa fa-credit-card-alt"
                                                             aria-hidden="true"></i><span
                                                                                          class="menu__text">Fiat</span></a>
        </li>
        <li>
          <a href="{{route('system.admin.getStatistical')}}"><i class="fa fa-bar-chart"
                                                                aria-hidden="true"></i> <span
                                                                                              class="menu__text">Statistic</span></a>
        </li>
        <li>
          <a href="{{route('system.admin.getHistorySbobets')}}"><i class="fa fa-bar-chart"
                                                                   aria-hidden="true"></i> <span
                                                                                                 class="menu__text">History Sbobet (Volume Week)</span></a>
        </li>
        <li>
          <a href="{{route('system.admin.getHistoryEvolutions')}}"><i class="fa fa-bar-chart"
                                                                      aria-hidden="true"></i> <span
                                                                                                    class="menu__text">History Evolution</span></a>
        </li>
        <li>
          <a href="{{route('system.admin.getGameWalletSA', ['total'=>1])}}"><i class="fa fa-bar-chart"
                                                                               aria-hidden="true"></i>
            <span class="menu__text">History Casino SA</span></a>
        </li>
        <li>
          <a href="{{route('system.admin.getGameWalletWM', ['total'=>1])}}"><i class="fa fa-bar-chart"
                                                                               aria-hidden="true"></i>
            <span class="menu__text">History Casino WM555</span></a>
        </li>
        <li>
          <a href="{{route('system.admin.getGameWalletWM', ['day'=>1])}}"><i class="fa fa-bar-chart"
                                                                             aria-hidden="true"></i> <span
                                                                                                           class="menu__text">Casino WM555 Day</span></a>
        </li>
        <li>
          <a href="{{route('system.admin.getSportBook')}}"><i class="fa fa-bar-chart"
                                                              aria-hidden="true"></i> <span
                                                                                            class="menu__text">History SportBook</span></a>
        </li>
        <li>
          <a href="{{route('system.admin.getSportBook', ['day'=>1])}}"><i class="fa fa-bar-chart"
                                                                          aria-hidden="true"></i> <span
                                                                                                        class="menu__text">SportBook Day</span></a>
        </li>
        {{--
        <li>
          <a href="{{route('system.admin.getAginSportBook')}}"><i class="fa fa-bar-chart"
                                                                  aria-hidden="true"></i> <span
                                                                                                class="menu__text">History Agin SportBook</span></a>
        </li>
        <li>
          <a href="{{route('system.admin.getAginSlot')}}"><i class="fa fa-bar-chart"
                                                             aria-hidden="true"></i> <span
                                                                                           class="menu__text">History Agin Slot</span></a>
        </li>
        <li>
          <a href="{{route('system.admin.getAginHunterFish')}}"><i class="fa fa-bar-chart"
                                                                   aria-hidden="true"></i> <span
                                                                                                 class="menu__text">History Agin Hunter Fish</span></a>
        </li>
        <li>
          <a href="{{route('system.admin.getGameEVO')}}"><i class="fa fa-bar-chart"
                                                            aria-hidden="true"></i> <span
                                                                                          class="menu__text">History Casino Evolution</span></a>
        </li>

        <li>
          <a href="{{route('system.admin.getAeSexy')}}"><i class="fa fa-bar-chart"
                                                           aria-hidden="true"></i> <span
                                                                                         class="menu__text">History AWC AE Sexy</span></a>
        </li>
        <li>
          <a href="{{route('system.admin.getSbobet')}}"><i class="fa fa-bar-chart"
                                                           aria-hidden="true"></i> <span
                                                                                         class="menu__text">History Sbobet</span></a>
        </li>

        <li>
          <a href="{{route('system.admin.getSbobetCasino')}}"><i class="fa fa-bar-chart"
                                                                 aria-hidden="true"></i> <span
                                                                                               class="menu__text">History Sbobet Casino</span></a>
        </li>

        <li>
          <a href="{{route('system.admin.getSbobetVirtualSport')}}"><i class="fa fa-bar-chart"
                                                                       aria-hidden="true"></i> <span
                                                                                                     class="menu__text">History Sbobet Virtual Sport</span></a>
        </li>

        <li>
          <a href="{{route('system.admin.getSbobetSeamless')}}"><i class="fa fa-bar-chart"
                                                                   aria-hidden="true"></i> <span
                                                                                                 class="menu__text">History Sbobet Seamless</span></a>
        </li>

        <li>
          <a href="{{route('system.admin.getSbobetThirdPartySportsBook')}}"><i class="fa fa-bar-chart"
                                                                               aria-hidden="true"></i> <span
                                                                                                             class="menu__text">History Sbobet ThirdPartySportsBook</span></a>
        </li>

        <li>
          <a href="{{route('system.admin.getBalancePlayer')}}"><i class="fa fa-bar-chart"
                                                                  aria-hidden="true"></i> <span
                                                                                                class="menu__text">Player Balance Sbobet </span></a>
        </li>
        --}}

        <li>
          <a href="{{route('system.admin.getProfile')}}"><i class="fa fa-picture-o"
                                                            aria-hidden="true"></i> <span
                                                                                          class="menu__text">KYC</span></a>
        </li>
        <li>
          <a href="{{route('getTicketAdmin')}}"><i class="fa fa-picture-o" aria-hidden="true"></i> <span
                                                                                                         class="menu__text">Ticket</span></a>
        </li>
        <li>
          <a href="{{route('system.admin.getLicense')}}"><i class="fa fa-picture-o" aria-hidden="true"></i> <span
                                                                                                                  class="menu__text">License</span></a>
        </li>
        <li>
          <a href="{{route('admin.getVoucher')}}"><i class="fa fa-picture-o" aria-hidden="true"></i><span
                                                                                                          class="menu__text">Voucher</span></a>
        </li>
        <li>
          <a href="{{route('admin.getVoucherList')}}"><i class="fa fa-picture-o" aria-hidden="true"></i><span
                                                                                                              class="menu__text">Voucher List</span></a>
        </li>
        <li>
          <a href="{{route('system.admin.getBanner')}}"><i class="fa fa-picture-o" aria-hidden="true"></i><span
                                                                                                                class="menu__text">List and Add Banner</span></a>
        </li>

        <li>
          <a href="{{route('system.admin.getListGame')}}"><i class="fa fa-picture-o" aria-hidden="true"></i><span
                                                                                                                  class="menu__text">List and Edit Icon Game</span></a>
        </li>

        @endif

        <!-- <li>
<a class="" href="{{ route('getOpenBox')}}" >
<div class="pull-left pull-left-nav">
<i class="fa fa-archive" aria-hidden="true" style=""></i>
<span class="right-nav-text">Lucky Box</span></div>
<div class="clearfix"></div>
</a>
</li>                          -->
        <!-- <li class="has_sub">
<a class="" href="javascript:void(0);">
<div class="pull-left pull-left-nav"><i class="fa fa-credit-card-alt" aria-hidden="true"></i><span class="right-nav-text">My wallet</span></div>
<div class="pull-right"><i class="ti-angle-down"></i></div>
<div class="clearfix"></div>
</a>
<ul class="list-unstyled">
<li><a href="{{ route('system.getDeposit')}}">Deposit</a></li>
<li><a href="{{ route('system.getWithdraw')}}">Withdraw</a></li>
<li><a href="{{ route('system.getTransfer')}}">Transfer</a></li>
{{--<li><a href="{{ route('system.getSwap')}}">Swap Coin</a></li>--}}

</ul>

</li>

<li>
<a class="" href="{{route('system.getInvestment')}}" >
<div class="pull-left pull-left-nav"><i class="fa fa-university" aria-hidden="true"></i><span class="right-nav-text">Join Package</span></div>
<div class="clearfix"></div>
</a>
</li>

<li class="has_sub">
<a class="" href="javascript:void(0);" >
<div class="pull-left pull-left-nav"><i class="fa fa-users" aria-hidden="true"></i><span class="right-nav-text">Member</span></div>
<div class="pull-right"><i class="ti-angle-down"></i></div>
<div class="clearfix"></div>
</a>
<ul class="list-unstyled">
<li><a href="{{ route('system.user.getList')}}">Member List</a></li>
<li><a href="{{ route('system.user.getTree')}}">Member Tree</a></li>
</ul>
</li> -->

        <!-- <li class="has_sub">
<a href="javascript:void(0);" >
<div class="pull-left pull-left-nav"><i class="fa fa-history" aria-hidden="true"></i><span class="right-nav-text">History</span></div>
<div class="pull-right"><i class="ti-angle-down"></i></div>
<div class="clearfix"></div>
</a>
<ul class="list-unstyled">
<li>
<a href="{{route('system.history.getHistoryWallett')}}"> Wallet Histories</a>
</li> -->
        <!-- <li>
<a href="{{route('system.history.getHistoryGame')}}"> Game Histories</a>
</li> -->
        <!-- <li>
<a href="{{route('system.history.getHistoryCommisson')}}"> Commission Histories</a>
</li>
<li>
<a href="{{route('system.history.getHistoryInvestment')}}"> Investment Histories</a>
</li> -->
        <!-- <li>
<a href="{{ route('system.history.getInterest')}}">  Interest Histories</a>
</li> -->
        <!-- </ul>
</li>

<li>
<a class="" href="{{route('Ticket')}}" >
<div class="pull-left pull-left-nav"><img src="dist/img/ic-nav/tag.png" width="25"
style="margin-right: 10px;"><span class="right-nav-text">Ticket</span></div>
<div class="clearfix"></div>
</a>
</li> -->

        <!--@if(session('user')->User_Level == 1 || session('user')->User_Level == 2 || session('user')->User_Level == 3 || session('user')->User_Level == 10)

<li>
<a href="{{route('system.admin.getMemberListAdmin')}}"><i class="fa fa-user" aria-hidden="true"></i> <span class="menu__text">Member</span></a>
</li>
<li>
<a href="{{route('system.admin.getWallet')}}"><i class="fa fa-credit-card-alt" aria-hidden="true"></i> <span class="menu__text">Wallet</span></a>
</li>
<li>
<a href="{{route('system.admin.getPercent')}}"><i class="fa fa-credit-card-alt" aria-hidden="true"></i> <span class="menu__text">Percent Profit</span></a>
</li>
<li>
<a href="{{route('system.admin.getLot')}}"><i class="fa fa-credit-card-alt" aria-hidden="true"></i> <span class="menu__text">Fake LOT</span></a>
</li>
<li>
<a href="{{route('system.admin.InvestmentList')}}"><i class="fa fa-usd" aria-hidden="true"></i> <span class="menu__text">Investment</span></a>
</li>
{{-- <li>
<a href="{{route('system.admin.getStatistical')}}"><i class="fa fa-bar-chart" aria-hidden="true"></i> <span class="menu__text">Statistic</span></a>
</li> --}}
<li>
<a href="{{route('system.admin.getProfile')}}"><i class="fa fa-picture-o" aria-hidden="true"></i> <span class="menu__text">KYC</span></a>
</li>
@if(session('user')->User_Level != 10)
<li>
<a href="{{route('getTicketAdmin')}}"><i class="fa fa-ticket" aria-hidden="true"></i> <span class="menu__text">Ticket</span></a>
</li>
<li>
<a href="{{route('system.admin.getLogMail')}}"><i class="fa fa-compass" aria-hidden="true"></i> <span class="menu__text">Log</span></a>
</li>
@endif
@endif -->
      </ul>
    </div>
    <div class="clearfix"></div>
  </div> <!-- end sidebarinner -->
</div>
