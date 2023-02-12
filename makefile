# build
build :
	docker-compose -f docker/docker-compose.yml build

# up
up : build
	docker-compose -f docker/docker-compose.yml up -d && docker-compose -f docker/docker-compose.yml ps

# down
down:
	docker-compose -f docker/docker-compose.yml down

# restart (down + up)
restart: down up

# clean
clean :
	docker-compose -f docker/docker-compose.yml stop && docker-compose -f docker/docker-compose.yml rm -f -v

# bash of app container
bash:
	docker-compose -f docker/docker-compose.yml exec app bash

# test app (workdir : /var/www/html)
test  :
	docker-compose -f docker/docker-compose.yml exec  -T app bash -c "php artisan test --env=testing"
