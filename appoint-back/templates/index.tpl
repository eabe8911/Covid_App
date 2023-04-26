<!DOCTYPE html>
<html lang="en">
<title>LiboBio Appointment System</title>
    <head>{include file="indexdesign.tpl" }</head>
    <body style="font-family:Microsoft JhengHei;">
        {include file="indexheader.tpl" }
        <!-- LOGIN START -->
        <div class="wrapper-page">
            <div class="card-box login">
                <div class="panel-heading" style="text-align:center">
                    <h2 class="text-center"><strong class="login" style="font-family:Microsoft JhengHei;">請登入</strong></h2>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal m-t-20" method="post" action="{$Form}">
                        <div class="form-group" >
                            <div class="col-xs-12">
                                <input class="form-control" type="text" required="" name="{$Username}" id="{$Username}" placeholder="帳號" value="{$Username_Value}" autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" type="password" required="" name="{$Password}" id="{$Password}" placeholder="密碼" value="{$Password_Value}">
                            </div>
                        </div>
                        <div class="form-group text-center m-t-40">
                            <div class="col-xs-12">
                                <button class="btn btn-primary btn-block text-uppercase waves-effect waves-light btn-lg" name="{$Access}" id="{$Access}" type="submit">
                                確定
                                </button>
                            </div>
                        </div>
                        {$Hiddenfield}
                    </form>
                </div>
                </div>
        </div>
    </body>
</html>
