
Requirements:

```
php >=7.2.5
mysql
mongodb
```

Verify `composer.json` for more info.

Clone repository and run `composer install`
```
git clone <repository> promobit &&
cd promobit &&
composer install
```

copy env.example to env
```
cp .env.example .env
```

then edit credentials of database and redis in `.env` file

run command:
```
php bin/console doctrine:database:create
```

Run migrations:

```
php bin/console doctrine:migrations:migrate
```

Run fixtures:

```
php bin/console doctrine:fixtures:load
```

Now you can access application with default user, check credentials in fixture user file.

Run queue:
```
php bin/console messenger:consume -vv
```

Project tree:

- bin
- config - config files
- migrations - database migrations
- public - public files
- src
    - DataFixtures - database seeds
    - Entity - models
    - Helper - helpers, eq: uuid
    - Http
        - Controllers - controllers
        - Middlewares - request middlewares
        - Requests - request input contract validators
        - Resources - request response contract: not implemented
    - Job - Jobs for queue
    - Notification - Notification for jobs
    - Repository - repository layer, access and storage data
    - Security - auth service layer, provide authentication and authorization
    - Service - service layer
- Translations - translations: not implemented