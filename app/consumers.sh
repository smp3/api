#!/bin/bash


/var/www/vagrant/bin/console rabbitmq:consumer discover & >> ./var/logs/discover.log
/var/www/vagrant/bin/console rabbitmq:consumer fetch & >> ./var/logs/discover.log





