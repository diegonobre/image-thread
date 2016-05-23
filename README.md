image-thread
============

Welcome to ImageThread.

# Installing
```sh
# create database
CREATE DATABASE image_thread;
# create user
CREATE USER 'usr_img_thread'@'localhost' IDENTIFIED BY '#insided@16';
# set privileges
GRANT ALL PRIVILEGES ON image_thread.* TO 'usr_img_thread'@'localhost' IDENTIFIED BY '#insided@16';
flush privileges;

git clone https://diegocnobre@bitbucket.org/diegocnobre/image-thread.git
cd image-thread
composer install

php bin/console doctrine:schema:update --force

INSERT INTO analytics (view_counter) VALUES (0);

npm install gulp
npm update
npm install
gulp
```
