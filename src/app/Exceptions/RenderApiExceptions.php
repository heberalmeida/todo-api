<?php

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

return function (\Throwable $e, Request $request) {
    if (!$request->is('api/*')) {
        return null;
    }

    $status = 500;
    $mensagem = 'Ocorreu um erro inesperado.';

    if ($e instanceof ValidationException) {
        return response()->json([
            'mensagem' => 'Erro de validação.',
            'erros'    => $e->errors(),
            'codigo'   => 422
        ], 422);
    }

    if (
        $e instanceof AuthenticationException ||
        $e instanceof TokenExpiredException ||
        $e instanceof TokenInvalidException ||
        $e instanceof TokenBlacklistedException ||
        $e instanceof JWTException ||
        $e instanceof UnauthorizedHttpException
    ) {
        $status = 401;
        $mensagem = 'Não autenticado.';
    } elseif ($e instanceof AuthorizationException) {
        $status = 403;
        $mensagem = 'Acesso não autorizado.';
    } elseif ($e instanceof NotFoundHttpException) {
        $status = 404;
        $mensagem = 'Recurso não encontrado.';
    } elseif ($e instanceof MethodNotAllowedHttpException) {
        $status = 405;
        $mensagem = 'Método HTTP não permitido.';
    }

    if ($e instanceof HttpExceptionInterface && $mensagem === 'Ocorreu um erro inesperado.') {
        $status = $e->getStatusCode();
        $mensagem = $e->getMessage() ?: 'Erro HTTP';
    } elseif (method_exists($e, 'getMessage') && $e->getMessage() && $mensagem === 'Ocorreu um erro inesperado.') {
        $mensagem = $e->getMessage();
    }

    return response()->json([
        'message' => $mensagem,
        'code'   => $status,
    ], $status);
};
