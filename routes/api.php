<?php
use Illuminate\Http\Request;

// ==========================================================
// 인증부분
// ==========================================================
// ========== 개인 토큰 비할당 상태
//로그인
Route::post('/register', 'Auth\RegisterController@register');   //가입 요청
Route::post('/login', 'Auth\LoginController@login');    //로그인 요청
Route::post('/login-check-request', 'Auth\LoginCheckController@loginCheckRequest'); //로그인상태 조회 접수
Route::post('/logout-request', 'Auth\LoginCheckController@logoutRequest'); //로그아웃 조회 접수

//APP
Route::post('/oauth/clients-create-request', 'Auth\OauthClientsController@createAppRequest');   //서비스용 APP 할당
Route::post('/oauth/clients-list-request', 'Auth\OauthClientsController@listAppRequest');       //사용자의 서비스용 APP 목록
Route::get('/oauth/clients-get-user-request', 'Auth\OauthClientsController@getUserRequest');    //User의 APP 사용 신청
Route::post('/oauth/login-check-request', 'Auth\OauthClientsController@appLoginCheckRequest');  //APP에서 사용자 정보 조회

// ========== 개인 토큰 할당 상태
Route::middleware(['auth:api'])->group(function()
{
    //로그인
    Route::post('/login-check', 'Auth\LoginCheckController@loginCheck');    //내부 로그인 상태 확인
    Route::post('/logout-check', 'Auth\LoginCheckController@logoutCheck');  //로그아웃

    //APP
    Route::post('/app-login-check', 'Auth\OauthClientsController@appLoginCheck');    //APP에서 사용자 정보호출
});



// ==========================================================
//서비스 관련 모든 요청
// ==========================================================
Route::middleware(['processCheck'])->group(function()
{
    //시스템머
    Route::prefix('system')->group(function ()
    {
        Route::resource('user', 'UserController');
    });
    //이벤츠팩 홈페이지
    Route::prefix('central')->group(function ()
    {
        Route::resource('user', 'UserController');
    });

    //이벤츠팩 홈페이지
    Route::prefix('work')->group(function ()
    {
        Route::resource('user', 'UserController');
    });

    //이벤츠팩 홈페이지
    Route::prefix('web')->group(function ()
    {
        Route::resource('user', 'UserController');
    });
});

//임시
Route::get('/site-info', function()
{
    return [
        'sdx' => 10,
        'wdx' => 3,
        'name' => 'site_name',
        'email_name' => 'email_name',
        'email_address' => 'email_address',
        'phone_name' => 'phone_name',
        'phone_address' => 'phone_address',
        'title' => 'site_title',
        'description' => 'site_description',
        'keyword' => 'site_keyword',
        'favicon_fdx' => 14,
        'og_title' => 'site_og_title',
        'og_url' => 'site_og_url',
        'og_description' => 'site_og_description',
        'og_images' => 'https://blogimgs.pstatic.net/nblog/mylog/post/og_default_image_160610.png',
        'meta' => '<meta name="progma" content="no-cache" />',
        'state' => 10,
    ];
});
Route::get('/layout-info', function()
{
    return [
        'id' => 448,
        'lodx' => 16,
        'lotdx' => 17,
        'top_html' => '<div>Top Layout</div>',
        'top_css' => '#TOP {width: 100%; height: 100px; background: #FF0000; color: #FFFFFF;}',
        'londx' => 17,
        'use_site_menu' => true,
        'navigation_html' => '<div>Navigation</div>',
        'navigation_css' => '#NAVIGATION {width: 100%; height: 50px; background: blue; color: #FFFFFF;}',
        'lomdx' => 10,
        'middle_html' => '<div>Middle</div>',
        'middle_css' => '#MIDDLE {width: 100%; height: 50px; border: 1px solid orange;}',
        'lobdx' => 4,
        'bottom_html' => '<div>Bottom</div>',
        'bottom_css' => '#BOTTOM {width: 100%; height: 150px; background: #F9F9F9; color: #000000;}',
        'display_type' => 'fadeIn',
        'display_duration' => 200,
        'font_default' => 'font-family: tahoma;',
        'font_resource' => 'font_resource',
        ];
});
Route::get('/navigation-info', function()
{
    return [
        ['sndx' => 1, 'parent' => 0, 'name' => 'nav1', 'destination_stdx' => 111, 'destination_url' => '/nav1', 'new_window' => false,
          'children' => [
            ['sndx' => 2, 'parent' => 1, 'name' => 'nav1-1', 'destination_stdx' => 112, 'destination_url' => '/nav1/nav1-1', 'new_window' => false,
              'children' => [
                ['sndx' => 3, 'parent' => 2, 'name' => 'nav1-1-1', 'destination_stdx' => 113, 'destination_url' => '/nav1/nav1-1/nav1-1-1', 'new_window' => false],
                ['sndx' => 4, 'parent' => 2, 'name' => 'nav1-1-2', 'destination_stdx' => 114, 'destination_url' => '/nav1/nav1-1/nav1-1-2', 'new_window' => false],
                ['sndx' => 5, 'parent' => 2, 'name' => 'nav1-1-3', 'destination_stdx' => 115, 'destination_url' => '/nav1/nav1-1/nav1-1-3', 'new_window' => false],
              ],
            ],
          ],
        ],
        ['sndx' => 20, 'parent' => 0, 'name' => 'nav2', 'destination_stdx' => 211, 'destination_url' => '/nav2', 'new_window' => false,
          'children' => [
            ['sndx' => 21, 'parent' => 20, 'name' => 'nav2-1', 'destination_stdx' => 212, 'destination_url' => '/nav2/nav2-1', 'new_window' => false,
              'children' => [
                ['sndx' => 23, 'parent' => 21, 'name' => 'nav2-1-1', 'destination_stdx' => 213, 'destination_url' => '/nav2/nav2-1/nav2-1-1', 'new_window' => false],
                ['sndx' => 24, 'parent' => 21, 'name' => 'nav2-1-2', 'destination_stdx' => 214, 'destination_url' => '/nav2/nav2-1/nav2-1-2', 'new_window' => false],
                ['sndx' => 25, 'parent' => 21, 'name' => 'nav2-1-3', 'destination_stdx' => 215, 'destination_url' => '/nav2/nav2-1/nav2-1-3', 'new_window' => false],
              ],
            ],
            ['sndx' => 22, 'parent' => 20, 'name' => 'nav2-2', 'destination_stdx' => 412, 'destination_url' => '/nav2/nav2-2', 'new_window' => false,
              'children' => [
                ['sndx' => 223, 'parent' => 22, 'name' => 'nav2-2-1', 'destination_stdx' => 413, 'destination_url' => '/nav2/nav2-2/nav2-2-1', 'new_window' => false],
                ['sndx' => 224, 'parent' => 22, 'name' => 'nav2-2-2', 'destination_stdx' => 414, 'destination_url' => '/nav2/nav2-2/nav2-2-2', 'new_window' => false],
                ['sndx' => 225, 'parent' => 22, 'name' => 'nav2-2-3', 'destination_stdx' => 415, 'destination_url' => '/nav2/nav2-2/nav2-2-3', 'new_window' => false],
              ],
            ],
          ],
        ],
      ];
});
