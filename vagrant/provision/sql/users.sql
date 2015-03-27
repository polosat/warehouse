GRANT ALL PRIVILEGES ON warehouse.* TO 'warehouse'@'localhost' IDENTIFIED BY '$ecureP@$$w0rd';
GRANT ALL PRIVILEGES ON warehouse.* TO 'warehouse'@'192.168.33.1' IDENTIFIED BY '$ecureP@$$w0rd';
GRANT ALL PRIVILEGES ON test_warehouse.* TO 'test_warehouse'@'localhost' IDENTIFIED BY 'Te$tP@$$w0rd';
GRANT ALL PRIVILEGES ON test_warehouse.* TO 'test_warehouse'@'192.168.33.1' IDENTIFIED BY 'Te$tP@$$w0rd';
FLUSH PRIVILEGES;