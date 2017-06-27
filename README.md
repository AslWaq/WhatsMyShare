# WhatsMyShare
#### A stock market trading web application/game
- [Waqaas Aslam](https://github.com/AslWaq)
- [Mabior Deng Mabior](https://github.com/mabiorm)

Required for project deployment
----------------
- [Composer](https://getcomposer.org/download/)
- [Laravel](https://laravel.com/)
- [php](http://php.net/downloads.php)
- [mysql](http://dev.mysql.com/downloads/)   

Install
----------------
1. go to the src directory in the repository
2. vim/emacs .env (edit the following lines or if .env.example file is included in project folder in src simply rename the file to .env)
  <pre>
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=wms_db
   DB_USERNAME='insert mysql username'{default is root}
   DB_PASSWORD='insert mysql password'{default is blank}  
  </pre>
3. enter in terminal (enter mysql username in place of root if different)
```
   mysql -u root -p
   create database wms_db;
   exit
```
4. type `composer update` in terminal to install composer dependencies
4. type `sh buildnrun.sh` in terminal to build project(use only once, after this type `sh run.sh` to start application again or run the following commands everytime you would like to start the server:
```
  php artisan schedule:run
  php artisan serve
```
 )
5. connect to http://localhost:8000/ in the browser
6. login in with (ex:user name: "bman@gmail.com", password: "test12")
> To view test facebook usernames and passwords for system, go to /facebooktestaccounts.txt in project folder
