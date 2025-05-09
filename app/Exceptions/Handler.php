<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Http\Responses\ApiErrorResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseStatus;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    public const EXCEPTION_CLASS_CODE_MAP = [
        'basic' => ResponseStatus::HTTP_INTERNAL_SERVER_ERROR,
        QueryException::class => ResponseStatus::HTTP_INTERNAL_SERVER_ERROR,
        NotFoundHttpException::class => ResponseStatus::HTTP_NOT_FOUND,
        ModelNotFoundException::class => ResponseStatus::HTTP_NOT_FOUND,
        MethodNotAllowedHttpException::class => ResponseStatus::HTTP_METHOD_NOT_ALLOWED,
        ValidationException::class => ResponseStatus::HTTP_UNPROCESSABLE_ENTITY,
        AuthenticationException::class => ResponseStatus::HTTP_UNAUTHORIZED,
        AuthorizationException::class => ResponseStatus::HTTP_FORBIDDEN,
        UnauthorizedException::class => ResponseStatus::HTTP_UNAUTHORIZED,
    ];

    public function render($request, Throwable $e): ApiErrorResponse
    {
        $message = $e->getMessage();
        $code = self::EXCEPTION_CLASS_CODE_MAP[get_class($e)] ?? self::EXCEPTION_CLASS_CODE_MAP['basic'];

        return new ApiErrorResponse(
            message: $message,
            exception: $e,
            code: $code
        );
    }
}
