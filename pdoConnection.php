<?php

class PDOConnection
{
    static protected $db;
    
    public static function initPDO() 
    {
        $dbname = 'njouini';
        $user = 'njouini';
        $password = 'Noufnouf-78800';
        $host = 'database-etudiants.iut.univ-paris8.fr';
        $port = '5432';
        try {
            self::$db = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}