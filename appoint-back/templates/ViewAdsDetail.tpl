<!--POP UP MODAL TO VIEW MEMBER DETAILS AND RESULTS FOR CONSULTATION-->
<form method="post" action="" name="FormViewAdsDetail" id="FormViewAdsDetail">
<input type="hidden" id="UserName" name="UserName" value="{$UserName}">
<div id="ViewAdsDetail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none;">
    <div class="modal-dialog container-fluid" style="width:95%;">
        <div class="modal-content p-0 b-0">
            <div class="panel panel-color panel-danger">
                <div class="panel-heading">
                    <!--THE x BUTTON TO CLOSE THE MODAL POP UP OR MEMBER DETAILS POP UP-->
                    <button id="btnCloseDetail" type="button" data-dismiss="modal"  style="float:right;"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <!--TITLE OF MODAL POP UP-->
                    <h4 class="text-center" style="font-weight:bold;font-size:20px;color:white;">預約採檢資料維護</h4>
                </div>
                <div class="container-fluid" style="width:100%;">
                    <div class="row"><br>
                        <!---- Ads Details ---->
                        <input type="hidden" id="uuid">
                        <div class="col-sm-12">
                            <div class="card-box" style="height:100%;">
                                <div class="row">
                                    <div class="form-horizontal" role="form">
                                        <!---- 第一排 ---->
                                        <div class="col-md-4" style="right:1%;">
                                            <!---- 中文姓名 ---->
                                            <div class="form-group">
                                                <!--HEALTHCARD STATUS FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label for="c_name" class="col-md-3 control-label">中文姓名:</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="c_name" class="form-control">
                                                </div>
                                            </div>
                                            <!---- 英文姓名 ---->
                                            <div class="form-group">
                                                <!--PRINCIPAL NAME FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label for="e_name" class="col-md-3 control-label">英文姓名:</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="e_name" class="form-control">
                                                </div>
                                            </div>
                                            <!---- 身分證號 ---->
                                            <div class="form-group">
                                                <!--MEMBER NAME FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label for="user_id" class="col-md-3 control-label">身分證號:</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="user_id" class="form-control">
                                                </div>
                                            </div>
                                            <!---- 護照號碼 ---->
                                            <div class="form-group">
                                                <!--MEMBER NAME FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label for="passport_id" class="col-md-3 control-label">護照號碼:</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="passport_id" class="form-control">
                                                </div>
                                            </div>
                                            <!---- 台胞證號 ---->
                                            <div class="form-group">
                                                <!--MEMBER NAME FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label for="mtp_id" class="col-md-3 control-label">台胞證號:</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="mtp_id" class="form-control">
                                                </div>
                                            </div>
                                            <!---- 健保卡號 ---->
                                            <div class="form-group">
                                                <!--MEMBER NAME FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label for="healthcare_id" class="col-md-3 control-label">健保卡號:</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="healthcare_id" class="form-control">
                                                </div>
                                            </div>
                                            <!---- 手機號碼 ---->
                                            <div class="form-group">
                                                <!--MEMBER NAME FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label for="mobile_phone" class="col-md-3 control-label">手機號碼:</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="mobile_phone" class="form-control">
                                                </div>
                                            </div>
                                            <!---- 電子郵件 ---->
                                            <div class="form-group">
                                                <!--MEMBER NAME FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label for="email" class="col-md-3 control-label">電子郵件:</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="email" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <!---- 第二排 ---->
                                        <div class="col-md-4 container-fluid" style="right:2%;">
                                            <!---- 預約日期 ---->
                                            <div class="form-group">
                                                <!--CARDNUMBER FIELD, THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label for="appoint_date" class="col-md-3 control-label">預約日期:</label>
                                                <div class="col-md-9">
                                                    <input type="text" id="appoint_date" class="form-control">
                                                </div>
                                            </div>
                                            <!---- 報到日期 ---->
                                            <div class="form-group">
                                                <!--BIRTHDATE FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label for="checkin_date" class="col-md-3 control-label">報到日期:</label>
                                                <div class="col-md-9">
                                                    <input type="text" id="checkin_date" class="form-control">
                                                </div>
                                            </div>
                                            <!---- 採檢編號 ---->
                                            <div class="form-group">
                                                <!--CONTACT NUMBER FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label for="inspection_id" class="col-md-3 control-label">採檢編號:</label>
                                                    <div class="col-md-9">
                                                        <input type="text" id="inspection_id" class="form-control">
                                                    </div>
                                            </div>
                                            <!---- 報告時效 ---->
                                            <div class="form-group">
                                                <!--MEMBER BALANCE FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label for="report_ageing" class="col-md-3 control-label">報告時效:</label>
                                                <div class="col-md-9">
                                                    <select id="report_ageing" class="form-control">
                                                    <option value="normal">一般件</option>
                                                    <option value="urgent">急件</option>
                                                    <option value="hiurgent">特急件</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!---- 篩檢原因 ---->
                                            <div class="form-group">
                                                <!--PRINCIPAL NAME FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label for="inspection_reason" class="col-md-3 control-label">篩檢原因:</label>
                                                <div class="col-md-9">
                                                    <select id="inspection_reason" class="form-control">
                                                        <option value="1">因旅外親屬事故或重病等緊急特殊因素入境他國家/地區須檢附檢驗證明之民眾</option>
                                                        <option value="2">因工作因素須檢附檢驗證明之民眾</option>
                                                        <option value="3">短期商務人士</option>
                                                        <option value="4">出國求學須檢附檢驗證明之民眾</option>
                                                        <option value="5">外國或中國大陸、香港、澳門人士出境</option>
                                                        <option value="6">相關出境適用對象之眷屬</option>
                                                        <option value="7">其他</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!---- 生日 ---->
                                            <div class="form-group">
                                                <!--CARDNUMBER FIELD, THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label for="birthday" class="col-md-3 control-label">生日:</label>
                                                <div class="col-md-9">
                                                    <input type="text" id="birthday" class="form-control">
                                                </div>
                                            </div>
                                            <!---- 國籍 ---->
                                            <div class="form-group">
                                                <!--MEMBER BALANCE FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label for="nationality" class="col-md-3 control-label">國籍:</label>
                                                <div class="col-md-9">
                                                    <select id="nationality" class="form-control">
                                                    <option value="TWN">台灣/Taiwan</option>
                                                    <option value="USA">美國/USA</option>
                                                    <option value="JPN">日本/JAPAN</option>
                                                    <option value="CHN">中國大陸/China</option>
                                                    <option value="OTHER">其他/other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!---- 第三排 ---->
                                        <div class="col-md-4">
                                            <!---- 採樣日期 ---->
                                            <div class="form-group">
                                                <!--BIRTHDATE FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label for="sampling_date" class="col-md-3 control-label">採樣日期:</label>
                                                <div class="col-md-9">
                                                    <input type="text" id="sampling_date" class="form-control" style="font-weight:bold;font-size:12px;">
                                                </div>
                                            </div>
                                            <!---- 檢測日期 ---->
                                            <div class="form-group">
                                                <!--BIRTHDATE FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label for="inspection_date" class="col-md-3 control-label">檢測日期:</label>
                                                <div class="col-md-9">
                                                    <input type="text" id="inspection_date" class="form-control" style="font-weight:bold;font-size:12px;">
                                                </div>
                                            </div>
                                            <!---- 檢測項目 ---->
                                            <div class="form-group">
                                                <!--PRINCIPAL NAME FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label for="inspection_item" class="col-md-3 control-label">檢測項目:</label>
                                                <div class="col-md-9">
                                                    <select id="inspection_item" class="form-control" style="font-weight:bold;font-size:12px;">
                                                        <option value="1" selected>新冠肺炎病毒核酸檢測</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!---- 檢體類型 ---->
                                            <div class="form-group">
                                                <!--PRINCIPAL NAME FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label for="inspection_tyep" class="col-md-3 control-label">檢體類型:</label>
                                                <div class="col-md-9">
                                                    <select id="inspection_type" class="form-control" style="font-weight:bold;font-size:12px;">
                                                        <option value="1" selected>鼻咽拭子</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!---- 檢測方法 ---->
                                            <div class="form-group">
                                                <!--PRINCIPAL NAME FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label for="inspection_method" class="col-md-3 control-label">檢測方法:</label>
                                                <div class="col-md-9">
                                                    <select id="inspection_method" class="form-control" style="font-weight:bold;font-size:12px;">
                                                        <option value="1" selected>即時反轉錄聚合酶連鎖反應</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!---- 檢測結果 ---->
                                            <div class="form-group">
                                                <!--MEMBER NAME FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label for="inspection_result" class="col-md-3 control-label">檢測結果:</label>
                                                <div class="col-md-9">
                                                    <input type="text" id="inspection_result" class="form-control" style="font-weight:bold;font-size:12px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!---- button area ---->
                        <div >
                            <div id="ds1" align="center" >
                                <p id="Message"></p>
                                <p id="Query">
                                    <button class="btn btn-danger btn-md" id="BtnExit" style="font-weight:bold;font-size:20px;margin:30px;">
                                    <i class="fa fa-eject"></i> 離 開</button> 
                                    <button class="btn btn-danger btn-md" id="BtnEdit" style="font-weight:bold;font-size:20px;margin:30px;">
                                    <i class="fa fa-edit"></i> 修 改</button> 
                                    <button class="btn btn-danger btn-md" id="BtnDelete" style="font-weight:bold;font-size:20px;margin:30px;">
                                    <i class="fa fa-trash"></i> 刪 除</button>
                                </p>
                                <!--SUBMIT BUTTON IS CONNECTED TO HOME.PHP-->
                                <p id="Modify">
                                    <button class="btn btn-custom btn-danger btn-md" id="BtnCancel" style="font-weight:bold;font-size:20px;margin:30px;">
                                    <i class="fa fa-window-close"></i> 取 消</button> 
                                    <button class="btn btn-custom btn-success btn-md" id="BtnSubmit" style="font-weight:bold;font-size:20px;margin:30px;">
                                    <i class="fa fa-paper-plane"></i> 確 認</button>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<!---------------------------End----------------------------->
