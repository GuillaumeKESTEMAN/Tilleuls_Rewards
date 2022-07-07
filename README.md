# Tilleuls_Rewards

### Project initialization :
- Install the symfony dependencies with `composer install`
- Generate JWT keypair with this command : `php bin/console lexik:jwt:generate-keypair`
- Go to the directory `my-admin/` and install the admin panel dependencies with `yarn install`

### Database initialization :
- Create the database : `php bin/console doctrine:database:create`
- Execute the migrations : `php bin/console doctrine:migrations:migrate`

### Project Launch :
- Open the first terminal in the root and launch this command : `symfony serve`
- Open a second terminal in the `my-admin/` directory and launch this command : `yarn start`

### Project Custom Commands :
#### app:comment:getRecentTweets
Command to get recent tweets with a query to update DB and a query to reply to play a game
- query -> Argument to the query parameter (ex : `#something`)
- need-follow -> twitter account id for the condition that asks if the twitter account that made the tweet follows this account (ex : `--need-follow 123456`)
- update-db -> Boolean to update database (ex : `--update-db`)
- reply-game-url -> Boolean to reply with game URL (ex : `--reply-game-url`)