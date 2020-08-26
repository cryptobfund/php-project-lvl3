
setup:
	composer install
	cp -n .env.example .env|| true
	php artisan key:gen --ansi
	php artisan migrate
	#touch database/database.sqlite

lint:
	composer phpcs

lint-fix:
	composer phpcbf

test:
	php artisan test

test-coverage:
	composer phpunit -- tests --whitelist tests --coverage-clover coverage-report

deploy:
	git push heroku

migrate:
	php artisan migrate

console:
	php artisan tinker


log:
	tail -f storage/logs/laravel.log

