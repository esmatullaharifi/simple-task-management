## Coalition Technologies - Laravel Developer

----------
Developer/Author: Esmatullah Arifi (<a href="mailto:esmatullaharifi@gmail.com">esmatullaharifi@gmail.com</a> | <a href="fb.com/esmatullaharifi.official">Facebook</a>)

# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/5.4/installation#installation)

Switch to the repo folder

    cd coalition-test

Install all the dependencies using composer

    composer install

Install all the dependencies using npm

    npm install && npm run prod

Create a database in MySQL with proper collation (e.g. <code>utf8_general_ci</code>)

Copy the example env file and make the required configuration changes (database connection setting) in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Run the database migration to create the required tables in the database

    php artisan migrate

## Technologies Used

For development of this web application I used the following technologies:
<ol>
    <li>Laravel 8</li>
    <li>Laravel Breeze (For Authentication)</li>
    <li>Alpine.js</li>
    <li>Tailwind CSS</li>
    <li>SortableJS</li>
</ol>
