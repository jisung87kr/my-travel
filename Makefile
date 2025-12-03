.PHONY: up down build restart logs shell composer artisan install setup npm npx

# ===================================
# Docker Commands
# ===================================

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

# ===================================
# NPM Commands
# ===================================

# Run npm commands
npm:
	docker-compose exec app npm $(filter-out $@,$(MAKECMDGOALS))

# Run npx commands
npx:
	docker-compose exec app npx $(filter-out $@,$(MAKECMDGOALS))

# ===================================
# Project Setup Commands
# ===================================

# Full project setup (run after fresh clone)
setup: install-packages npm-install setup-dirs setup-classes
	@echo "✅ Project setup complete!"

# Install all composer packages
install-packages:
	@echo "📦 Installing Composer packages..."
	docker-compose exec app composer require laravel/sanctum
	docker-compose exec app composer require laravel/socialite
	docker-compose exec app composer require spatie/laravel-permission
	docker-compose exec app composer require intervention/image
	docker-compose exec app composer require simplesoftwareio/simple-qrcode
	@echo "✅ Composer packages installed!"

# Install all npm packages
npm-install:
	@echo "📦 Installing NPM packages..."
	docker-compose exec app npm install vue@latest @vitejs/plugin-vue pinia vue-router
	docker-compose exec app npm install @fullcalendar/core @fullcalendar/vue3 @fullcalendar/daygrid @fullcalendar/interaction
	docker-compose exec app npm install @zxing/library
	docker-compose exec app npm install -D @types/node
	@echo "✅ NPM packages installed!"

# Create directory structure
setup-dirs:
	@echo "📁 Creating directory structure..."
	docker-compose exec app mkdir -p app/Enums
	docker-compose exec app mkdir -p app/Services
	docker-compose exec app mkdir -p app/Http/Responses
	docker-compose exec app mkdir -p app/Repositories
	docker-compose exec app mkdir -p app/DTOs
	docker-compose exec app mkdir -p resources/js/components
	docker-compose exec app mkdir -p resources/js/stores
	docker-compose exec app mkdir -p resources/js/composables
	@echo "✅ Directory structure created!"

# Setup base classes (ApiResponse, Enums, etc.)
setup-classes:
	@echo "📝 Setting up base classes..."
	docker-compose exec app php artisan vendor:publish --provider="Spatie\\Permission\\PermissionServiceProvider"
	docker-compose exec app php artisan vendor:publish --provider="Laravel\\Sanctum\\SanctumServiceProvider"
	@echo "✅ Base classes setup complete!"

# ===================================
# Development Commands
# ===================================

# Start development server
dev:
	docker-compose exec app npm run dev

# Build for production
build-assets:
	docker-compose exec app npm run build

# Run tests
test:
	docker-compose exec app php artisan test

# Run Laravel Pint (code style)
pint:
	docker-compose exec app ./vendor/bin/pint

# Fresh migration with seeds
fresh:
	docker-compose exec app php artisan migrate:fresh --seed

# ===================================
# Utility Commands
# ===================================

# Check container status
status:
	docker-compose ps

# Database shell
db-shell:
	docker-compose exec mysql mysql -u root -p

# Redis CLI
redis-cli:
	docker-compose exec redis redis-cli

# Catch-all target for additional arguments
%:
	@:
