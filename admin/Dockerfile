# https://docs.docker.com/develop/develop-images/multistage-build/#stop-at-a-specific-build-stage
# https://docs.docker.com/compose/compose-file/#target

# https://docs.docker.com/engine/reference/builder/#understand-how-arg-and-from-interact
ARG NODE_VERSION=16

# "common" stage
FROM node:${NODE_VERSION}-alpine AS app_admin_common

EXPOSE 3000

WORKDIR /usr/src/admin

ENV NEXT_TELEMETRY_DISABLED 1

# prevent the reinstallation of node modules at every changes in the source code
COPY package.json yarn.lock ./
RUN yarn

COPY . .

VOLUME /usr/src/admin/node_modules

# "development" stage
# depends on the "common" stage above
FROM app_admin_common AS app_admin_dev

VOLUME /usr/src/admin/build

CMD ["yarn", "start"]

# "build" stage
# depends on the "common" stage above
FROM app_admin_common AS app_admin_prod

ENV NODE_ENV production
ARG NEXT_PUBLIC_ENTRYPOINT

RUN set -eux; \
	yarn build

CMD ["yarn", "start"]
