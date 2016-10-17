sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password xxx666'
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password xxx666'
apt-get -y install apache2 php5 rabbitmq-server mysql-server mysql-client wget php5-mysql unzip inotify-tools python-pip python-dev ffmpeg
sudo pip install watchdog
sudo pip install subprocess32

cd /var/www/vagrant
mysql -u root --password=xxx666  -e "create database if not exists smp3"
sudo rabbitmq-plugins enable rabbitmq_management
sudo rabbitmqctl add_user test test
sudo rabbitmqctl set_user_tags test administrator
sudo rabbitmqctl set_permissions -p / test ".*" ".*" ".*"
sudo service rabbitmq-server stop
sudo service rabbitmq-server start

cd /var/www/vagrant
php bin/console doctrine:schema:update --force


cd ~

wget https://getcomposer.org/download/1.2.1/composer.phar 
sudo mv ./composer.phar /usr/bin/composer
sudo chmod a+x /usr/bin/composer

wget https://files.phpmyadmin.net/phpMyAdmin/4.6.4/phpMyAdmin-4.6.4-all-languages.zip
unzip -o phpMyAdmin-4.6.4-all-languages.zip -d /var/www/pma 

wget https://github.com/joh/when-changed/archive/master.zip
unzip master.zip 
sudo mv when-changed-master /usr/lib/when-changed
sudo ln -s /usr/lib/when-changed/when-changed /usr/bin/when-changed

sudo chmod a+rx /usr/bin/when-changed


