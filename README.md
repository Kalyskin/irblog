irblog
======
1) `unzip vendor.zip`
2) Set mysql credentials here `app/config/parameters.yml`
3) Create database `php bin/console doctrine:database:create`
4) Create tables `php bin/console doctrine:schema:update --force`
5) Create admin user and first post and comment`php bin/console doctrine:fixtures:load`
6) Run server `php bin/console server:run`

<hr/>

 admin email: `admin@example.com`<br/>
 admin pass: `admin`
 
<hr>
Api<br/>
*posts*<br>
 GET `/api/posts`<br>
 POST `/api/post/`<br>
 PUT `/api/post/{id}`<br>
 DELETE `/api/post/{id}`<br>
<br/>
*comments*<br>
 GET `/api/comments/{postId}`<br>
 POST `/api/comment/`<br>
 PUT `/api/comment/{id}`<br>
 DELETE `/api/comment/{id}`<br>
<br/><br/>
