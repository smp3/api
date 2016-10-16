sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password xxx666'
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password xxx666'
apt-get -y install apache2 php5 rabbitmq-server mysql-server mysql-client wget php5-mysql
cd /var/www/vagrant
mysql -u root --password=xxx666  -e "create database if not exists smp3"
rabbitmq-plugins enable rabbitmq_management
rabbitmqctl add_user test test
rabbitmqctl set_user_tags test administrator
rabbitmqctl set_permissions -p / test ".*" ".*" ".*"

#wget https://getcomposer.org/download/1.2.1/composer.phar 
#sudo mv ./composer.phar /usr/bin/composer
#sudo chmod a+x /usr/bin/composer
