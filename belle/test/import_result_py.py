# RECEIVING COMMAND LINE ARG
import pandas as pd
import sys, os
from os import walk
import re
import time
import stat
import datetime
import pymysql

arg = sys.argv[1]
arg2 = sys.argv[2]

db_settings = {
                "host": "localhost",
                "port": 3306,
                "user": "libo_user",
                "password": "xxx",
                "db": "libodb",
            }

def somefunction(p):
    result=p
    return result

def input_data(dict_sample, df):
    for x in range(7, len(df)):
        #input("pause")
        if re.match(r'^PC', df.iloc[x, 1], re.I) != None:
            df.iloc[x, 1] = "PC"
        if pd.isna(df.iloc[x, 1]):
            continue
        try:
            if dict_sample[df.iloc[x, 1]][0][0] == df.iloc[x, 2].upper().replace(" ", "").replace("GENE", "").replace("&E", ""):
                dict_sample[df.iloc[x, 1]][0].append(df.iloc[x, 6])
            elif dict_sample[df.iloc[x, 1]][1][0] == df.iloc[x, 2].upper().replace(" ", "").replace("GENE", "").replace("P", "").replace("ASE", ""):
                dict_sample[df.iloc[x, 1]][1].append(df.iloc[x, 6])
        except:
            dict_sample[df.iloc[x, 1]] = []
            dict_sample[df.iloc[x, 1]].append(['RDRP'])
            dict_sample[df.iloc[x, 1]].append(['HRN'])
            #print(dict_sample[df.iloc[x, 1]][0][0])
            if dict_sample[df.iloc[x, 1]][0][0] == df.iloc[x, 2].upper().replace(" ", "").replace("GENE", "").replace("&E", ""):
                dict_sample[df.iloc[x, 1]][0].append(df.iloc[x, 6])
            elif dict_sample[df.iloc[x, 1]][1][0] == df.iloc[x, 2].upper().replace(" ", "").replace("GENE", "").replace("P", "").replace("ASE", ""):
                dict_sample[df.iloc[x, 1]][1].append(df.iloc[x, 6])
    return dict_sample

def query_SQL_data(sample_name):
    sample_id=str(sample_name)
    sample_name_full =re.match(r"PC|NTC|^Q[0-9]{9}|^QH[0-9]{9}|^F[0-9]{9}", sample_id)
    if sample_name_full!=None:
        sample_id=sample_name_full[0]
    else:
        sample_id=sample_id
    cmd = "SELECT pcrtest FROM covid_test WHERE sampleid2 = '" + sample_id + "'"
    cursor.execute(cmd)
    #conn.commit()
    result = cursor.fetchone()[0]
    return result

def upload_SQL(sample_name, result_all):
    sample_id=str(sample_name)
    sample_name_full =re.match(r"PC|NTC|^Q[0-9]{9}|^QH[0-9]{9}|^F[0-9]{9}", sample_id)
    if sample_name_full!=None:
        sample_id=sample_name_full[0]
    else:
        sample_id=sample_id

    if result_all.split(',')[-2].upper() == "":
        if result_all.split(',')[-1].upper() in ['POSITIVE', 'NEGATIVE']:
            now = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
            cmd = "UPDATE covid_test SET pcrtest = '" + result_all.split(',')[-1] + "',rdat='" + now + "' WHERE sampleid2 = '" + sample_id + "'"
        else:
            cmd = "UPDATE covid_test SET xlspcrtest2 = 'N', pcrtest = '" + result_all.split(',')[-1] + "' WHERE sampleid2 = '" + sample_id + "'"
    elif result_all.split(',')[-2].upper() == result_all.split(',')[-1].upper() and result_all.split(',')[-1].upper() in ['POSITIVE', 'NEGATIVE']:
        cmd = "UPDATE covid_test SET xlspcrtest2 = 'Y' WHERE sampleid2 = '" + sample_id + "'"
    else:
        cmd = "UPDATE covid_test SET xlspcrtest2 = 'N', pcrtest = '', rdat=NULL WHERE sampleid2 = '" + sample_id + "'"
    cursor.execute(cmd)
    conn.commit()
    # return cmd

