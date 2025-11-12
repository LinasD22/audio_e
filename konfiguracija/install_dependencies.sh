#!/bin/bash

set -e 

echo "================================"
echo "Audio Shop - Dependency Installer"
echo "================================"
echo ""

if [ -f /etc/os-release ]; then
    . /etc/os-release
    OS=$ID
    VERSION=$VERSION_ID
else
    echo "Error: Cannot detect Linux distribution"
    exit 1
fi

echo "Detected OS: $OS $VERSION"
echo ""
# Update package manager
echo "Updating package manager..."
if [ "$OS" = "debian" ] || [ "$OS" = "ubuntu" ]; then
    sudo apt-get update
    sudo apt-get upgrade -y
elif [ "$OS" = "fedora" ] || [ "$OS" = "rhel" ] || [ "$OS" = "centos" ]; then
    sudo dnf update -y
else
    echo "Warning: Unsupported distribution. Skipping package update."
fi

echo ""
echo "Installing Apache2..."
if [ "$OS" = "debian" ] || [ "$OS" = "ubuntu" ]; then
    sudo apt-get install -y apache2
    sudo systemctl enable apache2
    sudo systemctl start apache2
elif [ "$OS" = "fedora" ] || [ "$OS" = "rhel" ] || [ "$OS" = "centos" ]; then
    sudo dnf install -y httpd
    sudo systemctl enable httpd
    sudo systemctl start httpd
fi

echo ""
echo "Installing PHP and extensions..."
if [ "$OS" = "debian" ] || [ "$OS" = "ubuntu" ]; then
    sudo apt-get install -y php php-cli php-mysql php-mbstring php-xml php-curl php-gd php-intl
    sudo a2enmod php8.2  # Enable PHP module (adjust version if needed)
    sudo systemctl restart apache2
elif [ "$OS" = "fedora" ] || [ "$OS" = "rhel" ] || [ "$OS" = "centos" ]; then
    sudo dnf install -y php php-cli php-mysql php-mbstring php-xml php-curl php-gd php-intl
    sudo systemctl restart httpd
fi

echo ""
echo "Installing MariaDB..."
if [ "$OS" = "debian" ] || [ "$OS" = "ubuntu" ]; then
    sudo apt-get install -y mariadb-server mariadb-client
    sudo systemctl enable mariadb
    sudo systemctl start mariadb
elif [ "$OS" = "fedora" ] || [ "$OS" = "rhel" ] || [ "$OS" = "centos" ]; then
    sudo dnf install -y mariadb-server mariadb
    sudo systemctl enable mariadb
    sudo systemctl start mariadb
fi

echo ""
echo "Installing additional tools..."
if [ "$OS" = "debian" ] || [ "$OS" = "ubuntu" ]; then
    sudo apt-get install -y git curl wget nano
elif [ "$OS" = "fedora" ] || [ "$OS" = "rhel" ] || [ "$OS" = "centos" ]; then
    sudo dnf install -y git curl wget nano
fi

echo ""
echo "================================"
echo "Installation Complete!"
echo "================================"
echo ""
