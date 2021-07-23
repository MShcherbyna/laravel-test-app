# Laravel queue test app

## Getting Started

- Rename ".env.example" to ".env"
- Set access to DB
- Set API key for coinlayer, COINLAYER_KEY=f4e26cc05a5df699ba75218890cee30d
```bash
mv .env.example .env
```

- Install dependencies for app

```bash
composer install && composer update
```

- Create necessary tables

```bash
php artisan migrate
```

- Run The Queue Worker

```bash
php artisan queue:work
```

## Usage
Go to home page, select a token and date, then click "Save".

Then you should see the token rate in the coin_rates table on DB.

