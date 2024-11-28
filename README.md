![LogoTechCompete](public/dark/img/tsPortada.png)
![LogoTechCompete](public/dark/img/tsLogo.png)


![LogoTechCompete](public/dark/img/logo-cucei-udg.png)

## Integrantes

- Hernández Dávila Alejandro (217525425)
- Núñez Nuño Roberto (217483803)
- Olivares Benítez Isidro Ismael (217415999)

## Tecnologías Utilizadas

- Laravel
- PHP
- Blade
- JavaScript

## Requisitos previos

Este proyecto está configurado para ejecutarse con [Laragon](https://laragon.org/).

Laragon es un entorno de desarrollo local liviano que incluye herramientas como Apache, MySQL, PHP y Node.js.

Asegúrate de tener instalados los siguientes programas:

- [PHP](https://www.php.net/) >= 8.2
- [Composer](https://getcomposer.org/)
- [MySQL](https://www.mysql.com/)
- [Node.js](https://nodejs.org/)

## Instalación de Laragon

1. Descarga Laragon desde su sitio oficial: [Descargar Laragon](https://laragon.org/download/).
2. Instálalo y asegúrate de seleccionar las herramientas necesarias (Apache, MySQL, PHP, Node.js).
3. Asegúrate de que el servicio de Laragon esté en ejecución.

## Implementación del proyecto

```bash
# Clona el repositorio en la carpeta raíz de Laragon (C:\laragon\www).
git clone https://github.com/AlexHD220/TechCompete.git
cd TechCompete

# Verifica que estas en la rama "main"
git branch

# Instala las dependencias de Composer
composer install

# Crea la base de datos MySQL
mysql -u root -p
CREATE DATABASE techcompete;
exit 

# Configura el archivo .env
cp .env.example .env
php artisan key:generate

# Edita el archivo .env para configurar las credenciales de la base de datos de Laragon:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=techcompete
DB_USERNAME=root
DB_PASSWORD=

# Ejecuta las migraciones y semillas
php artisan migrate:fresh
php artisan db:seed

# Instala las dependencias de Node.js y compila los assets
npm install
npm run build

# Crea el enlace simbólico para el almacenamiento de archivos
php artisan storage:link

# Accede al proyecto desde tu navegador
Laragon generará automáticamente un dominio local con la ruta 
http://techcompete.test