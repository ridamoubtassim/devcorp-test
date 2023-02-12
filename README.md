## Docker Setup
#### for linux
- build images : `make build`
- build + up services : `make up`
- stop services : `make down`
- down + up : `make restart`
- to execute shell inside the app container : `make bash`
- to execute laravel tests : `make test`

#### for windows
- `cd path/to/app/docker directory` then :
    - build images : `docker compose build`
    - up services : `docker compose up -d`
    - build + up services : `docker compose up -d --build`
    - down + up : `docker compose restart`
    - to execute shell inside the app container : `docker compose exec app bash`
    - to execute laravel tests : `docker compose exec -T app bash -c "php artisan test --env=testing"`

## DB Setup
- edit `src/tests/dumps/init.sql`
- run `make restart`

## Laravel env Setup
- for .env : edit `docker/configs/.env`
- for .env.testing : edit `docker/configs/.env.testing`


## Run Laravel
- go to http://localhost:82

## Run Adminer (PostgreSQL)
- go to http://localhost:8080
- System : `PostgreSQL`
- Server : `db`
- User : `postgres`
- Password : `root`
- Database : `devcorp-db` or `devcorp-db-test`


