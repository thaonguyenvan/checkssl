# Hướng dẫn deploy SSLCheck trên CentOS 7

## Mục lục

1. Yêu cầu chung
2. Hướng dẫn cài đặt môi trường
3. Hướng dẫn download và deploy mã nguồn

-------------------------------------------

### 1. Yêu cầu chung

Server: 1 Core, 1 GB Ram, 20gb Disk
OS: CentOS 7
PHP >= 7.1.3 with OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, Intl and JSON PHP Extensions.
MariaDB version 10.x
Laravel version 5.7
1 IP Pub + 1 domain name

### 2. Hướng dẫn cài đặt môi trường

- Cài đặt remi và epel repo

```
rpm -Uvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm
rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-7.rpm
```

- Cài đặt apache vs supervisor

`yum install httpd supervisor -y`

Khởi động apache

```
systemctl start httpd
systemctl enable httpd
```

Thêm rule cho firewalld

```
firewall-cmd –permanent –add-port=80/tcp
firewall-cmd –permanent –add-port=443/tcp
firewall-cmd --reload
```

- Cài đặt MySQL

```
yum install mariadb-server php-mysql
systemctl start mariadb.service
/usr/bin/mysql_secure_installation
```

- Cài đặt php 7.2


```
yum install yum-utils
yum-config-manager --enable remi-php72
yum install php php-fpm php-common php-xml php-mbstring php-json php-zip php-intl
```

- Cài đặt composer và laravel

```
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/bin/composer
chmod +x /usr/bin/composer
```

### 3. Hướng dẫn download và deploy mã nguồn

- Install git & clone source code

```
yum install git -y
git clone 
```
