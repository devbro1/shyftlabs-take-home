FROM almalinux:9.2-20230512

#open ports for access from host
# EXPOSE 5432:5432
# EXPOSE 80:80
# EXPOSE 8000:8000

SHELL ["/bin/bash", "-c"]

RUN ln -sf /usr/share/zoneinfo/America/Toronto /etc/localtime

WORKDIR /

# RUN amazon-linux-extras enable nginx1=latest
# RUN amazon-linux-extras enable vim=latest
# RUN amazon-linux-extras enable epel=latest
# RUN amazon-linux-extras enable postgresql13=latest
# RUN amazon-linux-extras enable php8.2=latest
# RUN amazon-linux-extras install epel -y

RUN yum install -y epel-release vim
RUN dnf -y install http://rpms.remirepo.net/enterprise/remi-release-9.rpm
RUN yum clean all && yum update -y && yum autoremove -y && yum clean all
RUN dnf module reset php -y
RUN dnf -y module install php:remi-8.2
# RUN yum clean all && yum autoremove -y && yum clean all
RUN yum install -y yum-utils
RUN yum-config-manager --add-repo https://rpm.releases.hashicorp.com/AmazonLinux/hashicorp.repo
RUN yum -y install php \
    nginx postgresql \
    wget zip unzip make rsync git vim bash-completion tar \
    oathtool jq terraform ncurses \
    php-cli php-fpm php-pgsql php-mbstring php-xml php-json php-pdo php-pecl-zip php-pear gcc php-devel php-gd \
    libX11 atk cups libxkbcommon libXcomposite pango alsa-lib at-spi2-core at-spi2-atk 
# awscli amazon-linux-extras-yum-plugin libatk libcups
# RUN pecl install Xdebug 

WORKDIR /root


ENV NVM_DIR /root/.nvm
ENV NODE_VERSION 20.0.0
RUN wget -qO- https://raw.githubusercontent.com/nvm-sh/nvm/v0.38.0/install.sh | bash \
    && . $NVM_DIR/nvm.sh \
    && nvm install $NODE_VERSION \
    && nvm alias default $NODE_VERSION \
    && nvm use default \
    && npm install -g yarn secure-spreadsheet playwright@1.35.1
RUN source /root/.bashrc && npx playwright install
RUN yum install -y libdrm libXdamage libXfixes libXrandr libgbm

#nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

#php
RUN sed -i 's/^display_errors = Off$/display_errors = On/' /etc/php.ini
RUN sed -i 's/^display_startup_errors = Off$/display_startup_errors = On/' /etc/php.ini
RUN sed -i 's/^upload_max_filesize = .*$/upload_max_filesize = 50M/' /etc/php.ini
RUN sed -i 's/^post_max_size = .*$/post_max_size = 50M/' /etc/php.ini
RUN sed -i 's/^memory_limit = .*$/memory_limit = 3000M/' /etc/php.ini
#COPY ./docker/php.d/20-xdebug.ini /etc/php.d/20-xdebug.ini

WORKDIR /root/php-composer/
RUN wget https://getcomposer.org/installer -O composer-installer.php
RUN php composer-installer.php --filename=composer --install-dir=/usr/local/bin 
RUN composer global require laravel/installer
RUN export PATH=$PATH:/root/.composer/vendor/bin
COPY docker/bashrc /root/.bashrc

RUN git config --global user.email "devop@reformulary.com"
RUN git config --global user.name "devop"
RUN git config --global core.eol lf
RUN git config --global core.autocrlf false
RUN git config --global pull.rebase false
