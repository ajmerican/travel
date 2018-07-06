<?php
  require_once 'travel.class.php';

  class flight extends travel{
    function saveBooking($data){
      $this->tblName= 'booking';
      $this->isNew  = true;

      $this->tblData= array(
        array('field'=>'created_by','type'=>'i','value'=>$_SESSION[SESSION_PREFIX.'_user']->user_id),
        array('field'=>'created_date','type'=>'s','value'=>date('Y-m-d h:i:s')),

        array('field'=>'date','type'=>'s','value'=>date('Y-m-d h:i:s',strtotime($data['date']))),
        array('field'=>'time_from','type'=>'s','value'=>date('Y-m-d h:i:s',strtotime($data['date']))),
        array('field'=>'time_to','type'=>'s','value'=>date('Y-m-d h:i:s',strtotime($data['time_to']))),
        array('field'=>'flight_id','type'=>'s','value'=>$data['flight_id']),
        array('field'=>'u_id','type'=>'i','value'=>$_SESSION[SESSION_PREFIX.'_user']->user_id),
        array('field'=>'portal_pnr_no','type'=>'s','value'=>rand(10000,99999)),
        array('field'=>'pnr_no','type'=>'s','value'=>rand(10000,99999)),
        array('field'=>'contact_id','type'=>'s','value'=>$_SESSION[SESSION_PREFIX.'_user']->email),
        array('field'=>'promocode','type'=>'s','value'=>''),
        array('field'=>'payment_method_id','type'=>'s','value'=>1),
        array('field'=>'payment_status','type'=>'s','value'=>'Paid')

        //array('field'=>'portal_pnr_no','type'=>'s','value'=>$data['portal_pnr_no']),
        //array('field'=>'pnr_no','type'=>'s','value'=>$data['pnr_no']),
        //array('field'=>'contact_id','type'=>'s','value'=>$data['contact_id']),
        //array('field'=>'promocode','type'=>'s','value'=>$data['promocode']),
        //array('field'=>'payment_method_id','type'=>'s','value'=>$data['payment_method_id']),
        //array('field'=>'payment_status','type'=>'s','value'=>$data['payment_status'])
      );

      if($data['module'] == 'hotel'){
        $this->tblData = array_merge($this->tblData,array(
          array('field'=>'hotel_name','type'=>'s','value'=>$data['hotel_name']),
        ));
      }else{
        $this->tblData = array_merge($this->tblData,array(
          array('field'=>'from_city','type'=>'s','value'=>$data['from_city']),
          array('field'=>'to_city','type'=>'s','value'=>$data['to_city']),
        ));
      }

      return $this->save();
    }

    function getBookingById($id){
      global $db;
      $sql	= "SELECT * FROM `booking` WHERE id = '{$id}' LIMIT 1";
			$result = $db->query($sql);
			return $result;
    }

    function getBookingByUserId(){
      global $db;
      $sql	= "SELECT * FROM `booking` WHERE u_id = '{$_SESSION[SESSION_PREFIX.'_user']->user_id}' ORDER BY `id` DESC";
			$result = $db->query($sql);
			return $result;
    }

    function getCustomerById($id){
      global $db;
      $sql	= "SELECT * FROM `users` WHERE `user_id` = '{$id}' LIMIT 1";
			$result = $db->query($sql);
			return $result;
    }
  }
