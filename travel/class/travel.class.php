<?php
  class travel{
    protected $tblName;
    protected $tblData;
    protected $isNew;
    protected $where;

    function save(){
      global $db;

      if($this->isNew){
        return $db->insert($this->tblName,$this->tblData);
      }else{
        return $db->update($this->tblName,$this->tblData,$this->where);
      }
    }
  }
