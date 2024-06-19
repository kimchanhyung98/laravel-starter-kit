# laravel starter kit

PHP 8.3 & Laravel 11.x를 사용한 최소 기능(User, Post, Comment, Like)을 구현한 템플릿입니다.

## Install

[Sail](https://laravel.com/docs/10.x/sail)을 사용하여 실행할 수 있습니다.
(배포는 AWS ECR, EB를 사용합니다)

```bash
brew install php@8.3 composer && composer install
cp .env.example .env && php artisan key:generate && php artisan key:generate --env=testing

./vendor/bin/sail up -d  # 최초 실행시 약 10분 소요
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail artisan scout:import "App\Models\Post"
# ./vendor/bin/sail artisan test
```

## OpenAPI

Scramble - Laravel OpenAPI(Swagger) Generator 를 사용하여 API 문서를 확인할 수 있습니다.

- [localhost/docs/api](http://localhost/docs/api)

### FE

- [next-starter-kit](https://github.com/LeeByeongMuk/next-starter-kit)
