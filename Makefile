generateDB = docker-compose exec php bin/console doctrine:database:drop --force --if-exists; \
	docker-compose exec php bin/console doctrine:database:create; \
	docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction;

generateJWT = docker-compose exec php bin/console lexik:jwt:generate-keypair --overwrite -n;


start:
	bash -c "trap 'trap - SIGINT SIGTERM ERR; $(MAKE) stop-all; exit 1' SIGINT SIGTERM ERR; $(MAKE) start-all"

start-all:
	docker-compose up -d
	$(MAKE) jwt-keypair
	docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction
	# or `$(MAKE) new-db`
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
