@extends('System.Layouts.Master')
@section('title', 'Admin-Fiat')
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
          <h4 class="pull-left page-title">Fiat</h4>
          <ol class="breadcrumb pull-right">
            <li><a href="javascript:void(0);">DAPP</a></li>
            <li class="active" style="color:#fff">Fiat</li>
          </ol>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="container-fluid">
          <div class="col-md-8 mx-auto">
            <form method="GET" action="{{route('system.admin.getListFiat')}}">
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
                                                                aria-hidden="true"></i> ID</label>
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
                                  <option value="1">Order Deposit</option>
                                  <option value="2">Order Withdraw</option>
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
                                <a href="{{ route('system.admin.getListFiat') }}"
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
                    List Fiat Table</h3>
                </div>
              </div>
              <div class="panel-wrapper collapse in">
                <div class="panel-body">
                  <div class="table-wrap">
                    <div class="table-responsive">
                      {{$list->appends(request()->input())->links('System.Layouts.Pagination')}}
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
                              User
                            </th>
                            <th data-hide="phone">
                              Amount
                            </th>
                            <th data-hide="phone">
                              Rate VNĐ-USDT
                            </th>
                            <th data-hide="phone">
                              Rate USDT-VNĐ
                            </th>
                            <th data-hide="phone">
                              Hash
                            </th>
                            <th data-hide="phone">
                              Action
                            </th>
                            <th data-hide="phone">
                              Comment
                            </th>
                            <th data-hide="phone">
                              Channel
                            </th>
                            <th data-hide="phone">
                              Time
                            </th>
                            <th data-hide="phone">
                              Currency
                            </th>
                            <th data-hide="phone">
                              Wallet ID
                            </th>
                            <th data-hide="phone">
                              Status
                            </th>
                            <th data-hide="phone">
                              Info Bank
                            </th>
                            <th data-hide="phone">
                              Detail
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($list as $item)
                          <tr>
                            <td>{{$item->Money_1VPN_ID}}</td>
                            <td>{{$item->Money_1VPN_User}}</td>
                            <td>{{$item->Money_1VPN_Amount}}</td>
                            <td>{{number_format($item->Money_1VPN_Rate_VNDUSDT,4)}}</td>
                            <td>{{number_format($item->Money_1VPN_Rate_USDTVND,4)}}</td>
                            <td>{{$item->Money_1VPN_Hash}}</td>
                            <td>{{$item->MoneyAction_Name}}</td>
                            <td>{{$item->Money_Comment}}</td>
                            <td>{{$item->money_channel_name}}</td>
                            <td>{{date("Y-m-d H:i:s",$item->Money_1VPN_Time)}}</td>
                            <td>{{$item->Currency_Name}}</td>
                            <td>{{$item->Money_1VPN_MoneyID}}</td>
                            <td>
                              @if($item->Money_1VPN_Status == 0)
                              <span class="badge badge-warning">Pending</span>
                              @endif
                              @if($item->Money_1VPN_Status == 1)
                              <span class="badge badge-success">Confirmed</span>
                              @endif
                              @if($item->Money_1VPN_Status == -1)
                              <span class="badge badge-danger">Canceled</span>
                              @endif
                            </td>
                            <td>
                              Bank: {{$item->bank_name}}
                              <br>
                              Bank number: {{$item->Money_1VPN_Bank_Number}}
                              <br>
                              Beneficiary name: {{$item->Money_1VPN_Beneficiary_Name}}
                            </td>
                            <td>
                              <a class="btn btn-rounded btn-primary btn-xs"
                                 href="{{route('system.admin.getDetailFiat',['id'=>$item->Money_1VPN_ID])}}">Detail</a>
                            </td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                      {{$list->appends(request()->input())->links('System.Layouts.Pagination')}}
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
