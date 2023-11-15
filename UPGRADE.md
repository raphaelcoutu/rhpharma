# Utile pour faciliter la mise à jour Laravel (ex: v8.x à v9.x)

Important de travailler sur une branche propre.

- `git remote add -f laravel https://github.com/laravel/laravel`
- `git remote update`
- `git diff main remotes/laravel/9.x <directory> > <directory>.patch`
- `git apply <directory>.patch`
- Comparer dans VS Code
- `git remote rm laravel`

Ménage des tags:
- `git tag -l | xargs git tag -d`
- `git fetch --tags`

# Marche à suivre pour mettre à jour

- `git pull`
- `composer install`
- `rm -rf node_modules`
- `npm install`
- `npm run prod`
- `php artisan migrate`
- `php artisan cache:clear`
- `php artisan config:cache`
- `php artisan route:cache`
- `php artisan view:cache`
