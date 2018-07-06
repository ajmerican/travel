<?php
	class DashboardController extends Controller{

		public function __construct($data = array()){
			parent::__construct($data);
			$this->model	= new DashboardModel();
		}

		public function index(){
			$this->data['title']		= 'Dashboard';
			$this->data['sub-title']	= 'dashboard & statistics';
			$this->data['breadcrumb']	= array();
			$this->data['body']			= $this->model->getGeneralData();
		}

		public function logout(){
			Session::destroy();
			Router::redirect('dashboard');
			exit;
		}
	}
