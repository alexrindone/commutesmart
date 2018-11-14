<!DOCTYPE html>
<html>
    <head>
        <title>User Logged First Trip</title>
    </head>
    <body>
        <p>Name: {{$user->name}}</p>
        <p>Address: {{$user->street}} {{$user->city}}, {{$user->state}} {{$user->zip}}</p>
        <p>Email: {{$user->email}}</p>
    </body>
</html>