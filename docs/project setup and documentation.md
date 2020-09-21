# Project Setup and Documentation

This document outlines how to get this web app up and running, and how to maintain the app.

* [Operating System Setup](#os)
* [MySQL Setup](#mysql)
* [Installation of Web App](#installation)
* [Environment Variables Setup](#env)
* [Commands](#commands)

## <a name='os'></a> Operating System Setup

This project was developed on Ubuntu Linux version 19.10. The recommended operating system for deployment is the latest stable release of Ubuntu Linux or Ubuntu Server, however any system capable of deploying PHP web apps should suffice. This guide will only cover setup for Debian based Linux systems (such as Ubuntu).
* Simply download the latest stable [Ubuntu ISO](https://ubuntu.com/download) and install it on the target machine.

## <a name='mysql'></a> MySQL Setup

This project was developed using mySQL version 8.0.19, however current supported database drivers include:

* mySQL
* SQL Server
* SQLite
* Postgre SQL

To change the database driver, see [Environment Variables Setup](#env).

This guide will cover mySQL setup, however the process will be similar across drivers.

#### The following steps are performed via the Ubuntu Terminal
###### Installing mySQL

1. Open terminal, `sudo apt install mysql-server`

###### Changing Default mySQL Password

2. `sudo service mysql stop`
3. `sudo mkdir /var/run/mysqld`
4. `sudo chown mysql: /var/run/mysqld`
5. `sudo mysqld_safe --skip-grant-tables --skip-networking`
6. In a new terminal window: `mysql -uroot mysql`
7. `FLUSH PRIVILEGES;`

##### For mySQL < v8.0, follow step 8. otherwise follow step 9.
8. `UPDATE user SET authentication_string=PASSWORD('<your new password>'), plugin='mysql_native_password' WHERE User='root' AND Host='localhost';`
9. `ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '<your new password>';`
10. `EXIT;`
11. Restart computer.

###### Create Database for Inventory Assets
12. ```mysql -u root -p```
13. Type your database password
14. ```create database nuf_inventory;```


## <a name='installation'></a> Installation of Web App

This web app is made in PHP version 7.3.11 using the [Laravel (version 6)](https://laravel.com/) MVC framework, and uses [Composer](https://getcomposer.org/) and [Node Package Manager (NPM)](https://www.npmjs.com/) to manage packages.

* **Copy or download the project folder to the target system, extract if necessary.**

#### The following steps are performed via the Ubuntu Terminal
###### Installing Composer, NPM and PHP
1. `sudo apt install composer`
2. `sudo apt install curl php7.3 php7.3-curl php7.3-json  php7.3-mysql php7.3-xml php7.3-zip php7.3-mbstring`
3. `composer global require laravel/installer`
4. `export PATH="$HOME/.config/composer/vendor/bin:$PATH"`
5. `sudo apt install npm`
6. `cd /path/to/project/folder`
7. `npm install`
8. `composer install`

###### Creating an Environment Variables File and Encryption Key
1. `cd /path/to/project/folder`
2. `cp .env.example .env`
3. `php artisan key:generate`

###### Initializing the Database for the Web App
1. `cd /path/to/project/folder`
2. `php artisan migrate:fresh; php artisan db:install`

###### Setting up Cron Jobs to Run
1. `crontab -e`
2. Scroll to bottom
3. Type `* * * * * php /path/to/project/folder/artisan schedule:run >> /path/to/project/folder/storage/logs/cron.log 2>&1`
4. Hit Ctrl+X, then press enter

## <a name='env'></a> Environment Variables Setup

The environment variables file (.env) created in the last step of [Installation of Web App](#installation) holds information like the database name and, driver, the app's name, SMTP information for emailing, and more. *Sensitive data is stored here like passwords.* The folder is hidden, is ignored by the git repository and cannot be accessed by anyone besides the maintainer of the server.

#### The following are the various settings of interest in /path/to/project/folder/.env that should be configured
###### App Settings
* `APP_NAME` The name of the app (enclose in double quotes)
* `APP_ENV` Deployment type, `local` or `production`
* `APP_KEY` Encryption key
* `APP_DEBUG` Debug mode, `false` for production, `true` for testing
* `APP_URL` Web URL of the app

###### DB Settings
* `DB_CONNECTION` The database driver: `mysql`, `sqlsrv`, `sqlite`, or `pgsql`
* `DB_HOST` IP address of the database server
* `DB_PORT` Port of the database server (default: 3306)
* `DB_DATABASE` Name of database to access
* `DB_USERNAME` Username to access database
* `DB_PASSWORD` Password to access database

###### Email Settings
* `MAIL_DRIVER` Email driver (default: smtp)
* `MAIL_HOST` SMTP server address
* `MAIL_PORT` Email port
* `MAIL_USERNAME` Username to access email server
* `MAIL_PASSWORD` Password to access email server
* `MAIL_ENCRYPTION` Encryption type
* `MAIL_FROM_ADDRESS` Address emails should come from
* `MAIL_FROM_NAME` Name of the sender your emails should come from

> Remember to put double quotes around 'MAIL_PASSWORD' and 'MAIL_FROM_NAME'


## <a name='commands'></a> Commands

Here are some helpful commands and shortcuts that may prove useful:

#### Starting the Web App
###### Set up shortcuts, only must be done once
1. In terminal, `sudo nano ~/.bashrc`
2. Scroll to the bottom, add following two lines:
* `alias serve-nuf='cd /path/to/project/folder; php artisan serve;'`
* `alias serve-nuf-apache='cd /path/to/project/folder; php artisan serve --host 0.0.0.0;'`
3. Hit Ctrl+X, then press enter

Now the following commands will be available to you from anywhere in the command line:
* `serve-nuf` will start the web app for testing
* `serve-nuf-apache` will start the web app for production

#### Database Commmands (Inside the project folder)
* `php artisan tinker` will open an interactive terminal to view and edit the database live, among other things
* `php artisan migrate:fresh; php artisan db:install` *will wipe the database* and reinstall it, **this can not be undone**
* `php artisan db:backup` will run a backup of the database and save it to /path/to/project/folder/storage/backups
* `php artisan db:recover /path/to/project/folder/storage/backups/<backup name>.sql` will recover the database using the provided backup

#### Compiling Front-End Changes
If changes are made to the sass (css) or javascript files, you must run the following commands for the changes to take effect:
1. `cd /path/to/project/folder`
2. `npm run development` or `npm run production`
3. Wait for the command to finish.
