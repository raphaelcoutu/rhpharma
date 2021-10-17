# RhPharma - Docker

## Version WSL

```bash
# Build le Dockerfile pour rhpharma/app
docker-compose up -d

# Création du fichier .env
cp .env.example .env
sed -i 's/APP_URL=.*/APP_URL=http://localhost:8080/;  s/DB_HOST=.*/DB_HOST=mysql/; s/DB_DATABASE=.*/DB_DATABASE=rhpharma/; s/DB_USERNAME=.*/DB_USERNAME=root/; s/DB_PASSWORD=.*/DB_PASSWORD=admin/' .env
```
Au besoin, il faut spécifier le UID du WWWUSER dans le .env (à exécuter dans WSL, pas dans le container)
```console
$ id
uid=1000(raph244) gid=1000(raph244) groups=1000(raph244),4(adm),20(dialout),24(cdrom),25(floppy),27(sudo),29(audio),30(dip),44(video),46(plugdev),117(netdev),1001(docker)
```

On peut donc spécifier dans le fichier .env
```
WWWUSER=1000
```

```
# On doit se connecter comme l'utilisateur vessel pour éviter problèmes de permissions
docker compose exec -u vessel app bash

composer install
php artisan key:generate
php artisan key:migrate


# Installer nodejs (cf github.com/nodesource/distributions)
npm install
npm run dev
```
