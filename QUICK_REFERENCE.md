# ⚡ Quick Reference Card

## 🚀 Quick Start Commands

```bash
# Development
composer dev                    # Run all services (server, queue, logs, vite)
npm run dev                     # Run Vite dev server only
php artisan serve              # Run Laravel server only

# Build
npm run build                   # Build assets for production

# Clear Cache
php artisan optimize:clear      # Clear all caches
php artisan config:clear        # Clear config cache
php artisan view:clear          # Clear view cache
php artisan route:clear         # Clear route cache

# Filament
php artisan filament:optimize   # Optimize Filament
php artisan make:filament-user  # Create admin user

# Shield (Permissions)
php artisan shield:install      # Install Shield
php artisan shield:generate     # Generate permissions
```

## 📁 Important Files

```
app/Providers/Filament/AdminPanelProvider.php  # Main config
resources/css/filament/admin/theme.css         # Custom theme
tailwind.config.js                             # Tailwind config
vite.config.js                                 # Vite config
public/images/logo.svg                         # Brand logo
public/images/favicon.png                      # Favicon
```

## 🎨 Color Codes

```php
Primary:  #3b82f6  (Blue)
Success:  #10b981  (Emerald)
Warning:  #f59e0b  (Amber)
Danger:   #f43f5e  (Rose)
Info:     #0ea5e9  (Sky)
Gray:     Slate palette
```

## 🔧 Common Customizations

### Change Primary Color
```php
// AdminPanelProvider.php
->colors([
    'primary' => Color::Emerald, // Change this
])
```

### Change Font
```php
// AdminPanelProvider.php
->font('Poppins') // Change this

// theme.css
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
```

### Add Custom CSS
```css
/* theme.css - Add at bottom */
.my-custom-class {
    @apply rounded-xl shadow-lg;
}
```

### Change Logo
```
1. Replace: public/images/logo.svg
2. Replace: public/images/favicon.png
3. No rebuild needed!
```

## 🎯 Navigation Icons

```php
'heroicon-o-cog-6-tooth'              // Settings
'heroicon-o-document-text'            // Content
'heroicon-o-academic-cap'             // Academic
'heroicon-o-user-group'               // Users
'heroicon-o-book-open'                // Books
'heroicon-o-chat-bubble-left-right'   // Chat
'heroicon-o-clipboard-document-check' // Checklist
'heroicon-o-trophy'                   // Trophy
'heroicon-o-calendar-days'            // Calendar
'heroicon-o-users'                    // Users
```

## 📊 Widget Types

```php
// Stats Widget
Stat::make('Label', $value)
    ->description('Description')
    ->descriptionIcon('heroicon-o-icon')
    ->color('primary')
    ->chart([1, 2, 3, 4, 5]);

// Chart Widget
protected function getData(): array
{
    return [
        'datasets' => [...],
        'labels' => [...],
    ];
}
```

## 🎨 CSS Classes Reference

### Rounded Corners
```css
rounded-lg    /* 0.5rem */
rounded-xl    /* 0.75rem */
rounded-2xl   /* 1rem */
rounded-3xl   /* 1.5rem */
```

### Shadows
```css
shadow-sm     /* Subtle */
shadow-md     /* Medium */
shadow-lg     /* Large */
shadow-xl     /* Extra large */
shadow-2xl    /* Huge */
```

### Gradients
```css
bg-gradient-to-r from-blue-600 to-blue-500
bg-gradient-to-b from-gray-50 to-white
```

### Animations
```css
animate-slide-in-up
animate-slide-in-right
animate-fade-in
transition-all duration-300
hover:scale-105
```

## 🔍 Troubleshooting

### Theme not showing
```bash
npm run build
php artisan config:clear
php artisan view:clear
```

### Plugin error
```bash
composer update
php artisan optimize:clear
```

### Build error
```bash
npm install
npm run build
```

### Database error
```bash
# Check .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=root
DB_PASSWORD=
```

## 📦 Installed Plugins

### Filament Curator
```php
// Access: Admin Panel > Content > Media Library
// Features: Media management, image optimization
```

### Filament Shield
```php
// Setup:
php artisan shield:install
php artisan shield:generate --all

// Access: Admin Panel > Shield > Roles & Permissions
```

## 🎯 Panel Features

```php
✓ SPA Mode                    // Fast navigation
✓ Database Notifications      // Real-time alerts
✓ Sidebar Collapsible        // Space saving
✓ Unsaved Changes Alerts     // Prevent data loss
✓ Dark Mode                  // Eye comfort
✓ Responsive                 // Mobile friendly
```

## 📱 Responsive Breakpoints

```css
sm:   640px   /* Small devices */
md:   768px   /* Medium devices */
lg:   1024px  /* Large devices */
xl:   1280px  /* Extra large */
2xl:  1536px  /* 2X large */
```

## 🎨 Tailwind Utilities

### Spacing
```css
p-4    /* padding: 1rem */
m-4    /* margin: 1rem */
gap-4  /* gap: 1rem */
space-y-4  /* vertical spacing */
```

### Flexbox
```css
flex flex-col      /* Column layout */
flex items-center  /* Center items */
flex justify-between  /* Space between */
```

### Grid
```css
grid grid-cols-3   /* 3 columns */
grid gap-4         /* Gap between */
```

## 🔐 Common Permissions

```php
// Shield generates these automatically
view_any_{resource}
view_{resource}
create_{resource}
update_{resource}
delete_{resource}
delete_any_{resource}
force_delete_{resource}
force_delete_any_{resource}
restore_{resource}
restore_any_{resource}
replicate_{resource}
reorder_{resource}
```

## 📚 Documentation Links

- [Filament Docs](https://filamentphp.com/docs)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Heroicons](https://heroicons.com)
- [Laravel Docs](https://laravel.com/docs)

## 💡 Pro Tips

1. **Always build after CSS changes**: `npm run build`
2. **Clear cache after config changes**: `php artisan config:clear`
3. **Use SPA mode for better UX**: Already enabled!
4. **Test dark mode**: Toggle in browser
5. **Check responsive**: Use browser dev tools

## 🎯 Performance Tips

```bash
# Production optimization
npm run build                    # Minify assets
php artisan config:cache         # Cache config
php artisan route:cache          # Cache routes
php artisan view:cache           # Cache views
php artisan optimize             # Optimize all
```

## 🚨 Emergency Commands

```bash
# If everything breaks
composer install
npm install
php artisan key:generate
php artisan migrate:fresh --seed
php artisan optimize:clear
npm run build
```

## 📞 Support

### Check These First
1. ✅ Database running?
2. ✅ .env configured?
3. ✅ Assets built? (`npm run build`)
4. ✅ Cache cleared?
5. ✅ Permissions correct?

### Still Issues?
- Check Laravel logs: `storage/logs/laravel.log`
- Check browser console
- Check network tab
- Read error messages carefully

---

## 🎉 Quick Win Checklist

- [ ] Run `composer dev`
- [ ] Access `http://localhost:8000/admin`
- [ ] Create admin user
- [ ] Upload logo
- [ ] Customize colors
- [ ] Test all features
- [ ] Deploy to production

---

**Keep this file handy for quick reference! 📌**

**Dibuat dengan ❤️ untuk SMP Al Wathoniyah 9**
