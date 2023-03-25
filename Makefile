#!make
MAKEFLAGS += --silent

DC = docker-compose

include backend/.env

install:
	$(DC) run --rm backend bash -c "composer install; php artisan migrate:fresh; php artisan key:generate; php artisan storage:link; php artisan db:seed;"

start:
	$(DC) up

stop:
	$(DC) down
	docker-sync stop

docker:
	$(DC) $(ARGS)

phpunit:
	$(DC) exec backend bash -c "php artisan test"

phpmd:
	$(DC) exec backend bash -c "./vendor/bin/phpmd app html phpmd.xml --reportfile storage/docs/phpmd/phpmd.html --excludes tests,Config,Database,Tests"

phpcs:
	$(DC) exec backend bash -c "./vendor/bin/phpcs --standard=psr2 --extensions=php app"

phpcs-fix:
	$(DC) exec backend bash -c "./vendor/bin/phpcbf --standard=psr2 --extensions=php app"

phpcsfixer:
	$(DC) exec backend bash -c "./vendor/bin/php-cs-fixer fix"

fix:
	$(DC) exec backend bash -c "./vendor/bin/phpcbf --standard=psr2 --extensions=php app; ./vendor/bin/php-cs-fixer fix"
