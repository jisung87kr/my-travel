<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>비밀번호 찾기</title>
</head>
<body>
    <h1>비밀번호 찾기</h1>

    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <p>이메일 주소를 입력하시면 비밀번호 재설정 링크를 보내드립니다.</p>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <label for="email">이메일</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <button type="submit">비밀번호 재설정 링크 보내기</button>
    </form>

    <p><a href="{{ route('login') }}">로그인으로 돌아가기</a></p>
</body>
</html>
