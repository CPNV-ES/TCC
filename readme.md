# TTC

## Getting started

1. To install homestead follow the instructions on their website [Laravel Homestead](https://laravel.com/docs/5.3/homestead).
2. Download the zip from the github repository [TTC](https://github.com/CPNV-ES/TCC). Unzip the content into the syncronised folder for homestead.
3. Connect to homestead
4. Navigate to the projects folder
5. Execute the commande `composer install`
5. If you already have an instance of the database, drop all tables and make sure the credentials in config/database.php are correct
6. Execute the commande `php artisan migrate --seed`
7. Copie the `.env.exemple`, rename it to `.env` and modify the necessary settings.
8. Open your navigator of choise and enter the URL for the site (e.g. : 127.0.0.1:8000). If the site shows up good if not try again (.env, commandes).


## SMTP Settings for a gmail account

1. Modify `.env` as follows :
```
MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=cpnv.tcc@gmail.com
MAIL_PASSWORD=****
MAIL_ENCRYPTION=tls
```

2. Edit security settings for your google account. Go to [Gmail Settings](https://www.google.com/settings/security/lesssecureapps)
3. Thats done.



<!-- Previous readme -->
## Laravel PHP Framework

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/d/total.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, queueing, and caching.

Laravel is accessible, yet powerful, providing powerful tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

## Official Documentation

Documentation for the framework can be found on the [Laravel website](http://laravel.com/docs).

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

### License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
