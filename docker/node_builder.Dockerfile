#docker build --pull --rm -f "docker\node_builder.Dockerfile" -t my-node-app:v1 .
#docker create --name my-node-app-container my-node-app:v1
#docker cp my-node-app-container:/root/source_code/frontend/build .
#docker rm my-node-app-container

FROM node:20.0.0

#change default shell from sh to bash
SHELL ["/bin/bash", "-c"]


COPY . /root/source_code/

WORKDIR /root/source_code/frontend
RUN yarn install && yarn build