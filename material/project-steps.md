# STEPS FOR CREATE NEW LARAVEL PROJECT

1. create project laravel

    composer create-project laravel/laravel php_m15 --prefer-dist

2. composer install
3. artisan key generate
    php artisan key:generate
4. npm install
    npm install
4. config .env

    project name 
        
        APP_NAME="PHP M15"
    
    database

        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=php_m15
        DB_USERNAME=root
        DB_PASSWORD=    
    
- Execute migrations
    php artisan migrate

5. Database elements
    - migrations
        php artisan make:migration create_table_xxxxs
    - factories
    - seeders
6. Models
7. Controllers
8. Routes
9. Views
    - Home / Welcome
