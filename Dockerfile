FROM node:latest as frontend
COPY app /app
WORKDIR /app
RUN yarn && yarn build


FROM golang as backend
RUN mkdir /app
COPY go.mod /app
COPY go.sum /app
WORKDIR /app
ARG app_version=dev
RUN go mod download
COPY . /app
RUN CGO_ENABLED=0 go build -o BOILERPLATE -ldflags "-X main.version=$app_version" .


FROM alpine as runner
RUN mkdir -p /run
WORKDIR /run
COPY --from=backend /app/BOILERPLATE ./BOILERPLATE
RUN chmod a+x BOILERPLATE
COPY --from=frontend /dist ./dist
ENV BOILERPLATE_ENV=prod
ENTRYPOINT /run/BOILERPLATE

