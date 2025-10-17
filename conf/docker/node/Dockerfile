FROM node:lts-alpine AS builder

RUN npm install -g pnpm

WORKDIR /app/web

COPY ./web/package.json ./web/pnpm-lock.yaml ./

RUN pnpm install --frozen-lockfile

COPY ./web ./

RUN pnpm build

RUN pnpm prune --prod

FROM node:lts-alpine

WORKDIR /app/web

RUN npm install -g pnpm

COPY --from=builder /app/web/package.json ./
COPY --from=builder /app/web/dist ./dist
COPY --from=builder /app/web/node_modules ./node_modules

EXPOSE 4173

CMD [ "pnpm", "preview" ]