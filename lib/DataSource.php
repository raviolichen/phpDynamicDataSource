<?php
class DataSource
{
    var $fields;
    var $selectCommandText;
    var $tableArray;
    var $dbh;
    var $tableName;
    function __construct(SQLite3 $dbh, $selectCommandText, $tableName = '')
    {
        $this->dbh = $dbh;
        $this->tableName = $tableName;
        $this->selectCommandText = $selectCommandText;
        $this->tableArray=array();
        $this->initNames();
    }
    function BIND()
    {
        if ($result = $this->dbh->query($this->selectCommandText)) {
            while ($row = $result->fetchArray()) {
                array_push($this->tableArray,$row);
            }
        }
        return $this->tableArray;
    }
    function GetFields()
    {
        return $this->fields;
    }
    function INSERT($keyName, array $valus)
    {
        $keys = "";
        $_valus = "";
        foreach ($valus as $key => $value) {
                $keys .= "," . $key;
                $_valus .= ",'" . $value . "'";
        }
        $this->dbh->exec(sprintf("INSERT INTO $this->tableName (%s) VALUES (%s)", substr($keys, 1), substr($_valus, 1)));
    }
    function UPDATE(array $valus, $where)
    {
        $valuepair = "";
        foreach ($valus as $key => $value) {
            $valuepair .= ", $key='$value' ";
        }
        $this->dbh->exec(sprintf("UPDATE $this->tableName SET %s WHERE %s", substr($valuepair, 1), $where));
    }
    function DELETE($where)
    {
        $this->dbh->exec(sprintf("DELETE FROM $this->tableName WHERE %s", $where));
    }
    private function initNames()
    {
        if (preg_match('/SELECT([\\w\\W].*?)FROM/', $this->selectCommandText, $matches)) {
            $this->fields = preg_split("/[\s,]/", $matches[1], -1, PREG_SPLIT_NO_EMPTY);
        }
    }
}
