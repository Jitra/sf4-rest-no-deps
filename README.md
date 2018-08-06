# Simple Symfony 4 Rest api (No extra bundles)
##External helpers:
- https://github.com/ramsey/uuid - for unique ID support
- https://github.com/briannesbitt/carbon - DateTime wrapper

To run project execute:
```
git clone https://github.com/Jitra/sf4-rest-no-deps.git
composer install
##Configure your db connection in .env file, then follow
bin/console doctrine:database:create
bin/console doctrine:schema:update --force
bin/console server:start
```

Api contains swagger documentation at 
`localhost:8000/documentation`

Create user account and have fun.
