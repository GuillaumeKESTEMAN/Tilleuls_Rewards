# Tilleuls_Rewards

### Project Initialization :
- Install all dependencies of the project with the `make install` command and then create JWT keypair by the command : `make jwt-keypair`

  or
- Build docker with `docker-compose build --pull --no-cache`
- Launch docker-compose with `docker-compose up -d`
- Generate JWT keypair with `docker-compose exec php bin/console lexik:jwt:generate-keypair`
- Stop docker-compose with `docker-compose down`
- Go to the directory `admin/` and install the admin panel dependencies with `yarn install`

### Database Schema :

[On this pdf](/doc/schema.pdf)

### Database Initialization :
- Initialize database with `make new-db`

or
- Drop the existing database if it exists : `docker-compose exec php bin/console doctrine:database:drop --force`
- Create a new database : `docker-compose exec php bin/console doctrine:database:create`
- And migrate the migrations : `docker-compose exec php bin/console doctrine:migrations:migrate`

### Database Data Initialization :
- Initialize data in database with `docker-compose exec php bin/console doctrine:fixtures:load --no-interaction`

### Project Launch :
- Launch this command : `make start` or just `make`

or
- Open a terminal in the root and launch this command : `docker-compose up -d`
- Initialize the database with : `docker-compose exec php bin/console doctrine:fixtures:load`
- And go to the `admin/` directory and launch this command : `yarn start`

### Project Stop :
- Terminate the command `make`/`make start`/`yarn start`
- If you didn't use the make command, you must terminate docker-compose with `docker-compose down`

### Project Custom Commands :
#### app:comment:getRecentTweets (`docker-compose exec php bin/console app:comment:getRecentTweets query`)
Command to get recent tweets with a query to update DB and a query to reply to play a game
- query -> Argument to the query parameter (ex : `#something`)
- need-follow -> Twitter account id for the condition that asks if the Twitter account that made the tweet follows this account (ex : `--need-follow 123456`)
- update-db -> Boolean to update database (ex : `--update-db`)
- reply-game-url -> Boolean to reply with game URL (ex : `--reply-game-url`)
