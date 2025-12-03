<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>대시보드</title>
</head>
<body>
    <h1>대시보드</h1>

    <p>환영합니다, {{ auth()->user()->name }}님!</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">로그아웃</button>
    </form>
</body>
</html>
