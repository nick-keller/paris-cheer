paris-cheer
===========

Install
-------
```
bower install
composer install --no-dev --optimize-autoloader
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console cache:clear --env=prod --no-debug --no-warmup
```

Import athletes
---------------

Create file `src/AppBundle/Fixture/Data/athletes-2016-2017.csv` with this columns:
```
first_name,last_name,email,birthday,phone,emergency_name,emergency_phone,emergency_email,address,zipcode,city
```

Create file `src/AppBundle/Fixture/Data/fffa-licences.csv` with this columns:
```
liscence,name,gender,birthday
```

For both files dates have to be formatted in ISO: `yyyy-mm-dd`.

Then run:
```
php bin/console athlete:import
```
