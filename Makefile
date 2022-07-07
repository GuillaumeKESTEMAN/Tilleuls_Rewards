start:
	bash -c "trap 'trap - SIGINT SIGTERM ERR; $(MAKE) stop-all; exit 1' SIGINT SIGTERM ERR; $(MAKE) start-all"

start-all:
	symfony server:start -d
	cd my-admin/ && yarn start

stop-all:
	symfony server:stop

install:
	composer install
	cd my-admin/ && yarn install