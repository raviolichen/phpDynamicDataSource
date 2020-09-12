<?php
include_once 'DataSource.php';
class table
{
    private $table;
    private $Editindex = -1;
    private $Feilds;
    private DataSource $dbh;
    private $keyName;
    function __construct($postData, $keyName, DataSource $dbh)
    {
        $this->dbh = $dbh;
        $this->Feilds = $dbh->GetFields();
        $this->keyName = $keyName;
        $this->actionRouter($postData);
        $this->table = $this->dbh->BIND();
    }
    function Render()
    {
        $html = "<table>";
        $html .= "<tr>";
        foreach ($this->Feilds as $feild) {
            $html .= "<th>" . $feild . "</th>";
        }
        $html .= "</tr>";
        $html .= $this->getInsertRow();
        if ($this->table != null) {
            $i = 0;
            foreach ($this->table as $row) {
                if ($i == $this->Editindex)
                    $html .= $this->getEditRow($this->Editindex, $row);
                else {
                    $html .= "</tr>";
                    foreach ($this->Feilds as $col)
                        $html .= "<td>" . $row[$col] . "</td>";
                    $html .= "<td><input type='hidden' name='" . $this->keyName . "[" . $i . "]' value='" . $row[$this->keyName] . "' /><input name='edit[" . $i . "]' type='submit' value='編輯'> 
                    <input type='submit' name='delete[" . $i . "]' value='刪除'></tr>";
                }
                $i++;
            }
        }
        $html .= "</table>";
        return $html;
    }
    private function actionRouter($postData)
    {
        if (isset($postData['edit'])) {
            $aSubmitVal = array_keys($postData['edit'])[0];
            $this->Editindex = $aSubmitVal;
        } else {
            if (isset($postData['insert'])) {
                $this->insertRow($postData);
            } else if (isset($postData['delete'])) {
                $aSubmitVal = array_keys($postData['delete'])[0];
                $this->deleteRow($_POST[$this->keyName][$aSubmitVal]);
            } else if (isset($postData['save'])) {
                $aSubmitVal = array_keys($postData['save'])[0];
                $this->saveRow($postData, $_POST[$this->keyName][$aSubmitVal]);
            }
        }
    }
    private function getInsertRow()
    {
        $rowhtml = "</tr>";
        for ($i = 0; $i < count($this->Feilds); $i++)
            $rowhtml .= sprintf("<td><input type='text' value='' name='%s'/></td>", $this->Feilds[$i]);
        $rowhtml .= "<td><input name='insert' type='submit' value='新增'></td></tr>";
        return $rowhtml;
    }
    private function getEditRow($Editindex, $row)
    {
        $rowhtml = "</tr>";
        for ($i = 0; $i < count($this->Feilds); $i++) {
            if (strcmp($this->Feilds[$i], $this->keyName) == 0)
                $rowhtml .= sprintf("<td>%s</td>", $row[$i]);
            else
                $rowhtml .= sprintf("<td><input type='text' value='%s' name='%s'/></td>", $row[$i], $this->Feilds[$i]);
        }
        $rowhtml .= "<td><input type='hidden' name='" . $this->keyName . "[" . $Editindex . "]' value='" . $row[$this->keyName] . "' /><input name='save[" . $Editindex . "]' type='submit' value='儲存'><input name='cancel' type='submit' value='取消'></td></tr>";
        return $rowhtml;
    }
    private function insertRow($postData)
    {
        $values = array();
        foreach ($this->Feilds as $feild) {
            if (strcmp($feild, $this->keyName) != 0)
                $values[$feild] = $postData[$feild];
        }
        $this->dbh->INSERT($this->keyName, $values);
    }
    private function saveRow($postData, $Id)
    {
        $values = array();
        foreach ($this->Feilds as $feild) {
            if (strcmp($feild, $this->keyName) != 0)
                $values[$feild] = $postData[$feild];
        }
        $this->dbh->UPDATE($values, $this->keyName . '=' . $Id);
    }
    private function deleteRow($Id)
    {
        $this->dbh->DELETE($this->keyName . '=' . $Id);
    }
}
