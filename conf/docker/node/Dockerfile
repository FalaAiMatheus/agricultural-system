FROM node:lts-alpine AS builder

RUN npm install -g pnpm

WORKDIR /app/web

COPY ./web/package.json ./web/pnpm-lock.yaml ./

RUN pnpm install --frozen-lockfile

COPY ./web ./

RUN pnpm prune --prod

FROM node:lts-alpine

WORKDIR /app/web

RUN npm install -g pnpm

COPY --from=builder /app/web/package.json ./
COPY --from=builder /app/web/node_modules ./node_modules

EXPOSE 5173

CMD [ "pnpm", "dev" ]