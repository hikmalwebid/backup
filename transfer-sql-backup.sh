#!/bin/bash

cd /home/cpanelaccount/folder

HOST=FTPhost
USER=FTPuser
PASSWORD=FTPass

ftp -inv $HOST <<EOF
user $USER $PASSWORD
cd /folder
binary
mput *.sql.zip
bye
EOF

rm -rf *.sql.zip
