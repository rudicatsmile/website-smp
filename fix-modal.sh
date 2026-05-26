#!/bin/bash
# Script untuk menghapus modal/overlay yang muncul di semua halaman
# Jalankan di server production setelah deploy

echo "🔧 Fixing modal overlay issue..."

# 1. Clear semua cache
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan event:clear

# 2. Hapus compiled views secara manual
rm -rf storage/framework/views/*
echo "✅ Compiled views deleted"

# 3. Clear Filament cache
php artisan filament:optimize-clear
php artisan filament:optimize
echo "✅ Filament cache cleared"

# 4. Clear application cache (skip jika DB error)
php artisan cache:clear 2>/dev/null || echo "⚠️ Cache clear skipped (DB issue)"

# 5. Rebuild autoload
composer dump-autoload -o
echo "✅ Autoload rebuilt"

# 6. Rebuild assets (jika npm tersedia)
if command -v npm &> /dev/null; then
    npm run build
    echo "✅ Assets rebuilt"
fi

echo ""
echo "🎉 Done! Refresh browser (Ctrl+Shift+R) to see changes."
echo ""
echo "Jika modal masih muncul, cek:"
echo "  1. php artisan route:list --path=admin (pastikan tidak error)"
echo "  2. tail -f storage/logs/laravel.log (cek error baru)"
