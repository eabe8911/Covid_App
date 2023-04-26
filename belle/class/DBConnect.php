<?php
class DBConnect
{
    // private $_db_param = [
    //     'server' => '127.0.0.1',
    //     'dbname' => 'libodb',
    //     'port' => '3306',
    //     'user' => 'root',
    //     'password' => ''
    // ];
    private $_db_param = [
        'server' => 'maxcheng.tw',
        'dbname' => 'libodb',
        'port' => '3307',
        'user' => 'root',
        'password' => ',-4,4p-2'
    ];

    function __construct()
    {
        $HostName = getenv("MYSQL_HOSTNAME");
        $HostPort = getenv("MYSQL_PORT");
        $HostUser = getenv("MYSQL_USERNAME");
        $HostPass = getenv("MYSQL_PASSWORD");
        if ($HostName !== false) {
            $this->_db_param['server'] = $HostName;
            $this->_db_param['port'] = $HostPort;
            $this->_db_param['user'] = $HostUser;
            $this->_db_param['password'] = $HostPass;
        }
    }

    public function connect()
    {
        try {
            $server = $this->_db_param['server'];
            $dbname = $this->_db_param['dbname'];
            $port = $this->_db_param['port'];
            $user = $this->_db_param['user'];
            $password = $this->_db_param['password'];

            $conn = new PDO(
                "mysql:host=$server; dbname=$dbname; port=$port",
                $user, $password,
                array(PDO::MYSQL_ATTR_FOUND_ROWS => true)
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $th) {
            echo "Database Error: " . $th->getMessage();
            throw new Exception($th->getMessage(). $server . json_encode($this->_db_param), 1);
        }
    }
}
?>