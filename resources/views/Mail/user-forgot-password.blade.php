<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot password</title>
</head>
<body>
    <h2>Please comfirm to active forgot password</h2>
    <form role="form" action="{{route('userForgotPassword')}}" method="GET">
        <input type="hidden" name="user_token" value="{{$data['token']}}">
        <input type="hidden" name="user_password" value="{{bcrypt($data['pass'])}}">
        <p>email: {{$data['User_Email']}}</p>
        <p>password: {{$data['pass']}}</p>
        <p>token: {{$data['token']}}</p>
        <button type="submit" class="btn btn-primary">Confirm</button>
</body>
</html>