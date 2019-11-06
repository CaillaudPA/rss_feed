# rss_feed
Simple RSS reader web application

## OS currently used 
Ubuntu 19.04

## Start the application

The simplest way is to run the application with Symfony server

## Requirements

### Install PHP 7.2

PHP7.2 is the version used on this project

### Download Symfony

https://symfony.com/download

```bash
wget https://get.symfony.com/cli/installer -O - | bash
```

### Download Composer

https://getcomposer.org/download/

## Run the app

Go to the main folder

```bash
cd rss_feed/
```

Run composer install

```bash
composer install
```

Run the Symfony local web server

```bash
symfony server:start
```

Then you should have a message saying : Web server listening on http://127.0.0.1:8000

## Routes

There is 3 differents routes

http://127.0.0.1:8000 -> login with email / password
http://127.0.0.1:8000/register -> create new user
http://127.0.0.1:8000/rss_flux -> count the most used words in a rss feed view

