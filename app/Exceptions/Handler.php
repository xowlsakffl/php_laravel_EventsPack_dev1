<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {        
        // if ($request->wantsJson()) {   //add Accept: application/json in request
        // return parent::render($request, $exception);
        if (true)
        {
            return $this->handleApiException($request, $exception);
        } else {
            return parent::render($request, $exception);
        }
    }

    private function handleApiException($request, Exception $exception)
    {
        $exception = $this->prepareException($exception);
        if ($exception instanceof \Illuminate\Http\Exception\HttpResponseException) {
            $exception = $exception->getResponse();
        }
        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            $exception = $this->unauthenticated($request, $exception);
        }
        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            $exception = $this->convertValidationExceptionToResponse($exception, $request);
        }
        return $this->customApiResponse($exception);
    }

    private function customApiResponse($exception)
    {
        $response = [];   
        //상태
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        // } else if (method_exists($exception, 'getCode')) {
        //     $statusCode = $exception->getCode();
        } else {
            $statusCode = 500;
        }     
        $response['status'] = $statusCode;

        //기본메세지
        $response['message'] = getMSG($statusCode);
        // |로 분리된 직접전달받은 메세지
        if(!empty($exception->getMessage()))
        {
            $response['message'] = explode('|', $exception->getMessage());
        }
        //상세내역
        if (config('app.debug')) {
            $response['trace'] = $exception->getTrace();
            $response['code'] = $exception->getCode();
        }
        return dd($response);
        return response($response, $statusCode); //오리지날
        // return response($response);
        // return response()->json($response, $statusCode);
    }
}

// switch ($statusCode) {
//     case 400:   //잘못된 요청 : 자동
//         $response['message'] = ['잘못된 요청입니다.', 'Bad Request'];
//         break;
//     case 401:   //로그인필요
//         $response['message'] = ['로그인이 필요합니다.', 'Unauthorized'];
//         break;
//     case 403:   //금지된 대상
//         $response['message'] = ['금지된 대상입니다.', 'Forbidden'];
//         break;
//     case 404:   //대상없음 : 자동
//         $response['message'] = ['대상이 존재하지 않습니다.', 'Not Found'];
//         break;
//     case 405:   //권한없음
//         $response['message'] = ['권한이 필요합니다.', 'Not Allowed'];
//         break;
//     case 422:   //기타 처리실패 : require, validation 실패 등
//             if(!empty($exception->original['message']))
//             {
//                 $response['message'] = $exception->original['message'];
//                 $response['errors'] = $exception->original['errors'];
//             }else{
//                 $response['message'] = ['처리할 수 없습니다.', 'Unprocessable Entity'];
//             }
//             $response['message'] = ['처리할 수 없습니다.', 'Unprocessable Entity'];
//         break;
//     default:
//         //서버에러
//         $response['message'] = ($statusCode == 500) ? ['오류발생', 'Something Went Wrong'] : [$exception->getMessage(), $exception->getMessage()];
//         break;
// }