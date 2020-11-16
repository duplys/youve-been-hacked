FROM ubuntu:14.04

RUN apt-get update
RUN apt-get install git -y
RUN apt-get install vim -y

WORKDIR /opt/lamp
COPY lamp_starter.sh .
RUN chmod +x lamp_starter.sh
COPY README .

# instructions for installing LAMP on Ubuntu 14.04 can be found here:
# https://www.digitalocean.com/community/tutorials/how-to-install-lamp-on-ubuntu-14-04-quickstart
RUN apt-get -y install apache2 mysql-server php5-mysql php5 libapache2-mod-php5 php5-mcrypt

COPY php_cli.ini /etc/php5/cli/php.ini
COPY php_apache2.ini /etc/php5/apache2/php.ini

EXPOSE 80

RUN a2enmod rewrite

COPY info.php /var/www/html/
COPY ins-webroot/hacking-index.html /var/www/html/
ADD ins-webroot/app /var/www/html/app/
ADD ins-webroot/daten /var/www/html/daten/

#CMD ["./lamp_starter.sh"]