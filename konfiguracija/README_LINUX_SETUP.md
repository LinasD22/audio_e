# Audio Shop - Linux Setup Guide

This guide explains how to set up the Audio Shop project on Linux.

## Quick Start

### 1. Run the Installation Script

```bash
cd konfiguracija
sudo bash install_dependencies.sh
```

This script will automatically install:
- Apache2 web server
- PHP 8.x with required extensions (MySQLi, mbstring, xml, curl, gd, intl)
- MariaDB database server
- Git, curl, wget, nano

### 2. Configure MariaDB

After installation, secure your MariaDB installation:

```bash
sudo mysql_secure_installation
```

### 3. Create Database and User

```bash
sudo mysql -u root -p
```

Inside the MySQL prompt, run:

```sql
CREATE DATABASE audio_shop;
CREATE USER 'stud'@'localhost' IDENTIFIED BY 'stud';
GRANT ALL PRIVILEGES ON audio_shop.* TO 'stud'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 4. Import Database Schema

```bash
cd konfiguracija
mysql -u stud -p audio_shop < scheme.sql
```

When prompted, enter password: `stud`

### 5. Import Sample Data (Optional)

```bash
mysql -u stud -p audio_shop < inser_mock_data.sql
```

### 6. Configure Apache

#### Option A: Create Symlink (Recommended)
```bash
sudo ln -s /path/to/audio_e/www /var/www/html/audio_shop
```

#### Option B: Configure VirtualHost
Create `/etc/apache2/sites-available/audio_shop.conf`:

```apache
<VirtualHost *:80>
    ServerName audio-shop.local
    DocumentRoot /path/to/audio_e/www
    
    <Directory /path/to/audio_e/www>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/audio_shop_error.log
    CustomLog ${APACHE_LOG_DIR}/audio_shop_access.log combined
</VirtualHost>
```

Then enable it:
```bash
sudo a2ensite audio_shop
sudo systemctl restart apache2
```

### 7. Verify Installation

Check Apache:
```bash
sudo systemctl status apache2
```

Check PHP:
```bash
php -v
php -m | grep mysqli
```

Check MariaDB:
```bash
mysql -u stud -p -h localhost audio_shop -e "SELECT VERSION();"
```

### 8. Test the Application

Open your browser and navigate to:
- http://localhost/audio_shop (if using symlink)
- http://audio-shop.local (if using VirtualHost)

## Troubleshooting

### PHP Extensions Not Loading
If you get "Call to undefined function mysqli_connect()":
```bash
sudo apt-get install php-mysql
sudo systemctl restart apache2
```

### Permission Denied on Files
```bash
sudo chown -R www-data:www-data /path/to/audio_e
sudo chmod -R 755 /path/to/audio_e
```

### MariaDB Connection Failed
Verify credentials in `konfiguracija/nustatymai.php`:
```php
define("DB_USER",   "stud");
define("DB_PASS",   "stud");
define("DB_NAME",   "audio_shop");
```

### Apache Document Root Issues
Check Apache configuration:
```bash
sudo apache2ctl -S
```

## System Requirements

- **OS**: Debian/Ubuntu, Fedora/RHEL/CentOS
- **PHP**: 7.4+ (8.0+ recommended)
- **MariaDB**: 10.3+
- **Apache**: 2.4+
- **Disk Space**: ~500MB

## Default Credentials

After setup, you can log in with:

### Admin (Vadybininkas)
- Username: `vadybininkas1`
- Password: `h`

### Accountant (Buhalteris)
- Username: `buhalteris1`
- Password: `hash123`

### Regular User
- Username: `jonas`
- Password: `hash123`

See `inser_mock_data.sql` for more test accounts.

## Additional Notes

- The script detects your Linux distribution and uses appropriate package managers
- All services are enabled to start on boot
- Update your hosts file if using VirtualHost:
  ```bash
  sudo nano /etc/hosts
  # Add: 127.0.0.1 audio-shop.local
  ```
