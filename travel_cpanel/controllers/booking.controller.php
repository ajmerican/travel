<?php
	class BookingController extends Controller{

		public function __construct($data = array()){
			parent::__construct($data);
			$this->model	= new BookingModel();
			$this->data['sub-title']	= '';
			$this->data['breadcrumb']	= array(Router::link('booking') => 'Booking');
		}

		public function index(){
			$this->data['title']		= 'Booking List';
			$this->data['var']			= array('records' => $this->model->getList());
		}

		public function view(){
			$this->data['title']		= 'View Booking';

			$id	= 0;

			if(isset($this->params[0])){
				$id	= $this->params[0];
			}
			
			$this->data['var']	= $this->model->getById($id);
		}
	}
