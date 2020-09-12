.Phony: start
start:
	docker-compose up -d
	docker-compose exec -T php sh ./.docker/wait_for_nginx.sh

.Phony: php
php:
	docker-compose exec php bash

.Phony: env
env:
	cp .env.dist .env

.Phony: db
db:
	docker-compose exec php ./bin/console d:d:c --if-not-exists
	docker-compose exec php ./bin/console d:m:m -n

.Phony: stop
stop:
	docker-compose stop

.Phony: cs_fix
cs_fix:
	docker-compose exec php bin/php-cs-fixer fix --allow-risky=yes

.Phony: mutation
mutation:
	docker-compose exec php bin/infection.phar

.Phony: test
test:
	docker-compose exec php bin/phpunit


.Phony: phpstan
phpstan:
	docker-compose exec php bin/phpstan.phar analyse

.Phony: psalm
psalm:
	docker-compose exec php bin/psalm.phar