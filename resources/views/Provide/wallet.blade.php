@extends('Provide.Layouts.Master')
@section('title', 'Admin-Game-123BetNow')

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


            <div class="col-md-12 mx-auto">
              <form method="GET" action="">
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
                                       for="exampleInputuname_1"><i
                                                                    class="fa fa-chevron-down"
                                                                    aria-hidden="true"></i>
                                  Action</label>
                                <div class="form-group">
                                  <select type="select" class="form-control"
                                          name="action">
                                    <option value=""> --- Select --- </option>
                                    @foreach($action as $value)
                                    <option  value="{{$value->Money_MoneyAction}}" {{request()->input('action') == $value->Money_MoneyAction ? 'selected' : ''}}>{{$value->MoneyAction_Name}}</option>
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
                                  <a href="{{ route('provide.getWallet') }}"
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


                        <table id="dttable-wallet"
                               class="table table-striped table-bordered table-responsive"
                               cellspacing="0" width="100%">
                          <thead>
                            <tr>
                              <th data-toggle="true">
                                ID
                              </th>
                              <th data-hide="phone">
                                USER ID
                              </th>
                              <th data-hide="phone">
                                AMOUNT
                              </th>

                              <th data-hide="phone">
                                FEE
                              </th>
                              <th data-hide="phone">
                                RATE
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

                            </tr>
                          </thead>
                          <tbody>
                            @foreach($walletList as $value)
                            <tr>
                              <td><p>{{ $value->Money_ID }}</p></td>
                              <td>{{$value->Money_User}}</td>  
                              <td>{{$value->Money_CurrentAmount}}</td>
                              <td>{{$value->Money_USDTFee }}</td>
                              <td>{{$value->Money_Rate }}</td>
                              <td>{{$value->MoneyAction_Name }}</td>
                              <td>{{$value->Money_Comment }}</td>
                              <td>{{date("Y-m-d H:i:s", $value->Money_Time) }}</td>

                            </tr>
                            @endforeach
                          </tbody>
                        </table>

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

<!-- THIS PAGE LEVEL JS -->
<script src="datetime/plugins/momentjs/moment.js"></script>
<script
        src="datetime/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js">
</script>
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
  $('#datetime').bootstrapMaterialDatePicker({ format : 'YYYY/MM/DD', clearButton: true, time: false });
</script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/modernizr.min.js"></script>
<script src="assets/js/detect.js"></script>
<script src="assets/js/fastclick.js"></script>
<script src="assets/js/jquery.slimscroll.js"></script>

<script src="assets/js/jquery.blockUI.js"></script>
<script src="assets/js/waves.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/jquery.nicescroll.js"></script>
<script src="assets/js/jquery.scrollTo.min.js"></script>

<script src="assets/js/app.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.js"></script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

@endsection
