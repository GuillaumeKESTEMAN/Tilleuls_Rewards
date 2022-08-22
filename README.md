# Tilleuls_Rewards

### Documentation :
- [Roles and permissions](/doc/roles_and_permissions.docx)

### Project Initialization :
- Install all dependencies of the project with the `make install` command

  or
- Build docker with `docker-compose build --pull --no-cache`

### Database Initialization :
- Initialize database with `make new-db`

or
- Drop the existing database if it exists : `docker-compose exec php bin/console doctrine:database:drop --force`
- Create a new database : `docker-compose exec php bin/console doctrine:database:create`
- And migrate the migrations : `docker-compose exec php bin/console doctrine:migrations:migrate`

### Database Data Initialization :
- Initialize data in database with `docker-compose exec php bin/console hautelook:fixtures:load`

### Database Schema :

[On this pdf](/doc/schema.pdf)

[On this sql file](/doc/schema.sql)

### GitHub Repository Secrets :
Create GitHub repository secrets for :
- TWITTER_ACCESS_TOKEN
- TWITTER_ACCESS_TOKEN_SECRET
- TWITTER_BEARER_TOKEN
- TWITTER_CONSUMER_KEY
- TWITTER_CONSUMER_SECRET

### Makefile :
A Makefile is available in this project, you can have all Makfile's commands by using `make help` or `make`

### Project Launch :
- Launch this command : `make start`

or
- Open a terminal in the root and launch this command : `docker-compose up -d`
- Initialize the database with : `docker-compose exec php bin/console hautelook:fixtures:load`

### Project Stop :
- Terminate the command `make start`
- If you didn't use the make command, you must terminate with `docker-compose stop`

### Project Tests :
- Launch the project
- To launch all tests use : `make tests`
- To launch security tests use : `make tests-security`
- To launch api tests use : `make tests-api`
- To launch command tests use : `make tests-command`

### Mercure :
To use Mercure in the admin you must have a JWT stored in ./admin/.env like this : `REACT_APP_MERCURE_JWT=YOUR_JWT`

### Project Custom Commands :
#### app:get-recent-tweets (`docker-compose exec php bin/console app:get-recent-tweets`)
Command to get recent tweets with a query to update DB and a query to reply to play a game
Players must follow all active Twitter accounts needed and must have a tweet(s) with all active hashtags.
- update-db -> Boolean to update database (ex : `--update-db`)
- reply -> Boolean to reply with game URL (ex : `--reply`)
