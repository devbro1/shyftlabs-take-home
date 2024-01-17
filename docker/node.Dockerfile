#command to build:
#docker build --tag test1:v1.1 .
#make sure to delete from docker desktop before building

FROM node:20.0.0

#open ports for access from host
# EXPOSE 5432:5432
# EXPOSE 80:80
# EXPOSE 3000:3000

#change default shell from sh to bash
SHELL ["/bin/bash", "-c"]


# COPY docker/bashrc /root/.bashrc
# #set SSH keys
# COPY docker/ssh /root/.ssh/
# RUN chmod 600 /root/.ssh/id_rsa
# RUN chmod 644 /root/.ssh/id_rsa.pub
# RUN chmod 644 /root/.ssh/known_hosts

# RUN git config --global user.email "farzadk@gmail.com"
# RUN git config --global user.name "Farzad Meow Khalafi"
# RUN git config --global core.eol lf
# RUN git config --global core.autocrlf false
# RUN git config --global pull.rebase false

COPY ../ /root/source_code/

# WORKDIR /root/source_code
# COPY ./docker/git/ ./.git/
# RUN chmod -R 777 .git/hooks/

WORKDIR /root/
COPY ./docker/run-at-start.sh ./run-at-start.sh
CMD ["/root/run-at-start.sh"]
#ENTRYPOINT ["/bin/tail", "-f", "/root/.bashrc"]
