## App listing all districts from City. Build-in support for Gdańsk and Kraków. 

### Requirements: 

* php >= 7.2.5
* mysql
* and all pointed by composer php extensions

### Installation:

* composer install
* php bin/console doctrine:database:create
* php bin/console doctrine:migrations:migrate
* php bin/console doctrine:fixtures:load
* php bin/console app:fetch-districts "Gdańsk"
* php bin/console app:fetch-districts "Kraków"

* php bin/console server:run // to see admin panel
* php bin/phpunit (run small test case)
