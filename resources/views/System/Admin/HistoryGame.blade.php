@extends('System.Layouts.Master')
@section('title', 'History game')
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
                    <h4 class="pull-left page-title">Fishs</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">DAPP</a></li>
                        <li class="active" style="color:#fff">Fishs</li>
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
                        <div class="col-md-12">
                            <form method="GET" action="">
                                <div class="panel panel-default card-view">
                                    <div class="panel-wrapper collapse in">
                                        <div class="panel-body">
                                            <div class="form-wrap">
                                                <div class="form-body">
                                                    <div class="row">

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Subaccount user</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Enter Subaccount user" name="subaccount_user"
                                                                    value="{{request()->input('subaccount_user')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Bets</label>
                                                                <select id="inputState" class="form-control"
                                                                    name="bet">
                                                                    <option selected value=""
                                                                        {{request()->input('bet') == '' ? 'selected' : ''}}>
                                                                        --- Select ---</option>
                                                                    <option value="4000"
                                                                        {{request()->input('bet') == '4000' ? 'selected' : ''}}>
                                                                        Sedie</option>
                                                                    <option value="3500"
                                                                        {{request()->input('bet') == '3500' ? 'selected' : ''}}>
                                                                        Snail Racing</option>
                                                                    <option value="BO"
                                                                        {{request()->input('bet') == 'BO' ? 'selected' : ''}}>
                                                                        BO</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Match ID</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Match ID" name="match_id"
                                                                    value="{{request()->input('match_id')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Action</label>
                                                                <select id="inputState" class="form-control"
                                                                    name="action">
                                                                    <option selected value=""
                                                                        {{request()->input('action') == '' ? 'selected' : ''}}>
                                                                        --- Select ---</option>
                                                                    <option value="Bet"
                                                                        {{request()->input('action') == 'Bet' ? 'selected' : ''}}>
                                                                        Bet</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Currency</label>
                                                                <select id="inputState" class="form-control"
                                                                    name="currency">
                                                                    <option selected value=""
                                                                        {{request()->input('currency') == '' ? 'selected' : ''}}>
                                                                        --- Select ---</option>
                                                                    <option value="9"
                                                                        {{request()->input('currency') == '9' ? 'selected' : ''}}>
                                                                        Gold</option>
                                                                    <option value="3"
                                                                        {{request()->input('currency') == '3' ? 'selected' : ''}}>
                                                                        EUSD</option>
                                                                </select>
                                                            </div>
                                                        </div>
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
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <div class="form-actions mt-10">
                                                                    <button type="submit"
                                                                        class="btn-filler btn btn-primary  mr-10"><i
                                                                            class="fa fa-search" aria-hidden="true"></i>
                                                                        Search</button>
                                                                    @if(Session('user')->User_Level != 3)
                                                                    <button type="submit"
                                                                        name="export" value="1"
                                                                        class="btn-filler btn btn-success  mr-10"><i
                                                                            class="fa fa-file-excel-o"
                                                                            aria-hidden="true"></i> Export</button>
                                                                    @endif
                                                                    <a href="{{ route('system.admin.getHistoryGame') }}"
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
                                    <div class="">
                                        <h6 class="panel-title txt-light"><i class="fa fa-table" aria-hidden="true"></i>
                                            History game</h6>
                                    </div>
                                </div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="table-wrap">
                                            <div class="table-responsive">
                                                <div style="clear:both"></div>
                                                <table
                                                    class="dt-responsive demo-foo-col-exp table table-striped table-bordered table-responsive"
                                                    cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                ID</th>

                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                Amount bet</th>

                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                Amount Win</th>
                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                Status</th>
                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                SubAccount user</th>

                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                Game</th>

                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                Bet</th>

                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                Match ID</th>
                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                Bet</th>
                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                Currency</th>
                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                Datetime</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($gameBet as $game)
                                                        <tr>
                                                            <td>{{ $game->_id }}</td>
                                                            <td>{{ $game->GameBet_AmountBet ?? $game->GameBet_Amount }}</td>
                                                            <td>{{ $game->GameBet_AmountWin }}</td>
                                                            <td>{{ $game->GameBet_Status == 0? 'Waiting': ($game->GameBet_Status == 1? 'Win': 'Lose') }}</td>
                                                            <td>{{ $game->GameBet_SubAccountUser }}</td>
                                                            <td>{{ $game->GameBet_Game == '3500'? 'Snail racing': ($game->GameBet_Game == '4000'? 'Sedie': 'BO') }}</td>
                                                            <td>{{ $game->GameBet_Bets ? json_encode($game->GameBet_Bets) : $game->GameBet_Type }}</td>
                                                            <td>{{ $game->GameBet_MatchID }}</td>
                                                            <td>{{ $game->GameBet_Log }}</td>
                                                            <td>{{ $game->GameBet_Currency == 9? 'Gold': 'EUSD'}}</td>
                                                            <td>{{ date('d-m-Y H:i:s', $game->GameBet_datetime) }}</td>
                                                        </tr>
                                                        @endforeach

                                                    </tbody>
                                                     {{$gameBet->appends(request()->input())->links('System.Layouts.Pagination')}}
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