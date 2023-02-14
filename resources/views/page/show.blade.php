<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Page Show</title>

    @vite(['resources/scss/app.scss'])
</head>
<body>
    <a href="/">Home</a>

    <h1>{{ $question }}</h1>

    <form action="/word/{{ $id }}/new" method="post">
        @csrf
        <input type="text" name="newWord" id="newWord" maxlength="20" placeholder="New Word">
        <button type="submit">Submit</button>
    </form>

    <br>

    @foreach ($words as $word)
        @if (!$word->banned)

            <div class="flex flex__gap_10px">
                {{ $word->word }}: {{ $word->count }}

                <form action="/word/{{ $id }}/ban" method="post">
                    @csrf
                    <input type="hidden" name="word_to_ban" value="{{ $word->word }}">
                    <button type="submit">ban</button>

                </form>
            </div>
            
            <br>

        @endif
    @endforeach

    <h3>Admin</h3>
    @if (session('pageLoggedIn') === $id)

        <form action="/page/{{ $id }}" method="post">
            @method('DELETE')
            @csrf
            
            <button type="submit">Delete Page</button>
        </form>

        <form action="/page/{{ $id }}" method="post" class="admin-form">
            @method('PUT')
            @csrf

            <div class="change-question">
                <input type="text" name="newQuestion" id="new-question" placeholder="New Question">
                <button type="submit">Change Question</button>
            </div>
        </form>

        <form action="/page/{{ $id }}" method="post" class="admin-form">
            @method('PUT')
            @csrf

            <div class="change-password">
                <input type="password" name="newPassword" id="new-password" placeholder="New Password">
                <button type="submit">Change Password</button>
            </div>
        </form>

        <h4>Banned Words</h4>
        @foreach ($words as $word)
            @if ($word->banned)
                <div class="flex flex__gap_10px">
                    {{ $word->word }}

                    <form action="/word/{{ $id }}/unban" method="post">
                        @csrf
                        <input type="hidden" name="word_to_unban" value="{{ $word->word }}">
                        <button type="submit">unban</button>

                    </form>
                </div>
                <br>

            @endif
        @endforeach

        <form action="/word/{{ $id }}/ban" method="post" class="admin-form">
            @csrf

            <input type="text" name="word_to_ban" id="word-to-ban" maxlength="20" placeholder="Word">
            <button type="submit">Ban Word</button>
        </form>

        <br>

        <form action="/page/{{ $id }}/logout" method="get">
            <button type="submit">Logout</button>
        </form>
    @else
        <form action="/page/auth/login" method="POST">
            @csrf
            <input type="hidden" name="pageId" value="{{ $id }}">
            <input type="password" name="password" id="password">
            <button type="submit">Log In</button>
        </form>
    @endif
</body>
</html>