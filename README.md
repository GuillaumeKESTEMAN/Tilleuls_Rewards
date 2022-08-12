# Tilleuls_Rewards

### Project Initialization :
- Install all dependencies of the project with the `make install` command

  or
- Build docker with `docker-compose build --pull --no-cache`

### Database Schema :

[On this pdf](/doc/schema.pdf)

[On this sql file](/doc/schema.sql)

### Database Initialization :
- Initialize database with `make new-db`

or
- Drop the existing database if it exists : `docker-compose exec php bin/console doctrine:database:drop --force`
- Create a new database : `docker-compose exec php bin/console doctrine:database:create`
- And migrate the migrations : `docker-compose exec php bin/console doctrine:migrations:migrate`

### Database Data Initialization :
- Initialize data in database with `docker-compose exec php bin/console hautelook:fixtures:load`

### Project Launch :
- Launch this command : `make start`

or
- Open a terminal in the root and launch this command : `docker-compose up -d`
- Initialize the database with : `docker-compose exec php bin/console hautelook:fixtures:load`

### Project Stop :
- Terminate the command `make start`
- If you didn't use the make command, you must terminate with `docker-compose down`

### Project Tests :
- Launch the project
- To launch all tests use : `make tests`
- To launch security tests use : `make tests-security`
- To launch api tests use : `make tests-api`

### Mercure :
To use Mercure in the admin you must have a JWT stored in ./admin/.env like this : `REACT_APP_MERCURE_JWT=YOUR_JWT`

### Project Custom Commands :
#### app:comment:getRecentTweets (`docker-compose exec php bin/console app:comment:getRecentTweets`)
Command to get recent tweets with a query to update DB and a query to reply to play a game
- update-db -> Boolean to update database (ex : `--update-db`)
- reply -> Boolean to reply with game URL (ex : `--reply`)
