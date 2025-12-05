#!/bin/bash

set -e

echo "================================"
echo "Audio Shop - Start Services"
echo "================================"
echo ""

# Check if services are installed
echo "Checking if required services are installed..."

if ! command -v apache2 &> /dev/null && ! command -v httpd &> /dev/null; then
    echo "Error: Apache is not installed. Run install_dependencies.sh first."
    exit 1
fi

if ! command -v mysql &> /dev/null && ! command -v mariadb &> /dev/null; then
    echo "Error: MySQL/MariaDB is not installed. Run install_dependencies.sh first."
    exit 1
fi

if ! command -v php &> /dev/null; then
    echo "Error: PHP is not installed. Run install_dependencies.sh first."
    exit 1
fi

echo "All required services are installed."
echo ""

# Start Apache
echo "Starting Apache web server..."
if systemctl list-unit-files | grep -q apache2.service; then
    sudo systemctl start apache2
    sudo systemctl status apache2 --no-pager | head -5
elif systemctl list-unit-files | grep -q httpd.service; then
    sudo systemctl start httpd
    sudo systemctl status httpd --no-pager | head -5
fi

echo ""

# Start MariaDB/MySQL
echo "Starting MariaDB/MySQL database..."
if systemctl list-unit-files | grep -q mariadb.service; then
    sudo systemctl start mariadb
    sudo systemctl status mariadb --no-pager | head -5
elif systemctl list-unit-files | grep -q mysql.service; then
    sudo systemctl start mysql
    sudo systemctl status mysql --no-pager | head -5
fi

echo ""
echo "================================"
echo "Services started successfully!"
echo "================================"
echo ""
echo "Service status:"
echo "- Apache: http://localhost"
echo "- phpMyAdmin (if installed): http://localhost/phpmyadmin"
echo "- Audio Shop: http://localhost/audio_e"
echo ""
echo "To check service status manually:"
echo "  sudo systemctl status apache2"
echo "  sudo systemctl status mariadb"
echo ""
echo "To stop services:"
echo "  sudo systemctl stop apache2"
echo "  sudo systemctl stop mariadb"
echo ""
#sudo cp /home/stud/audio_e/konfiguracija/audio.conf /etc/apache2/sites-available/
#sudo a2ensite audio.conf
#sudo service apache2 restart