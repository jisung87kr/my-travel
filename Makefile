.PHONY: up down build restart logs shell composer artisan install

# Start all containers
up:
	docker-compose up -d

# Stop all containers
down:
	docker-compose down

# Build containers
build:
	docker-compose build

# Rebuild and start
rebuild:
	docker-compose down
	docker-compose build --no-cache
	docker-compose up -d

# Restart containers
restart:
	docker-compose restart

# View logs
logs:
	docker-compose logs -f

# Shell into app container
shell:
	docker-compose exec app bash

# Run composer commands
composer:
	docker-compose exec app composer $(filter-out $@,$(MAKECMDGOALS))

# Run artisan commands
artisan:
	docker-compose exec app php artisan $(filter-out $@,$(MAKECMDGOALS))

# Install Laravel (run this first time)
install:
	docker-compose exec app composer create-project laravel/laravel .
	docker-compose exec app php artisan key:generate
	@echo "Laravel installed! Visit http://localhost:8080"

# Install MongoDB package for Laravel
install-mongodb:
	docker-compose exec app composer require mongodb/laravel-mongodb

# Clear all caches
clear:
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan config:clear
	docker-compose exec app php artisan route:clear
	docker-compose exec app php artisan view:clear

# Run migrations
migrate:
	docker-compose exec app php artisan migrate

# Catch-all target for additional arguments
%:
	@:
