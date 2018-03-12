.PHONY: install

install:
	php composer.phar install
	php composer.phar update
	php artisan migrate
	php artisan db:seed --class=PermissionsTablesSeeder
	php artisan db:seed --class=VotesTablesSeeder
	php artisan db:seed --class=DidYouKnowTableSeeder
