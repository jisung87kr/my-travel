#!/bin/bash
set -e

echo "=========================================="
echo "  My Travel - Deployment Script"
echo "=========================================="
echo ""

# Configuration
APP_DIR="${APP_DIR:-/var/www/my-travel}"
BACKUP_DIR="${BACKUP_DIR:-/var/backups/my-travel}"
PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"
NPM_BIN="${NPM_BIN:-npm}"

# Ensure backup directory exists
mkdir -p "$BACKUP_DIR"

# Get timestamp
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

echo "1. 유지보수 모드 활성화..."
$PHP_BIN artisan down --retry=60 --secret="my-travel-deploy-$TIMESTAMP" || true

echo ""
echo "2. 배포 전 백업..."
if [ -d "$BACKUP_DIR" ]; then
    # Backup .env file
    if [ -f .env ]; then
        cp .env "$BACKUP_DIR/.env.$TIMESTAMP"
    fi
    echo "   ✓ 환경설정 백업 완료"
fi

echo ""
echo "3. 코드 업데이트..."
git fetch origin
git pull origin main
echo "   ✓ 코드 업데이트 완료"

echo ""
echo "4. Composer 의존성 설치..."
$COMPOSER_BIN install --no-dev --optimize-autoloader --no-interaction
echo "   ✓ Composer 의존성 설치 완료"

echo ""
echo "5. NPM 빌드..."
$NPM_BIN ci --prefer-offline
$NPM_BIN run build
echo "   ✓ 프론트엔드 빌드 완료"

echo ""
echo "6. 데이터베이스 마이그레이션..."
$PHP_BIN artisan migrate --force
echo "   ✓ 마이그레이션 완료"

echo ""
echo "7. 캐시 최적화..."
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache
$PHP_BIN artisan event:cache
$PHP_BIN artisan icons:cache 2>/dev/null || true
echo "   ✓ 캐시 최적화 완료"

echo ""
echo "8. Queue 워커 재시작..."
$PHP_BIN artisan queue:restart
echo "   ✓ Queue 워커 재시작 완료"

echo ""
echo "9. Storage 링크 확인..."
$PHP_BIN artisan storage:link 2>/dev/null || true
echo "   ✓ Storage 링크 확인 완료"

echo ""
echo "10. 권한 설정..."
chmod -R 755 storage bootstrap/cache
echo "   ✓ 권한 설정 완료"

echo ""
echo "11. 유지보수 모드 해제..."
$PHP_BIN artisan up

echo ""
echo "=========================================="
echo "  배포가 완료되었습니다!"
echo "  Timestamp: $TIMESTAMP"
echo "=========================================="
