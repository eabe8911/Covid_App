<?php

ini_set("display_errors", "on");
error_reporting(E_ALL);

if (!isset($_SESSION)) {
    session_start();
}

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {

    if (($_SESSION["division"] == 0) || ($_SESSION["division"] == 1)) {
	} else {
		echo '<script language="javascript">alert("您沒有權限訪問喔~即將跳轉回首頁");</script>';
		echo '<script language="javascript">window.location.replace("menu.php");</script>';
	}
} else {
	header("location: login.php");
}

require_once 'php/search_info.php';
// require_once 'email_ftestpdfreport.php';
// require_once 'email_pcrtestpdfreport.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>查詢/寄送報告</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" href="css/search_info.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="js/d3.min.js" charset="utf-8"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(function() {
            $("#nav").load("nav.html");
        });
    </script>
</head>
<script type="text/javascript">
    $(document).ready(function() {
        $('#email_pcrtestpdfreport').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: 'email_pcrtestpdfreport.php',
                data: {
                    pcrsampleid_post: $('#sendpcrtest_email').val(),
                },
                type: 'POST',
                dataType: 'json',
                success: function(msg) {
                    console.log(msg);
                    let message_back = msg['message_back'];
                    alert(message_back);
                },
                error: function() {
                    console.log('error')
                }
            });
        });
    });
    $(document).ready(function() {
        $('#email_ftestpdfreport').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: 'email_ftestpdfreport.php',
                data: {
                    ftestsampleid_post: $('#sendftest_email').val(),
                },
                type: 'POST',
                dataType: 'json',
                success: function(msg) {
                    console.log(msg);
                    let message_back = msg['message_back'];
                    alert(message_back);
                },
                error: function() {
                    console.log('error')
                }
            });
        });
    });
</script>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-success" id="nav"></nav>

    <div class="container">
        <div id="search_people">
            <h3>查詢/寄送報告</h3>
            <h5>
                <p>請刷快篩或qPCR條碼</p>
            </h5>
            <?php
            if (!empty($sampleid1_err) && !empty($sampleid2_err)) {
                echo '<div class="alert alert-danger">' . $sampleid1_err . '</div>';
            }
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style='display:inline'>
                <div class="row g-3">
                    <div class="col-auto">
                        <label>快篩ID</label>
                        <input type="text" :focus name="sampleid1" class="form-control <?php echo (!empty($sampleid1_err)) ? 'is-invalid' : ''; ?>" onkeypress="if (window.event.keyCode==13) return false;" value="<?php echo $sampleid1; ?>">
                        <span class="invalid-feedback"><?php echo $sampleid1_err; ?></span>
                    </div>

                    <div class="col-auto">
                        <label>PCR ID</label>
                        <input type="text" :focus name="sampleid2" id="sampleid2" class="form-control <?php echo (!empty($sampleid2_err)) ? 'is-invalid' : ''; ?>" onkeypress="if (window.event.keyCode==13) return false;" value="<?php echo $sampleid2; ?>">
                        <span class="invalid-feedback"><?php echo $sampleid2_err; ?></span>
                    </div>
                    <div class="col-sm-4" style="border:1px; border-color:black;">
                        <p>檢驗進度</p>
                        <div class="progress" id="progress_0" hidden>
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">未報到</div>
                        </div>
                        <div class="progress" id="progress_25" hidden>
                            <div class="progress-bar bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">未檢驗</div>
                        </div>
                        <div class="progress" id="progress_50" hidden>
                            <div class="progress-bar bg-success" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">未製作報告</div>
                        </div>
                        <div class="progress" id="progress_75" hidden>
                            <div class="progress-bar bg-success" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">未寄送報告</div>
                        </div>
                        <div class="progress" id="progress_100" hidden>
                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">已完成</div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="col-auto">
                    <input type="submit" name="search" class="btn btn-success" value="搜尋">
                    <input type="submit" name="clear" class="btn btn-success" value="清除">
                    <input type="button" class="btn btn-secondary" onclick="history.back()" value="回到上一頁">
                    <input type="button" class="btn btn-secondary" onclick="window.location.href='menu.php'" value="回首頁">
                </div>
                <br>
            </form>
            <div id="result_table">
                <?php
                search_result($sampleid1, $sampleid2);
                ?>
            </div>
        </div>

    </div>

</body>
</html>