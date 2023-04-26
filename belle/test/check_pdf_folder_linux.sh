#!/bin/bash

if python3 check_pdf_folder_linux_test.py 
then
        echo "Running"
else
        echo "Stopped at:" >> check_pdf_folder_linux_log.txt
        date >>check_pdf_folder_linux_log.txt
		python3 -c 'from check_pdf_folder_linux import sent_error_message;sent_error_message("line 通知程式發生錯誤，5 秒後重啟")'
        sleep 5
        echo "Sleep for 5sec" >>check_pdf_folder_linux_log.txt
		python3 -c 'from check_pdf_folder_linux import sent_error_message;sent_error_message("執行 line 通知程式重啟...")'
        python3 check_pdf_folder_linux_test.py
        if echo $? == "1: command not found"
        then
		echo "Stopped again at:">>check_pdf_folder_linux_log.txt
		date >> check_pdf_folder_linux_log.txt
		python3 -c 'from check_pdf_folder_linux import sent_error_message;sent_error_message("line 通知程式重啟失敗，程式終止")'
        else
		echo "Restart_success at: ">>check_pdf_folder_linux_log.txt
		date >> check_pdf_folder_linux_log.txt
        fi
fi

