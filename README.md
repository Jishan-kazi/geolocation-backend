create .env file manually
create database & specify in .env file
add IPGEOLOCATION_API_KEY env variable in .env file, if not added then it will use default api key(which I have generated)
run the following commands
composer i
php artisan key:generate
npm i
php artisan migrate
php artisan serve
