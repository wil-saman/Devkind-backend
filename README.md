# Devkind Backend

This project aims to create an authentication system that allows users to register and log in to their accounts.

## Tech Stack

Laravel Sanctum

## Requirements

- PHP
- Composer
- SQLite Browser

## How to run

- clone the project using 'git clone https://github.com/wil-saman/Devkind-backend.git'
- open the command line inside the project or run 'cd Devkind-backend'
- navigate to php.ini which is located at your PHP installation folder. uncomment the required extensions. some notable extensions are zip, sqlite3, pdo_sqlite, and fileinfo.
- run 'composer install' to install all required dependencies
- run 'cp .env.example .env'
- run 'php artisan key:generate'
- open .env file and replace the value for DB_CONNECTION to "sqlite" (in line 11)
- remove "DB_DATABASE", "DB_USERNAME", and "DB_PASSWORD" from the .env file (line 14,15 and 16 respectively)
- run 'php artisan migrate' (If you got an error here, it is most likely that you have not uncommented the required extensions)
- enter "yes" on the prompt "would you like to create it?"
- run 'php artisan serve'
- run 'composer artisan serve' to start the server

## Things that have been done in the project

- Learning basics of laravel - 5 hours
- Setting up the project - 1 hour
- Worked on "Changelog" model - 1 hour
- Worked on "Changelog" api route and controllers - 30 mins
- Worked on auth controllers and protected routes using laravel sanctum - 3 hour
- Worked on logout controller - 2 hour
- Worked on update password and update data - 4 hours


