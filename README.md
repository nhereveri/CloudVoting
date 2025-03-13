# CloudVoting

## Installation

```sh
git clone https://github.com/nhereveri/CloudVoting.git
cd CloudVoting
composer install
npm install
npm run build
cp .env.example .env
# edit .env file
php artisan storage:link --relative
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## Production Optimization

```sh
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache
```

## Development

```sh
php artisan optimize:clear
```