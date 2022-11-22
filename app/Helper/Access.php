<?php
//서비스 구분 mode : system(시스템) / central(이벤츠팩 홈페이지) / work(행사:운영자용) / web(웹사이트:사용자용)
//접속자 구분 type : super(시스템관리자) / user(로그인 [normal, host]) / guest(비로그인)
 
//현재 접속자 정보 반환
function getUser()
{
    //guest or user
    if(empty(Auth::User()))
    {
        $userInfo = ['udx' => 0, 'uid' => 'guest', 'name' => 'GUEST', 'type' => 'guest', 'super' => false, ];
    }else{
        $userInfo = (array)Auth::User()->getAttributes();
        $userInfo = array_merge($userInfo, ['type' => 'user', 'super' => false, ]);
        unset($userInfo['password']);        
    }
    
    //super ※ 추후 수퍼 컬럼이 정상 운영 시 이부분 삭제
    if(!empty(Auth::User()))
    {
        $userInfo['super'] = true;
    }

    return $userInfo;
}

//웹서비스 접근권한 점검 : 컨트롤러, 접속자구분, 액션
function checkCentralAccess($controller, $type, $action)
{
    //접근 권한 배열
    $permissions['UserController'] = [
        'guest' => ['index', 'store', 'show', 'update', 'destory',],
        'user' => ['index', 'store', 'show', 'update', 'destory',],
    ];
    $permissions['SystemController'] = [
        'guest' => ['index', 'store', 'show', 'update', 'destory',],
        'user' => ['index', 'store', 'show', 'update',],
    ];
    $permissions['ProjectController'] = [
        'guest' => ['index', 'store', 'show', 'update', 'destory',],
        'user' => ['index', 'store', 'show', 'update', 'destory',],
    ];

    //배열에 허용된 액션이 들어가 있어야 True
    if(array_key_exists($controller, $permissions)) //컨트롤러
    {        
        if(array_key_exists($type, $permissions[$controller])) //접속자구분
        {
            if(in_array($action,  $permissions[$controller][$type])){        return true;        }else{    return false;       }
        }
        return false;
    }else{
        return false;
    }
}

//웹서비스 접근권한 정의서
function accessCheck($mode, $controller, $action)
{
    $user = getUser();

    //시스템 관리자는 모든 권한에 패스
    if($user['super'])
    {
        return true;
    }

    //서비스가 system인 경우에는 무조건 실패
    if($mode == 'system')
    {
        return false;
    }

    //서비스가 central, work 인 경우 접근점검
    if(($mode == 'central')||($mode == 'work'))
    {
        return checkCentralAccess($controller, $user['type'], $action);
    }

    //서비스가 web인 경우 접근점검 : menu에서 해당 권한 수령하여 점검
    if($mode == 'web')
    {
        // return checkCentralAccess($controller, $user['type'], $action);
    }
}
    // //모듈의 가변형
    // $per['MenuModuleController'] = [
    //     'guest' => ['index', 'store', 'show', 'update', 'destory',],
    //     'user' => ['index', 'store', 'show', 'update', 'destory',],
    //     //접속자의 프로젝트에 소속된 사용자 그룹번호
    //     'type' => [459 => ['index', 'store', 'show', 'update', 'destory',]  ,]    ,],
    //     //사용자의 부가적 컨디션 : 숙박결제, 참가비결제 등
    //     'condition' => ['index' => [1], 'store' => [12], 'show' => [1, 128, 36], 'destory' => [36],],
    // ];