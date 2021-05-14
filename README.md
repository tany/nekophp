# Description
Mew...Mew!!

## Installation

```sh
## PHP
sudo add-apt-repository -y ppa:ondrej/php
sudo apt update
sudo apt install -y php8.0 \
  php8.0-dev \
  php8.0-fpm \
  php8.0-apcu \
  php8.0-opcache \
  php8.0-mbstring \
  php8.0-xml

## FPM
sudo systemctl start php8.0-fpm
sudo systemctl enable php8.0-fpm

## Source
mkdir /var/www/neko && cd $_
git clone -b master git@github.com:tany/nekophp.git .

## Nginx
sudo ln -s /var/www/neko/conf/server/nginx.conf /etc/nginx/conf.d/neko.conf
sudo nginx -t
sudo systemctl restart nginx
```

## Development (Assets)

```sh
## npm
sudo apt install -y nodejs npm
npm install

npm run watch # development
npm run build # deployment
```

## Test

```sh
bin/test code       # Code Checker
bin/test benchmark  # PHP Benchmark
```

## Production

```sh
cp conf/examples/settings.php conf
rm tmp/cache.lock
```
