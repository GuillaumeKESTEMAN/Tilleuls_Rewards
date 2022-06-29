# Tilleuls_Rewards

### Project initialization :
- Install the symfony dependencies with `composer install`
- Generate JWT keypair with this command : `php bin/console lexik:jwt:generate-keypair`
- Go to the directory `my-admin/` and install the admin panel dependencies with `yarn`

### Database initialization :
- Create the database : `php bin/console doctrine:database:create`
- Execute the migrations : `php bin/console doctrine:migrations:migrate`

### Project Launch :
- Open the first terminal in the root and launch this command : `symfony serv`
- Open a second terminal in the `my-admin/` directory and launch this command : `yarn start