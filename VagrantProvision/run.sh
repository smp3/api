#cd /var/www/vagrant
#php bin/console cache:clear --env=dev
#php bin/console cache:clear --env=prod
cd /var/www/vagrant
php bin/console doctrine:schema:update --force