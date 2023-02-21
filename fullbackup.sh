#!/bin/bash
UNAME="cpanelusername"

cd /home/$UNAME/backup/file

zip -r public_html.zip /home/$UNAME/public_html

zip -r mail.zip /home/$UNAME/mail

backup () {
mysqldump -u "$UNAME"_mysqluser -pmysqlpassword $dbs | zip > $dbs-$(date +"%Y.%m.%d").sql.zip
}

for dbs in $(mysql -u "$UNAME"_mysqluser -pmysqlpassword -e 'show databases' | sed 1d);

do if [ "$dbs" != "mysql" ] && [ "$dbs" != "information_schema" ] && [ "$dbs" != "performance_schema" ];

then backup;

fi;

done;

exit 0
