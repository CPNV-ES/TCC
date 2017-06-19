# TTC

## Getting started
These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. If during the installation, you come up with any problems, we have a section at the end of the readme that may help you.

## Installation/Configuration of Homestead

1. If you don\'t have homestead installed, follow the instructions on their website [Laravel Homestead](https://laravel.com/docs/5.3/homestead). Don't use the command `vagrant box add laravel/homestead`, clone the project from their github.
2. You need to clone the project onto your local machine in the syncronised folder for homestead.  
Download the zip from the github repository [TTC](https://github.com/CPNV-ES/TCC) or use by using the git command.
    ```git
     $ git clone https://github.com/CPNV-ES/TCC.git
    ```
3. Start and connect to homestead  

If you already have an instance of the database, drop all tables and make sure the credentials in config/database.php are correct.  
Add the DB name in your Homestead.yaml file. Then provision your VM by running the following command.
    ```
    $ vagrant provision
    ```
    
## Intallation of the project

1. Navigate to the projects folder
2. Execute the command `composer install`
3. Copy the `.env.exemple`, rename it to `.env` and modify the necessary settings, i.e: the DB_* params. (More details for SMTP in next point)
4. Execute the command `php artisan key:generate` and `php artisan migrate --seed`
5. Open your navigator of choice and enter the URL for the site (e.g. : 127.0.0.1:8000). If the site shows up good if not try again (.env, commands).  
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
For the passwords, please ask your project manager.

## Database script
We write a script to ease the reset of the database and execution of the seeds. it's called `resetdb.sh` and is at the root of the project. If you want to use it, fallow these steps:

1. Edit the file and change the variables username='DB_USER', password='DB_PASSWORD', db='DB_NAME' then save it 
2. If needed, go on your homestead box and make a `sudo chmod u+x resetdb.sh`
3. Now, you can execute the script with `./resetdb.sh <DB_NAME>`

If the script throws the error unexpected end of file, you've to change the EOF to LF


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
## Description
TCC is a WebApp developed for the Tennis Club of Chavornay to manage the reservations and the membership fee.

It has been developed under the MAW1.2 module.

## Actors
The different actors of the website are:
- Member - simple member of the club who pays membership fee
- Staff - is apart of the club crew. he can make multiple reservations (For example for junior training)
- Administrator - manage the users, seasons, courts and configuration of the site
- Non-member - is the person who doesn't pay membership fee
- Guest - is non-member which are invited by a member.


## Features
- Simple reservation
- Simple / multiple reservation as staff      
- Manage the site(add court, season, change start/end hour for court etc.)
- Subscription to the club


## Improvements
- Develop a system of pyramid to know the best player of the club
- Use AJAX


## Compatibility
As ask by the client we\'ve tried to keep our site compatible with 3 of the most used Web Browsers:
- Firefox (tested with v.53.0.3, 32bits)
- Chrome (tested with v.58.0.3029.110, 64bits)
- Internet Explorer(tested from v.10 to Edge)


## Development environment
To develop this app we used the vagrant box Homestead-7(2.1.0) with these specifications:
- Nginx 1.11.5
- MySql v5.7.16
- Php v7.0.13

## Project documentation
You can find all the documentations here : `/docs`
