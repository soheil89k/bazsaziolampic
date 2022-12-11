<?php

namespace Database;

class PDO
{


    public function __construct()
    {

    }

    public static function fetch($sql)
    {
        $dbname = APP_DB_NAME;
        $servername = APP_DB_HOSTNAME;
        $username = APP_DB_USERNAME;
        $password = APP_DB_PASSWORD;

        $conn = new \PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->query($sql);

        $result = [];
        try {
            do {
                array_push($result, $stmt->fetchAll(\PDO::FETCH_ASSOC));
            } while ($stmt->nextRowset());

        } catch (PDOException $Exception) {

        }

        return $result;

    }

    public static function execute($sql)
    {
        $dbname = APP_DB_NAME;
        $servername = APP_DB_HOSTNAME;
        $username = APP_DB_USERNAME;
        $password = APP_DB_PASSWORD;

        $conn = new \PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $conn->exec($sql);

        return $conn->lastInsertId();
    }
}