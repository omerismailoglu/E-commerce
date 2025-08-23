#!/bin/bash

echo "🐳 Starting E-Commerce API Docker Setup..."

# Create necessary directories
mkdir -p docker/nginx
mkdir -p docker/php

# Copy environment file
cp .env.example .env

# Build and start containers
echo "📦 Building and starting containers..."
docker-compose up -d --build

# Wait for containers to be ready
echo "⏳ Waiting for containers to be ready..."
sleep 30

# Install dependencies
echo "📦 Installing PHP dependencies..."
docker-compose exec app composer install

# Generate application key
echo "🔑 Generating application key..."
docker-compose exec app php artisan key:generate

# Generate JWT secret
echo "🔐 Generating JWT secret..."
docker-compose exec app php artisan jwt:secret

# Run migrations
echo "🗄️ Running database migrations..."
docker-compose exec app php artisan migrate

# Seed database
echo "🌱 Seeding database..."
docker-compose exec app php artisan db:seed --class=SampleDataSeeder

# Set permissions
echo "🔐 Setting file permissions..."
docker-compose exec app chmod -R 775 storage bootstrap/cache

echo "✅ Docker setup completed!"
echo "🌐 API is available at: http://localhost:8000"
echo "📊 PostgreSQL is available at: localhost:5432"
echo "🔴 Redis is available at: localhost:6379"
echo ""
echo "📝 Test credentials:"
echo "Admin: admin@test.com / admin123"
echo "User: user@test.com / user123"
