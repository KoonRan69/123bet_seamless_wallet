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

        <li>
          <a href="{{route('provide.getUser')}}">
            <div class="pull-left pull-left-nav">
              <i class="fa fa-bar-chart" aria-hidden="true"></i><span
                                                                      class="right-nav-text"> Users</span></div>
            <div class="clearfix"></div>
          </a>
        </li>
        <li>
          <a href="{{route('provide.getWallet')}}">
            <div class="pull-left pull-left-nav">
              <i class="fa fa-bar-chart" aria-hidden="true"></i><span
                                                                      class="right-nav-text">Wallet</span></div>
            <div class="clearfix"></div>
          </a>
        </li>
        
       
         <li>
          <a href="{{route('provide.getHistoryAginSport')}}">
            <div class="pull-left pull-left-nav">
              <i class="fa fa-bar-chart" aria-hidden="true"></i><span
                                                                      class="right-nav-text">Agin Sport Book</span></div>
            <div class="clearfix"></div>
          </a>
        </li>
        
        
        
         <li>
          <a href="{{route('provide.getHistoryAginFish')}}">
            <div class="pull-left pull-left-nav">
              <i class="fa fa-bar-chart" aria-hidden="true"></i><span
                                                                      class="right-nav-text">Agin Fish Shooter</span></div>
            <div class="clearfix"></div>
          </a>
        </li>
        
        
        
         <li>
          <a href="{{route('provide.getHistoryAginSlot')}}">
            <div class="pull-left pull-left-nav">
              <i class="fa fa-bar-chart" aria-hidden="true"></i><span
                                                                      class="right-nav-text">Agin Slot</span></div>
            <div class="clearfix"></div>
          </a>
        </li>
        
         <li>
          <a href="{{route('provide.getHistoryWM')}}">
            <div class="pull-left pull-left-nav">
              <i class="fa fa-bar-chart" aria-hidden="true"></i><span
                                                                      class="right-nav-text">Casino WM555</span></div>
            <div class="clearfix"></div>
          </a>
        </li>
         <li>
          <a href="{{route('provide.getHistoryEvol')}}">
            <div class="pull-left pull-left-nav">
              <i class="fa fa-bar-chart" aria-hidden="true"></i><span
                                                                      class="right-nav-text">Casino Evolution</span></div>
            <div class="clearfix"></div>
          </a>
        </li>
         <li>
          <a href="{{route('provide.getHistoryAeSexy')}}">
            <div class="pull-left pull-left-nav">
              <i class="fa fa-bar-chart" aria-hidden="true"></i><span
                                                                      class="right-nav-text">AWC Ae Sexy</span></div>
            <div class="clearfix"></div>
          </a>
        </li>




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
