<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인</title>
</head>
<body>
    <h1>로그인</h1>

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

    <form method="POST" action="{{ url('/login') }}">
        @csrf

        <div>
            <label for="email">이메일</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label for="password">비밀번호</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div>
            <label for="remember">
                <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                로그인 상태 유지
            </label>
        </div>

        <button type="submit">로그인</button>
    </form>

    <p><a href="{{ route('password.request') }}">비밀번호를 잊으셨나요?</a></p>
    <p>계정이 없으신가요? <a href="{{ route('register') }}">회원가입</a></p>

    <hr>

    <h3>소셜 로그인</h3>
    <a href="{{ route('social.redirect', 'google') }}">Google로 로그인</a>
    <a href="{{ route('social.redirect', 'kakao') }}">Kakao로 로그인</a>
    <a href="{{ route('social.redirect', 'apple') }}">Apple로 로그인</a>
</body>
</html>
