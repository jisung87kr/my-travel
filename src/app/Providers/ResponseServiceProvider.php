<?php

namespace App\Providers;

use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ApiResponse::class, function () {
            return new ApiResponse();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerResponseMacros();
    }

    /**
     * Register response macros for convenience.
     */
    protected function registerResponseMacros(): void
    {
        Response::macro('success', function (mixed $data = null, ?string $message = null, int $code = 200) {
            return ApiResponse::success($data, $message, $code);
        });

        Response::macro('created', function (mixed $data = null, ?string $message = '리소스가 생성되었습니다.') {
            return ApiResponse::created($data, $message);
        });

        Response::macro('error', function (string $message, int $code = 400, mixed $errors = null) {
            return ApiResponse::error($message, $code, $errors);
        });

        Response::macro('validationError', function (mixed $errors, string $message = '입력값을 확인해주세요.') {
            return ApiResponse::validationError($errors, $message);
        });

        Response::macro('forbidden', function (string $message = '권한이 없습니다.') {
            return ApiResponse::forbidden($message);
        });

        Response::macro('notFound', function (string $message = '리소스를 찾을 수 없습니다.') {
            return ApiResponse::notFound($message);
        });

        Response::macro('paginated', function ($paginator, ?string $message = null) {
            return ApiResponse::paginated($paginator, $message);
        });

        Response::macro('deleted', function (?string $message = '삭제되었습니다.') {
            return ApiResponse::deleted($message);
        });

        Response::macro('unauthorized', function (string $message = '인증이 필요합니다.') {
            return ApiResponse::unauthorized($message);
        });

        Response::macro('serverError', function (string $message = '서버 오류가 발생했습니다.') {
            return ApiResponse::serverError($message);
        });
    }
}
