@extends('System.Layouts.Master')
@section('title', 'Admin-Wallet')
@section('css')

<link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css" rel="stylesheet" />

<!-- DataTables -->
<link href="assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="assets/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="assets/plugins/datatables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="assets/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css" />

<!--THIS PAGE LEVEL CSS-->
<link href="datetime/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"
      rel="stylesheet" />
<link href="datetime/plugins/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css" rel="stylesheet" />
<link href="datetime/plugins/boootstrap-datepicker/bootstrap-datepicker3.min.css" rel="stylesheet" />
<link href="datetime/plugins/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" />
<link href="datetime/plugins/bootstrap-daterange/daterangepicker.css" rel="stylesheet" />
<link href="datetime/plugins/clockface/css/clockface.css" rel="stylesheet" />
<link href="datetime/plugins/clockpicker/clockpicker.css" rel="stylesheet" />
<!--REQUIRED THEME CSS -->
<link href="datetime/assets/css/style.css" rel="stylesheet">
<link href="datetime/assets/css/themes/main_theme.css" rel="stylesheet" />

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<style>
  .dtp-btn-cancel {
    background: #9E9E9E;
  }
  #changeComment{
    display: none;
  }
  .dtp-btn-ok {
    background: #009688;
  }

  .dtp-btn-clear {
    color: black;
  }

  .btn-filler {
    margin-bottom: 10px;
  }

  .pagination {
    float: right;
  }

