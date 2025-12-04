#!/bin/bash
set -e

echo "=========================================="
echo "  My Travel - Backup Script"
echo "=========================================="
echo ""

# Configuration
BACKUP_DIR="${BACKUP_DIR:-/var/backups/my-travel}"
RETENTION_DAYS="${RETENTION_DAYS:-7}"
APP_DIR="${APP_DIR:-/var/www/my-travel}"

# Database config from .env
if [ -f "$APP_DIR/.env" ]; then
    source <(grep -E "^DB_(HOST|DATABASE|USERNAME|PASSWORD)=" "$APP_DIR/.env" | sed 's/=/="/;s/$/"/')
fi

DB_HOST="${DB_HOST:-127.0.0.1}"
DB_DATABASE="${DB_DATABASE:-my_travel}"
DB_USERNAME="${DB_USERNAME:-root}"
DB_PASSWORD="${DB_PASSWORD:-}"

# Get timestamp
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_PATH="$BACKUP_DIR/$TIMESTAMP"

# Create backup directory
mkdir -p "$BACKUP_PATH"

echo "백업 시작: $TIMESTAMP"
echo "백업 위치: $BACKUP_PATH"
echo ""

echo "1. 데이터베이스 백업..."
if [ -n "$DB_PASSWORD" ]; then
    mysqldump -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" 2>/dev/null | gzip > "$BACKUP_PATH/database.sql.gz"
else
    mysqldump -h "$DB_HOST" -u "$DB_USERNAME" "$DB_DATABASE" 2>/dev/null | gzip > "$BACKUP_PATH/database.sql.gz"
fi

if [ -f "$BACKUP_PATH/database.sql.gz" ]; then
    DB_SIZE=$(du -h "$BACKUP_PATH/database.sql.gz" | cut -f1)
    echo "   ✓ 데이터베이스 백업 완료 ($DB_SIZE)"
else
    echo "   ✗ 데이터베이스 백업 실패 (스킵됨)"
fi

echo ""
echo "2. 업로드 파일 백업..."
if [ -d "$APP_DIR/storage/app/public" ]; then
    tar -czf "$BACKUP_PATH/storage.tar.gz" -C "$APP_DIR/storage/app" public 2>/dev/null
    if [ -f "$BACKUP_PATH/storage.tar.gz" ]; then
        STORAGE_SIZE=$(du -h "$BACKUP_PATH/storage.tar.gz" | cut -f1)
        echo "   ✓ 업로드 파일 백업 완료 ($STORAGE_SIZE)"
    else
        echo "   ✗ 업로드 파일 백업 실패"
    fi
else
    echo "   ⚠ 업로드 파일 디렉토리 없음 (스킵됨)"
fi

echo ""
echo "3. 환경 설정 백업..."
if [ -f "$APP_DIR/.env" ]; then
    cp "$APP_DIR/.env" "$BACKUP_PATH/.env.backup"
    echo "   ✓ 환경 설정 백업 완료"
else
    echo "   ⚠ .env 파일 없음 (스킵됨)"
fi

echo ""
echo "4. 오래된 백업 정리..."
DELETED_COUNT=0
if [ -d "$BACKUP_DIR" ]; then
    while IFS= read -r -d '' dir; do
        rm -rf "$dir"
        ((DELETED_COUNT++))
    done < <(find "$BACKUP_DIR" -maxdepth 1 -type d -mtime +$RETENTION_DAYS -print0 2>/dev/null)
fi
echo "   ✓ $DELETED_COUNT개의 오래된 백업 삭제됨 ($RETENTION_DAYS일 이상)"

echo ""
echo "5. 백업 목록 생성..."
ls -la "$BACKUP_PATH" > "$BACKUP_PATH/manifest.txt"
echo "   ✓ 백업 매니페스트 생성 완료"

echo ""
TOTAL_SIZE=$(du -sh "$BACKUP_PATH" | cut -f1)
echo "=========================================="
echo "  백업이 완료되었습니다!"
echo "  위치: $BACKUP_PATH"
echo "  크기: $TOTAL_SIZE"
echo "  보관 기간: $RETENTION_DAYS일"
echo "=========================================="
