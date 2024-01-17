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
RUN amazon-linux-extras enable php8.2=latest
RUN amazon-linux-extras install epel -y
RUN yum clean all && yum update -y && yum autoremove -y && yum clean all
# RUN yum clean all && yum autoremove -y && yum clean all
RUN yum install -y yum-utils
RUN yum-config-manager --add-repo https://rpm.releases.hashicorp.com/AmazonLinux/hashicorp.repo
RUN yum -y install php \
    nginx postgresql\
    wget zip unzip make rsync git vim bash-completion tar \
    awscli amazon-linux-extras-yum-plugin oathtool jq terraform
RUN yum install -y php-cli php-fpm php-pgsql php-mbstring php-xml php-json php-pdo php-pecl-zip php-pear phpdevel gcc php-devel php-gd

# packages for playwright testing
RUN yum install -y libatk libcups libX11 atk cups libxkbcommon libXcomposite pango alsa-lib at-spi2-core at-spi2-atk
RUN pecl install Xdebug

WORKDIR /root


ENV NVM_DIR /root/.nvm
ENV NODE_VERSION 17.2.0
RUN wget -qO- https://raw.githubusercontent.com/nvm-sh/nvm/v0.38.0/install.sh | bash \
    && . $NVM_DIR/nvm.sh \
    && nvm install $NODE_VERSION \
    && nvm alias default $NODE_VERSION \
    && nvm use default \
    && npm install -g yarn secure-spreadsheet

#nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

#php
RUN sed -i 's/^display_errors = Off$/display_errors = On/' /etc/php.ini
RUN sed -i 's/^display_startup_errors = Off$/display_startup_errors = On/' /etc/php.ini
RUN sed -i 's/^upload_max_filesize = .*$/upload_max_filesize = 50M/' /etc/php.ini
RUN sed -i 's/^post_max_size = .*$/post_max_size = 50M/' /etc/php.ini
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
