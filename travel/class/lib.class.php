<?php
	class lib{
		private	$messages;

		public function __construct() {

		}

		public function date($date,$format = '') {
			$format = ($format != '') ? $format : DATE_FORMAT;

			$fDate	= ($date != '' && $date != '0000-00-00 00:00:00' && $date != '0000-00-00') ? date($format,strtotime($date)) : '-';
			return $fDate;
		}

		public function convert_date($date,$fromformat = '',$toformat = '') {
			if(isset($date) && $date != ''){
				$fromformat = ($fromformat != '') ? $fromformat : DATE_FORMAT;
				$toformat	= ($toformat != '') ? $toformat : MYSQL_DATE_FORMAT;

				$fDate = DateTime::createFromFormat(DATE_FORMAT, $date);

				if($fDate){
					$fDate = $fDate->format(MYSQL_DATE_FORMAT);
				}

				return $fDate;
			}

			return null;
		}

		//use below funstions
		public function getRequest($key,$defalutValue = '')
		{
			if(isset($_REQUEST[$key]) && !is_array($_REQUEST[$key]))
			{
				$safeValue	= (isset($_REQUEST[$key])) ? trim(strip_tags($_REQUEST[$key])) : '';
			}
			else
			{
				$safeValue	= (isset($_REQUEST[$key])) ? $_REQUEST[$key] : '';
			}

			return ($safeValue != '') ? $safeValue : $defalutValue;
		}

		public function setMessage($msg)
		{
			$_SESSION['msg'][] = $msg;
			return true;
		}

		public function getMessage($message = array())
		{
			$msg	= '';
			$isMsg	= false;

			if(count($message) > 0)
			{
				foreach($message AS $key => $val)
				{
					if(isset($val['type']) && $val['type'] != '' && isset($val['message']) && $val['message'] != '')
					{
						$msg.= '<div class="alert alert-'.$val['type'].' alert-dismissable">'.$val['message'].'</div>';
					}
				}

				$isMsg	= true;
			}

			if(isset($_SESSION['msg']) && count($_SESSION['msg']) > 0)
			{
				foreach($_SESSION['msg'] AS $key => $val)
				{
					if(isset($val['type']) && $val['type'] != '' && isset($val['message']) && $val['message'] != '')
					{
						$msg.= '<div class="alert alert-'.$val['type'].' alert-dismissable">'.$val['message'].'</div>';
					}
				}
				unset($_SESSION['msg']);
				$_SESSION['msg']	= array();
				$isMsg	= true;
			}

			if(!$isMsg)
			{
				$msg.= '<div class="alert alert-success alert-dismissable" style="display:none;"></div>';
			}

			return $msg;
		}

		public function totPages($obj,$filters,$search)
		{
			$total_pages = 0;

			$per_page	= defined('PAGING_RECORDS_PER_PAGE') ? PAGING_RECORDS_PER_PAGE : 10;
			$per_page	= $this->getRequest('records', $per_page);

			$total_pages = ceil($obj->totRows($filters,$search) / $per_page);	//total pages we going to have

			return $total_pages;
		}

		public function paging($obj,$filters,$search)
		{
			$adjacents	= PAGING_ADJACENTS;

			$prevlabel	= '<i class="fa fa-angle-left" aria-hidden="true"></i>';
			$nextlabel	= '<i class="fa fa-angle-right" aria-hidden="true"></i>';

			$firstlabel	= '<i class="fa fa-angle-double-left" aria-hidden="true"></i>';
			$lastlabel	= '<i class="fa fa-angle-double-right" aria-hidden="true"></i>';

			$page	= $this->getRequest('page',1);
			$tpages	= $this->totPages($obj,$filters,$search);

			$out = '<input type="hidden" name="page" value="'.$page.'" id="page" />
				<ul class="pagination">';

			//previous
			if ($page == 1) {
				$out.= "<li class=\"disabled\"><a>" . $firstlabel . "</a></li>";
				$out.= "<li class=\"disabled\"><a>" . $prevlabel . "</a></li>";
			} elseif ($page == 2) {
			$out.= "<li><a  href=\"javascript:goToPage(1);\">" . $firstlabel . "</a></li>";
			$out.= "<li><a  href=\"javascript:goToPage(1);\">" . $prevlabel . "</a></li>";
			} else {
				$out.= "<li><a  href=\"javascript:goToPage(1);\">" . $firstlabel . "</a></li>";
				$out.= "<li><a  href=\"javascript:goToPage(".($page - 1).");\">" . $prevlabel . "</a></li>";
			}

			$pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
			$pmax = ($page < ($tpages - $adjacents)) ? ($page + $adjacents) : $tpages;

			for ($i = $pmin; $i <= $pmax; $i++) {
				if ($i == $page) {
					$out.= "<li><a class=\"active\">" . $i . "</a></li>\n";
				} else {
					$out.= "<li><a  href=\"javascript:goToPage(". $i . ");\">" . $i . "</a></li>";
				}
			}

			// next
			if ($page < $tpages) {
				$out.= "<li><a  href=\"javascript:goToPage(". ($page + 1) . ");\">" . $nextlabel . "</a></li>";
				$out.= "<li><a  href=\"javascript:goToPage(". ($tpages) . ");\">" . $lastlabel . "</a></li>";
			} else {
				$out.= "<li class=\"disabled\"><a>" . $nextlabel . "</a></li>";
				$out.= "<li class=\"disabled\"><a>" . $lastlabel . "</a></li>";
			}

			$out.= "</ul>";

			return $out;
		}

		public function getLimits($obj,$filters,$search)
		{
			$show_page	= $this->getRequest('page',1);
			$total_pages= $this->totPages($obj,$filters,$search);

			$per_page	= defined('PAGING_RECORDS_PER_PAGE') ? PAGING_RECORDS_PER_PAGE : 10;
			$per_page	= $this->getRequest('records', $per_page);

			if ($show_page > 1 && $show_page <= $total_pages) {
				$start = ($show_page - 1) * $per_page;
			} else {
				$start = 0;
			}

			$results = array('offset' => $start,'row_count' => $per_page);
			return $results;
		}

		public function pagingShowing($obj,$filters,$search)
		{
			$limits = $this->getLimits($obj,$filters,$search);
			$tot 	= $obj->totRows($filters,$search);

			$start	= ($tot > 0) ? ($limits['offset']+1) : 0;
			$end	= $limits['row_count']+$limits['offset'];
			$end	= ($end > $tot) ? $tot : $end;

			return 'Showing '.$start.'–'.$end.' of '.$tot.' results';
		}

		public function getConfig($key)
		{
			$q		= "SELECT key_value from ".TBL_PREFIX."config WHERE key_name = '".$key."' ";
			$result	= $this->db->query($q);
			$value	= $result->fetch_object();

			return ($value) ? $value->key_value : '';
		}

		public function cleanURL($str) {

			$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $str);
			$clean = strtolower(trim($clean, '-'));
			$clean = preg_replace("/[\/_|+ -]+/", '-', $clean);

			return $clean;
		}

		/**
		 * easy image resize function
		 * @param  $file - file name to resize
		 * @param  $string - The image data, as a string
		 * @param  $width - new image width
		 * @param  $height - new image height
		 * @param  $proportional - keep image proportional, default is no
		 * @param  $output - name of the new file (include path if needed)
		 * @param  $delete_original - if true the original image will be deleted
		 * @param  $use_linux_commands - if set to true will use "rm" to delete the image, if false will use PHP unlink
		 * @param  $quality - enter 1-100 (100 is best quality) default is 100
		 * @return boolean|resource
		 */
		  function resizeImage($file,
                              $width              = 0,
                              $height             = 0,
                              $output             = 'file',
							  $proportional       = false,
                              $delete_original    = false,
                              $use_linux_commands = false,
                              $quality			  = 100,
							  $string             = null
				 ) {

			if ( $height <= 0 && $width <= 0 ) return false;
			if ( $file === null && $string === null ) return false;

			# Setting defaults and meta
			$info                         = $file !== null ? getimagesize($file) : getimagesizefromstring($string);
			$image                        = '';
			$final_width                  = 0;
			$final_height                 = 0;
			list($width_old, $height_old) = $info;
			$cropHeight = $cropWidth = 0;

			# Calculating proportionality
			if ($proportional) {
			  if      ($width  == 0)  $factor = $height/$height_old;
			  elseif  ($height == 0)  $factor = $width/$width_old;
			  else                    $factor = min( $width / $width_old, $height / $height_old );

			  $final_width  = round( $width_old * $factor );
			  $final_height = round( $height_old * $factor );
			}
			else {
			  $final_width = ( $width <= 0 ) ? $width_old : $width;
			  $final_height = ( $height <= 0 ) ? $height_old : $height;
			  $widthX = $width_old / $width;
			  $heightX = $height_old / $height;

			  $x = min($widthX, $heightX);
			  $cropWidth = ($width_old - $width * $x) / 2;
			  $cropHeight = ($height_old - $height * $x) / 2;
			}

			# Loading image to memory according to type
			switch ( $info[2] ) {
			  case IMAGETYPE_JPEG:  $file !== null ? $image = imagecreatefromjpeg($file) : $image = imagecreatefromstring($string);  break;
			  case IMAGETYPE_GIF:   $file !== null ? $image = imagecreatefromgif($file)  : $image = imagecreatefromstring($string);  break;
			  case IMAGETYPE_PNG:   $file !== null ? $image = imagecreatefrompng($file)  : $image = imagecreatefromstring($string);  break;
			  default: return false;
			}

			# This is the resizing/resampling/transparency-preserving magic
			$image_resized = imagecreatetruecolor( $final_width, $final_height );
			if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
			  $transparency = imagecolortransparent($image);
			  $palletsize = imagecolorstotal($image);

			  if ($transparency >= 0 && $transparency < $palletsize) {
				$transparent_color  = imagecolorsforindex($image, $transparency);
				$transparency       = imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
				imagefill($image_resized, 0, 0, $transparency);
				imagecolortransparent($image_resized, $transparency);
			  }
			  elseif ($info[2] == IMAGETYPE_PNG) {
				imagealphablending($image_resized, false);
				$color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
				imagefill($image_resized, 0, 0, $color);
				imagesavealpha($image_resized, true);
			  }
			}
			imagecopyresampled($image_resized, $image, 0, 0, $cropWidth, $cropHeight, $final_width, $final_height, $width_old - 2 * $cropWidth, $height_old - 2 * $cropHeight);

			# Taking care of original, if needed
			if ( $delete_original ) {
			  if ( $use_linux_commands ) exec('rm '.$file);
			  else @unlink($file);
			}

			# Preparing a method of providing result
			switch ( strtolower($output) ) {
			  case 'browser':
				$mime = image_type_to_mime_type($info[2]);
				header("Content-type: $mime");
				$output = NULL;
			  break;
			  case 'file':
				$output = $file;
			  break;
			  case 'return':
				return $image_resized;
			  break;
			  default:
			  break;
			}

			# Writing image according to type to the output destination and image quality
			switch ( $info[2] ) {
			  case IMAGETYPE_GIF:   imagegif($image_resized, $output);    break;
			  case IMAGETYPE_JPEG:  imagejpeg($image_resized, $output, $quality);   break;
			  case IMAGETYPE_PNG:
				$quality = 9 - (int)((0.9*$quality)/10.0);
				imagepng($image_resized, $output, $quality);
				break;
			  default: return false;
			}

			return true;
		  }

		function mkdir($path)
		{
			if(!file_exists($path))
			{
				mkdir($path);
			}
		}

		function upload($file,$path)
		{
			$fInfo	= pathinfo($file["name"]);


			$fileName	= $fInfo['filename'].'_'.time().'.'.$fInfo['extension'];
			$target_file= $path . $fileName;
			$target_file;

			if (move_uploaded_file($file["tmp_name"], $target_file)) {
				return $fileName;
			} else {
				return '';
			}
		}

		function formatBytes($bytes, $precision = 2)
		{
			$kilobyte = 1024;
			$megabyte = $kilobyte * 1024;
			$gigabyte = $megabyte * 1024;
			$terabyte = $gigabyte * 1024;

			if (($bytes >= 0) && ($bytes < $kilobyte)) {
				return $bytes . ' B';
			} elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
				return round($bytes / $kilobyte, $precision) . ' KB';
			} elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
				return round($bytes / $megabyte, $precision) . ' MB';
			} elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
				return round($bytes / $gigabyte, $precision) . ' GB';
			} elseif ($bytes >= $terabyte) {
				return round($bytes / $terabyte, $precision) . ' TB';
			} else {
				return $bytes . ' B';
			}
		}

		public function showImage($imgName, $path, $size = 'org', $forFront = true)
		{
			$img_path = ($forFront) ? PRO_DEFAULT_IMG_PATH_1 : ADMIN_PRO_DEFAULT_IMG_PATH_1;

			if($size!='org')
			{
				$wxh	= explode('x',$size);
			}

			if(file_exists($path.$size.'/'.$imgName))
			{
				return $path.$size.'/'.$imgName;
			}
			elseif(file_exists($path.'org/'.$imgName))
			{
				$this->mkdir($path.$size.'/');

				if($this->resizeImage($path.'org/'.$imgName,$wxh[0],$wxh[1],$path.$size.'/'.$imgName))
				{
					return $path.$size.'/'.$imgName;
				}
			}

			return $img_path;
		}

		public function removeFile($file)
		{
			if(file_exists($file) && $file != '')
			{
				return unlink($file);
			}

			return false;
		}

		public function sendmail($subject,$body,$to,$to_name)
		{
			global $mail;

			if(IS_SMTP)
			{
				$mail->isSMTP();
				$mail->SMTPDebug = SMTP_DEBUG;
				$mail->Host = SMTP_HOST;
				$mail->Port = SMTP_PORT;
				$mail->SMTPAuth = USE_SMTP_AUTH;
				$mail->Username = SMTP_USER;
				$mail->Password = SMTP_PASS;
			}

			$mail->Debugoutput = DEBUG_OUTPUT;
			$mail->setFrom(FROM_EMAIL, FROM_NAME);
			$mail->Subject = $subject;
			$mail->msgHTML($body);

			$mail->addAddress($to,$to_name);

			$recipients = explode(',', CC_EMAILS);
			$names		= explode(',', CC_NAMES);

			if(count($recipients) > 0)
			{
				foreach($recipients as $key => $email)
				{
					$mail->addCc($email, $names[$key]);
				}
			}

			$recipients = explode(',', BCC_EMAILS);
			$names		= explode(',', BCC_NAMES);

			if(count($recipients) > 0)
			{
				foreach($recipients as $key => $email)
				{
					$mail->addBcc($email, $names[$key]);
				}
			}

			if (!$mail->send()) {
				/* $msg	= array('type' => 'danger', 'message' => 'Your Password Has Been Send To Your Registered Email ID');
				$this->setMessage($msg); */
				return false;
			}
			else
			{
				/* $msg	= array('type' => 'danger', 'message' => 'Password Sending Fail');
				$this->setMessage($msg); */
				return true;
			}
		}

		public function debug($var,$isPrint = 1)
		{
			if($isPrint)
			{
				echo '<pre>';
				var_dump($var);
				echo '</pre>';
			}
		}

		public function sendAdminMail($subject,$body)
		{
			require_once('mail/class.phpmailer.php');
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

		public function timeRemaining($date)
		{
			$now = new DateTime();
			$future_date = new DateTime($date);

			$interval = $future_date->diff($now);

			$format = '%i minutes';

			if($interval->h > 0)
			{
				$format = '%h hours, and '.$format;
			}

			if($interval->d > 0)
			{
				$format = '%a days, '.$format;
			}

			if($interval->invert == 0)
			{
				$format = 'Deadline Passed '.$format.' ago';
			}
			else
			{
				$format = $format.' remaining';
			}

			return $interval->format($format);
			//%a days, %h hours, %i minutes, %s seconds
		}

		public function changeStatus($table, $id, $status=1) {
			$sql = "UPDATE `$table` SET `status` = $status WHERE `id`=$id";

			if ($this->db->query($sql) === TRUE) {
				return true;
			} else {
				return "Error updating record: " . $this->db->error;
			}
		}

		function unique_multidim_array($array, $key) {
	    $temp_array = array();
	    $i = 0;
	    $key_array = array();

	    foreach($array as $val) {
	        if (!in_array($val[$key], $key_array)) {
	            $key_array[$i] = $val[$key];
	            $temp_array[$i] = $val;
	        }
	        $i++;
	    }
	    return $temp_array;
		}
	}
