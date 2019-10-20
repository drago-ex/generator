wget https://raw.githubusercontent.com/Vincit/travis-oracledb-xe/master/accept_the_license_agreement_for_oracledb_xe_11g_and_install.sh
wget https://raw.githubusercontent.com/accgit/docker/master/docker-oracle/oracle/oracle-instantclient12.2-basic_12.2.0.1.0-2_amd64.deb
wget https://raw.githubusercontent.com/accgit/docker/master/docker-oracle/oracle/oracle-instantclient12.2-devel_12.2.0.1.0-2_amd64.deb

bash accept_the_license_agreement_for_oracledb_xe_11g_and_install.sh

sudo dpkg -i oracle-instantclient12.2-basic_12.2.0.1.0-2_amd64.deb
sudo dpkg -i oracle-instantclient12.2-devel_12.2.0.1.0-2_amd64.deb

echo 'instantclient,/usr/lib/oracle/12.2/client64/lib' | pecl install oci8
