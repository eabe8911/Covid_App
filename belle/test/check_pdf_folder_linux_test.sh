#!/bin/bash
while :
do
    compteur=`ps -ef | grep python3" "check_pdf_folder_linux_test.py | grep -v "grep" | wc -l`

    if [ "$compteur" == "0" ]
    then
        echo " Process terminated "
        exit 1
    else
        echo " 有找到程式  $compteur"
    fi
    sleep 10;
done
