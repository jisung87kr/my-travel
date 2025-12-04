<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class CheckEnvironment extends Command
{
    protected $signature = 'app:check-env
                            {--fix : 누락된 환경변수 템플릿 생성}
                            {--verbose : 상세 정보 출력}';

    protected $description = '프로덕션 환경에 필요한 환경변수 및 서비스 점검';

    private array $requiredEnvVars = [
        'APP_KEY' => '애플리케이션 암호화 키',
        'APP_URL' => '애플리케이션 URL',
        'DB_HOST' => '데이터베이스 호스트',
        'DB_DATABASE' => '데이터베이스 이름',
        'DB_USERNAME' => '데이터베이스 사용자명',
        'DB_PASSWORD' => '데이터베이스 비밀번호',
    ];

    private array $optionalEnvVars = [
        'MAIL_MAILER' => '메일 드라이버',
        'MAIL_HOST' => '메일 호스트',
        'MAIL_PORT' => '메일 포트',
        'MAIL_USERNAME' => '메일 사용자명',
        'MAIL_PASSWORD' => '메일 비밀번호',
        'MAIL_FROM_ADDRESS' => '발신 이메일 주소',
        'MAIL_FROM_NAME' => '발신자 이름',
        'GOOGLE_CLIENT_ID' => 'Google OAuth Client ID',
        'GOOGLE_CLIENT_SECRET' => 'Google OAuth Client Secret',
        'KAKAO_CLIENT_ID' => 'Kakao OAuth Client ID',
        'KAKAO_CLIENT_SECRET' => 'Kakao OAuth Client Secret',
        'NAVER_CLIENT_ID' => 'Naver OAuth Client ID',
        'NAVER_CLIENT_SECRET' => 'Naver OAuth Client Secret',
        'QUEUE_CONNECTION' => 'Queue 연결 드라이버',
        'CACHE_DRIVER' => '캐시 드라이버',
        'SESSION_DRIVER' => '세션 드라이버',
        'LOG_SLACK_WEBHOOK_URL' => 'Slack 에러 로그 Webhook',
    ];

    public function handle(): int
    {
        $this->info('');
        $this->info('==========================================');
        $this->info('  My Travel - 환경 점검');
        $this->info('==========================================');
        $this->info('');

        $hasErrors = false;
        $hasWarnings = false;

        // 1. Required environment variables
        $this->info('1. 필수 환경변수 점검');
        $missing = $this->checkRequiredEnvVars();
        if (count($missing) > 0) {
            $hasErrors = true;
            $this->error('   ✗ 누락된 필수 환경변수:');
            foreach ($missing as $key => $description) {
                $this->error("     - $key ($description)");
            }
        } else {
            $this->info('   ✓ 모든 필수 환경변수 설정됨');
        }

        // 2. Optional environment variables
        $this->info('');
        $this->info('2. 선택 환경변수 점검');
        $missingOptional = $this->checkOptionalEnvVars();
        if (count($missingOptional) > 0) {
            $hasWarnings = true;
            $this->warn('   ⚠ 설정되지 않은 선택 환경변수:');
            foreach ($missingOptional as $key => $description) {
                $this->warn("     - $key ($description)");
            }
        } else {
            $this->info('   ✓ 모든 선택 환경변수 설정됨');
        }

        // 3. Database connection
        $this->info('');
        $this->info('3. 데이터베이스 연결 점검');
        if ($this->checkDatabaseConnection()) {
            $this->info('   ✓ 데이터베이스 연결 성공');
        } else {
            $hasErrors = true;
            $this->error('   ✗ 데이터베이스 연결 실패');
        }

        // 4. Storage directories
        $this->info('');
        $this->info('4. 스토리지 디렉토리 점검');
        if ($this->checkStorageDirectories()) {
            $this->info('   ✓ 모든 스토리지 디렉토리 쓰기 가능');
        } else {
            $hasErrors = true;
            $this->error('   ✗ 스토리지 디렉토리 권한 문제');
        }

        // 5. App key
        $this->info('');
        $this->info('5. 암호화 키 점검');
        if ($this->checkAppKey()) {
            $this->info('   ✓ APP_KEY가 유효함');
        } else {
            $hasErrors = true;
            $this->error('   ✗ APP_KEY가 유효하지 않음');
        }

        // 6. Cache driver
        $this->info('');
        $this->info('6. 캐시 드라이버 점검');
        if ($this->checkCacheDriver()) {
            $this->info('   ✓ 캐시 드라이버 정상 작동');
        } else {
            $hasWarnings = true;
            $this->warn('   ⚠ 캐시 드라이버 연결 실패');
        }

        // 7. Queue driver (if not sync)
        $this->info('');
        $this->info('7. Queue 드라이버 점검');
        $queueDriver = config('queue.default');
        if ($queueDriver === 'sync') {
            $this->warn('   ⚠ Queue가 sync 모드로 설정됨 (프로덕션에서는 권장하지 않음)');
            $hasWarnings = true;
        } else {
            $this->info("   ✓ Queue 드라이버: $queueDriver");
        }

        // 8. Debug mode
        $this->info('');
        $this->info('8. 디버그 모드 점검');
        if (config('app.debug')) {
            if (config('app.env') === 'production') {
                $hasErrors = true;
                $this->error('   ✗ 프로덕션 환경에서 디버그 모드가 활성화됨!');
            } else {
                $this->warn('   ⚠ 디버그 모드 활성화됨 (개발 환경)');
            }
        } else {
            $this->info('   ✓ 디버그 모드 비활성화됨');
        }

        // Summary
        $this->info('');
        $this->info('==========================================');

        if ($hasErrors) {
            $this->error('  ✗ 환경 점검 실패: 오류를 수정해주세요');
            return 1;
        } elseif ($hasWarnings) {
            $this->warn('  ⚠ 환경 점검 완료: 경고 사항이 있습니다');
            return 0;
        } else {
            $this->info('  ✓ 환경 점검 통과');
            return 0;
        }
    }

    private function checkRequiredEnvVars(): array
    {
        $missing = [];
        foreach ($this->requiredEnvVars as $key => $description) {
            if (empty(env($key))) {
                $missing[$key] = $description;
            }
        }
        return $missing;
    }

    private function checkOptionalEnvVars(): array
    {
        $missing = [];
        foreach ($this->optionalEnvVars as $key => $description) {
            if (empty(env($key))) {
                $missing[$key] = $description;
            }
        }
        return $missing;
    }

    private function checkDatabaseConnection(): bool
    {
        try {
            DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            if ($this->option('verbose')) {
                $this->error("     Error: " . $e->getMessage());
            }
            return false;
        }
    }

    private function checkStorageDirectories(): bool
    {
        $directories = [
            storage_path('app'),
            storage_path('app/public'),
            storage_path('framework/cache'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            storage_path('logs'),
        ];

        $allWritable = true;
        foreach ($directories as $dir) {
            if (!File::isWritable($dir)) {
                if ($this->option('verbose')) {
                    $this->error("     Not writable: $dir");
                }
                $allWritable = false;
            }
        }

        return $allWritable;
    }

    private function checkAppKey(): bool
    {
        $key = config('app.key');
        return !empty($key) && strlen($key) >= 32;
    }

    private function checkCacheDriver(): bool
    {
        try {
            cache()->put('env_check_test', 'ok', 10);
            $value = cache()->get('env_check_test');
            cache()->forget('env_check_test');
            return $value === 'ok';
        } catch (\Exception $e) {
            if ($this->option('verbose')) {
                $this->error("     Error: " . $e->getMessage());
            }
            return false;
        }
    }
}
