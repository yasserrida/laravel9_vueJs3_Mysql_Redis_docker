<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## Project setup

```
composer install
```
```
cp .env.local .env
```
```
php artisan migrate:fresh --seed
```
```
php artisan key:generate
```
```
php artisan passport:install
```

## Setup laravel echo server

```
npm install -g laravel-echo-server
```

## Run for developpement
```
php artisan serve
```
```
npx laravel-echo-server start
```
```
php artisan queue:work --timeout=0 --tries=1
```

## Api Client
### Get Token
    POST: /api/oauth/token
    Header: {
        grant_type: 'client_credentials',
        client_id: '',
        client_secret: ''
    }

### Send Request
    POST: /api/v1/[]
    Header: {
        Accept: 'application/json',
        Content-Type: 'application/json',
        Authorization: 'Bearer []'
    }

