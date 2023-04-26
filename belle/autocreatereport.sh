#!/bin/bash

if python3 autocreatereport.py 
then
        echo "Running"
else
        echo "Stopped at:" >> autocreatereport_log.txt
        date >>autocreatereport_log.txt
		python3 -c 'import sys;sys.path.append("../pdf_reports/");from check_pdf_folder_linux import sent_error_message;sent_error_message("autocreatereport 程式發生錯誤，5 秒後重啟")'
        sleep 5
        echo "Sleep for 5sec" >>autocreatereport_log.txt
		python3 -c 'import sys;sys.path.append("../pdf_reports/");from check_pdf_folder_linux import sent_error_message;sent_error_message("執行 autocreatereport 程式重啟...")'
        python3 autocreatereport.py
        if echo $? == "1: command not found"
        then
		echo "Stopped again at:">>autocreatereport_log.txt
		date >> autocreatereport_log.txt
		python3 -c 'import sys;sys.path.append("../pdf_reports/");from check_pdf_folder_linux import sent_error_message;sent_error_message("執行 autocreatereport 程式重啟失敗，程式終止")'
        else
		echo "Restart_success at: ">>autocreatereport_log.txt
		date >> autocreatereport_log.txt
        fi
fi

