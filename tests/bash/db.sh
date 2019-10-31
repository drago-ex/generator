export ORACLE_HOME=/u01/app/oracle/product/11.2.0/xe;
export ORACLE_SID=XE;
$ORACLE_HOME/bin/sqlplus -S travis/travis <<SQL
whenever sqlerror exit 2;
CREATE TABLE test (sample_id INTEGER NOT NULL PRIMARY KEY, sample_string VARCHAR2(255) NOT NULL);
CREATE TABLE error (id INTEGER NOT NULL PRIMARY KEY, error(...) VARCHAR2(255) NOT NULL);
SQL
