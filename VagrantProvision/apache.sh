sudo a2dissite 000-default
sudo mv /home/vagrant/symfony.conf /etc/apache2/sites-available/000-symfony.conf
sudo mv /home/vagrant/pma.conf /etc/apache2/sites-available/001-pma.conf
sudo a2ensite 000-symfony
sudo a2ensite 001-pma
sudo a2enmod rewrite
sudo mv /home/vagrant/php.ini /etc/php5/apache2/php.ini
sudo mv /home/vagrant/ports.conf /etc/apache2/ports.conf


sudo service apache2 restart


