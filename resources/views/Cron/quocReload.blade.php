<html>
    <head>
        <meta http-equiv="refresh" content="{{$timeout}};url=https://apiv2.123betnow.net/static-balance?page={{$page+1}}" />
    </head>
    <body>
        <h1>Redirecting in <span id="count">{{$timeout}}</span> seconds...</h1>
      	<script>
          	var countdown = {{$timeout}};
      		var myVar = setInterval(function(){
              countdown--;
              console.log(countdown);
              document.getElementById("count").innerHTML = countdown;
            }, 1000);
      	</script>
    </body>
</html>