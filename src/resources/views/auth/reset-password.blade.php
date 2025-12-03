<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>비밀번호 재설정</title>
</head>
<body>
    <h1>비밀번호 재설정</h1>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label for="email">이메일</label>
            <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" required>
        </div>

        <div>
            <label for="password">새 비밀번호</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div>
            <label for="password_confirmation">새 비밀번호 확인</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <button type="submit">비밀번호 재설정</button>
    </form>
</body>
</html>
