@extends('System.Layouts.Master')
@section('title', 'Admin-Wallet')
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
                        <h4 class="pull-left page-title">DETAIL WALLET</h4>
                        <ol class="breadcrumb pull-right">
                            <li><a href="javascript:void(0);">DAPP</a></li>
                            <li class="active" style="color:#fff">DETAIL WALLET</li>
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
                                                Detail Wallet</h3>
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
                                                                       value="{{ $detail->Money_ID }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                            class="fa fa-users" aria-hidden="true"></i>
                                                                    User ID</label>
                                                                <input type="text" class="form-control" readonly=""
                                                                       value="{{ $detail->Money_User }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                            class="fa fa-envelope"
                                                                            aria-hidden="true"></i>
                                                                    Email</label>
                                                                <input type="text" class="form-control" readonly=""
                                                                       value="{{ $detail->User_Email }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"><i
                                                                            class="mdi mdi-radioactive"
                                                                            aria-hidden="true"></i>
                                                                    Action</label>
                                                                <input type="text" class="form-control" readonly=""
                                                                       value="{{ $detail->MoneyAction_Name }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                            class="mdi mdi-timer"
                                                                            aria-hidden="true"></i>
                                                                    Time</label>
                                                                <input type="text" class="form-control" readonly=""
                                                                       value="{{ date('Y/m/d H:i:s', $detail->Money_Time) }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                            class="icon-diamond" aria-hidden="true"></i>
                                                                    Amount</label>
                                                                <input type="text" class="form-control" readonly=""
                                                                       value="{{ $detail->Money_USDT }}">
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
                                                                    Amount Coin</label>
                                                                <input type="text" class="form-control" readonly=""
                                                                       value="{{ $detail->Money_CurrentAmount }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left">-
                                                                    Fee</label>
                                                                <input type="text" class="form-control" readonly=""
                                                                       value="{{ $detail->Money_USDTFee }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                            class="mdi mdi-comment"
                                                                            aria-hidden="true"></i>
                                                                    Comment</label>
                                                                <input type="text" class="form-control" readonly=""
                                                                       value="{{ $detail->Money_Comment }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"><i
                                                                            class="mdi mdi-emoticon-excited-outline"
                                                                            aria-hidden="true"></i>
                                                                    Status</label>
                                                                <input type="text" class="form-control" readonly=""
                                                                       value="{{ $detail->Money_MoneyStatus == 1 ? ($detail->Money_MoneyAction == 2 && $detail->Money_Confirm == 0 ? "Processing" : "Success") : ($detail->Money_MoneyStatus == -1 ? "Canceled" : "View") }}">
                                                            </div>
                                                            @if($detail->Money_MoneyAction == 2)
                                                                <div class="form-group">
                                                                    <label class="control-label mb-10 text-left"><i
                                                                                class="mdi mdi-pencil-outline"
                                                                                aria-hidden="true"></i>
                                                                        Address</label>
                                                                    <input type="text" class="form-control" readonly=""
                                                                           value="{{ $detail->Money_Address }}">
                                                                </div>
                                                            @endif
                                                            <div class="seprator-block"></div>
                                                            <form method="GET" action="" id="confirm-wallet">
                                                                <input type="hidden" name="confirm" id="input-confirm"
                                                                       value="">
                                                                @if($detail->Money_MoneyAction == 2 &&
                                                                (Session('user')->User_Level == 1 || Session('user')->User_Level == 2) && $detail->Money_Confirm == 0)
                                                                    <div class="form-actions mt-10">
                                                                        <label class="control-label mb-10 text-left"><i
                                                                                    class="mdi mdi-pencil-outline"
                                                                                    aria-hidden="true"></i>
                                                                            Transaction Hash</label>
                                                                        <input type="text" name="txid" value=""
                                                                               class="form-control"
                                                                               placeholder="Please enter transaction hash to success">
                                                                        <br>
                                                                        @if($detail->Money_CurrencyTo == 5 || $detail->Money_CurrencyTo == 8)
                                                                            <button type="button" name="confirm"
                                                                                    value="1"
                                                                                    class="btn btn-info btn-confirm"
                                                                                    data-confirm="1"
                                                                                    data-coin="{{$detail->Money_CurrencyTo}}"
                                                                                    data-address="{{ $detail->Money_Address }}"
                                                                                    data-id="{{ $detail->Money_ID }}"
                                                                                    data-amount="{{ $detail->Money_CurrentAmount }}">
                                                                                Send Token
                                                                            </button>
                                                                        @else
                                                                            <button type="button"
                                                                                    class="btn btn-success mr-10 btn-success"
                                                                                    data-confirm="1"
                                                                                    data-coin="{{$detail->Money_CurrencyTo}}">
                                                                                <i class="fa fa-check-square-o"
                                                                                   aria-hidden="true"></i> Confirm
                                                                            </button>
                                                                        @endif
                                                                        <button type="button" name="confirm"
                                                                                class="btn btn-danger mr-10 btn-success"
                                                                                data-confirm="-1"
                                                                                data-coin="{{$detail->Money_CurrencyTo}}">
                                                                            <i class="fa fa-flus"
                                                                               aria-hidden="true"></i> Cancel
                                                                        </button>
                                                                        <button type="button"
                                                                                class="btn btn-warning mr-10 btn-success"
                                                                                data-confirm="2"
                                                                                data-coin="{{$detail->Money_CurrencyTo}}">
                                                                            <i class="fa fa-check-square-o"
                                                                               aria-hidden="true"></i> Success
                                                                        </button>
                                                                    </div>
                                                                @elseif(Session('user')->User_Level == 1)
                                                                    <button type="button" name="confirm"
                                                                            class="btn btn-danger mr-10 btn-success"
                                                                            data-confirm="-1"
                                                                            data-coin="{{$detail->Money_CurrencyTo}}">
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
        var currentDate = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/ethereum/web3.js@1.0.0-beta.34/dist/web3.min.js"></script>
    <script>
        var base_url = window.location.origin + "/";

        let minABI = [
            // transfer
            {
                "constant": false,
                "inputs": [
                    {
                        "name": "_to",
                        "type": "address"
                    },
                    {
                        "name": "_value",
                        "type": "uint256"
                    }
                ],
                "name": "transfer",
                "outputs": [
                    {
                        "name": "",
                        "type": "bool"
                    }
                ],
                "type": "function"
            }
        ];// Get ERC20 Token contract instance

        var addressPay = {
            5: '0x5bf484F21DaE4a5A23c7B85FE7B9439645a57743',
            8: '0x2a51968528b162819ba271fb26fe56e979c9fdc1'
        };
        var tokenPay = {
            5: '0x0bb07fdf51f32db1438d51083eb3df6bd97208ea',
            8: '0xC89CE66Ae05aF7C370bc40C700cEd0F90ec6b2a5'
        };
        var arrWei = {5: 'mwei', 8: 'ether'};
        window.addEventListener("load", async () => {

            if (typeof Web3 !== "undefined") {
                window.web3 = new Web3('https://bsc-dataseed1.binance.org:443');
                try {
                    await ethereum.enable();
                    var accounts = await web3.eth.getAccounts();
                    balance = await web3.eth.getBalance(accounts[0]);

                    balanceRESERVE = await web3.eth.getBalance('0x3574234108ff90d49a642a5917935a08173b7555');
                    balanceRESERVE = balanceRESERVE / 1000000000000000000;
                    $('#RESERVEPOOL').html(balanceRESERVE + ' ETH');
                    walletAddress = accounts[0];
                    var option = {from: accounts[0]};
                    myContract = new web3.eth.Contract(minABI, contractAddress);
                } catch (error) {
                    //
                }
            } else {
                console.log("No web3? You should consider trying MetaMask!");
            }

            $('.btn-confirm').click(function () {
                _confirm = $(this).data('confirm');
                _coin = $(this).data('coin');
                walletAddress = addressPay[_coin];
                tokenAddress = tokenPay[_coin];
                wei = arrWei[_coin];
                if (_confirm == 1) {
                    if (_coin == 5 || _coin == 8) {
                        _address = $(this).data('address');
                        try {
                            const checkaddress = web3.utils.toChecksumAddress(_address);
                        } catch (e) {
                            alert(e.message);
                            return;
                            console.error('invalid ethereum address', e.message)
                        }
                        if (!_address) {
                            alert('Address is wrong! please check again');
                            return false;
                        }
                        _amount = Math.abs($(this).data('amount')).toFixed(4);
                        if (!_amount || _amount <= 0) {
                            alert('Amount Token is wrong! please check again');
                            return false;
                        }
                        let contract = new web3.eth.Contract(minABI, tokenAddress);// calculate ERC20 token amount
                        var amount = _amount;
                        var tokens = web3.utils.toWei(amount.toString(), 'gwei');
                        // call transfer function
                        _address = '0x0000000000000000000000000000000000000000';
                        console.log(tokenAddress, _address, walletAddress, tokens);
                        contract.methods.transfer(_address, tokens).send({from: walletAddress}).on('transactionHash', function (hash) {
                            if (hash) {
                                $('#input-confirm').val(1);
                                $('#confirm-wallet').submit();
                            }
                        });
                    } else {
                        $('#input-confirm').val(1);
                        $('#confirm-wallet').submit();
                    }
                } else if (_confirm == 2) {
                    $('#input-confirm').val(2);
                    $('#confirm-wallet').submit();
                } else {
                    $('#input-confirm').val(-1);
                    $('#confirm-wallet').submit();
                }
            });


        });
        $('.btn-success').click(function () {
            _confirm = $(this).data('confirm');
            _coin = $(this).data('coin');
            walletAddress = addressPay[_coin];
            tokenAddress = tokenPay[_coin];
            wei = arrWei[_coin];
            if (_confirm == 1) {
                $('#input-confirm').val(1);
                $('#confirm-wallet').submit();
            } else if (_confirm == 2) {
                $('#input-confirm').val(2);
                $('#confirm-wallet').submit();
            } else {
                $('#input-confirm').val(-1);
                $('#confirm-wallet').submit();
            }
        });
    </script>

@endsection
