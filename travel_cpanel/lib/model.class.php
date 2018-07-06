<?php
	class Model{
		protected $db;

		public function __construct(){
			$this->db = App::$db;
		}

		public function upload($file) {
			if(isset($file["name"]) && $file["name"] != ''){

				$conroller	= App::getRouter()->getController();
				$path		= UPLOAD_PATH.DS.$conroller.DS;

				// Undefined | Multiple Files | $_FILES Corruption Attack
		    // If this request falls under any of them, treat it invalid.
		    if (
		        !isset($file['error']) ||
		        is_array($file['error'])
		    ) {
					$msg	= array('type' => 'danger', 'message' => 'Invalid parameters.');
					Session::setMessage($msg);
		    }

		    // Check $file['error'] value.
		    switch ($file['error']) {
		        case UPLOAD_ERR_OK:
		            break;
		        case UPLOAD_ERR_NO_FILE:
							$msg	= array('type' => 'danger', 'message' => 'No file sent.');
							Session::setMessage($msg);
							break;
		        case UPLOAD_ERR_INI_SIZE:
		        case UPLOAD_ERR_FORM_SIZE:
								$msg	= array('type' => 'danger', 'message' => 'Exceeded filesize limit.');
								Session::setMessage($msg);
								break;
		        default:
								$msg	= array('type' => 'danger', 'message' => 'Unknown errors.');
								Session::setMessage($msg);
								break;
		    }

				$this->mkdir($path);

				$fInfo	= pathinfo($file["name"]);
				//$this->d($fInfo);
				if(!isset($fInfo['extension'])){
					$msg	= array('type' => 'danger', 'message' => 'Not a valid file.');
					Session::setMessage($msg);
					return null;
				}

				$fileName	= str_replace(' ','-',trim(strtolower($fInfo['filename']))).'_'.time().'.'.$fInfo['extension'];
				$target_file= $path . $fileName;

				if (move_uploaded_file($file["tmp_name"], $target_file)) {
					//echo $fileName;
					return $fileName;
				} else {
					print_r($file);
					return null;
				}
			}

			return null;
		}

		public function mkdir($path) {
			if(!file_exists($path))
			{
				mkdir($path);
			}
		}

		/*
		*    This function figures out what fiscal year a specified date is in.
		*    $inputDate - the date you wish to find the fiscal year for. (12/4/08)(m/d/y)
		*    $fyStartDate - the month and day your fiscal year starts. (7/1)(m/d)
		*    $fyEndDate - the month and day your fiscal year ends. (6/30)(m/d)
		*    $fy - returns the correct fiscal year
		*/
		function calculateFiscalYearForDate($inputDate, $fyStart = '4/1', $fyEnd = '3/31'){
			$date = strtotime($inputDate);
			$inputyear = strftime('%Y',$date);

			$fystartdate = strtotime($fyStart.$inputyear);
			$fyenddate = strtotime($fyEnd.$inputyear);

			if($date < $fyenddate){
				$fy = intval($inputyear);
			}else{
				$fy = intval(intval($inputyear) + 1);
			}

			return $fy;
		}

		//The function returns the no. of business days between two dates and it skips the holidays
		function getWorkingDays($startDate,$endDate,$holidays){
			// do strtotime calculations just once
			$endDate = strtotime($endDate);
			$startDate = strtotime($startDate);

			//The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
			//We add one to inlude both dates in the interval.
			$days = ($endDate - $startDate) / 86400 + 1;

			$no_full_weeks = floor($days / 7);
			$no_remaining_days = fmod($days, 7);

			//It will return 1 if it's Monday,.. ,7 for Sunday
			$the_first_day_of_week = date("N", $startDate);
			$the_last_day_of_week = date("N", $endDate);

			//---->The two can be equal in leap years when february has 29 days, the equal sign is added here
			//In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
			if ($the_first_day_of_week <= $the_last_day_of_week) {
				if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
				if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
			}
			else {
				// (edit by Tokes to fix an edge case where the start day was a Sunday
				// and the end day was NOT a Saturday)

				// the day of the week for start is later than the day of the week for end
				if ($the_first_day_of_week == 7) {
					// if the start date is a Sunday, then we definitely subtract 1 day
					$no_remaining_days--;

					if ($the_last_day_of_week == 6) {
						// if the end date is a Saturday, then we subtract another day
						$no_remaining_days--;
					}
				}
				else {
					// the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
					// so we skip an entire weekend and subtract 2 days
					$no_remaining_days -= 2;
				}
			}

			//The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
			//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
			$workingDays = $no_full_weeks * 5;
			if ($no_remaining_days > 0 )
			{
			  $workingDays += $no_remaining_days;
			}

			//We subtract the holidays
			foreach($holidays as $holiday){
				$time_stamp=strtotime($holiday);
				//If the holiday doesn't fall in weekend
				if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
					$workingDays--;
			}

			return $workingDays;
		}

		public function add_business_days($startdate,$buisnessdays,$holidays,$dateformat)
		{
			$i=1;
			$startdate	= (isset($startdate) && $startdate != '') ? $startdate : date('Y-m-d');
			$dayx = strtotime($startdate);
			while($i < $buisnessdays){
				$day = date('N',$dayx);
				$date = date('Y-m-d',$dayx);
				if($day < 7 && !in_array($date,$holidays))$i++;
				$dayx = strtotime($date.' +1 day');
			}
			return date($dateformat,$dayx);
		}

		public function add_newbusiness_days($startdate,$buisnessdays,$holidays,$dateformat)
		{
			$i=1;
			$startdate	= (isset($startdate) && $startdate != '') ? $startdate : date('Y-m-d');
			$dayx = strtotime($startdate);
			$date = date('Y-m-d',$dayx);
			$holidayExtention = $buisnessdays + count($holidays);
			$dayx = strtotime($date.' +'.$holidayExtention.' day');
			$batchenddate= date($dateformat,$dayx);
			return $batchenddate;
		}

		public function d($a,$print=true,$exit=false) {
			if($print){
				echo '<pre>';
				var_dump($a);
				echo '</pre>';
				if($exit) exit();
			}
		}

		public function isValidTime($time) {
			return preg_match("/(2[0-3]|[01][0-9]):([0-5][0-9])/", $time);
		}

		public function createColumnsArray($end_column, $first_letters = ''){
			$columns = array();
			$length = strlen($end_column);
			$letters = range('A', 'Z');

			// Iterate over 26 letters.
			foreach ($letters as $letter) {
				// Paste the $first_letters before the next.
				$column = $first_letters . $letter;

				// Add the column to the final array.
				$columns[] = $column;

				// If it was the end column that was added, return the columns.
				if ($column == $end_column)
				  return $columns;
			}

			// Add the column children.
			foreach ($columns as $column) {
			  // Don't itterate if the $end_column was already set in a previous itteration.
			  // Stop iterating if you've reached the maximum character length.
			  if (!in_array($end_column, $columns) && strlen($column) < $length) {
				  $new_columns = $this->createColumnsArray($end_column, $column);
				  // Merge the new columns which were created with the final columns array.
				  $columns = array_merge($columns, $new_columns);
			  }
			}

			return $columns;
		}

		public function getAge($date){
			$from = new DateTime($date); //Formate: yyyy-mm-dd
			$to   = new DateTime('today');
			return $from->diff($to)->y;
		}

		public function generateRandomString($length = 10) {
			//$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ~!@$%^&()_+|`-=\[];,./{}:"<>?';
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ~!@$%^&()_|`-=[];,./{}:"<>?';
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			return $randomString;
		}

		public function sendMail($to,$subject,$body)
		{
			require_once('ext/mail/class.phpmailer.php');
			$mail = new PHPMailer();

			$body	= '<!doctype html>
	<html>
		<head>
			<style type="text/css">
				body{font-family: Verdana,Geneva,sans-serif;font-size: 14px;font-style: normal;font-variant: normal;font-weight: 400;line-height: 20px;width:600px;}
			</style>
		</head>
		<body>
			<div>'.$body.'<br />IP: '.$_SERVER['REMOTE_ADDR'].'</div>
		</body>
	</html>';
			//return true;

			/* $mail->IsSMTP();
			$mail->SMTPDebug = 0;
			$mail->Host = '';
			$mail->Port = 25;
			$mail->SMTPAuth = true;
			$mail->Username = '';
			$mail->Password = ''; */
			$mail->IsMail();
			$mail->Debugoutput = 'html';
			$mail->setFrom('patelalicen@gmail.com','Alice Patel');
			$mail->Subject = $subject;
			$mail->msgHTML($body);

			$recipients = array(
							array($to)
						);

			if(count($recipients) > 0)
			{
				foreach($recipients as $key => $val)
				{
					$email	= (isset($val[0]) && $val[0] != '') ? $val[0] : '';
					$name	= (isset($val[1]) && $val[1] != '') ? $val[1] : '';

					if($email != '')
					{
						$mail->addAddress($email, $name);
					}
				}
			}

			if ($mail->send()) {
				return true;
			}
			else
			{
				return false;
			}
		}

		public function sendAdminMail($subject,$body)
		{
			require_once('ext/mail/class.phpmailer.php');
			$mail = new PHPMailer();

			$body	= '<!doctype html>
	<html>
		<head>
			<style type="text/css">
				body{font-family: Verdana,Geneva,sans-serif;font-size: 14px;font-style: normal;font-variant: normal;font-weight: 400;line-height: 20px;width:600px;}
			</style>
		</head>
		<body>
			<div>'.$body.'<br />IP: '.$_SERVER['REMOTE_ADDR'].'</div>
		</body>
	</html>';
			//return true;

			/* $mail->IsSMTP();
			$mail->SMTPDebug = 0;
			$mail->Host = '';
			$mail->Port = 25;
			$mail->SMTPAuth = true;
			$mail->Username = '';
			$mail->Password = ''; */
			$mail->IsMail();
			$mail->Debugoutput = 'html';
			$mail->setFrom('patelalicen@gmail.com','Alice Patel');
			$mail->Subject = $subject;
			$mail->msgHTML($body);

			$recipients = array(
							array('patelalicen@gmail.com','Alice Patel'),
							array('rpatel@gmail.com','Rajendra Patel')
						);

			if(count($recipients) > 0)
			{
				foreach($recipients as $key => $val)
				{
					$email	= (isset($val[0]) && $val[0] != '') ? $val[0] : '';
					$name	= (isset($val[1]) && $val[1] != '') ? $val[1] : '';

					if($email != '')
					{
						$mail->addAddress($email, $name);
					}
				}
			}

			if ($mail->send()) {
				return true;
			}
			else
			{
				return false;
			}
		}

		public function sendDevMail($subject,$body)
		{
			require_once('ext/mail/class.phpmailer.php');
			$mail = new PHPMailer();

			$body	= '<!doctype html>
	<html>
		<head>
			<style type="text/css">
				body{font-family: Verdana,Geneva,sans-serif;font-size: 14px;font-style: normal;font-variant: normal;font-weight: 400;line-height: 20px;width:600px;}
			</style>
		</head>
		<body>
			<div>'.$body.'<br />IP: '.$_SERVER['REMOTE_ADDR'].'</div>
		</body>
	</html>';
			//return true;

			/* $mail->IsSMTP();
			$mail->SMTPDebug = 0;
			$mail->Host = '';
			$mail->Port = 25;
			$mail->SMTPAuth = true;
			$mail->Username = '';
			$mail->Password = ''; */
			$mail->IsMail();
			$mail->Debugoutput = 'html';
			$mail->setFrom('patelalicen@gmail.com','Alice Patel');
			$mail->Subject = $subject;
			$mail->msgHTML($body);

			$recipients = array(
							array('patelalicen@gmail.com','Alice Patel')
						);

			if(count($recipients) > 0)
			{
				foreach($recipients as $key => $val)
				{
					$email	= (isset($val[0]) && $val[0] != '') ? $val[0] : '';
					$name	= (isset($val[1]) && $val[1] != '') ? $val[1] : '';

					if($email != '')
					{
						$mail->addAddress($email, $name);
					}
				}
			}

			if ($mail->send()) {
				return true;
			}
			else
			{
				return false;
			}
		}
	}
