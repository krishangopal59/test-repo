## Documentation 

Documentation for installing

Step 1: clone project from git hub
     git clone  https://github.com/krishangopal59/test-repo.git

Step 2 please run the composer update 

Step 3: create .env file inside the project.

Step 4: crate database (Test)

Step 5: Update .env file


    DB_DATABASE=**** 

    DB_USERNAME=****

    DB_PASSWORD=****

Step 6: Ran migration file 
    (a) php artisan migrate

    (b) php artisan db:seed –class=UserSeeder

    (c) php artisan jwt:secret

    (d) php artisan serve

Step 6  check Api  json file 

https://www.getpostman.com/collections/9b1f61010d7dc3484547