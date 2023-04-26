<!--POP UP MODAL TO VIEW MEMBER DETAILS AND RESULTS FOR CONSULTATION-->
<form method="post" action="{$FormAction}" onsubmit="{$Onsubmit}" name="{$FormName}">
<div id="ViewEligibility" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none;">
    <div class="modal-dialog" style="width:95%;">
        <div class="modal-content p-0 b-0">
            <div class="panel panel-color panel-danger">
                <div class="panel-heading">
                    <!--THE x BUTTON TO CLOSE THE MODAL POP UP OR MEMBER DETAILS POP UP-->
                    <button id="btnCloseEligibility" type="button" data-dismiss="modal"  style="float:right;"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <!--TITLE OF MODAL POP UP-->
                    <h4 style="font-weight:bold;font-size:20px;color:white;"><center>MEMBER DETAILS</center></h4>
                </div>
                <div class="container" style="width:100%;">
                    <div class="row"><br>
                        <div class="col-sm-12">
                        <div align="center">
                            <!--TITLE (CARD HOLDER DETAILS)-->
                            <font style="font-weight:bold;font-size:22px;">CARD HOLDER DETAILS</font>
                        </div>
                        <div class="card-box" style="height:100%;">
                            <div class="row">
                                <div class="form-horizontal" role="form">
                                    <div class="col-md-4" style="right:1%;">
                                        <div class="form-group">
                                            <!--MEMBER NAME FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                            <label class="col-md-3 control-label">Member      Name:</label>
                                            <div class="col-md-8">
                                                <input type="text" id="E_Membername" class="form-control" style="font-weight:bold;" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <!--HEALTHCARD STATUS FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                            <label class="col-md-3 control-label">Healthcard Status:</label>
                                            <div class="col-md-8">
                                                <input type="text" id="E_HealthcardStatus" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <!--PRINCIPAL NAME FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                            <label class="col-md-3 control-label">Principal    Name:</label>
                                            <div class="col-md-8">
                                                <input type="text" id="E_PrincipalName" class="form-control" style="font-weight:bold;" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <!--MEMBER BALANCE FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                            <label class="col-md-3 control-label">Balance:</label>
                                            <div class="col-md-8">
                                                <input type="text" id="E_Balance" class="form-control" style="font-weight:bold;" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4" style="right:2%;">
                                        <div class="form-group">
                                            <!--CARDNUMBER FIELD, THIS FIELD IS CONNECTED TO HOME.PHP-->
                                            <label class="col-md-3 control-label">Policy Number:</label>
                                            <div class="col-md-9">
                                                <input type="text" id="E_Cardnumber" class="form-control" style="font-weight:bold;" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <!--BIRTHDATE FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                            <label class="col-md-3 control-label">Birth date:</label>
                                            <div class="col-md-9">
                                                <input type="text" id="E_Birthdate" class="form-control" style="font-weight:bold;" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <!--EXPIRY FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                            <label class="col-md-3 control-label">Expiry:</label>
                                            <div class="col-md-9">
                                                <input type="text" id="E_Expiry" class="form-control" style="font-weight:bold;" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <!--CONTACT NUMBER FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                            <label class="col-md-3 control-label">Contact Number:</label>
                                            <div class="col-md-9">
                                                <input type="number" id="E_EcontactNumber" class="form-control" style="font-weight:bold;" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <!--COMPANY NAME FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                            <label class="col-md-3 control-label">Company Name:</label>
                                            <div class="col-md-9">
                                                <input type="text" id="E_Companyname" class="form-control" style="font-weight:bold;" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <!--MEMBER TYPE FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                            <label class="col-md-3 control-label">Member Type:</label>
                                            <div class="col-md-9">
                                                <input type="text" id="E_MemberType" class="form-control" style="font-weight:bold;" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <!--MAXIMUM COVERAGE BENEFIT FIELD , THIS FIELD IS CONNECT TO HOME.PHP-->
                                            <label class="col-md-3 control-label">Maximum Coverage Benefit:</label>
                                            <div class="col-md-9">
                                                <input type="text" id="E_Mcb" class="form-control" style="font-weight:bold;" readonly>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    {* <div class="col-md-8">
                                        <div class="form-group">
                                            <!--HOSPITAL NAME FIELD , THIS FIELD IS THE RESULT OF CONSULTATION AND CONNECTED TO HOME.PHP-->
                                            <label class="col-md-1 control-label">Hospital Name:</label>
                                            <div class="col-md-11">
                                                <input type="text" id="E_Hospitalname" class="form-control" style="font-weight:bold;" readonly>
                                            </div>
                                        </div>
                                    </div> *}
                                     <!--WAIVER && HOSPITAL NAME-->
                                        <div class="row">
                                            <div class="col-md-12 form-group" style="">
                                                <label class="col-md-1 control-label" style="font-weight:bold;font-size:15px;">Hospital Name:</label>
                                                    <div class="col-md-11">
                                                        <input type="text" id="E_Hospitalname" class="form-control" style="font-weight:bold;" readonly>
                                                    </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 form-group" style="">    
                                                <label class="col-md-1 control-label" style="font-weight:bold;font-size:15px;">WAIVER:</label>
                                                    <div class="col-md-11">
                                                        <input id="E_Waiver" type="textarea" class="form-control" style="font-weight:bold;font-size:15px;" readonly>
                                                    </div>
                                            </div>
                                        </div>
                                    <div align="right">
                                        <!--BUTTON FOR IVIEW IS CONNECTED TO VALUCARE-->
                                        <button class="btn btn-custom btn-danger btn-md" onclick=" window.open('https://www.valucarehealth.com/iview/details.php?id=' + document.getElementById('E_Cardnumber').value);"><i class="fa fa-wpforms" style="font-size:23px;"></i> i-View</button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>