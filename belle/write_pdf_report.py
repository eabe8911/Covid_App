# encoding=utf-8
import os
import sys
from PyPDF2 import PdfFileWriter, PdfFileReader
import io
from reportlab.pdfgen import canvas
from reportlab.lib.pagesizes import letter
from reportlab.lib.units import cm
from PIL import Image
import reportlab.rl_config
import pymysql
import datetime

#need to change mysqldatabase name and path

def find_sample_info(PCRid):
    #連線mysql
    db_settings = {
                    "host": "localhost",
                    "port": 3306,
                    "user": "libo_user",
                    "password": "xxx",
                    "db": "libodb",
                }
    try:
        # 建立Connection物件
        conn = pymysql.connect(**db_settings)
    except Exception as ex:
        print(ex)
        sys.exit()
    else:
        cursor = conn.cursor()
        
        ##################################### Need to be modified
        cmd = "SELECT sex,fname,dob,pcrtest,passportid,vuser2,tdat,rdat,nationality FROM covid_trans WHERE sampleid2='" + PCRid + "'"
        #####################################

        cursor.execute(cmd)
        Infos = cursor.fetchone()
    finally:
        conn.close()
        return Infos

path = '/var/www/html/pdf_reports/Japanese_report/'
pcrid = sys.argv[1]

#Find sample infoormation
Infos = find_sample_info(pcrid)
sex,fname,dob,pcrtest,passportid,vuser2,tdat,rdat,nationality = Infos[:]