</style>
@endsection
@section('content')
<div class="content">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="page-header-title">
          <h4 class="pull-left page-title">Wallet</h4>
          <ol class="breadcrumb pull-right">
            <li><a href="javascript:void(0);">DAPP</a></li>
            <li class="active" style="color:#fff">Wallet</li>
          </ol>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="container-fluid">
          <!-- /Title -->
          <div class="row">
            @if(Session('user')->User_Level == 1)
            <div class="col-md-4">
              <form method="POST" id="post-deposit" action="{{route('system.admin.postDepositAdmin')}}">
                @csrf
                <div class="panel panel-default card-view">
                  <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                      <div class="form-wrap">
                        <div class="form-body">
                          <div class="row">
                            <div class="form-group">
                              <label for="exampleInputEmail1"><i class="fa fa-users"></i>
                                User ID</label>
                              <input type="text" class="form-control" name="user"
                                     id="exampleInputEmail1" placeholder="Enter User ID">
                            </div>
                            <div class="form-group">
                              <label for="exampleInputEmail1"><i class="fa fa-money"></i>
                                Amount</label>
                              <input type="number" step="any" name="amount"
                                     class="form-control" placeholder="Enter amount USD">
                            </div>
                            <label><i class="fa fa-hand-o-down"></i> Currency</label>
                            <div class="form-group row">
                              <div class="col-sm-12">
                                <select class="form-control c-select" name="coin">
                                  <option value="5" selected="">USDT</option>
                                  <option value="2">ETH</option>
                                  <option value="4">DP-NFT</option>
                                  <option value="7">HBG</option>
                                  <option value="8">EBP</option>
                                  <option value="12">SOL</option>
                                  <option value="13">C98</option>
                                  <option value="14">ADA</option>
                                  <option value="15">TRX</option>
                                  <option value="16">BNB</option>
                                </select>
                              </div>
                            </div>
                            <label><i class="fa fa-hand-o-down"></i> Action</label>
                            <div class="form-group row">
                              <div class="col-sm-12">
                                <select class="form-control c-select" id="changeAction" name="action">
                                  <option value="1" selected="">Deposit</option>
                                  <option value="86">Reward Promotion </option>
                                  <option value="10">Bonus Deposit </option>
                                </select>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="exampleInputEmail1"><i class="fa fa-money"></i>
                                Transaction Hash (Optional)</label>
                              <input type="text" name="hash"
                                     class="form-control" placeholder="Enter Transaction Hash">
                            </div>
                            <div class="form-group" id="changeComment">
                              <label for="exampleInputEmail1"><i class="fa fa-money"></i>
                                Comment</label>
                              <input type="text" name="comment"
                                     class="form-control" placeholder="Enter Transaction Comment">
                            </div>
                            <div class="m-t-43">
                              <button type="submit" class="btn btn-lucky"
                                      id="btn-deposit"><i class="fa fa-paper-plane"
                                                          aria-hidden="true"></i>
                                Deposit</button>
                            </div>
                          </div>

                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            @endif
            <div class="col-md-8 mx-auto">
              <form method="GET" action="{{route('system.admin.getWallet')}}">
                @csrf
                <div class="panel panel-default card-view">
                  <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                      <div class="form-wrap">
                        <div class="form-body">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="control-label mb-10"
                                       for="exampleInputpwd_1"><i class="fa fa-user"
                                                                  aria-hidden="true"></i> Wallet ID</label>
                                <input type="text" name="id" class="form-control"
                                       placeholder="Enter Wallet ID"
                                       value="{{request()->input('id')}}">
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="control-label mb-10"
                                       for="exampleInputpwd_1"><i class="fa fa-user"
                                                                  aria-hidden="true"></i> User ID</label>
                                <input type="text" name="user_id" class="form-control"
                                       placeholder="Enter User ID"
                                       value="{{request()->input('user_id')}}">
                              </div>
                            </div>
                            <!--/span-->

                            <div class="col-sm-6">
                              <div class="form-group">
                                <label class="control-label mb-10 text-left"><i
                                                                                class="fa fa-calendar" aria-hidden="true"></i>
                                  From</label>
                                <input type='text' name="datefrom" id="datefrom"
                                       class="form-control"
                                       value="{{request()->input('datefrom')}}" />
                              </div>
                            </div>

                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="control-label mb-10"
                                       for="exampleInputpwd_1"><i class="fa fa-users"
                                                                  aria-hidden="true"></i> Status</label>
                                <select name="status" class="form-control">
                                  <option value="">--- Select ---</option>
                                  <option value="0"
                                          {{request()->input('status') == 0 && request()->input('status') != '' ? 'selected' : ''}}>
                                    Pending</option>
                                  <option value="2"
                                          {{request()->input('status') == 2 ? 'selected' : ''}}>
                                    view</option>
                                  <option value="1"
                                          {{request()->input('status') == 1 ? 'selected' : ''}}>
                                    Confirmed</option>
                                  <option value="-1"
                                          {{request()->input('status') == -1 ? 'selected' : ''}}>
                                    Canceled</option>
                                </select>
                              </div>
                            </div>

                            <div class="col-sm-6">
                              <div class="form-group">
                                <label class="control-label mb-10 text-left"><i
                                                                                class="fa fa-calendar" aria-hidden="true"></i>
                                  To</label>
                                <input type='text' name="dateto" id="dateto"
                                       class="form-control"
                                       value="{{request()->input('dateto')}}" />
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="control-label mb-10"
                                       for="exampleInputpwd_1"><i class="fa fa-user"
                                                                  aria-hidden="true"></i> Tree</label>
                                <input type="text" name="tree" class="form-control"
                                       placeholder="Enter User ID Or Tree"
                                       value="{{request()->input('tree')}}">
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="control-label mb-10"
                                       for="exampleInputpwd_1"><i class="fa fa-user"
                                                                  aria-hidden="true"></i> Comment</label>
                                <input type="text" name="comment" class="form-control"
                                       placeholder="Enter Comment"
                                       value="{{request()->input('comment')}}">
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="control-label mb-10"
                                       for="exampleInputuname_1"><i
                                                                    class="fa fa-chevron-down"
                                                                    aria-hidden="true"></i>
                                  Action</label>
                                <div class="form-group">
                                  <select name="action[]"
                                          class="form-control select2-multi"
                                          multiple="multiple">

                                    <option value="">--- Select ---</option>
                                    @foreach($action as $a)
                                    <option value="{{$a->MoneyAction_ID}}"
                                            {{(request()->input('action')) && array_search($a->MoneyAction_ID, request()->input('action'))  !== false ? 'selected' : ''}}>
                                      {{$a->MoneyAction_Name}}</option>
                                    @endforeach
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="control-label mb-10"
                                       for="exampleInputuname_1"><i
                                                                    class="fa fa-chevron-down"
                                                                    aria-hidden="true"></i>
                                  Level</label>
                                <div class="form-group">
                                  <select type="number" class="form-control" name="User_Level">
                                    <option value=""> --- Select --- </option>
                                    @foreach($level as $k=>$l)
                                    <option value="{{$k}}" {{request()->input('User_Level') && request()->input('User_Level') == $k ? 'selected' : ''}}>{{$l}}</option>
                                    @endforeach
                                  </select>
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-6">
                              <div class="form-group">
                                <div class="form-actions mt-10">
                                  <button type="submit"
                                          class="btn-filler btn btn-lg1 btn-primary mr-10"><i
                                                                                              class="fa fa-search" aria-hidden="true"></i>
                                    Search
                                  </button>
                                  <button type="submit" name="export" value="1"
                                          class="btn-filler btn btn-lg1 btn-success mr-10"><i
                                                                                              class="fa fa-file-excel-o"
                                                                                              aria-hidden="true"></i> Export</button>
                                  <a href="{{ route('system.admin.getWallet') }}"
                                     class="btn-filler btn btn-default mr-10">Cancel</a>
                                </div>
                              </div>
                            </div>
                          </div>

                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>

          </div>
          <!-- Row -->
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default card-view">
                <div class="panel-heading">
                  <div>
                    <h3 class="panel-title txt-light"><i class="fa fa-table" aria-hidden="true"></i>
                      List Wallet Table</h3>
                  </div>
                </div>
                <div class="panel-wrapper collapse in">
                  <div class="panel-body">
                    <div class="table-wrap">
                      <div class="table-responsive">
                        {{$walletList->appends(request()->input())->links('System.Layouts.Pagination')}}
                        <div style="clear:both"></div>
                        <table id="dttable-wallet"
                               class="table table-striped table-bordered table-responsive"
                               cellspacing="0" width="100%">
                          <thead>
                            <tr>
                              <th data-toggle="true">
                                ID
                              </th>
                              <th data-hide="phone">
                                LEVEL
                              </th>
                              <th data-hide="phone">
                                USER ID
                              </th>
                              <th data-hide="phone">
                                AMOUNT
                              </th>
                              <th data-hide="phone">
                                AMOUNT COIN
                              </th>
                              <th data-hide="phone">
                                FEE
                              </th>
                              <th data-hide="phone">
                                RATE
                              </th>
                              <th data-hide="phone">
                                CURRENCY
                              </th>
                              <th data-hide="phone">
                                ACTION
                              </th>
                              <th data-hide="phone">
                                COMMENT
                              </th>
                              <th data-hide="phone">
                                TIME
                              </th>
                              <th data-hide="phone">
                                STATUS
                              </th>
                              <th data-hide="phone">
                                ACTION
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($walletList as $item)
                            <tr>
                              <td class="bg-{{$level[$item->User_Level]}}">{{$item->Money_ID}}</td>
                              <td>{{$level[$item->User_Level]}}</td>
                              <td>{{$item->Money_User}}</td>
                              <td>{{number_format($item->Money_USDT,2)}}
                              </td>
                              <td>{{$item->Money_CurrentAmount}}
                              </td>
                              <!--<td>{{number_format($item->Money_USDT*$item->Money_Rate, 2)}}</td>-->
                              <td>{{number_format($item->Money_USDTFee, 2)}}</td>
                              <td>{{$item->Money_CurrencyTo==4?number_format($item->Money_Rate, 8):number_format($item->Money_Rate, 3)}}</td>
                              <td>{{$item->Money_MoneyAction == 1 ? $arr_coin[$item->Money_CurrencyFrom] : ($item->Money_MoneyAction == 2 ? $arr_coin[$item->Money_CurrencyTo]: $arr_coin[$item->Money_Currency])}}</td>

                              <td>{{$item->MoneyAction_Name}}
                                @if($item->MoneyAction_ID== 2 && $item->Money_TXID)
                                ({{ $item->Money_TXID }})

                                @endif
                              </td>
                              <td>{{$item->Money_Comment}}</td>
                              <td>{{date('Y-m-d H:i:s',$item->Money_Time)}}</td>
                              <td>
                                @if($item->Money_MoneyStatus == 1)
                                @if($item->Money_MoneyAction == 2 &&
                                $item->Money_Confirm == 0)
                                <span class="badge badge-warning">Pending</span>

                                @else
                                <span class="badge badge-success">Confirmed</span>
                                @endif
                                @elseif($item->Money_MoneyStatus == 2)
                                <span class="badge badge-warning">View</span>
                                @else

                                <span class="badge badge-danger">Canceled</span>
                                @endif
                              </td>
                              <td>
                                <a class="btn btn-rounded btn-primary btn-xs"
                                   href="{{ route('system.admin.getWalletDetail', $item->Money_ID) }}">Detail</a>
                              </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                        {{$walletList->appends(request()->input())->links('System.Layouts.Pagination')}}
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

