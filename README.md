It's not particularly tidy but you should be able to download the code and run the migrations.

Create an empty mysql database and update the details in the .env file

In a command line change to the base directory of the project then run the following

composer install
php artisan migrate
php artisan db:seed
php artisan route:cache

Intially I installed Laravel Breeze with this build. However, for ease of testing I've removed all requirements to login.

If you look around you will find models, controllers, resource files, request files, migration files, factory files and a bunch of the vue files amongst other things.

I hope it all makes sense.

kind regards,
Ron
