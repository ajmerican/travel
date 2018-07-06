<?php
	class Payment_gatewayController extends Controller{

		public function __construct($data = array()){
			parent::__construct($data);
			$this->model	= new Payment_gatewayModel();
			$this->data['sub-title']	= '';
			$this->data['breadcrumb']	= array(Router::link('payment_gateway') => 'Payment Gateway');
		}

		public function index(){
			$this->data['title']		= 'List';
			$this->data['var']			= array('records' => $this->model->getList());
		}

		public function add(){
			$this->data['title']	= 'Payment Gateway';

			if($_POST){
				$id	= (isset($_POST['id']) && $_POST['id'] > 0) ? $_POST['id'] : null;
				$result = $this->model->save($_POST, $id);

				if($result){
					$msg	= array('type' => 'success', 'message' => 'Data was successfully saved.');
					Session::setMessage($msg);
				} else {
					$msg	= array('type' => 'danger', 'message' => 'Error while saving data.');
					Session::setMessage($msg);
				}

				if(isset($_POST['savecontbtn'])){
					Router::redirect('payment_gateway/add/'.$result);
				} else {
					Router::redirect('payment_gateway');
				}
			}

			$id	= 0;

			if(isset($this->params[0])){
				$id	= $this->params[0];
			}

			$this->data['var']	= $this->model->getById($id);
		}

		public function delete(){
			if(isset($this->params[0])){
				$result = $this->model->delete($this->params[0]);

				if($result){
					$msg	= array('type' => 'success', 'message' => 'Payment Gateway was deleted');
					Session::setMessage($msg);
				} else {
					$msg	= array('type' => 'danger', 'message' => 'Error while delete payment_gateway');
					Session::setMessage($msg);
				}
			}

			Router::redirect('payment_gateway');
		}
	}
