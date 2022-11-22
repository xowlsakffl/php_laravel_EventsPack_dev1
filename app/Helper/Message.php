<?php
//메세지 정의서 : 메세지코드
function getMSG($kind)
{
    $messages = [
        //회원관련
        'user.notexist' => ['사용자가 존재하지 않습니다.', 'User Not Exist',],

        //상황적 메세지
        'require' => ['필수입력 요소가 필요합니다.', 'Essencial Needs',],
        'valid' => ['형식에 맞지 않습니다.', 'Not Valid',],
        

        //일반적인 메세지
        500 => ['오류', 'Something Wrong',],
        400 => ['잘못된 요청입니다.', 'Bad Request',],
        401 => ['로그인이 필요합니다.', 'Unauthorized',],
        403 => ['금지된 대상입니다.', 'Forbidden',],
        404 => ['대상이 존재하지 않습니다.', 'Not Found',],
        405 => ['권한이 필요합니다.', 'Not Allowed',],
        422 => ['처리할 수 없습니다.', 'Unprocessable Entity',],
    ];
    if(array_key_exists($kind, $messages))
    {
        return $messages[$kind];
    }else{
        return ['존재하지 않는 알람', 'Not Exist'];
    }
}