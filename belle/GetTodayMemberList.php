<?php
require_once("./class/DBConnect.php");
$objDb = new DBConnect;
$conn = $objDb->connect();

$page = filter_input(INPUT_GET, "page"); // get the requested page
$limit = filter_input(INPUT_GET, "rows"); // get how many rows we want to have into the grid
$sidx = filter_input(INPUT_GET, "sidx"); // get index row - i.e. user click to sort
$sord = filter_input(INPUT_GET, "sord"); // get the direction


if (!$sidx)
    $sidx = 1;
try {
    $sql = "SELECT COUNT(*) AS count FROM covid_trans WHERE DATE_FORMAT(apdat,'%Y-%m-%d') = CURDATE()";
    $sth = $conn->prepare($sql);
    $sth->execute();
    $result = $sth->fetch(PDO::FETCH_ASSOC);
    $count = $result['count'];
    if ($count > 0) {
        if ($limit < 1)
            $limit = 30;
        $total_pages = ceil($count / $limit);
    } else {
        $total_pages = 0;
    }
    if ($page > $total_pages)
        $page = $total_pages;
    $start = $limit * $page - $limit; // do not put $limit*($page - 1)
    if ($start < 0)
        $start = 0;
    $sql = "SELECT * FROM covid_trans WHERE DATE_FORMAT(apdat,'%Y-%m-%d') = CURDATE() AND not isnull(sampleid2) ORDER BY $sidx $sord";
    $sth = $conn->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    if (is_null($page))
        $page = 1;
    $responce = new stdClass();
    $responce->page = $page;
    $responce->total = (int) $total_pages;
    $responce->records = (int) $count;
    $i = 0;

    foreach ($result as $row) {
        $responce->rows[$i]['id'] = (int) $row['uuid'];
        $responce->rows[$i]['cell'] = array(
            $row['sampleid2'],$row['apdat'],$row['tdat']
        );
        $i++;
    }
    echo json_encode($responce);
} catch (PDOException | Exception $th) {
    echo $th->getMessage();
}
?>