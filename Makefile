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
	mkdir -p api/public/media
	sudo chown -R $(user):docker .
	chmod -R g+w .
	docker-compose build --pull --no-cache
	cd admin/ && yarn install

kill-docker-builds:
	docker-compose stop && docker-compose kill && docker-compose down --volumes --remove-orphans
