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
	$(MAKE) jwt-keypair
	docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction # or `$(MAKE) new-db`
	docker-compose exec php bin/console doctrine:fixtures:load --no-interaction
	sleep 5
	cd admin/ && yarn start

stop-all:
	docker-compose down

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
	docker-compose down
else
	$(generateDB)
endif

jwt-keypair:
ifeq ($(shell docker-compose ps | wc -l),2)
	docker-compose up -d
	$(generateJWT)
	docker-compose down
else
	$(generateJWT)
endif

tests:
ifeq ($(shell docker-compose ps | wc -l),2)
	docker-compose up -d
	bash -c "trap 'trap - SIGINT SIGTERM ERR; docker-compose down; exit 1' SIGINT SIGTERM ERR; $(MAKE) tests-security"
	bash -c "trap 'trap - SIGINT SIGTERM ERR; docker-compose down; exit 1' SIGINT SIGTERM ERR; $(MAKE) tests-api"
	docker-compose down
else
	$(MAKE) tests-security
	$(MAKE) tests-api
endif

tests-security:
ifeq ($(shell docker-compose ps | wc -l),2)
	docker-compose up -d
	$(MAKE) jwt-keypair
	bash -c "trap 'trap - SIGINT SIGTERM ERR; docker-compose down; exit 1' SIGINT SIGTERM ERR; docker-compose exec php bin/phpunit tests/Security"
	docker-compose down
else
	$(MAKE) jwt-keypair
	docker-compose exec php bin/phpunit tests/Security
endif

tests-api:
ifeq ($(shell docker-compose ps | wc -l),2)
	docker-compose up -d
	$(generateTestsDB)
	docker-compose exec php bin/console --env=test doctrine:fixtures:load --no-interaction
	bash -c "trap 'trap - SIGINT SIGTERM ERR; docker-compose down; exit 1' SIGINT SIGTERM ERR; docker-compose exec php bin/phpunit tests/Api"
	docker-compose down
else
	$(generateTestsDB)
	docker-compose exec php bin/console --env=test doctrine:fixtures:load --no-interaction
	docker-compose exec php bin/phpunit tests/Api
endif
