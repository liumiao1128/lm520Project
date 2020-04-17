<?php

namespace App\Exceptions;

use App\Common\ResponseCode;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Routing\Router;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        if (method_exists($e, 'report') && $response = $e->render($request)) {
            return Router::toResponse($request, $response);
        } elseif ($e instanceof Responsable) {
            return $e->toResponse($request);
        }

        $route_arr = explode('/', ltrim($request->getRequestUri(), '/'));

        $e = $this->prepareException($e);

        if ($route_arr[0] == 'api' && $e instanceof MethodNotAllowedHttpException) {
            return $this->responseAndLog(ResponseCode::HTTP_ERROR, 'Method not allowed. get or post ?');
        } elseif ($route_arr[0] == 'api' && $e instanceof NotFoundHttpException) {
            return $this->responseAndLog(ResponseCode::INVALID_URI, 'Invalid url');
        } elseif ($route_arr[0] == 'api' && $e instanceof QueryException) {
            return $this->responseAndLog(ResponseCode::MYSQL_QUERY_ERROR, '数据库异常');
        } elseif ($route_arr[0] == 'api') {
            return apiResponse(null, ResponseCode::SYS_ERROR, $e->getMessage());
        } elseif ($e instanceof ResponseException) {
            return $this->responseAndLog($e->getCode(), $e->getMessage());
        } elseif ($e instanceof HttpResponseException) {
            return $e->getResponse();
        } elseif ($e instanceof AuthenticationException) {
            return $this->unauthenticated($request, $e);
        } elseif ($e instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($e, $request);
        }

        return $request->expectsJson()
            ? $this->prepareJsonResponse($request, $e)
            : $this->prepareResponse($request, $e);
//        return parent::render($request, $exception);
    }

    private function responseAndLog($code, $message)
    {
        $response = array(
            'code' => $code,
            'msg' => $message,
            'time' => date('Y-m-d H:i:s'),
            'data' => '',
        );
        $requestReferer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

        $requestUrl = explode('?', $_SERVER['REQUEST_URI']);
        $requestUrl = $requestUrl[0];
        $responseInfo = [
            'response' => $response,
            'url' => $requestUrl,
            'referer' => $requestReferer,
        ];
        $uniqid = apiUniqId();
        \Log::debug("{$uniqid} access_response", $responseInfo);
        return $response;
    }
}
