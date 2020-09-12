<?php
abstract class SqliteHelp
{
    static $filename = "\\App_Data\\mydb.db";
    public static $db;
    static function init()
    {
        if (self::$db == null)
            if (self::$db = new SQLite3($_SERVER["DOCUMENT_ROOT"] . (self::$filename))) {
                self::$db->exec('CREATE TABLE IF NOT EXISTS stu (sId INTEGER PRIMARY KEY AUTOINCREMENT, sname text,  age INTEGER,  addr text )');
                return true;
            } else
                return false;
    }
    static function Query($sql)
    {
        $rows=array();
        $results = self::$db->query($sql);
        while ($row = $results->fetchArray()) {
            array_push($stack, $row);
        }
        $results->close();
        return $rows;
    }
}
