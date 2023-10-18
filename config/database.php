<?php
class DataBase{
    private $hostname = "localhost";
    private $database = "u701878193_tobynilos";
    private $username = "u701878193_rduran";
    private $password = "Tobynilos_2023";
    private $charset = "utf8";

    function getConnection()
    {
        try{
            $conn = "mysql:host=".$this->hostname."; dbname=".$this->database."; charset=".$this->charset;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            $pdo= new PDO($conn, $this->username, $this->password, $options);

            return $pdo;
        }catch(PDOException $ex){
            echo 'Error de Conexion: '.$e->getMessage();
            exit;
        }
    }
}

?>