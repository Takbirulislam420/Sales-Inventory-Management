<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Password reset</title>
</head>
<body>
    <h1>Hello user {{ $user->name }} </h1>
    <p>Token:{{$token}}
    <p>you have requested for password reset </p>
    <p>Please click the below link for reset your password</p>
    <a href="{{url('password/Password_reset?token='.$token)}}">Reseet password </a>

    <p> if you not request just ignore this mail</p>
    <p> Remamber , the token is valid for 60 minutes</p>
    <h2> Thank you </h2>

    
</body>
</html>