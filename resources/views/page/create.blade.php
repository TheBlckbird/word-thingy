<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create new Word Cloud</title>
</head>
<body>
    <form action="/page" method="post">
        @csrf

        <label>
            Question:<br>
            <input type="text" name="question" id="question">
        </label><br>

        <label>
            Password:<br>
            <input type="password" name="password" id="password">
        </label><br>
        
        <button type="submit">Create</button>
    </form>
</body>
</html>