# Configurations

---

## 1. Requirements 
    - PHP 8.5+
    - composer 8.5.6 + 

## 2. Configure Project 
```bash
git clone https://github.com/khaledfathi/documents-vault.git
cd documents-vault/
cp .env.example .env 
composer install
php artisan key:generate
```
- **Note**: Laravel 12+ uses SQLite as the default DBMS. You can change this in your ***.env*** file to any DBMS that Laravel supports.

```bash 
php artisan migrate
php artisan db:seed
php artisan storage:link
```

- Run the Local Development Server

```bash 
php artisan serve 
```

- Run on the default port:

```bash 
php artisan serve --port=<port_number>
```
- Run on a specific host IP and port:

```bash 
php artisan serve --host=0.0.0.0 --port=<port_number>
```

## 3. IMPORTANT !

- The application allows file uploads up to 160 MB. To support this, make sure to update your php.ini file with the following settings:

```ini
file_uploads = On
memory_limit = 512M
post_max_size = 170M
upload_max_filesize = 160M
max_file_uploads = 20
```

## 4. NOTE !

- This project is currently under active development, so some features have not yet been implemented.


