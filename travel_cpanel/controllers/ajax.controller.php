<?php
	class AjaxController extends Controller{

		public function __construct($data = array()){
			parent::__construct($data);
		}

		public function index(){
			//if($_POST)
			{
				$mode = $this->getRequest('mode');

				switch($mode){
					case 'getTaluka':
						$dist_id 	= $this->getRequest('search_by');
						$t_id	 	= $this->getRequest('selected');
						$talukaModel= new TalukaModel();
						$taluka 	= $talukaModel->getByDistrict($dist_id);
						if($taluka && count($taluka) > 0){
							$arg = array(
									'selected'	=> $t_id
								);
							$return = array('msg' => '','op' => HTML::toOptions($taluka,$arg));
						} else {
							$return = array('msg' => 'Taluka Not Found');
						}
					break;

					case 'getTradeFullBatchNo':
						$course_id 		= $this->getRequest('course_id');
						$batch_no 		= $this->getRequest('batch_no');
						$obj			= new BatcheModel();

						$course_by_id	= $obj->getCourseByID($course_id);
						$BatchNo 		= $obj->mkTradeFullBatchNo((int)$batch_no,$course_by_id['code']);

						if($BatchNo && $BatchNo != ''){
							$return = array('msg' => '','op' => $BatchNo);
						} else {
							$return = array('msg' => 'NoBatchNo');
						}
					break;

					case 'getFullBatchNo':
						$course_id 		= $this->getRequest('course_id');
						$batch_no 		= $this->getRequest('batch_no');
						$obj			= new BatcheModel();

						//$course_by_id	= $obj->getCourseByID($course_id);
						$BatchNo 		= $obj->mkFullBatchNo((int)$batch_no,$course_id);
						$batchExist		= $obj->ckBatchNoExist($BatchNo);

						if($batchExist){
							$return = array('msg' => 'Batch Already Exists', 'op' => '');
						}
						elseif($BatchNo && $BatchNo != ''){
							$return = array('msg' => '','op' => $BatchNo);
						} else {
							$return = array('msg' => 'NoBatchNo');
						}
					break;

					case 'getEndDate':
						$course_id 		= $this->getRequest('course_id');
						$start_date		= $this->getRequest('start_date');
						$obj			= new BatcheModel();

						$course_by_id	= $obj->getCourseByID($course_id);
						$duration		= (isset($course_by_id['duration_type']) && $course_by_id['duration_type'] == 'months') ? $course_by_id['duration']*30 : $course_by_id['duration'];
						$end_date 		= $obj->getEndDate($start_date,$duration);

						if($end_date && $end_date != ''){
							$return = array('msg' => '', 'end_date' => $end_date, 'working_days' => 'Total working days = '.($duration+0));
						} else {
							$return = array('msg' => 'NoEndDate');
						}
					break;

					case 'approveAge':
						$student_id = $this->getRequest('s_id');
						$s_status		= $this->getRequest('s_status');

						$obj			= new StudentModel();

						$result	= $obj->approveAge($student_id, $s_status);

						if($result){
							if($s_status == 1){
								$return = array('msg' => 'Student Approved!', 'hide' => 'yes');
							} else {
								$return = array('msg' => 'Student Decline!', 'hide' => 'yes');
							}
						} else {
							$return = array('msg' => 'Technical error! Please contact developer.', 'hide' => 'no');
						}
					break;

					default:
						$return = array('msg' => 'Nothing to do');
					break;
				}
			}

			echo json_encode($return);
			exit;
		}
	}
