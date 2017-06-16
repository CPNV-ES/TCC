# TTC

## Getting started
These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. If during the installation, you come up any problems, we have a section at the end of the readme that may help you.

## Installation

1. If you don\'t have homestead installed, follow the instructions on their website [Laravel Homestead](https://laravel.com/docs/5.3/homestead).
2. You need to clone the project onto your local machine in the syncronised folder for homestead.  
Download the zip from the github repository [TTC](https://github.com/CPNV-ES/TCC) or use by using the git command.
    ```git
     $ https://github.com/CPNV-ES/TCC.git
    ```
3. Start and connect to homestead
4. Navigate to the projects folder
5. Execute the commande `composer install`
6. If you already have an instance of the database, drop all tables and make sure the credentials in config/database.php are correct.  
Add the DB name in your Homestead.yaml file. Then provision your VM by running the following commande.
    ```
    $ vagrant provision
    ```
7. Copie the `.env.exemple`, rename it to `.env` and modify the necessary settings, i.e: the DB_* params. (More defails for SMTP in next point)
8. Execute the commande `php artisan key:generate` and `php artisan migrate --seed`
9. Open your navigator of choise and enter the URL for the site (e.g. : 127.0.0.1:8000). If the site shows up good if not try again (.env, commandes).  
The seeding process has created a user "admin".

### SMTP Settings for a gmail account

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
3. That\'s done.

## Passwords
For the passwords please ask your project manager for them.

## Possible problems
### Homestead
If you've used homestead, it might redirect you to the wrong site, so you'll need to exectute the following command :

```
$ vagrant provision
```

### Composer
When you try to do a `composer install` you might need to activate the `mbstring` extensionin your php.ini file

### DB problems
If you run into an error "Class XYZ not found" after seeding the DB, execute the following command :

```
$ composer dump-autoload
```

## Project documentation
You can find all the documentation here : `/docs`
