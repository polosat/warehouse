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

echo "Updating repositories"
add-apt-repository ppa:nginx/stable -y
apt-get update -y
apt-get dist-upgrade -y

echo "Installing unzip"
apt-get install unzip -y

echo "Installing nginx"
apt-get install nginx -y

echo "Configuring nginx"
ln -s /vagrant/provision/config/nginx_vhost.conf /etc/nginx/sites-available/warehouse.conf
ln -s /vagrant/provision/config/nginx_vhost.conf /etc/nginx/sites-enabled/warehouse.conf
rm -rf /etc/nginx/sites-available/default
rm -rf /etc/nginx/sites-enabled/default
service nginx restart

echo "Installing PHP"
apt-get install php5-common php5-dev php5-cli php5-fpm php5-mysqlnd php5-curl -y

echo "Installing xdebug"
mkdir /tmp/xdebug
cd /tmp/xdebug
wget http://xdebug.org/files/xdebug-2.3.2.tgz
tar -xvzf xdebug-2.3.2.tgz
cd xdebug-2.3.2
phpize
./configure
make
cp modules/xdebug.so /usr/lib/php5/20121212
echo -e '\nzend_extension = /usr/lib/php5/20121212/xdebug.so\n' >> /etc/php5/mods-available/xdebug.ini
php5enmod xdebug
cd /tmp
rm -rf xdebug
service php5-fpm restart

echo "Installing MySQL"
db_root_password='@dm1n@cce$$'
debconf-set-selections <<< "mysql-server mysql-server/root_password password $db_root_password"
debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $db_root_password"
apt-get install mysql-server-5.6 -y

echo "Creatting application databases";
query="
 source /vagrant/provision/sql/databases.sql;
 source /vagrant/provision/sql/users.sql;
 use warehouse;
 source /vagrant/provision/sql/tables.sql;
 use test_warehouse;
 source /vagrant/provision/sql/tables.sql;"
mysql -uroot -p${db_root_password} -e "$query"

echo "Enabling remote connections to MySQL"
sed -i 's/\(^\s*bind-address\s*=\s*127\.0\.0\.1\s*$\)/#\1/' /etc/mysql/my.cnf
service mysql restart

echo "Installing phantomjs"
wget -P /tmp/phantomjs/ https://bitbucket.org/polosat/packages/src/master/phantomjs/2.0.0/bin/ubuntu/trusty64/phantomjs.zip
unzip /tmp/phantomjs/phantomjs.zip -d /tmp/phantomjs
install -m 755 /tmp/phantomjs/phantomjs /usr/local/bin
rm -r /tmp/phantomjs
