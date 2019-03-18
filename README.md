# Hướng dẫn deploy SSLCheck trên CentOS 7

## Mục lục

[1. Yêu cầu chung](#1)

[2. Hướng dẫn cài đặt môi trường](#2)

[3. Hướng dẫn download và deploy mã nguồn](#3)

-------------------------------------------

<a name="1">
###1. Yêu cầu chung

Server: 1 Core, 1 GB Ram, 20gb Disk
OS: CentOS 7
PHP >= 7.1.3 with OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, Intl and JSON PHP Extensions.
MariaDB version 10.x
Laravel version 5.7
1 IP Pub + 1 domain name

<a name="2">
###2. Hướng dẫn cài đặt môi trường

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

Chuyển selinux về mode permissive

`setenforce 0`

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

- Tạo thử project

`composer create-project --prefer-dist laravel/laravel test`

<a name="3">
###3. Hướng dẫn download và deploy mã nguồn

- Install git & clone source code

```
yum install git -y
git clone https://github.com/thaonguyenvan/checkssl.git
```

- Copy folder source code vào thư mục `/var/www/html/` và phân quyền

```
cd checkssl
cp -R sslcheck /var/www/html/
chown -R apache:apache /var/www/html/sslcheck
chmod -R 755 /var/www/html/sslcheck
```

- Tạo virtual host

```
sudo mkdir /etc/httpd/sites-available
sudo mkdir /etc/httpd/sites-enabled
```

Chỉnh sửa file `/etc/httpd/conf/httpd.conf`

Thêm vào đoạn sau

```
<Directory /var/www/html/sslcheck>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

và

`IncludeOptional sites-enabled/*.conf`

Sau đó save lại

Tạo file `/etc/httpd/sites-available/supportdao.io.conf`

```
<VirtualHost *:80>
    ServerName supportdao.io
    DocumentRoot /var/www/html/sslcheck
    ServerAlias supportdao.io
    ErrorLog /var/www/html/sslcheck/error.log
    CustomLog /var/www/html/sslcheck/requests.log combined
</VirtualHost>
```

Chỉnh sửa lại thông tin về domain cho phù hợp.

Tạo liên kết cho file cấu hình virtual host

`ln -s /etc/httpd/sites-available/supportdao.io.conf /etc/httpd/sites-enabled/supportdao.io.conf`

- Chỉnh sửa thông tin trong file `/var/www/html/sslcheck/.env` với những thông tin phù hợp

```
APP_URL=http://xxxx

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sslcheck
DB_USERNAME=root
DB_PASSWORD=xxxx

MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=xxxx@gmail.com
MAIL_PASSWORD=xxxxx
MAIL_ENCRYPTION=tls

TELEGRAM_BOT_TOKEN=xxxx
```

Để lấy telegram bot token, tham khảo [tại đây](https://github.com/hocchudong/ghichep-telegram-bot)

- Chỉnh sửa file `addtele.php`

```
$bottoken = 'xxxx';
$offset = xxx;
```

Trong đó:

- $bottoken: là telegram token bot
- $offset: là update_id của message cuối cùng + 1. Ví dụ message gần nhất có update_id là : 4000 thì offset ta define vào file sẽ là 4001.

<img src="https://i.imgur.com/j92cUJK.png">

Tham khảo hướng dẫn get message của bot telegram [tại đây](https://github.com/hocchudong/ghichep-telegram-bot)

- Tạo database

```
mysql -u root -p
CREATE DATABASE sslcheck;
exit
```

- Migrate database

```
cd /var/www/html/sslcheck
php artisan migrate
```

<img src="https://i.imgur.com/A3C6GRO.png">

- Cấu hình supervisor

`vi /etc/supervisord.conf`

Thêm 2 program

```
[program:addtele]
command=php /var/www/html/sslcheck/addtele.php
autostart=true
autorestart=true
user=root
redirect_stderr=true
stdout_logfile=/home/addtele.log

[program:laravel-worker]
command=php /var/www/html/sslcheck/artisan queue:work --sleep=1 --tries=3 --timeout=120
autostart=true
autorestart=true
user=apache
redirect_stderr=true
stdout_logfile=/var/log/laravel-worker.log
```

Sau đó start supervisor

`systemctl start supervisord`

- Thêm crontab

`crontab -e`

`* * * * * php /var/www/html/sslcheck/artisan schedule:run >> /dev/null 2>&1`

- Restart lại httpd

`systemctl restart httpd`

<img src="https://i.imgur.com/KbVv8bS.png">

- Mặc định user sẽ chỉ có role member, chỉnh sửa trong database để thay đổi, sau đó truy cập vào dashboard admin

<img src="https://i.imgur.com/XdcBmqO.png">

ThaoNV
