@extends('System.Layouts.Master')
@section('title', 'Voucher')
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
<style>
  a:hover {
    cursor: pointer;
  }
</style>

<!--THIS PAGE LEVEL CSS-->
<link
      href="datetime/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"
      rel="stylesheet" />
<link href="datetime/plugins/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css"
      rel="stylesheet" />
<link href="datetime/plugins/boootstrap-datepicker/bootstrap-datepicker3.min.css"
      rel="stylesheet" />
<link href="datetime/plugins/bootstrap-timepicker/css/bootstrap-timepicker.css"
      rel="stylesheet" />
<link href="datetime/plugins/bootstrap-daterange/daterangepicker.css"
      rel="stylesheet" />
<link href="datetime/plugins/clockface/css/clockface.css" rel="stylesheet" />
<link href="datetime/plugins/clockpicker/clockpicker.css" rel="stylesheet" />
<!--REQUIRED THEME CSS -->
<link href="datetime/assets/css/style.css" rel="stylesheet">
<link href="datetime/assets/css/themes/main_theme.css" rel="stylesheet" />
<style>
  .dtp-btn-cancel {
    background: #9E9E9E;
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
          <h4 class="pull-left page-title">Voucher</h4>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="container-fluid">
          <!-- Search -->
          <div class="row">
            <div class="col-md-12">
              <form method="GET" action="">
                @csrf
                <div class="panel panel-default card-view">
                  <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                      <div class="form-wrap">
                        <div class="form-body">
                          <div class="row">
                            <div class="form-group col-span-2 lg:col-span-1 flex flex-col ">
                              <label for="">User ID</label>
                              <input class="form-control" type="text" placeholder="User ID" value="{{request()->input('UserID')}}" name="UserID">
                            </div>

                            <div class="form-group col-span-2 lg:col-span-1 flex flex-col ">
                              <label for="">Level</label>
                              <select  name="level" class="form-control" id="">
                                <option selected value="" class="text-black"
                                        {{request()->input('type') == '' ? 'selected' : ''}}>
                                  --- Select ---</option>
                                <option value="1" class="text-black"
                                        {{request()->input('level') == 1 ? 'selected' : ''}}>
                                  Admin</option>
                                <option value="6" class="text-black"
                                        {{request()->input('level') == 2 ? 'selected' : ''}}>
                                  Finance</option>
                                <option value="1" class="text-black"
                                        {{request()->input('level') == 5 ? 'selected' : ''}}>
                                  Bot</option>
                                <option value="2" class="text-black"
                                        {{request()->input('level') == 4 ? 'selected' : ''}}>
                                  Customer</option>
                                <option value="3" class="text-black"
                                        {{request()->input('level') == 3 ? 'selected' : ''}}>
                                  Support</option>
                                <option value="member" class="text-black"
                                        {{request()->input('level') == 'member' ? 'selected' : ''}}>
                                  Member</option>
                              </select>
                            </div>
                            <div class="form-group col-span-2 lg:col-span-1 flex flex-col ">
                              <label for="">Type</label>
                              <select  name="type" class="form-control" id="">
                                <option selected value="" class="text-black"
                                        {{request()->input('type') == '' ? 'selected' : ''}}>
                                  --- Select ---</option>
                                <option value="mission" class="text-black"
                                        {{request()->input('type') == 'mission' ? 'selected' : ''}}>
                                  Mission</option>
                                <option value="buy" class="text-black"
                                        {{request()->input('type') == 'buy' ? 'selected' : ''}}>
                                  Buy</option>
                                <option value="spin" class="text-black"
                                        {{request()->input('type') == 'spin' ? 'selected' : ''}}>
                                  Spin</option>

                              </select>
                            </div>
                            <div class="form-group col-span-2 lg:col-span-1 flex flex-col ">
                              <label for="">DataTime</label>
                              <input type="date" id="date-picker-3" class="form-control" name="datetime" value="{{request()->input('datetime')}}"
                                     placeholder="Select Date From">
                            </div>
                            <div class="form-group col-span-2 lg:col-span-1 flex flex-col ">
                              <label for="">Status</label>
                              <select  name="status" class="form-control" id="">
                                <option selected value="" class="text-black"
                                        {{request()->input('status') == '' ? 'selected' : ''}}>
                                  --- Select ---</option>
                                <option value="1" class="text-black"
                                        {{request()->input('status') == 1 ? 'selected' : ''}}>
                                  Used</option>
                                <option value="-1" class="text-black"
                                        {{request()->input('status') == -1 ? 'selected' : ''}}>
                                  No Use</option>

                              </select>
                            </div>
                            <div class="form-group col-span-2 lg:col-span-1 flex flex-col ">
                              <div class="">
                                <button class="btn button btn-success" type="submit">Search</button>
                                <button class="btn button btn-info" type="submit" name="export" value="1">Export</button>
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

          <!-- !Search-->
          <!-- Row -->
          <div class="row">
            <div class="col-md-12">

              <div class="panel panel-default card-view">
                <div class="panel-heading">
                  <div>
                    <h3 class="panel-title txt-light"><i class="fa fa-table" aria-hidden="true"></i>
                      List SportBook Table</h3>
                  </div>
                </div>
                <div class="panel-wrapper collapse in">
                  <div class="panel-body">
                    <div class="table-wrap">
                      <div class="table-responsive">
                        <div style="clear:both"></div>
                        {{$list->appends(request()->input())->links()}}
                        <table id="member-list"
                               class=" dt-responsive table table-striped table-bordered table-responsive"
                               cellspacing="0" width="100%">
                          <thead>
                            <tr>
                              <th data-toggle="true">
                                #
                              </th>
                              <th>
                                User ID
                              </th>
                              <th>
                                Level
                              </th>
                              <th data-hide="phone,tablet">
                                Type
                              </th>
                              <th data-hide="phone">
                                Status
                              </th>
                              <th data-hide="phone">
                                DateTime
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($list as $v)
                            <tr>
                              <td>{{$v->id}}</td>
                              <td>{{$v->User_ID}}</td>
                              <td><span class="badge badge-success">{{$level[$v->User_Level]}}</span></td>
                              <td>{{$v->type}}</td>
                              <td>
                                <span class="badge badge-{{$v->status == 1 ? 'success' : ($v->status == 0 ? 'warning' : 'danger') }}">
                                  {{$v->status == 1 ? 'Used' : ($v->status == 0 ? 'No use' : 'Cancel') }}
                                </span>
                              </td>
                              <td>{{$v->datetime}}</td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                        {{$list->appends(request()->input())->links()}}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--Modal show profile -->

        </div>
      </div>

    </div>
  </div>
</div>

@endsection
@section('script')
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
<script>
  var today = new Date();
  var currentTime = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();

  $('#dt-statistical').DataTable({
    "bLengthChange": false,
    "searching": false,
    "paging": false
  });
</script>

<script src="assets/jquery-table2excel/dist/jquery.table2excel.min.js"></script>

<script>
  $(function() {
    $('#exportTest').click(function(){
      $(".demo-foo-col-exp").table2excel({
        exclude: ".noExl",
        name: "Fishs",
        filename: "Fishs" + new Date().toISOString().replace(/[\-\:\.]/g, "")+".xls",
        fileext: ".xls",
        exclude_img: true,
        exclude_links: true,
        exclude_inputs: true
      });

    });
  });
</script>

<!-- THIS PAGE LEVEL JS -->
<script src="datetime/plugins/momentjs/moment.js"></script>
<script
        src="datetime/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js">
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
  $('#datefrom').bootstrapMaterialDatePicker({ format : 'YYYY/MM/DD', time: false, clearButton: true });

  $('#dateto').bootstrapMaterialDatePicker({ format : 'YYYY/MM/DD', time: false, clearButton: true });
</script>
@endsection