<?php

require_once('include/database.php');

class Page
{
    public /* unsigned int */ $ID = null;
    public /* string */ $Name = null;
    public /* string */ $Content = null;

    /* Page */ public function FromSQLRow($row)
    {
        $this->ID = (int)$row['id'];
        $this->Name = $row['name'];
        $this->Content = $row['content'];

        return $this;
    }

    /* array */ public function ToSQLRow()
    {
        $row = array();

        $row['id'] = $this->ID;
        $row['name'] = $this->Name;
        $row['content'] = $this->Content;

        return $row;
    }

    /* void */ public function Display()
    {
        print $this->Content;
    }
}

?>
