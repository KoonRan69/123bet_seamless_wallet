$(document).ready(function() {
	myShowcard();
	showStatistic();
	$.get( url_webhost+"/system/json/get-balance-sportsbook", function( data ) {
		if(data.status == 'OK'){
			$('.sportsbook').html(formatMoney(data.balance));
			
		}
			
	});
	$.get( url_webhost+"/system/json/get-balance-lottery", function( data ) {
		if(data.status == 'OK'){
			$('.lottery').html(formatMoney(data.balance));
		}
			
	});

	function myShowcard(){
		$.get( url_webhost+"/system/ajax/get-balance", function( data ) {
			console.log(data);
			_status =  data.status;
			_USDT = data.USDT.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
			_BMG = data.BalanceToken.toFixed(8).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
			// _COM = data.COM.toFixed(4).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
			_Income = data.Income.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
			_Point = data.Point.toFixed(0).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
			// _Matrix= data.Matrix.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
			if(_status == 'OK'){
				$(".balanceUSD").html(
					_USDT
				); 
				
				$("#balanceBMG").html(
					_BMG
				);
			
				// $("#totalProfit").html(
				// 	_COM
				// );
				$("#balanceIncome").html(
					_Income
				);
				$("#balancePoint").html(
					_Point
				);
				// $(".livecasino").html(
				// 	_LiveCasino
				// );
				// $('#matrix').html(_Matrix);
					//end
			}else {
				$('.loader').html(
					'<span class="loader-time"></span>'
				);
			}
			
		});
	}
	
	function showStatistic(){
		$.get( url_webhost+"/system/json/statistical", function( data ) {
			_status =  data.status;
			// console.log(data);
			if(_status == 'OK'){
				_bonus = data.Bonus_Deposit;
				if(_bonus == false ){
					$("#user-bonus-deposit").css('color','red');
					_bonus = 'false';
				}else{
					_bonus = '';
				}
				$("#lending-level").html(
					data.Agency_Level 
				);
				$("#user-level").html(
					data.User_Master
				);
				$("#user-total-deposit").html(
					data.User_TotalDeposit + " USDT"
				);
				
				$("#user-bonus-deposit").html(
					_bonus				
				);
				$("#user-refund-game").html(
					data.Total_Refund + " USDT"
				);
				$("#user-master-commission").html(
					data.Total_Master + " USDT"
				);
				
				$("#user-total-withdraw").html(
					data.User_TotalWithdraw + " USDT"
				);
				$("#user-numberF1").html(
					data.NumberF['1']
				);
				$("#user-numberF2").html(
					data.NumberF['2']
				);
				$("#user-numberF3").html(
					data.NumberF['3']
				);
				$("#user-totalF1").html(
					data.VolumeF['1'].toFixed(2)
				);
				$("#user-totalF2").html(
					data.VolumeF['2'].toFixed(2)
				);
				$("#user-totalF3").html(
					data.VolumeF['3'].toFixed(2)
				);
				$("#user-total-amount-attack").html(
					data.total_amount_attack + " USDT"
				);
				$("#user-total-win").html(
					data.total_win + " USDT"
				);
				$("#user-total-lose").html(
					data.total_lose + " USDT"
				);
				$("#user-total-invest").html(
					data.total_invest + " USDT"
				);
				if(data.active == 1){
					$('#user-active').html(
						'<span class="">Yes</span>'
					);
				}else{
					$('#user-active').html(
						'<span class="text-red">No</span>'
					);
				}
				
				
				$("#system-total-attack").html(
					data.system_total_amount_attack + " USDT"
				);
				$("#system-total-win").html(
					data.system_total_win + " USDT"
				);
				$("#system-total-invest").html(
					data.total_nember_invest + " USDT"
				);
				$("#system-total-lose").html(
					data.system_total_lose + " USDT"
				);
				$("#system-profit").html(
					data.system_profit + " USDT"
				);
				
			}else {
				$('.loader').html(
					'<span class="loader-time"></span>'
				);
			}
		});
	}
	function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
		try {
			decimalCount = Math.abs(decimalCount);
			decimalCount = isNaN(decimalCount) ? 2 : decimalCount;
			
			const negativeSign = amount < 0 ? "-" : "";
			
			let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
			let j = (i.length > 3) ? i.length % 3 : 0;
			
			return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
		} catch (e) {
			console.log(e)
		}
	};
	
});