### start all ###
startAll = docker-compose up -d; \
           docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction; \
           docker-compose exec php bin/console hautelook:fixtures:load --no-interaction; \
           docker-compose logs -f;
### start all ###

### dev database ###
generateDB = docker-compose exec php bin/console doctrine:database:drop --force --if-exists; \
			 docker-compose exec php bin/console doctrine:database:create; \
			 docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction;
### dev database ###

### lexik/jwt-authentication-bundle ###
generateJWT = docker-compose exec php bin/console lexik:jwt:generate-keypair --overwrite -n;
### lexik/jwt-authentication-bundle ###

### test database ###
generateTestsDB = docker-compose exec php bin/console --env=test doctrine:database:drop --force --if-exists; \
				  docker-compose exec php bin/console --env=test doctrine:database:create; \
				  docker-compose exec php bin/console --env=test doctrine:migrations:migrate --no-interaction;
### test database ###

help:
	@echo "MAKEFILE HELP :"
	@echo "\t - help: list all makefile commands"
	@echo "\t - start: start docker's containers, execute database migrations, execute fixtures, show docker's logs and stop docker containers on terminate signal"
	@echo "\t - stop: stop docker containers"
	@echo "\t - install: make a docker build"
	@echo "\t - kill-docker-builds: stop, kill and down docker's containers (remove JWT keys)"
	@echo "\t - new-db: make a new database with the new migrations"
	@echo "\t - jwt-keypair: regenerate JWT keys"
	@echo "\t - tests: launch all tests"
	@echo "\t - tests-security: launch security tests"
	@echo "\t - tests-api: launch api tests"
	@echo "\t - tests-command: launch command tests"
	@echo "\t - fixtures: launch fixtures"
	@echo "\t - fixtures-test: launch fixtures for tests"

start:
	@bash -c "trap 'trap - SIGINT SIGTERM ERR; $(MAKE) stop-all; exit 1' SIGINT SIGTERM ERR; $(startAll)"

stop-all:
	@docker-compose stop

install:
	@docker-compose build --pull --no-cache
	@cp -R -n api/.env api/.env.local

kill-docker-builds:
	@docker-compose stop
	@docker-compose kill
	@docker-compose down --volumes --remove-orphans
	@rm -rf api/config/jwt

new-db:
ifeq ($(shell docker-compose ps | wc -l),2)
	@docker-compose up -d
	@$(generateDB)
	@docker-compose stop
else
	@$(generateDB)
endif

jwt-keypair:
ifeq ($(shell docker-compose ps | wc -l),2)
	@docker-compose up -d
	@$(generateJWT)
	@docker-compose stop
else
	@$(generateJWT)
endif

tests:
ifeq ($(shell docker-compose ps | wc -l),2)
	@docker-compose up -d
	@$(generateTestsDB)
	@docker-compose exec php bin/console --env=test hautelook:fixtures:load --no-interaction
	@bash -c "trap 'trap - SIGINT SIGTERM ERR; docker-compose stop; exit 1' SIGINT SIGTERM ERR; docker-compose exec php bin/phpunit"
	@docker-compose stop
else
	@$(generateTestsDB)
	@docker-compose exec php bin/console --env=test hautelook:fixtures:load --no-interaction
	@docker-compose exec php bin/phpunit
endif

tests-security:
ifeq ($(shell docker-compose ps | wc -l),2)
	@docker-compose up -d
	@bash -c "trap 'trap - SIGINT SIGTERM ERR; docker-compose stop; exit 1' SIGINT SIGTERM ERR; docker-compose exec php bin/phpunit tests/Security"
	@docker-compose stop
else
	@docker-compose exec php bin/phpunit tests/Security
endif

tests-api:
ifeq ($(shell docker-compose ps | wc -l),2)
	@docker-compose up -d
	@$(generateTestsDB)
	@docker-compose exec php bin/console --env=test hautelook:fixtures:load --no-interaction
	@bash -c "trap 'trap - SIGINT SIGTERM ERR; docker-compose stop; exit 1' SIGINT SIGTERM ERR; docker-compose exec php bin/phpunit tests/Api"
	@docker-compose stop
else
	@$(generateTestsDB)
	@docker-compose exec php bin/console --env=test hautelook:fixtures:load --no-interaction
	@docker-compose exec php bin/phpunit tests/Api
endif

tests-command:
ifeq ($(shell docker-compose ps | wc -l),2)
	@docker-compose up -d
	@$(generateTestsDB)
	@docker-compose exec php bin/console --env=test hautelook:fixtures:load --no-interaction
	@bash -c "trap 'trap - SIGINT SIGTERM ERR; docker-compose stop; exit 1' SIGINT SIGTERM ERR; docker-compose exec php bin/phpunit tests/Command"
	@docker-compose stop
else
	@$(generateTestsDB)
	@docker-compose exec php bin/console --env=test hautelook:fixtures:load --no-interaction
	@docker-compose exec php bin/phpunit tests/Command
endif

fixtures:
	@docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction
	@docker-compose exec php bin/console hautelook:fixtures:load --no-interaction --purge-with-truncate

fixtures-test:
	@docker-compose exec php bin/console --env=test doctrine:migrations:migrate --no-interaction
	@docker-compose exec php bin/console --env=test hautelook:fixtures:load --no-interaction --purge-with-truncate
