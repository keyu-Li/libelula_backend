# This is backend of libelula
## How to run

```bash
composer install
cp .env.example .env
# config db host and password
php artisan key:generate
# import database
php artisan migrate
php artisan db:seed
# generate jwt
php artisan jwt:secret
# run
php artisan serve
```

