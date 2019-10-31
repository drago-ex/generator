#!/bin/sh -e

# Script and guide to setup Oracle Database Express Edition on Travis CI
# Details of the script and author can be found at: https://github.com/Vincit/travis-oracledb-xe
wget https://raw.githubusercontent.com/Vincit/travis-oracledb-xe/master/accept_the_license_agreement_for_oracledb_xe_11g_and_install.sh
wget https://raw.githubusercontent.com/Vincit/travis-oracledb-xe/master/test_db.sh

# Run install Oracle Database Express Edition 11g Release 2
bash accept_the_license_agreement_for_oracledb_xe_11g_and_install.sh

# Test the database after installation
bash test_db.sh

# Downlaod Oracle Instant Client
wget https://raw.githubusercontent.com/accgit/docker/master/docker-oracle/oracle/oracle-instantclient12.2-basic_12.2.0.1.0-2_amd64.deb
wget https://raw.githubusercontent.com/accgit/docker/master/docker-oracle/oracle/oracle-instantclient12.2-devel_12.2.0.1.0-2_amd64.deb

# Installation
sudo dpkg -i oracle-instantclient12.2-basic_12.2.0.1.0-2_amd64.deb
sudo dpkg -i oracle-instantclient12.2-devel_12.2.0.1.0-2_amd64.deb

# Add support OCI8 for PHP
pecl channel-update pecl.php.net
echo 'instantclient,/usr/lib/oracle/12.2/client64/lib' | pecl install oci8
