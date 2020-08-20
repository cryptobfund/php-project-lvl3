
install:
	composer install

lint:
	composer run-script phpcs -- --standard=PSR12 tests

lint-fix:
	composer phpcbf

test:
	php artisan test

test-coverage:
	composer test -- --coverage-clover build/logs/clover.xml

deploy:
	git push heroku

migrate:
	php artisan migrate

console:
	php artisan tinker


log:
	tail -f storage/logs/laravel.lo
