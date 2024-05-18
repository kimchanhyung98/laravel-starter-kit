# laravel starter kit

필요한 최소한의 기능을 구현해놓은 템플릿입니다.  
[Sail](https://laravel.com/docs/10.x/sail)을 사용하여 실행할 수 있습니다.

- PHP 8.3 & Laravel 11
- Redis
- AWS ECR, EB, S3
- [next-starter-kit](https://github.com/LeeByeongMuk/next-starter-kit)

## Install

```bash
brew install php@8.3 composer
composer install
sail up # or ./vendor/bin/sail up 
sail artisan migrate --seed
sail artisan scout:import "App\Models\Post"
```

## OpenAPI

Scramble - Laravel OpenAPI(Swagger) Generator 를 사용하여 API 문서를 확인할 수 있습니다.
- localhost/docs/api
