import pandas as pd
import re
import sys,os

arg = sys.argv[1]
arg2 = sys.argv[2]

def somefunction(p):
    result=p
    return result

def input_data(dict_sample, df):
    for x in range(7, len(df)):
        #input("pause")
        if pd.isna(df.iloc[x, 1]):
            continue
        if re.match(r'^PC', df.iloc[x, 1], re.I) != None:
            df.iloc[x, 1] = "PC"
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

def dataframe(path):
    old_list_path = path
    df = pd.read_excel(old_list_path, sheet_name="Results")
    dict_sample = {}
    dict_sample = input_data(dict_sample, df)
    result=determine_result2(dict_sample)
    return result

def determine_result2(dict_sample):
    result_array = []
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
                        else:
                            result = "positive"
                    elif dict_sample[sample_name][0][1] > 38 and dict_sample[sample_name][0][1] <= 40:
                        if dict_sample[sample_name][1][1] == "Undetermined":
                            result = "error"
                        else:
                            result = "inconclusive"
                else:
                    if dict_sample[sample_name][1][1] != "Undetermined":
                        if dict_sample[sample_name][1][1] <= 38:
                            result = "negative"
                        elif dict_sample[sample_name][1][1] > 38 and dict_sample[sample_name][1][1] <= 40:
                            result = "inconclusive"
                    else:
                        result = "invalid"
                result_array.append({
                    "QC":QC,
                    "sampleid":sample_id,
                    "result":result
                })
        else:
            if sample_id in ['NTC','PC'] or sample_id[1]=="L":
                continue
            else:
                QC="QC_Failure"
                result = "qc_failure"
                result_array.append({
                    "QC":QC,
                    "sampleid":sample_id,
                    "result":result
                })
    return(result_array)

if somefunction(arg)=="import_file":
    result_array=dataframe(arg2)
    for a in result_array:
        print(a["sampleid"])
        print(a["QC"])
        print(a["result"])