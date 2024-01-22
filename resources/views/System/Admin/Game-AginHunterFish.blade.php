@extends('System.Layouts.Master')
@section('title', 'Admin-Game-123BetNow')
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
          <h4 class="pull-left page-title">123BetNow Game</h4>
          <ol class="breadcrumb pull-right">
            <li><a href="javascript:void(0);">123BetNow</a></li>
            <li class="active" style="color:#fff">Game</li>
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
            @if (Session('user')->User_Level == 1)
            <div class="col-md-4">
              <form method="post" action="{{route('system.admin.postImportGameAginHunterFish')}}"
                    enctype="multipart/form-data">
                @csrf
                <div class="panel panel-default card-view">
                  <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                      <div class="form-wrap">
                        <div class="form-body">
                          <div class="row">
                            <div class="form-group">
                              <label for="">Import Total Week Agin Hunter Fish</label>
                              <input type="file" name="ImportTotalWeekAgin"
                                     class="form-control" placeholder="Enter image">
                            </div>
                            <div class="form-group form-check">
                              <input class="form-check-input" type="radio" name="week" value="day" id="day-this" checked>
                              <label class="form-check-label" for="day-this">
                                Day
                              </label>
                            </div>
                            <div class="form-group form-check">
                              <input class="form-check-input" type="radio" name="week" value="this" id="week-this">
                              <label class="form-check-label" for="week-this">
                                This Week
                              </label>
                            </div>
                            <div class="form-group form-check">
                              <input class="form-check-input" type="radio" name="week" value="last" id="week-last">
                              <label class="form-check-label" for="week-last">
                                Last Week
                              </label>
                            </div>
                            <div class="m-t-43">
                              <button type="submit" class="btn btn-lucky"><i
                                                                             class="fa fa-paper-plane"
                                                                             aria-hidden="true"></i>Import</button>
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
              <form method="GET" action="{{route('system.admin.getAginHunterFish')}}">
                @csrf
                <input type="hidden" name="total" value="{{request()->input('total')}}">
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
                                                                  aria-hidden="true"></i> User ID</label>
                                <input type="text" name="user_id" class="form-control"
                                       placeholder="Enter User ID"
                                       value="{{ request()->input('user_id') }}">
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
                                       value="{{ request()->input('datefrom') }}" />
                              </div>
                            </div>

                            <div class="col-sm-6">
                              <div class="form-group">
                                <label class="control-label mb-10 text-left"><i
                                                                                class="fa fa-calendar" aria-hidden="true"></i>
                                  To</label>
                                <input type='text' name="dateto" id="dateto"
                                       class="form-control"
                                       value="{{ request()->input('dateto') }}" />
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="control-label mb-10"
                                       for="exampleInputpwd_1"><i class="fa fa-user"
                                                                  aria-hidden="true"></i> Tree</label>
                                <input type="text" name="tree" class="form-control"
                                       placeholder="Enter User ID Or Tree"
                                       value="{{ request()->input('tree') }}">
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
                                  <select type="number" class="form-control"
                                          name="User_Level">
                                    <option value=""> --- Select --- </option>
                                    @foreach ($level as $k => $l)
                                    <option value="{{ $k }}"
                                            {{ request()->input('User_Level') && request()->input('User_Level') == $k ? 'selected' : '' }}>
                                      {{ $l }}</option>
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
                                  <a href="{{ route('system.admin.getAginSportBook') }}"
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
                      List Agin Hunter Fish Table</h3>
                  </div>
                </div>
                <div class="panel-wrapper collapse in">
                  <div class="panel-body">
                    <div class="table-wrap">
                      <div class="table-responsive">
                        <div style="clear:both"></div>
                        {{ $gameWallet->appends(request()->input())->links() }}
                        <table id="dttable-wallet"
                               class="table table-striped table-bordered table-responsive"
                               cellspacing="0" width="100%">
                          <thead>
                            <tr>
                              @foreach($columnTable as $column)
                              <th>
                                {{$column}}
                              </th>
                              @endforeach
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($gameWallet as $v)
                            <tr>
                              <td>{{ $v->id}}</td>
                              <td>{{ $v->statistical}}</td>
                              <td>{{ $v->userid}}</td>
                              <td>{{ $v->username}}</td>
                              <td>{{ $v->productid}}</td>
                              <td>{{ $v->roomid}}</td>
                              <td>{{ $v->betx}}</td>
                              <td>{{ $v->sceneid}}</td>
                              <td>{{ date('Y-m-d H:i:s',$v->starttime)}}</td>
                              <td>{{ date('Y-m-d H:i:s',$v->endtime)}}</td>
                              <td>{{ date('Y-m-d H:i:s',$v->billtime)}}</td>
                              <td>{{ $v->gametype}}</td>
                              <td>{{ $v->currency}}</td>
                              <td>{{ $v->totalbulletcost}}</td>
                              <td>{{ $v->totalfishcost}}</td>
                              <td>{{ $v->profit}}</td>
                              <td>{{ $v->totaljpcontribute}}</td>
                              <td>{{ $v->totaljackpot}}</td>
                              <td>{{ $v->totalfirstprize}}</td>
                              <td>{{ $v->remark}}</td>
                              <td>{{ $v->devicetype}}</td>
                              <td>{{ $v->totalweaponHit}}</td>
                              <td>{{ $v->totalcollection}}</td>
                              <td>{{ $v->time_123betnow}}</td>
                              <td>{{ $v->create_date}}</td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                        {{ $gameWallet->appends(request()->input())->links() }}
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
    $('#datefrom').bootstrapMaterialDatePicker({
      format: 'YYYY/MM/DD',
      time: false,
      clearButton: true
    });

    $('#dateto').bootstrapMaterialDatePicker({
      format: 'YYYY/MM/DD',
      time: false,
      clearButton: true
    });

  </script>
  <script>
    var e = $("#demo-foo-col-exp");
    $("#demo-input-search2").on("input", function(o) {
      o.preventDefault(), e.trigger("footable_filter", {
        filter: $(this).val()
      })
    })

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
    var currentDate = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
    $('#revenue-product').DataTable({
      dom: 'Bfrtip',
      "order": [
        [7, "desc"]
      ],
      buttons: [{
        extend: 'excelHtml5',
        title: "Wallet-" + currentDate
      }]
    });
    $('#dttable-wallet').DataTable({
      "bLengthChange": false,
      "searching": false,
      "paging": false,
      "order": [0, 'desc']
    });
    $('#post-deposit').submit(function() {
      $(this).find("button[type='submit']").prop('disabled', true);
    });
    $(".select2-multi").select2({
      tags: true,
      tokenSeparators: [',', ' ']
    })

  </script>

  @endsection
