<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원가입</title>
</head>
<body>
    <h1>회원가입</h1>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ url('/register') }}">
        @csrf

        <div>
            <label for="name">이름</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        </div>

        <div>
            <label for="email">이메일</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label for="password">비밀번호</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div>
            <label for="password_confirmation">비밀번호 확인</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <button type="submit">회원가입</button>
    </form>

    <p>이미 계정이 있으신가요? <a href="{{ route('login') }}">로그인</a></p>

    <hr>

    <h3>소셜 로그인</h3>
    <a href="{{ route('social.redirect', 'google') }}">Google로 로그인</a>
    <a href="{{ route('social.redirect', 'kakao') }}">Kakao로 로그인</a>
    <a href="{{ route('social.redirect', 'apple') }}">Apple로 로그인</a>
</body>
</html>
