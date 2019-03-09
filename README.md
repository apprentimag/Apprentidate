# Apprentidate

Apprentidate is a web application to create events and polls (and even events
with polls!)

## Development

Just install [Docker](https://docs.docker.com/install/) and [Docker Compose](https://docs.docker.com/compose/install/),
then run:

```console
$ make up
```

Now, open [localhost:8000](http://localhost:8000) and start hacking!

In background, the `Makefile` calls `docker-compose` with the `docker/docker-compose.yml`
file. This file defines two services:

- `nginx` which serves static files (from the `public` folder) and pass PHP
  requests to the second service
- `php` which serves all the PHP requests

You can also take a look to the `docker/nginx.conf` file which defines how
Nginx talks with the `php` service.

If you want to put Apprentidate in production, you can reuse these files but
take care to change the environment variables in the `docker-compose.yml` file:

- `APP_ENVIRONMENT` must be set to `production`
- `APP_SECRET_KEY` must be changed to a random string