<!-- THIS PAGE LEVEL JS -->
<script src="datetime/plugins/momentjs/moment.js"></script>
<script src="datetime/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js">
</script>
<script src="datetime/plugins/boootstrap-datepicker/bootstrap-datepicker.min.js">
</script>
<script src="datetime/plugins/bootstrap-datetime-picker/js/bootstrap-datetimepicker.js">
</script>
<script src="datetime/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js">
</script>
<script src="datetime/plugins/bootstrap-daterange/daterangepicker.js"></script>
<script src="datetime/plugins/clockface/js/clockface.js"></script>
<script src="datetime/plugins/clockpicker/clockpicker.js"></script>

<script src="datetime/assets/js/pages/forms/date-time-picker-custom.js"></script>
<script>
  $('#datefrom').bootstrapMaterialDatePicker({ format : 'YYYY/MM/DD HH:mm:00', clearButton: true });

  $('#dateto').bootstrapMaterialDatePicker({ format : 'YYYY/MM/DD HH:mm:59', clearButton: true });
</script>
<script>
  var e=$("#demo-foo-col-exp");
  $("#demo-input-search2").on("input",function(o){o.preventDefault(),e.trigger("footable_filter",{filter:$(this).val()})})
</script>

<!-- Datatables-->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables/buttons.bootstrap.min.js"></script>
<script src="assets/plugins/datatables/jszip.min.js"></script>
<script src="assets/plugins/datatables/pdfmake.min.js"></script>
<script src="assets/plugins/datatables/vfs_fonts.js"></script>
<script src="assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="assets/plugins/datatables/buttons.print.min.js"></script>
<script src="assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>
<script src="assets/plugins/datatables/dataTables.keyTable.min.js"></script>
<script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>
<script src="assets/plugins/datatables/dataTables.scroller.min.js"></script>

<!-- Datatable init js -->
<script src="assets/pages/datatables.init.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
<script>
  var today = new Date();
  var currentDate = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
  $('#revenue-product').DataTable({
    dom: 'Bfrtip',
    "order": [[ 7, "desc" ]],
    buttons: [
      {
        extend: 'excelHtml5',
        title: "Wallet-"+currentDate
      }
    ]
  });
  $('#dttable-wallet').DataTable({
    "bLengthChange": false,
    "searching": false,
    "paging": false,
    "order": [0,'desc']
  });
  $('#post-deposit').submit(function() {
    $(this).find("button[type='submit']").prop('disabled',true);
  });
  $(".select2-multi").select2({
    tags: true,
    tokenSeparators: [',', ' ']
  })
  $(document).ready(function(){
    _val = 1;
    $('#changeAction').change(function(){
      _val = $(this).val();
      if(_val == 1){
        $('#changeComment').css('display','none');
      }else{
        $('#changeComment').css('display','block');
      }
    });
  });

</script>

@endsection
