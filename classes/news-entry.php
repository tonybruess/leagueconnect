<?php
/* 
 * Information related to a News entry.
 */

class NewsEntry
{
    public /* unsigned int */ $ID = null;
    public /* string */ $Author = null;
    public /* string */ $Message = null;
    public /* unsigned int */ $Created = null;

    /* NewsEntry */ public function FromSQLRow($row)
    {
        $this->ID = (int)$row['id'];
        $this->Author = $row['author'];
        $this->Message = $row['message'];
        $this->Created = strtotime($row['created']);

        return $this;
    }

    /* array */ public function ToSQLRow()
    {
        $row = array();

        $row['id'] = $this->ID;
        $row['author'] = $this->Author;
        $row['message'] = $this->Message;
        $row['created'] = $this->Created;

        return $row;
    }
}
?>
