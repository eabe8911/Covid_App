<?php
class LogDbConnect{
    private $server = 'maxcheng.tw';
    private $dbname = 'TransLog';
    private $port = '3307';
    private $user = 'root';
    private $password = ',-4,4p-2';

    function __construct(){
        //
    }

    public function connect(){
        try {
                $conn = new PDO(
                    "mysql:host=$this->server;
                    dbname=$this->dbname;
                    port=$this->port", 
                    $this->user, $this->password,
                    array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $th) {
            echo "Database Error: ".$th->getMessage();
        }
    }
}
?>
