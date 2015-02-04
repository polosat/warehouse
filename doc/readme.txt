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