def determine_result2(dict_sample):
    for sample_name in dict_sample.keys():
        sample_id=str(sample_name)
        sample_name_full =re.match(r"PC|NTC|^Q[0-9]{9}|^QH[0-9]{9}|^F[0-9]{9}", sample_id)
        if sample_name_full!=None:
            sample_id=sample_name_full[0]
        else:
            sample_id=sample_id
        if dict_sample["PC"][0][1] <= 38 and dict_sample["PC"][1][1] <= 38 and dict_sample["NTC"][0][1] == "Undetermined" and dict_sample["NTC"][1][1] == "Undetermined":
            QC="PC_and_NTC_PASS"
            if sample_id in ['NTC','PC'] or sample_id[1]=="L":
                continue
            if sample_id != "PC" and sample_id != "NTC":
                if dict_sample[sample_name][0][1] != "Undetermined":
                    if dict_sample[sample_name][0][1] <= 38:
                        if dict_sample[sample_name][1][1] == "Undetermined":
                            result = "error"
                            result_0 = query_SQL_data(sample_id)
                            result_all = sample_id + "," + str(dict_sample[sample_name]).replace("[", "").replace("]", "").replace("'", "").replace(" ", "") + "," + result_0 + "," + result #+ ": RdRP&E <= 38 and hRNase == Undetermined "
                            cmd=upload_SQL(sample_id, result_all)
                        else:
                            result = "positive"
                            result_0 = query_SQL_data(sample_id)
                            result_all = sample_id + "," + str(dict_sample[sample_name]).replace("[", "").replace("]", "").replace("'", "").replace(" ", "") + "," + result_0 + "," + result
                            cmd=upload_SQL(sample_id, result_all)
                    elif dict_sample[sample_name][0][1] > 38 and dict_sample[sample_name][0][1] <= 40:
                        if dict_sample[sample_name][1][1] == "Undetermined":
                            result = "error"
                            result_0 = query_SQL_data(sample_id)
                            result_all = sample_id + "," + str(dict_sample[sample_name]).replace("[", "").replace("]", "").replace("'", "").replace(" ", "") + "," + result_0 + "," + result #+ ": 38 < RdRP&E <= 40 and hRNase == Undetermined"
                            cmd=upload_SQL(sample_id, result_all)
                        else:
                            result = "inconclusive"
                            result_0 = query_SQL_data(sample_id)
                            result_all = sample_id + "," + str(dict_sample[sample_name]).replace("[", "").replace("]", "").replace("'", "").replace(" ", "") + "," + result_0 + "," + result
                            cmd=upload_SQL(sample_id, result_all)
                else:
                    if dict_sample[sample_name][1][1] != "Undetermined":
                        if dict_sample[sample_name][1][1] <= 38:
                            result = "negative"
                            result_0 = query_SQL_data(sample_id)
                            result_all = sample_id + "," + str(dict_sample[sample_name]).replace("[", "").replace("]", "").replace("'", "").replace(" ", "") + "," + result_0 + "," + result
                            cmd=upload_SQL(sample_id, result_all)
                        elif dict_sample[sample_name][1][1] > 38 and dict_sample[sample_name][1][1] <= 40:
                            result = "inconclusive"
                            result_0 = query_SQL_data(sample_id)
                            result_all = sample_id + "," + str(dict_sample[sample_name]).replace("[", "").replace("]", "").replace("'", "").replace(" ", "") + "," + result_0 + "," + result
                            cmd=upload_SQL(sample_id, result_all)
                    else:
                        result = "invalid"
                        result_0 = query_SQL_data(sample_id)
                        result_all = sample_id + "," + str(dict_sample[sample_name]).replace("[", "").replace("]", "").replace("'", "").replace(" ", "") + "," + result_0 + "," + result
                        cmd=upload_SQL(sample_id, result_all)
            print(QC,sample_id,result_0,result,cmd)
        else:
            QC="QC_Failure"
            result = "qc_failure"
            print(QC,sample_id,result)

def dataframe(path):
    old_list_path = path
    df = pd.read_excel(old_list_path, sheet_name="Results")
    dict_sample = {}
    dict_sample = input_data(dict_sample, df)
    # determine_result(dict_sample)
    determine_result2(dict_sample)

if somefunction(arg)=="import_file":
    try:
        # 建立Connection物件
        conn = pymysql.connect(**db_settings)
    except Exception as ex:
        print(ex)

    cursor = conn.cursor()
    dataframe(arg2)

    conn.close()