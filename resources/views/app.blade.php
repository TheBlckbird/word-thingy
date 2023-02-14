<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Word thingy</title>
    @vite(['resources/ts/app.ts'])
</head>
<body>
    <form id="go-to-page-form">
        <label>
            Go to page
            <input type="text" id="page-id">
        </label>

        <button type="submit" id="go-to-page">Open</button>
    </form>

    <br>
    <br>

    <a href="/page/create">Create new word cloud</a>
</body>
</html>