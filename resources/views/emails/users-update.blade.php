<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Update</title>
</head>
<body>
<table>
    <thead>
        <tr>
            <th>Before</th>
            <th>After</th>
        </tr>
    </thead>
    <tbody>
        @foreach($before as $value)
        <tr>
            <th>{{$value}}</th>
        </tr>
        @endforeach
        @foreach($after as $value)
            <tr>
                <th>{{$value}}</th>
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
