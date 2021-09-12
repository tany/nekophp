# Description
Mew...Mew!!

## Installation

```sh
## PHP
sudo add-apt-repository -y ppa:ondrej/php
sudo apt update
sudo apt install -y php8.0 \
  php8.0-apcu \
  php8.0-curl \
  php8.0-dev \
  php8.0-fpm \
  php8.0-mbstring \
  php8.0-opcache \
  php8.0-xml

## FPM
sudo systemctl start php8.0-fpm
sudo systemctl enable php8.0-fpm

## Source
mkdir /var/www/neko && cd $_
git clone -b master git@github.com:tany/nekophp.git .
chmod -R 777 log tmp

## Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer install --no-dev

## Nginx
sudo ln -s /var/www/neko/conf/server/nginx.conf /etc/nginx/conf.d/neko.conf
sudo nginx -t
sudo systemctl restart nginx
```

### Settings

```sh
sudo vi /etc/nginx/conf.d/neko.conf

cp conf/examples/settings.php conf/
vi conf/settings.php
```

## Development

### Assets

```sh
sudo apt install -y nodejs npm
npm install

npm run watch      # Development
npm run build      # Deployment
npm run stylelint  # Stylelint
```

### Test

```sh
VER=`wget -q -O - https://chromedriver.storage.googleapis.com/LATEST_RELEASE`
wget https://chromedriver.storage.googleapis.com/$VER/chromedriver_linux64.zip
sudo unzip chromedriver_linux64.zip -d /usr/local/bin/
rm chromedriver_linux64.zip

bin/test   # PHPUnit
bin/phpcs  # PHP Code Sniffer
bin/phpmd  # PHP Mess Detector
```

## Release Notes

- 0.0.2 Elasticsearch index
- 0.0.1 MongoDB
