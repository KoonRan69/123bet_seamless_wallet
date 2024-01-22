@extends('System.Layouts.Master')
@section('title', 'Admin-Fiat')
@section('css')
<link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css" rel="stylesheet"/>

<!-- DataTables -->
<link href="assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/plugins/datatables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css"/>

@endsection
@section('content')
<div class="content">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="page-header-title">
          <h4 class="pull-left page-title">DETAIL FIAT</h4>
          <ol class="breadcrumb pull-right">
            <li><a href="javascript:void(0);">DAPP</a></li>
            <li class="active" style="color:#fff">DETAIL FIAT</li>
          </ol>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default card-view">
                <div class="panel-heading">
                  <div>
                    <h3 class="panel-title txt-light"><i class="fa fa-table"
                                                         aria-hidden="true"></i>
                      Detail Fiat</h3>
                  </div>
                </div>
                <div class="panel-wrapper collapse in">
                  <div class="panel-body">
                    <div class="card">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-4">
                            <div class="form-group">
                              <label class="control-label mb-10 text-left"><i
                                                                              class="fa fa-user" aria-hidden="true"></i>
                                ID</label>
                              <input type="text" class="form-control" readonly=""
                                     value="{{ $detail->Money_1VPN_ID }}">
                            </div>
                            <div class="form-group">
                              <label class="control-label mb-10 text-left"><i
                                                                              class="fa fa-users" aria-hidden="true"></i>
                                User ID</label>
                              <input type="text" class="form-control" readonly=""
                                     value="{{ $detail->Money_1VPN_User }}">
                            </div>
                            <div class="form-group">
                              <label class="control-label mb-10 text-left"><i
                                                                              class="fa fa-envelope"
                                                                              aria-hidden="true"></i>
                                Amount</label>
                              <input type="text" class="form-control" readonly=""
                                     value="{{ number_format($detail->Money_1VPN_Amount,4) }}">
                            </div>
                            <div class="form-group">
                              <label class="control-label mb-10 text-left"><i
                                                                              class="fa fa-envelope"
                                                                              aria-hidden="true"></i>
                                Rate VNĐ-USDT</label>
                              <input type="text" class="form-control" readonly=""
                                     value="{{number_format($detail->Money_1VPN_Rate_VNDUSDT,4)}}">
                            </div>
                            <div class="form-group">
                              <label class="control-label mb-10 text-left"><i
                                                                              class="fa fa-envelope"
                                                                              aria-hidden="true"></i>
                                Rate USDT-VNĐ</label>
                              <input type="text" class="form-control" readonly=""
                                     value="{{number_format($detail->Money_1VPN_Rate_USDTVND,4)}}">
                            </div>
                            <div class="form-group">
                              <label class="control-label mb-10 text-left"><i
                                                                              class="mdi mdi-comment"
                                                                              aria-hidden="true"></i>
                                Hash</label>
                              <input type="text" class="form-control" readonly=""
                                     value="{{ $detail->Money_1VPN_Hash }}">
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label class="control-label mb-10 text-left"><i
                                                                              class="mdi mdi-timer"
                                                                              aria-hidden="true"></i>
                                Time</label>
                              <input type="text" class="form-control" readonly=""
                                     value="{{ date('Y/m/d H:i:s', $detail->Money_1VPN_Time) }}">
                            </div>
                            <div class="form-group">
                              <label class="control-label mb-10 text-left"><i
                                                                              class="icon-diamond" aria-hidden="true"></i>
                                Currency</label>
                              <input type="text" class="form-control" readonly=""
                                     value="{{ $detail->Currency_Name }}">
                            </div>
                            <div class="form-group">
                              <label class="control-label mb-10 text-left"><i
                                                                              class="icon-diamond" aria-hidden="true"></i>
                                Wallet ID</label>
                              <input type="text" class="form-control" readonly=""
                                     value="{{ $detail->Currency_Name }}">
                            </div>
                            <div class="form-group">
                              <label class="control-label mb-10"><i
                                                                    class="mdi mdi-emoticon-excited-outline"
                                                                    aria-hidden="true"></i>
                                Status</label>
                              <input type="text" class="form-control" readonly=""
                                     value="{{ $detail->Money_1VPN_Status == 1 ? "Confirm" : $detail->Money_MoneyStatus == -1 ? "Canceled" : "Pending"}}">
                            </div>
                            <div class="form-group">
                              <label class="control-label mb-10 text-left"><i
                                                                              class="mdi mdi-comment"
                                                                              aria-hidden="true"></i>
                                Action</label>
                              <input type="text" class="form-control" readonly=""
                                     value="{{ $detail->MoneyAction_Name }}">
                            </div>
                            <div class="form-group">
                              <label class="control-label mb-10 text-left"><i
                                                                              class="mdi mdi-comment"
                                                                              aria-hidden="true"></i>
                                Comment</label>
                              <input type="text" class="form-control" readonly=""
                                     value="{{ $detail->Money_Comment }}">
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label class="control-label mb-10 text-left"><i
                                                                              class="mdi mdi-comment"
                                                                              aria-hidden="true"></i>
                                Channel</label>
                              <input type="text" class="form-control" readonly=""
                                     value="{{ $detail->money_channel_name }}">
                            </div>
                            <div class="form-group">
                              <label class="control-label mb-10 text-left"><i
                                                                              class="mdi mdi-comment"
                                                                              aria-hidden="true"></i>
                                Bank</label>
                              <input type="text" class="form-control" readonly=""
                                     value="{{ $detail->bank_name }}">
                            </div>
                            <div class="form-group">
                              <label class="control-label mb-10 text-left"><i
                                                                              class="mdi mdi-comment"
                                                                              aria-hidden="true"></i>
                                Bank number</label>
                              <input type="text" class="form-control" readonly=""
                                     value="{{ $detail->Money_1VPN_Bank_Number }}">
                            </div>
                            <div class="form-group">
                              <label class="control-label mb-10 text-left"><i
                                                                              class="mdi mdi-comment"
                                                                              aria-hidden="true"></i>
                                Beneficiary name</label>
                              <input type="text" class="form-control" readonly=""
                                     value="{{ $detail->Money_1VPN_Beneficiary_Name }}">
                            </div>
                            <div class="seprator-block"></div>
                            <form method="GET" action="" id="confirm-wallet">
                              <input type="hidden" name="id" id="input-id"
                                     value="">
                              <input type="hidden" name="status" id="input-status"
                                     value="">
                              @if((Session('user')->User_Level == 1 || Session('user')->User_Level == 2) && $detail->Money_1VPN_Status == 0)
                              <div class="form-actions mt-10">
                                <label class="control-label mb-10 text-left"><i
                                                                                class="mdi mdi-pencil-outline"
                                                                                aria-hidden="true"></i>
                                  Transaction Hash</label>
                                <input type="text" name="txid" value=""
                                       class="form-control"
                                       placeholder="Please enter transaction hash to success">
                                <br>
                                <button type="button" name="confirm"
                                        class="btn btn-danger mr-10 btn-success"
                                        data-confirm="-1" data-idorder="{{ $detail->Money_1VPN_ID }}">
                                  <i class="fa fa-flus"
                                     aria-hidden="true"></i> Cancel
                                </button>
                                <button type="button" name="confirm"
                                        class="btn btn-warning mr-10 btn-success"
                                        data-confirm="2" data-idorder="{{ $detail->Money_1VPN_ID }}">
                                  <i class="fa fa-check-square-o"
                                     aria-hidden="true"></i> Success
                                </button>
                              </div>
                              @elseif(Session('user')->User_Level == 1)
                              <button type="button" name="confirm"
                                      class="btn btn-danger mr-10 btn-success"
                                      data-confirm="-1" data-idorder="{{ $detail->Money_1VPN_ID }}">
                                <i class="fa fa-flus" aria-hidden="true"></i>
                                Cancel
                              </button>
                              @endif
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
  $('.btn-success').click(function () {
    _confirm = $(this).data('confirm');
    _idOrder = $(this).data('idorder');
    console.log(_confirm)
    console.log(_idOrder)
    if (_confirm == 2) {
      $('#input-id').val(_idOrder);
      $('#input-status').val(2);
      $('#confirm-wallet').submit();
    } else {
      $('#input-id').val(_idOrder);
      $('#input-status').val(-1);
      $('#confirm-wallet').submit();
    }
  });
</script>
@endsection
