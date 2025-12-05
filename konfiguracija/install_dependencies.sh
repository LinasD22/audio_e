#!/bin/bash

set -e

echo "================================"
echo "Audio Shop - Linux Mint/Ubuntu Dependency Installer"
echo "================================"
echo ""

if [ -f /etc/os-release ]; then
    . /etc/os-release
    OS=$ID
    ID_LIKE=${ID_LIKE:-}
    VERSION=$VERSION_ID
else
    echo "Error: Cannot detect Linux distribution"
    exit 1
fi

echo "Detected OS: $OS $VERSION (ID_LIKE=$ID_LIKE)"
echo ""

# Allow Linux Mint, Ubuntu, and derivatives
if ! { [ "$OS" = "ubuntu" ] || [ "$OS" = "linuxmint" ] || echo "$ID_LIKE" | grep -q "ubuntu"; }; then
    echo "This script is intended for Linux Mint/Ubuntu only. Aborting."
    exit 1
fi

echo "Updating package lists..."
sudo apt-get update
sudo apt-get upgrade -y

echo ""
echo "Installing Apache2..."
sudo apt-get install -y apache2
sudo systemctl enable apache2
sudo systemctl start apache2

echo ""
echo "Installing MariaDB (server + client)..."
sudo apt-get install -y mariadb-server mariadb-client
sudo systemctl enable mariadb
sudo systemctl start mariadb

echo ""
echo "Installing PHP and required extensions..."
# Install PHP (default repo version) and common extensions needed by the app
sudo apt-get install -y php libapache2-mod-php php-mysql php-mbstring php-xml php-curl php-gd php-intl php-zip

echo "Restarting Apache to load PHP module..."
sudo systemctl restart apache2

echo ""
read -p "Install phpMyAdmin (web DB admin)? [y/N]: " INSTALL_PMA
if [[ "$INSTALL_PMA" =~ ^[Yy]$ ]]; then
    echo "Installing phpMyAdmin..."
    # Let apt prompt for configuration (dbconfig-common) so user can choose
    sudo apt-get install -y phpmyadmin
    # phpMyAdmin package typically needs Apache integration; enable after install
    sudo systemctl restart apache2
fi

echo ""
echo "Installing helper tools"
sudo apt-get install -y git curl wget nano

echo ""
echo "================================"
echo "installed" 
echo "================================"
echo ""
echo "Next steps"
echo "sudo mysql_secure_installation and create user"
# sudo mysql -u root -p
# then

# CREATE DATABASE audio_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
# CREATE USER 'stud'@'localhost' IDENTIFIED BY 'stud';
# GRANT ALL PRIVILEGES ON audio_shop.* TO 'stud'@'localhost';
# FLUSH PRIVILEGES;
# EXIT;

echo "3) Import DB schema and sample data:"
echo "   mysql -u stud -p audio_shop < $(pwd)/konfiguracija/audio_shop.sql"
# mysql -u stud -p audio_shop < $(pwd)/konfiguracija/audio_shop.sql

echo "4) Point Apache DocumentRoot"

# sudo ln -s /full/path/to/audio_e/www /var/www/html/audio_e
# sudo chown -R www-data:www-data /full/path/to/audio_e/www
# sudo find /full/path/to/audio_e/www -type d -exec chmod 755 {} \;
# sudo find /full/path/to/audio_e/www -type f -exec chmod 644 {} \;
# sudo systemctl restart apache2

