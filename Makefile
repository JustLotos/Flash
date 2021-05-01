# BASE
COMPOSE=docker-compose
PHP=$(COMPOSE) exec -T php
CONSOLE=$(PHP) bin/console
COMPOSER=$(PHP) composer
# BASE

up: docker-up v-dev
down: docker-down

update: docker-down docker-pull docker-build docker-up composer-update v-dev
install: docker-pull docker-build docker-up composer-update-fix lexik-jwt-install yarn-install first_install_db
first_install_db: create_db migdiff migrate fixtload create_db_test migrate_test fixtload_test

install_db: create_db migrate fixtload
reset_db: drop_db create_db migrate migrate fixtload
update_db: migdiff migrate fixtload

reset_db_test: drop_db_test create_db_test migrate_test fixtload_test
idbt: create_db_test migrate_test fixtload_test
udbt: migrate_test fixtload_test
#DOCKER-COMPOSE

docker-up:
	@${COMPOSE} up -d

docker-down:
	@${COMPOSE} down --remove-orphans

docker-pull:
	@${COMPOSE} pull

docker-build:
	@${COMPOSE} build
#DOCKER-COMPOSE

#COMPOSER START
composer-update:
	@${COMPOSER} update
composer-update-fix:
	@${PHP} sudo composer self-update 1.10.12
	@${PHP} sudo php -d memory_limit=-1 /usr/local/bin/composer install
	@${COMPOSER} update --no-scripts
composer-install:
	@${COMPOSER} install

#COMPOSER END

#DATABASE
	#DEV
create_db:
	@${CONSOLE} doctrine:database:create

drop_db:
	@${CONSOLE} doctrine:database:drop --force

migration:
	@${CONSOLE} make:migration --no-interaction

migrate:
	@${CONSOLE} doctrine:migrations:migrate --no-interaction

migdiff:
	@${CONSOLE} doctrine:migrations:diff --no-interaction

fixtload:
	@${CONSOLE} doctrine:fixtures:load --no-interaction

	#TEST
create_db_test:
	@${CONSOLE} doctrine:database:create --env=test --no-interaction
drop_db_test:
	@${CONSOLE} doctrine:database:drop --force --env=test --no-interaction
migrate_test:
	@${CONSOLE} doctrine:migrations:migrate --env=test --no-interaction
fixtload_test:
	@${CONSOLE} doctrine:fixtures:load --env=test --no-interaction
#DATABASE

#FRONT
yarn-install:
	@${COMPOSE} run --user root node mkdir -p /yarn
	@${COMPOSE} run --user root node chmod -R 777 /yarn
	@${COMPOSE} run node yarn install
	@${COMPOSE} run node yarn add @babel/compat-data
	@${COMPOSE} run node yarn add @babel/preset-env
yarn-upgrade:
	@${COMPOSE} run node yarn upgrade

v-dev:
	@${COMPOSE} run node yarn encore dev --watch
v-prod:
	@${COMPOSE} run node yarn encore production
#FRONT


#OTHER
routes:
	@${CONSOLE} debug:router

phpunit:
	@${PHP} bin/phpunit ./tests/Unit

lexik-jwt-install:
	mkdir -p config/jwt
	openssl genpkey -out config/jwt/private.pem -aes256 -pass pass:root  -algorithm rsa -pkeyopt rsa_keygen_bits:4096
	openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout -passin pass:root
