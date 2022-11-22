<?php
use Illuminate\Http\Request;

// APP 사용 신청
Route::get('authorize', '\Laravel\Passport\Http\Controllers\AuthorizationController@authorize')->name('passport.authorizations.authorize');
Route::post('authorize', '\Laravel\Passport\Http\Controllers\ApproveAuthorizationController@approve')->name('passport.authorizations.approve');
Route::delete('authorize', '\Laravel\Passport\Http\Controllers\DenyAuthorizationController@deny')->name('passport.authorizations.deny');
Route::middleware(['forceLogin'])->prefix('oauth')->group(function () {
    Route::get('clients', '\Laravel\Passport\Http\Controllers\ClientController@forUser')->name('passport.clients.index');
    Route::post('clients', '\Laravel\Passport\Http\Controllers\ClientController@store')->name('passport.clients.store');
});

//리턴 테스트
Route::get('return-test', 'ReturnTestController@tests');