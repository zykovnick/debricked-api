start:
	docker-compose up -d

stop:
	docker-compose down

run-migrations:
	docker-compose exec php-fpm php bin/console doctrine:migrations:migrate

run-command:
	docker-compose exec php-fpm php bin/console DebrickedPinger

composer-install:
	docker-compose exec php-fpm composer install

run-tests:
	docker-compose exec php-fpm vendor/bin/phpunit
