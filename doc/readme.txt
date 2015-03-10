$ sudo useradd -b /home -m -U test
$ sudo passwd test
  te$t@dm1n
$ sudo chsh -s /bin/bash test

$ sudo mkdir -p -m 755 /home/test/www
$ sudo mkdir -p -m 754 /home/test/logs
$ sudo chown -R test:test /home/test/www/
$ sudo chown -R test:test /home/test/logs/
$ sudo usermod -a -G test www-data
$ sudo nano /etc/nginx/conf.d/test.stepanov.in.conf
  <<< file text goes here >>>
===================================================

php-fpm is preinstalled already in mac-os
we just need to enable the config file (rename) and create directory for logs

nginx can be installed in native mode

===================================================
zend_extension = /usr/lib/php/extensions/no-debug-non-zts-20100525/xdebug.so
xdebug.remote_enable=true
xdebug.remote_port=9001
xdebug.remote_host=127.0.0.1
xdebug.remote_autostart=1
xdebug.idekey=

; disables 'orange error table' (and it seems xdebug tracing capabilities)
xdebug.default_enable=off

date.timezone = Europe/Moscow
====================================================

restart nginx+php-fpm macos:

#!/bin/bash
sudo nginx -s stop
sudo pkill php-fpm
sudo nginx
sudo php-fpm

