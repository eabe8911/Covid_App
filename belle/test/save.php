<?php
$user = $_POST["user"];
$nameArr = json_decode($_POST["name"]);
$pcrresultArr = json_decode($_POST["pcrresult"]);
$rdatresultArr = json_decode($_POST["rdatresult"]);
$vuser1resultArr = json_decode($_POST["vuser1result"]);
$vuser2resultArr = json_decode($_POST["vuser2result"]);
// $con=mysqli_connect("localhost","root","","php_ajax");
// if (mysqli_connect_errno())
// {
// echo "Failed to connect to MySQL: " . mysqli_connect_error();
// }
for ($i = 0; $i < count($nameArr); $i++) {
    if (($nameArr[$i] != "")) { /*not allowing empty values and the row which has been removed.*/
        $sql = "INSERT INTO covid_test (sampleid2, pcrresult,rdat,vuser1,vuser2)
VALUES
('$nameArr[$i]','$pcrresultArr[$i]','$rdatresultArr[$i]','$vuser1resultArr[$i]','$vuser2resultArr[$i]')";
    echo $sql."\n";
        // if (!mysqli_query($con, $sql)) {
        //     die('Error: ' . mysqli_error($con));
        // }
    }
}
echo "Data added Successfully !";
echo "{$user}";
// mysqli_close($con);
