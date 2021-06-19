FROM node:alpine
RUN apk add --update  sudo \
			          python3 \
		     	      yarn \
			          make \
                      build-essential
RUN yarn add --dev @symfony/webpack-encore
RUN echo "node:node" | chpasswd
WORKDIR /app
