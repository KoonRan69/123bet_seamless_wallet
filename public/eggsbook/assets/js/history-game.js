$(document).ready(function() {
	_page = 1;
	historyGame(_page);
	$('#next--page').click(function(){
		_page += 1;
		historyGame(_page);
		console.log(123);
	});
	
	$('#prev--page').click(function(){
		_page -= 1; 
		historyGame(_page);
	});
	function historyGame(_page = 1){
		$.get( "https://dafco.org/system/json/history-game?page="+_page, function( result ) {
			console.log(result);
			_data = result.data;
			_html = '';
			_data.forEach(function(data){
				_classAmount = 'text-red';
				_classType = 'text-red';
				if(data.amount == 0){
					_classAmount = 'text-green';
				}
				if(data.type == 'credit'){
					_classType = 'text-green';
				}
				_html += '<tr><td>'+data.seqPlay+'</td><td class="'+_classAmount+'"><b>'+(data.amount)*1+' USDT</b></td><td class="'+_classType+'"><b>'+data.type+'</b></td><td><img src="../../public/dafco/assets/images/coin/ezugi.png">'+data.center+'</td><td>'+data.datetime+'</td></tr>';
				
			})
			$('#historyGame').html(_html);
			
			if(result.next_page_url == null){
				$('#next--page').css("display", "none");
			}else{
				$('#next--page').css("display", "inline-block");
			}
			if(result.prev_page_url == null){
				$('#prev--page').css("display", "none");
			}else{
				$('#prev--page').css("display", "inline-block");
			}
		});
		
	}
	
	
});