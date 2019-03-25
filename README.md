irblog symfony3.4
======
How to run app
- 
1) `git clone https://github.com/Kalyskin/irblog.git`
2) `cd irblog/`
3) `unzip vendor.zip`
4) Set mysql credentials here `app/config/parameters.yml`
5) Create database `php bin/console doctrine:database:create`
6) Create tables `php bin/console doctrine:schema:update --force`
7) Create admin user and first post and comment`php bin/console doctrine:fixtures:load`
8) Run server `php bin/console server:run`
9) open `http://127.0.0.1:8000`

<hr/>

 admin email: `admin@example.com`<br/>
 admin pass: `admin`

Api<br/>
*posts*<br>
 GET `/api/posts`<br>
 POST `/api/post/`<br>
 PUT `/api/post/{id}`<br>
 DELETE `/api/post/{id}`<br>
<br/>
*comments*<br>
 GET `/api/comments/{postId}`<br>
 POST `/api/comment/{postId}`<br>
 PUT `/api/comment/{id}`<br>
 DELETE `/api/comment/{id}`<br>
<br/><br/>
