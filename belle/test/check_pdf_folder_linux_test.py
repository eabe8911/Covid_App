# -*- encoding: utf-8 -*-
import pandas as pd
import requests
import sys, os
from os import walk
import time
import re
from requests.exceptions import ConnectionError
from time import gmtime, strftime
import traceback

########### "pdf_file_list_old.csv" and this pipeline must in the same folder!! ######

####修改權杖
token = 'HPsbEsJyaf0OSkuQRdAZgIHuajpOILWHBBf6xRnDJOR' #姿伶測試用


####修改PDF檔案路徑
new_data_file = "/var/www/html/belle/test/pdf_reports/"
pre_new_data = []
dict_new_data = {}

####修改pdf_file_list_old.csv路徑
old_list_path = "/var/www/html/belle/test/pdf_reports/pdf_file_list_old.csv"

def lineNotifyMessage(token, msg):
    headers = {
        "Authorization": "Bearer " + token,
        "Content-Type" : "application/x-www-form-urlencoded"
    }
    payload = {'message': msg}
    r = requests.post("https://notify-api.line.me/api/notify", headers = headers, params = payload)
    return r.status_code

def sent_message(file_name_list, dict_):
    #message = '趕快寄報告!!' + file_name
    sent_message = []
    for x in range(0, len(file_name_list)):
        sent_message.append(dict_[file_name_list[x]] + " :  " + file_name_list[x])
    message = '共' + str(len(file_name_list)) + '份報告已完成，請盡快覆核與寄送報告!!\n' + '\n'.join(sent_message)
    print(message)
    lineNotifyMessage(token, message)

def sent_error_message(string):
    message=string
    # token = 'SGboRS8EqetP2n3qU6zrg0EQTR402dcJy0fsbFAvwYe'  #BI測試用
    lineNotifyMessage(token, message)
    print(message)

def update_old_list(old_data):####更新資料
    with open(old_list_path, 'w') as g:
        for x in range(len(old_data)):
            if x != len(old_data) - 1:
                g.write(old_data[x] + ",")
            else:
                g.write(old_data[x])

if __name__ == '__main__':
    try:
        #check_test = "xxxxxxxxxx.pdf"
        #check_test_1 = "xxxxxxxxxx_SSSSSS.pdf"
        p_date = re.compile(r'^\d\d\d\d-\d\d-\d\d$')
        p_pdf_0 = re.compile(r'^.*_.*\.pdf$')
        p_pdf_1 = re.compile(r'^.*\.pdf$')
        #print(p_pdf_0.match(check_test))
        #print(p_pdf_0.match(check_test_1))
        #input("pause")
        while True:
            ####讀取新資料目錄PDF
            for root, dirs, files in walk(new_data_file):
                if p_date.match(root.split('/')[-1]) != None:
                    for x in files:
                        if p_pdf_0.match(x) == None and p_pdf_1.match(x) != None:
                            dict_new_data[x] = root.split('/')[-1]
                            pre_new_data.append(x)
            new_data = list(set(pre_new_data))
            #print(new_data)
            #input('pause_select file')

            ####取舊資料
            with open(old_list_path, 'r') as g:
                old_data = g.read().replace('\n', '').split(",")
            #print(old_data)

            ####比對資料
            file_name_list = []
            for x in range(len(new_data)):
                if new_data[x] not in old_data:
                    print("sent_message: " + new_data[x])
                    file_name_list.append(new_data[x])

            # if len(file_name_list) > 0:
            #     sent_message(file_name_list, dict_new_data)
            #     old_data.extend(file_name_list)
            #     update_old_list(old_data)

            if len(file_name_list) > 0:
            #sent_message(file_name_list, dict_new_data)
            #'''
                try:
                    sent_message(file_name_list, dict_new_data)
                except requests.exceptions.ConnectionError:
                    print("Connection Error")
                    time=str(strftime("%Y-%m-%d %H:%M:%S", gmtime()))
                    with open("check_pdf_folder_linux_log.txt", "a",encoding="utf-8") as file_object:
                        file_object.write("current time: "+time+"\nconnection error\n")
                        file_object.close()
                    time.sleep(5)
                    continue
                #'''
                old_data.extend(file_name_list)
                update_old_list(old_data)

            print("sleep 5s")
            time.sleep(5)
    except Exception as ex:
        ex_str=str(ex)
        traceback_str=str(traceback.format_exc())
        time=str(strftime("%Y-%m-%d %H:%M:%S", gmtime()))
        print("current time:"+time+"\nthis is ex message: \n"+ex_str+"\nthis is traceback message: \n"+traceback_str)
        with open("check_pdf_folder_linux_log.txt", "a",encoding="utf-8") as file_object:
            file_object.write("\nthis is ex message: \ncurrent time: "+time+"\n"+ex_str)
            file_object.write("\nthis is traceback message: \ncurrent time: "+time+"\n"+traceback_str)
            file_object.close()


