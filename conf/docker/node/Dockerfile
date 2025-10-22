FROM node:lts-alpine AS builder

RUN npm install -g pnpm

WORKDIR /app/web

COPY ./web/package.json ./web/pnpm-lock.yaml ./
RUN pnpm install

COPY ./web ./

FROM node:lts-alpine

WORKDIR /app/web
RUN npm install -g pnpm

COPY --from=builder /app/web ./

EXPOSE 5173
CMD ["pnpm", "dev"]
