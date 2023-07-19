
# Kasino with PHP

A simple casino developed in PHP. It currently has only one game. I plan to add more games for learning purposes


## Demo

You can try the DEMO through the link below
[DEMO](https://emleons.online/casino/games/)


## TODO

- Adding more games
- Adding animations



## Installation

Create database 'games' then import game.sql the open .env file change it accordingly

```env
#data base connections
DB_HOST=localhost
DB_USER=emleons
DB_PASS=abcd
DB_NAME=games
```


## Encryption

Create 1024 RSA key pairs then go to the 'games' database and then put your public key and private key in the 'encrypt' table

## Set up OTP
Navigate to the 'src' folder and then enter the SecurLogin.php file in the NextSms() method, edit as follows 

```php
$user = "NextSms User name";//your nextsms user name
$pass = "++nf+rfOC8YJj2hGwOH/=";//your nextsms password
```
you are done move the project dir to your host open in the browser

'localhost/path_to_this_project'
## Feedback

If you have any feedback, please reach out to me at emleons23@gmail.com



[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/)