if nationality!="":
    #Processing PDF template
    reportlab.rl_config.warnOnMissingFontGlyphs = 0

    size = 12
    font = "Times-Roman"

    packet = io.BytesIO()
    #move to the beginning of the StringIO buffer
    packet.seek(0)
    can = canvas.Canvas(packet, pagesize=letter)
    can.setFont(font, size)

    #modify the location of input text
    #Now
    now = datetime.datetime.now().strftime("%Y /%m /%d")
    can.drawString(13.5*cm, 23.8*cm, now) #20220830 olive check new japan report
    #can.drawString(15.5*cm, 24.2*cm, now)
    #English name
    can.drawString(4.5*cm, 22.5*cm, fname)
    #PassportID
    #can.drawString(12*cm, 23*cm, passportid)


    ##################################### Need to be modified
    #Nationality
    # can.drawString(5.3*cm, 22.1*cm, "Nationality")
    #can.drawString(5.3*cm, 22.1*cm, nationality)
    #####################################


    #Birth
    dob = str(dob).replace('-', ' /')
    can.drawString(14*cm, 22.5*cm, dob) #20220830 olive check new japan report
    #can.drawString(10.5*cm, 22.1*cm, dob)
    #Sex
    # sex = sex.split('/')[-1].strip()
    # can.drawString(14.0*cm, 22.1*cm, sex)
    #Institution
    can.drawString(11.0*cm, 3.8*cm, "Lihpao Medical Laboratory") #20220830 olive check new japan report
    #can.drawString(11.0*cm, 4.3*cm, "Lihpao Medical Laboratory")
    #address of the institution
    size = 8
    can.setFont(font, size)
    # can.drawString(11.0*cm, 3.7*cm, "7F., No. 137, Sec. 2, Jianguo N. Rd., Zhongshan Dist.,")
    # can.drawString(11.0*cm, 3.3*cm, "Taipei City 104, Taiwan (R.O.C.)")

    #modify the location of input picture
    # if vuser2 == 'B122408253':#陳柏源-離職
    #     pic = '/var/www/html/pdf_reports/pic/0570.png'
    # elif vuser2 == 'N123478768':#陳毓峻-離職
    #     pic = '/var/www/html/pdf_reports/pic/0564.png'
    # elif vuser2 == 'H123160258':#鄭偉志
    #     pic = '/var/www/html/pdf_reports/pic/0550.png'
    # elif vuser2 == 'P124237860':#陳奕勳
    #     pic = '/var/www/html/pdf_reports/pic/0559.png'
    # elif vuser2 == 'P222717661':#李桂榕-離職
    #     pic = '/var/www/html/pdf_reports/pic/0544.png'  
    # elif vuser2 == 'N225198185':#黃琴涵-離職
    #     pic = '/var/www/html/pdf_reports/pic/0561.png'  
    # elif vuser2 == 'A225558000':#許育華-離職
    #     pic = '/var/www/html/pdf_reports/pic/0513.png'  
    # elif vuser2 == 'D221222459':#石鴻瑞
    #     pic = '/var/www/html/pdf_reports/pic/0581.png'   
    # elif vuser2 == 'R220853399':#曾彥瑜
    #     pic = '/var/www/html/pdf_reports/pic/0558.png'
    # else:
    #     pic = ''

    # if pic != '':
    #     image = Image.open(pic)
    #     can.drawImage(pic, 12*cm, 2.47*cm, width=image.width*0.4, height=image.height*0.4, mask="auto")
    # else:
    #     pass


    #can.drawImage("blank.png", 17.5*cm, 2.4*cm, width=80, height=50, mask=None)
    image = Image.open("/var/www/html/pdf_reports/pic/medical_lab.png")
    can.drawImage("/var/www/html/pdf_reports/pic/medical_lab.png", 17.3*cm, 2.0*cm, width=image.width*0.75, height=image.height*0.75, mask=None)
    #image = Image.open("pic.png")
    #can.drawImage("pic.png", 12*cm, 2.5*cm, width=image.width*0.7, height=image.height*0.7, mask="auto")
    can.showPage()
    can.save()

    packet2 = io.BytesIO()
    packet2.seek(0)
    can2 = canvas.Canvas(packet2, pagesize=letter)
    can2.setFillColorRGB(255,0,0)
    size = 12
    can2.setFont("Courier", size)
    can.setFont('Courier-Bold', 10)
    can2.drawString(1.4*cm, 16*cm, "✓") #20220830 olive check new japan report
    can2.drawString(5.4*cm, 16*cm, "✓") #20220830 olive check new japan report
    #can2.drawString(1.55*cm, 16.9*cm, "✓")
    #can2.drawString(5.55*cm, 16.9*cm, "✓")
    #pcrtest
    if pcrtest.lower() == 'negative':
        can2.drawString(12.1*cm, 16*cm, "✓") #20220830 olive check new japan report
    elif pcrtest.lower() == 'positive':
        can2.drawString(12.1*cm, 14*cm, "✓") #20220830 olive check new japan report
    # if pcrtest.lower() == 'negative':
    #     can2.drawString(12.3*cm, 16.9*cm, "✓")
    # elif pcrtest.lower() == 'positive':
    #     can2.drawString(12.3*cm, 15.65*cm, "✓")
    else:
        print("No PCR result was found !")
        
    size = 9
    can2.setFont("Times-Roman", size)
    can.setFont('Times-Bold', 15)
    #rdat
    # rdat = str(rdat).split(' ')[0]
    # rdat_year,rdat_month,rdat_day = rdat.split('-')[:]
    # can2.drawString(15.5*cm, 16*cm, rdat_year)
    # can2.drawString(16.44*cm, 16*cm, rdat_month)
    # can2.drawString(17.1*cm, 16*cm, rdat_day)

    #tdat
    tdat = str(tdat)
    tdat_year,tdat_month,tdat_day,tdat_hour,tdat_min = tdat.split(' ')[0].split('-')[:] + tdat.split(' ')[1].split(':')[:2]

    can2.drawString(15.6*cm, 15.6*cm, tdat_year) #20220830 olive check new japan report
    can2.drawString(16.5*cm, 15.6*cm, tdat_month) #20220830 olive check new japan report
    can2.drawString(17.2*cm, 15.6*cm, tdat_day) #20220830 olive check new japan report
    # can2.drawString(15.5*cm, 13.89*cm, tdat_year)
    # can2.drawString(16.44*cm, 13.89*cm, tdat_month)
    # can2.drawString(17.1*cm, 13.89*cm, tdat_day)
    if int(tdat_hour) >= 12:
        tdat_hour = int(tdat_hour) - 12
        if tdat_hour < 10:
            tdat_hour = '0' + str(tdat_hour)
        else:
            tdat_hour = str(tdat_hour)
        can2.drawString(16.6*cm, 14.3*cm, tdat_hour)
        can2.drawString(17.2*cm, 14.3*cm, tdat_min)
        # can2.drawString(16.5*cm, 13.45*cm, tdat_hour)
        # can2.drawString(17.1*cm, 13.45*cm, tdat_min)
        size = 22
        can2.setFont("Courier", size)
        can.setFont('Courier-Bold', 10)
        can2.drawString(15.9*cm, 14.3*cm, "O")
        #can2.drawString(15.9*cm, 13.35*cm, "O")
    else:
        can2.drawString(16.6*cm, 14.3*cm, tdat_hour)
        can2.drawString(17.6*cm, 14.3*cm, tdat_min)
        # can2.drawString(16.5*cm, 13.45*cm, tdat_hour)
        # can2.drawString(17.1*cm, 13.45*cm, tdat_min)
        size = 22
        can2.setFont("Courier", size)
        can.setFont('Courier-Bold', 10)
        can2.drawString(15.42*cm, 14.3*cm, "O")
        #can2.drawString(15.42*cm, 13.35*cm, "O")
    can2.showPage()
    can2.save()

    # create a new PDF with Reportlab
    new_pdf = PdfFileReader(packet)
    new_pdf2 = PdfFileReader(packet2)
    # read your existing PDF
    existing_pdf = PdfFileReader(open("/var/www/html/templates/Japan.pdf", "rb"))
    output = PdfFileWriter()
    # add the "watermark" (which is the new pdf) on the existing page
    page = existing_pdf.getPage(0)
    page.mergePage(new_pdf.getPage(0))
    #output.addPage(page)
    page.mergePage(new_pdf2.getPage(0))
    output.addPage(page)
    # finally, write "output" to a real file

    #####################################Need to be modified
    tdate = str(tdat).split(' ')[0]
    if tdate in os.listdir('/var/www/html/pdf_reports/Japanese_report/'):
        pass
    else:
        os.system('mkdir /var/www/html/pdf_reports/Japanese_report/' + tdate)
        os.system('chmod 777 /var/www/html/pdf_reports/Japanese_report/' + tdate)

    outputStream = open("/var/www/html/pdf_reports/Japanese_report/{}/{}_Japanese_report.pdf".format(tdate ,pcrid), "wb")
    output.write(outputStream)
    outputStream.close()
    os.system('chmod 777 /var/www/html/pdf_reports/Japanese_report/{}/{}_Japanese_report.pdf'.format(tdate,pcrid))
    print("<script>alert('入境日本證明報告位於後台伺服器 /var/www/html/pdf_reports/Japanese_report/{}/{}_Japanese_report.pdf 路徑');</script>".format(tdate ,pcrid) )
    #####################################

else:
    print("<script>alert('未輸入國籍，不需產出入境日本證明報告。');</script>")