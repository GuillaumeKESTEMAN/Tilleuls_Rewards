user := $(shell whoami)

start:
	bash -c "trap 'trap - SIGINT SIGTERM ERR; $(MAKE) stop-all; exit 1' SIGINT SIGTERM ERR; $(MAKE) start-all"

start-all:
	docker-compose up -d
	docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction
	docker-compose exec php bin/console doctrine:fixtures:load --no-interaction
	sleep 5
	cd admin/ && yarn start

stop-all:
	docker-compose down

install:
	docker-compose build --pull --no-cache
	docker-compose up -d
	docker-compose exec php bin/console lexik:jwt:generate-keypair --overwrite
	docker-compose down
	cd admin/ && yarn install

kill-docker-builds:
	docker-compose stop && docker-compose kill && docker-compose down --volumes --remove-orphans

new-db:
	docker-compose up -d
	docker-compose exec php bin/console doctrine:database:drop --force --if-exists
	docker-compose exec php bin/console doctrine:database:create
	docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction
