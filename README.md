irblog
======
1) `unzip vendor.zip`
2) Set mysql credentials here `app/config/parameters.yml`
3) Create database `php bin/console doctrine:database:create`
4) Create tables `php bin/console doctrine:schema:update --force`
5) Create admin user and first post and comment`php bin/console doctrine:fixtures:load`
6) Run server `php bin/console server:run`

<hr>

 admin email: `admin@example.com`<br>
 admin pass: `admin`