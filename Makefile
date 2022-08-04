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


start:
	bash -c "trap 'trap - SIGINT SIGTERM ERR; $(MAKE) stop-all; exit 1' SIGINT SIGTERM ERR; $(MAKE) start-all"

start-all:
	docker-compose up -d
	docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction
	docker-compose exec php bin/console hautelook:fixtures:load --no-interaction
	sleep 5
	cd admin/ && yarn start

stop-all:
	docker-compose stop

install:
	docker-compose build --pull --no-cache
	cp -R -n api/.env api/.env.local
	cd admin/ && yarn install

kill-docker-builds:
	docker-compose stop
	docker-compose kill
	docker-compose down --volumes --remove-orphans
	rmdir api/config/jwt

new-db:
ifeq ($(shell docker-compose ps | wc -l),2)
	docker-compose up -d
	$(generateDB)
	docker-compose stop
else
	$(generateDB)
endif

jwt-keypair:
ifeq ($(shell docker-compose ps | wc -l),2)
	docker-compose up -d
	$(generateJWT)
	docker-compose stop
else
	$(generateJWT)
endif

tests:
ifeq ($(shell docker-compose ps | wc -l),2)
	docker-compose up -d
	bash -c "trap 'trap - SIGINT SIGTERM ERR; docker-compose stop; exit 1' SIGINT SIGTERM ERR; $(MAKE) tests-security"
	bash -c "trap 'trap - SIGINT SIGTERM ERR; docker-compose stop; exit 1' SIGINT SIGTERM ERR; $(MAKE) tests-api"
	docker-compose stop
else
	$(MAKE) tests-security
	$(MAKE) tests-api
endif

tests-security:
ifeq ($(shell docker-compose ps | wc -l),2)
	docker-compose up -d
	bash -c "trap 'trap - SIGINT SIGTERM ERR; docker-compose stop; exit 1' SIGINT SIGTERM ERR; docker-compose exec php bin/phpunit tests/Security"
	docker-compose stop
else
	docker-compose exec php bin/phpunit tests/Security
endif

tests-api:
ifeq ($(shell docker-compose ps | wc -l),2)
	docker-compose up -d
	$(generateTestsDB)
	docker-compose exec php bin/console --env=test hautelook:fixtures:load --no-interaction
	bash -c "trap 'trap - SIGINT SIGTERM ERR; docker-compose stop; exit 1' SIGINT SIGTERM ERR; docker-compose exec php bin/phpunit tests/Api"
	docker-compose stop
else
	$(generateTestsDB)
	docker-compose exec php bin/console --env=test hautelook:fixtures:load --no-interaction
	docker-compose exec php bin/phpunit tests/Api
endif

fixtures:
	docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction
	docker-compose exec php bin/console hautelook:fixtures:load --no-interaction --purge-with-truncate

fixtures-test:
	docker-compose exec php bin/console --env=test doctrine:migrations:migrate --no-interaction
	docker-compose exec php bin/console --env=test hautelook:fixtures:load --no-interaction --purge-with-truncate
