#command to build:
#docker build --tag test1:v1.1 .
#make sure to delete from docker desktop before building

FROM amazonlinux:2.0.20211223.0

#open ports for access from host
# EXPOSE 5432:5432
# EXPOSE 80:80
# EXPOSE 8000:8000

#change default shell from sh to bash
SHELL ["/bin/bash", "-c"]

#change timezone to eastern/toronto
RUN ln -sf /usr/share/zoneinfo/America/Toronto /etc/localtime


COPY docker/bashrc /root/.bashrc
#set SSH keys
COPY docker/ssh /root/.ssh/
RUN chmod 600 /root/.ssh/id_rsa
RUN chmod 644 /root/.ssh/id_rsa.pub
RUN chmod 644 /root/.ssh/known_hosts



#change folder to /
WORKDIR /

#run commands to update packages, install php, nginx, and run respective systems
RUN amazon-linux-extras enable nginx1=latest
RUN amazon-linux-extras enable vim=latest
RUN amazon-linux-extras enable epel=latest
RUN amazon-linux-extras enable postgresql13=latest
RUN amazon-linux-extras enable php8.0=latest
RUN amazon-linux-extras install epel -y
RUN yum clean all && yum update -y && yum autoremove -y && yum clean all
# RUN yum clean all && yum autoremove -y && yum clean all
RUN yum -y install php \
    nginx postgresql\
    wget zip unzip make rsync git vim bash-completion tar
RUN yum install -y php-cli php-fpm php-pgsql php-mbstring php-xml php-json php-pdo php-pecl-zip php-pear phpdevel gcc php-devel

# packages for playwright testing
RUN yum install -y libatk libcups libX11 atk cups libxkbcommon libXcomposite pango alsa-lib at-spi2-core at-spi2-atk
RUN pecl install Xdebug
# RUN yum -y install php php-cli php-common php-fpm php-pgsql php-bcmath php-mysqlnd php-opcache php-gd php-pecl-apcu php-pecl-igbinary php-pecl-memcache php-pecl-memcached php-pecl-igbinary-devel php-soap php-sodium php-pecl-msgpack php-pecl-ssh2 php-intl \
#     ghostscript \
#     ImageMagick procps-ng.i686 openssl java-11-amazon-corretto.x86_64 \
#     java-11-amazon-corretto-headless.x86_64 javapackages-tools.noarch python-javapackages.noarch xorg-x11-server-Xvfb \
#     gtk2-devel gtk3-devel libnotify-devel GConf2 nss libXScrnSaver alsa-lib npm nginx-all-modules.noarch \
#      Xvfb nss-3.53.1-17.el8_3.i686 atk at-spi2-atk gdk-pixbuf2-devel.x86_64 gtk3 libgbm alsa-lib \
#     chromedriver.x86_64 chrome-remote-desktop.x86_64 atk-2.28.1-1.el8.i686 libnss3.so Xvfb
# RUN dnf -y install dnf-utils epel-release
# RUN dnf -y install https://rpms.remirepo.net/enterprise/remi-release-8.rpm
# RUN dnf -y module enable php:remi-8.0
# RUN dnf -y module enable nginx:1.18
# RUN dnf -y module enable nodejs:14
# RUN yum clean all && yum update -y && yum autoremove -y && yum clean all
# RUN dnf -y install php php-cli php-common php-fpm nginx wget \
#     php-pgsql.x86_64 php-bcmath.x86_64 php-pecl-json-post.x86_64 php-pdo-dblib.x86_64 \
#     php-pecl-xmldiff.x86_64 php-pecl-zip.x86_64 zip.x86_64 vim \
#     php74-php-pgsql.x86_64 bash-completion npm nginx-all-modules.noarch git \
#     nodejs Xvfb nss-3.53.1-17.el8_3.i686 atk at-spi2-atk gdk-pixbuf2-devel.x86_64 gtk3 libgbm alsa-lib \
#     chromedriver.x86_64 chrome-remote-desktop.x86_64 atk-2.28.1-1.el8.i686 libnss3.so Xvfb

# RUN yum install -y xorg-x11-server-Xvfb gtk2-devel gtk3-devel libnotify-devel GConf2 nss libXScrnSaver alsa-lib

# RUN dnf install -y https://download.postgresql.org/pub/repos/yum/reporpms/EL-8-x86_64/pgdg-redhat-repo-latest.noarch.rpm
# RUN dnf -qy module disable postgresql
# RUN dnf -y install postgresql13

#RUN systemctl enable nginx
#RUN systemctl start nginx #systemd is not active so run it manually eh
#CMD ["/usr/sbin/nginx"]

WORKDIR /root


ENV NVM_DIR /root/.nvm
ENV NODE_VERSION 17.2.0
RUN wget -qO- https://raw.githubusercontent.com/nvm-sh/nvm/v0.38.0/install.sh | bash \
    && . $NVM_DIR/nvm.sh \
    && nvm install $NODE_VERSION \
    && nvm alias default $NODE_VERSION \
    && nvm use default \
    && npm install -g yarn

#postgresql
#dnf -y module enable postgresql:12

#nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

#php
# RUN mkdir /run/php-fpm/
#RUN sed -i 's/\r$//' /root/start.sh
RUN sed -i 's/^display_errors = Off$/display_errors = On/' /etc/php.ini
RUN sed -i 's/^display_startup_errors = Off$/display_startup_errors = On/' /etc/php.ini
RUN sed -i 's/^upload_max_filesize = .*$/upload_max_filesize = 20M/' /etc/php.ini
RUN sed -i 's/^memory_limit = .*$/memory_limit = 3000M/' /etc/php.ini
COPY ./docker/php.d/20-xdebug.ini /etc/php.d/20-xdebug.ini

WORKDIR /root/php-composer/
RUN wget https://getcomposer.org/installer -O composer-installer.php
RUN php composer-installer.php --filename=composer --install-dir=/usr/local/bin 
RUN composer global require laravel/installer
RUN export PATH=$PATH:/root/.composer/vendor/bin

RUN git config --global user.email "farzadk@gmail.com"
RUN git config --global user.name "Farzad Meow Khalafi"
RUN git config --global core.eol lf
RUN git config --global core.autocrlf false

WORKDIR /root
COPY ../ source_code/
RUN git config --global pull.rebase false
WORKDIR /root/source_code
COPY ./docker/git/ ./.git/
RUN chmod -R 777 .git/hooks/

#prepare basic code for us
WORKDIR /root/source_code/frontend
#RUN yarn

WORKDIR /root/source_code/backend
RUN chmod -R 777 storage
RUN mkdir bootstrap/cache
RUN chmod -R 777 bootstrap
#RUN composer update

WORKDIR /root/
RUN chmod 777 .
COPY docker/init_script.sh /root/init_script.sh
COPY docker/run-at-start.sh /root/run-at-start.sh
RUN chmod 777 /root/init_script.sh /root/run-at-start.sh
CMD ["/root/init_script.sh"]
