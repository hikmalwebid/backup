#!/bin/bash

cd /home/cpanelaccount/folder

backup () {
mysqldump -u mysql_user -pmysql_password $dbs | zip > $dbs-$(date +"%Y.%m.%d").sql.zip
}

for dbs in $(mysql -u mysql_user -pmysql_password -e 'show databases' | sed 1d);

do if [ "$dbs" != "mysql" ] || [ "$dbs" != "information_schema" ] || [ "$dbs" != "performance_schema" ];

then backup;

fi;

done;

exit 0
