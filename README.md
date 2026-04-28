# EventsPack 웹 솔루션 API

![EventsPack 사용자 화면](https://user-images.githubusercontent.com/50791439/194877006-31264bd4-076c-45b2-8ef3-8ed9916c0cea.jpg)

EventsPack 사용자 서비스와 외부 사이트 빌더 기능을 위한 Laravel API 서버입니다. 회원가입/로그인, Passport 기반 OAuth, 앱 클라이언트 생성, 토큰 검증, 서비스 리소스 접근 권한 확인 흐름을 담당합니다.

단순 CRUD가 아니라 외부 서비스가 EventsPack 계정을 통해 인증을 위임받고, API 서버가 사용자 토큰과 리소스 접근 권한을 검증하는 구조를 구현했습니다.

EventsPack은 학술대회, 정부 행사, 기업 이벤트처럼 일정과 페이지 구성이 자주 바뀌는 행사 웹사이트를 빠르게 구축하기 위한 웹 솔루션입니다. 템플릿과 레이아웃 리소스를 기반으로 행사 사이트를 구성하고, 계정 인증과 OAuth 연동을 통해 여러 외부 사이트에서도 동일한 사용자 인증 흐름을 사용할 수 있도록 설계했습니다.

이 저장소는 그 웹 솔루션 중 사용자 인증과 API 연동 계층을 담당합니다. Laravel API는 회원/토큰/OAuth client/접근 권한을 처리하고, 프론트엔드 또는 외부 사이트 빌더는 이 API를 호출해 로그인 상태와 서비스 리소스 접근 가능 여부를 판단합니다.

## 주요 기능

- 회원가입 및 로그인 API
- Laravel Passport 기반 access token 발급
- OAuth client 생성/조회 프록시
- 외부 앱 로그인 상태 확인
- bearer token 기반 강제 로그인 처리
- 요청 컨트롤러/액션 기준 접근 권한 확인
- 사이트/레이아웃/내비게이션 정보 API 샘플
- 허용 Origin 기반 CORS 처리

## 기술 스택

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
- Laravel 6
- Laravel Passport
- Guzzle HTTP Client
- Laravel Mix

## 프로젝트 구조

```text
app/
├── Helper/                 # 메시지, 접근 권한 helper
├── Http/
│   ├── Controllers/Auth/    # 회원가입, 로그인, OAuth client API
│   └── Middleware/          # CORS, 강제 로그인, 권한 확인
├── Providers/
config/                     # Laravel 및 외부 서비스 설정
database/                   # migration, factory, seed
routes/
├── api.php                  # API endpoint
└── web.php                  # Passport/OAuth web route
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

## 주요 환경변수

| 변수 | 설명 |
| --- | --- |
| `APP_URL` | API 서버 URL |
| `DB_DATABASE` | MySQL 데이터베이스명 |
| `DB_USERNAME` | DB 계정 |
| `DB_PASSWORD` | DB 비밀번호 |
| `EVENTSPACK_API_BASE_URL` | OAuth/로그인 확인을 위임할 EventsPack API 기준 URL |
| `EVENTSPACK_ALLOWED_ORIGINS` | CORS 허용 Origin 목록. `|`로 구분 |

## 보완한 부분

- Laravel 실행에 필요한 `bootstrap/` 구조 복구
- `.gitignore`가 `bootstrap` 전체를 제외하던 문제 수정
- Composer/NPM 메타데이터를 프로젝트명 기준으로 정리
- PHP 요구 버전을 lock 파일과 맞게 `7.3+`로 정리
- CORS 허용 Origin을 `.env` 기반 설정으로 분리
- 외부 EventsPack API URL 하드코딩 제거
- `logoutRequest`의 잘못된 HTTP method 수정
- 회원가입 validation이 실패해도 생성 로직으로 진행될 수 있던 흐름 수정
- 라우트에서 호출되는 `register` 메서드 접근 제어자를 public으로 수정
- bearer 검증 실패나 사용자 미존재 시 401로 명확히 차단

## 관련 저장소

- 관리자: `php_laravel_EventPack_admin`
- 사용자 API: `php_laravel_EventsPack_dev1`
