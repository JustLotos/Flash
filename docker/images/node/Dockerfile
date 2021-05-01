FROM node:alpine
RUN apk add --update 	sudo \
			python3 \
		     	yarn
RUN yarn add --dev @symfony/webpack-encore
RUN echo "node:node" | chpasswd
WORKDIR /app
