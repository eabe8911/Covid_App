<?php
header('Content-Type: application/json');

if (isset($_POST["submit_file"])) {
	$file = $_FILES["file"]["tmp_name"];

}
// $file = $_FILES["file"]["tmp_name"];
// $file =$_POST['formData'];
echo json_encode(array(
    'text' => $file,
));

?>