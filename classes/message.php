<?php
/* 
 * Data relating to Messages
 */

class Message
{
    public /* unsigned int */ $ID = null;
    public /* string */ $Subject = null;
    public /* string */ $Contents = null;
    public /* unsigned int */ $From = null;
    public /* unsigned int */ $To = null;
    public /* bool */ $Read = null;
    public /* bool */ $SenderDeleted = null;
    public /* bool */ $RecipientDeleted = null;
    public /* unsigned int */ $Created = null;

    /* Message */ public function FromSQLRow($row)
    {
        $this->ID = $row['id'];
        $this->Subject = $row['subject'];
        $this->Contents = $row['contents'];
        $this->From = $row['from'];
        $this->To = $row['to'];
        $this->Read = ($row['read'] != 0);
        $this->SenderDeleted = ($row['sender_deleted'] != 0);
        $this->RecipientDeleted = ($row['recipient_deleted'] != 0);
        $this->Created = strtotime($row['created']);

        return $this;
    }

    /* array */ public function ToSQLRow()
    {
        $row = array();

        $row['id'] = $this->ID;
        $row['subject'] = $this->Subject;
        $row['contents'] = $this->Contents;
        $row['from'] = $this->From;
        $row['to'] = $this->To;
        $row['read'] = ($this->Read ? 1 : 0);
        $row['sender_deleted'] = ($this->SenderDeleted ? 1 : 0);
        $row['recipient_deleted'] = ($this->RecipientDeleted ? 1 : 0);
        $row['created'] = $this->Created;

        return $row;
    }
}

class MessageType
{
    const All = 1; // Bit 1
    const FromMe = 2; // Bit 2
    const ToMe = 4; // Bit 3
    const UnRead = 8; // Bit 4
    const Read = 16; // Bit 5
}

?>
