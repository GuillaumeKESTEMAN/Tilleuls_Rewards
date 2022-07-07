start:
	symfony serve -d
	cd my-admin/ && yarn start

install:
	composer install
	cd my-admin/ && yarn install