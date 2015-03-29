#!/bin/bash
echo "Disabling locales acception from ssh clients"
sed -i 's/\(^\s*AcceptEnv LANG LC_\*\s*$\)/#\1/' /etc/ssh/sshd_config
service ssh restart

echo "Granting permissions to the vagrant user"
usermod -a -G www-data vagrant

echo "Creating supplementary folders"
mkdir -p -m 770 /home/warehouse/logs
mkdir -p -m 770 /home/warehouse/storage
chown www-data:www-data /home/warehouse/logs
chown www-data:www-data /home/warehouse/storage

echo "Installing nginx"
add-apt-repository ppa:nginx/stable -y
apt-get update -y
apt-get install nginx -y

echo "Configuring nginx"
ln -s /vagrant/provision/config/nginx_vhost.conf /etc/nginx/sites-available/warehouse.conf
ln -s /vagrant/provision/config/nginx_vhost.conf /etc/nginx/sites-enabled/warehouse.conf
rm -rf /etc/nginx/sites-available/default
rm -rf /etc/nginx/sites-enabled/default
service nginx restart

echo "Installing PHP"
apt-get install php5-common php5-cli php5-fpm php5-mysqlnd php5-curl -y

echo "Installing xdebug"
wget --no-verbose -P /tmp/xdebug/ https://bitbucket.org/polosat/packages/downloads/xdebug-2.3.2-ubuntu-trusty64.tar.bz2
tar xjf /tmp/xdebug/xdebug-2.3.2-ubuntu-trusty64.tar.bz2 -C /tmp/xdebug
install -m 644 -T /tmp/xdebug/xdebug-2.3.2.so /usr/lib/php5/20121212/xdebug.so
echo -e '\nzend_extension = /usr/lib/php5/20121212/xdebug.so\n' >> /etc/php5/mods-available/xdebug.ini
php5enmod xdebug
rm -r /tmp/xdebug
service php5-fpm restart

echo "Installing MySQL"
db_root_password='@dm1n@cce$$'
debconf-set-selections <<< "mysql-server mysql-server/root_password password $db_root_password"
debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $db_root_password"
apt-get install mysql-server-5.6 -y

echo "Creating application databases";
query="
 source /home/warehouse/sql/databases.sql;
 source /home/warehouse/sql/users.sql;
 use warehouse;
 source /home/warehouse/sql/tables.sql;"
mysql -uroot -p${db_root_password} -e "$query"

echo "Enabling remote connections to MySQL"
sed -i 's/\(^\s*bind-address\s*=\s*127\.0\.0\.1\s*$\)/#\1/' /etc/mysql/my.cnf
service mysql restart

echo "Installing phantomjs as a service"
wget --no-verbose -P /tmp/phantomjs/ https://bitbucket.org/polosat/packages/downloads/phantomjs-2.0.0-ubuntu-trusty64.tar.bz2
tar xjf /tmp/phantomjs/phantomjs-2.0.0-ubuntu-trusty64.tar.bz2 -C /tmp/phantomjs
install -m 755 /tmp/phantomjs/phantomjs-2.0.0 /usr/sbin
install -m 755 -T /vagrant/provision/config/phantomjs_init.d /etc/init.d/phantomjs
install -m 644 -T /vagrant/provision/config/phantomjs_defaults /etc/default/phantomjs
ln -s /usr/sbin/phantomjs-2.0.0 /usr/sbin/phantomjs
rm -r /tmp/phantomjs
update-rc.d phantomjs defaults
service phantomjs start

echo "Building tests"
cd /home/warehouse/test
php codecept.phar build