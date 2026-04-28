# EventsPack 웹 솔루션 API

![EventsPack 사용자 화면](https://user-images.githubusercontent.com/50791439/194877006-31264bd4-076c-45b2-8ef3-8ed9916c0cea.jpg)

EventsPack은 학술대회, 정부 행사, 기업 이벤트처럼 일정과 화면 구성이 자주 바뀌는 행사 웹사이트를 빠르게 구축하기 위한 웹 솔루션입니다. 템플릿과 레이아웃 리소스를 조합해 행사 사이트를 만들고, 여러 외부 사이트나 빌더 화면에서도 동일한 계정 체계와 인증 흐름을 재사용할 수 있도록 설계되었습니다.

이 저장소는 EventsPack 전체 솔루션 중에서도 **사용자 인증, OAuth 연동, 토큰 검증, API 접근 제어**를 담당하는 Laravel API 서버입니다. 프론트엔드나 외부 사이트 빌더는 이 API를 호출해 로그인 상태를 확인하고, 특정 리소스에 접근 가능한지 검증받습니다.

단순 회원 CRUD 서버가 아니라, 외부 서비스가 EventsPack 계정 인증을 위임받고 API 서버가 bearer token과 요청 대상 컨트롤러/액션을 기준으로 접근 권한을 판단하는 구조를 담고 있습니다.

## 프로젝트 개요

- EventsPack 사용자 계정의 회원가입/로그인 처리
- Laravel Passport 기반 OAuth 클라이언트 및 토큰 발급
- 외부 앱/사이트 빌더에서 사용하는 로그인 상태 확인 API 제공
- bearer token을 내부 세션으로 전환해 Passport 승인 화면과 보호된 API에 연결
- 요청 컨트롤러/액션 기준 서비스 접근 권한 검사
- 사이트/레이아웃/내비게이션 리소스 연동용 샘플 API 제공

## 이 저장소가 맡는 역할

EventsPack 솔루션은 크게 다음과 같이 나뉩니다.

- 사용자 인증 계층: 회원, 토큰, OAuth client, 로그인 검증
- 관리자/빌더 계층: 행사 사이트 구성, 레이아웃 편집, 외부 사이트 연동
- 사용자 사이트 계층: 실제 행사 웹사이트 또는 외부 서비스 화면

이 저장소는 그중 첫 번째 계층에 해당합니다. 외부 서비스는 EventsPack API에 직접 사용자 인증을 위임하고, API 서버는 토큰 유효성 및 접근 권한을 검증한 뒤 필요한 사용자 정보나 승인 흐름으로 연결합니다.

## 핵심 인증 흐름

1. 사용자가 `/api/register` 또는 `/api/login`으로 EventsPack 계정에 로그인합니다.
2. API 서버는 Passport access token을 발급합니다.
3. 외부 사이트나 앱은 발급받은 bearer token으로 `/api/login-check-request`, `/api/oauth/login-check-request`, `/api/oauth/clients-create-request` 같은 프록시 API를 호출합니다.
4. 서버는 `EVENTSPACK_API_BASE_URL`에 설정된 EventsPack API를 기준으로 토큰을 다시 검증하고, 필요하면 내부 사용자 세션으로 로그인시킵니다.
5. 이후 Passport 승인 화면(`/oauth/authorize`) 또는 보호된 서비스 API에서 동일한 사용자 컨텍스트를 사용합니다.
6. 서비스 API는 `processCheck` 미들웨어를 통해 요청 대상 컨트롤러/액션과 사용자 유형을 비교해 접근 가능 여부를 판단합니다.

## 주요 기능

### 1. 계정 및 토큰

- 회원가입 API
- 로그인 API
- 로그인 사용자 정보 확인
- 로그아웃 시 access token revoke

주요 엔드포인트:

- `POST /api/register`
- `POST /api/login`
- `POST /api/login-check`
- `POST /api/logout-check`

### 2. 외부 서비스 인증 위임

- bearer token 기반 로그인 상태 확인 요청
- 외부 앱 로그인 상태 확인
- OAuth client 생성/조회 프록시
- bearer token을 세션 로그인으로 연결한 OAuth 승인 흐름

주요 엔드포인트:

- `POST /api/login-check-request`
- `POST /api/logout-request`
- `POST /api/oauth/clients-create-request`
- `POST /api/oauth/clients-list-request`
- `GET /api/oauth/clients-get-user-request`
- `POST /api/oauth/login-check-request`
- `GET /oauth/authorize`
- `GET|POST /oauth/clients`

### 3. 서비스 접근 제어

- `processCheck` 미들웨어에서 bearer token 검증
- 사용자 정보를 로컬 세션으로 연결
- `mode + controller + action` 기준 접근 권한 확인
- 인증 실패 시 `401`, 권한 불일치 시 `405` 응답

현재 권한 분기는 `system`, `central`, `work`, `web` 모드를 기준으로 구성되어 있으며, 실제 권한 정의는 `app/Helper/Access.php`에 위치합니다.

### 4. 사이트 빌더 연동용 샘플 리소스

이 저장소에는 행사 사이트 구성 연동을 위한 예시 응답도 포함되어 있습니다.

- `GET /api/site-info`
- `GET /api/layout-info`
- `GET /api/navigation-info`

위 API들은 사이트 기본 정보, 레이아웃 HTML/CSS, 내비게이션 트리 구조를 반환하는 샘플 응답입니다. 프로젝트 소개나 프론트엔드 연동 테스트용으로 활용할 수 있습니다.

## 기술 스택

### Backend

![PHP](https://img.shields.io/badge/PHP-7.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-6-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Laravel Passport](https://img.shields.io/badge/Laravel%20Passport-9-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Guzzle](https://img.shields.io/badge/Guzzle-HTTP%20Client-0E7490?style=for-the-badge)
![Composer](https://img.shields.io/badge/Composer-Package%20Manager-885630?style=for-the-badge&logo=composer&logoColor=white)

### Frontend / Asset Build

`package.json` 기준:

![Laravel Mix](https://img.shields.io/badge/Laravel%20Mix-4.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Axios](https://img.shields.io/badge/Axios-0.19-5A29E4?style=for-the-badge&logo=axios&logoColor=white)
![Lodash](https://img.shields.io/badge/Lodash-4.17-3492FF?style=for-the-badge&logo=lodash&logoColor=white)
![Sass](https://img.shields.io/badge/Sass-1.15-CC6699?style=for-the-badge&logo=sass&logoColor=white)
![Sass Loader](https://img.shields.io/badge/Sass%20Loader-7.1-CC6699?style=for-the-badge)

## 프로젝트 구조

```text
app/
├── Helper/                    # 메시지, 접근 권한 helper
├── Http/
│   ├── Controllers/
│   │   ├── Auth/              # 회원가입, 로그인, OAuth client, 로그인 확인
│   │   └── ...                # 사용자/프로젝트/시스템 관련 컨트롤러
│   └── Middleware/            # CORS, 강제 로그인, 접근 권한 검사
├── Providers/                 # AuthServiceProvider 등 Laravel provider
├── User.php                   # Passport 토큰 사용 사용자 모델
config/
├── services.php               # 외부 EventsPack API URL, 허용 Origin 설정
database/
├── migrations/                # users, password_resets, failed_jobs
routes/
├── api.php                    # 인증/리소스 API
└── web.php                    # Passport authorize/client route
```

## 실행 준비

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan passport:install
php artisan serve
```

PowerShell:

```powershell
composer install
npm install
Copy-Item .env.example .env
php artisan key:generate
php artisan migrate
php artisan passport:install
php artisan serve
```

기본 개발 서버는 `http://127.0.0.1:8000` 또는 `http://localhost:8000`에서 실행됩니다.

## 주요 환경 변수

| 변수 | 설명 |
| --- | --- |
| `APP_NAME` | 애플리케이션 이름 |
| `APP_URL` | API 서버 기본 URL |
| `DB_HOST` | MySQL 호스트 |
| `DB_PORT` | MySQL 포트 |
| `DB_DATABASE` | MySQL 데이터베이스명 |
| `DB_USERNAME` | DB 계정 |
| `DB_PASSWORD` | DB 비밀번호 |
| `EVENTSPACK_API_BASE_URL` | 토큰 검증 및 OAuth 승인 흐름에 사용할 기준 EventsPack API URL |
| `EVENTSPACK_ALLOWED_ORIGINS` | CORS 허용 Origin 목록. `|` 구분 |

## 현재 코드 기준 참고 사항

- 사용자 기본 키는 `udx`이며 로그인 ID는 `uid`를 사용합니다.
- `User` 모델은 Passport `HasApiTokens`를 사용합니다.
- `ForceLogin`, `ProcessCheck` 미들웨어는 외부 bearer token을 검증한 뒤 로컬 사용자 세션으로 연결합니다.
- `site-info`, `layout-info`, `navigation-info`는 샘플 응답이며, 일부 리소스 컨트롤러는 아직 골격 수준입니다.
- 따라서 이 저장소는 행사 웹사이트 전체 기능 구현본이라기보다, **EventsPack 솔루션의 인증/API 연동 계층과 연동 예시를 담은 서버**로 소개하는 편이 정확합니다.

## 보완한 부분

- Laravel 실행에 필요한 `bootstrap/` 구조 복구
- `.gitignore`가 `bootstrap` 전체를 제외하던 문제 수정
- Composer/NPM 메타데이터를 프로젝트명 기준으로 정리
- PHP 요구 버전을 lock 파일과 맞게 `7.3+`로 정리
- CORS 허용 Origin을 `.env` 기반 설정으로 분리
- 외부 EventsPack API URL 하드코딩 제거
- `logoutRequest`의 잘못된 HTTP method 수정
- 회원가입 validation이 실패해도 생성 로직으로 진행될 수 있던 흐름 수정
- 라우트에서 호출되는 `register` 메서드 접근 제어자를 `public`으로 수정
- bearer 검증 실패나 사용자 미존재 시 `401`로 명확히 차단

## 관련 저장소

- 관리자: `php_laravel_EventPack_admin`
- 사용자 API: `php_laravel_EventsPack_dev1`
