<?php
	class Agent_typesController extends Controller{

		public function __construct($data = array()){
			parent::__construct($data);
			$this->model	= new Agent_typesModel();
			$this->data['sub-title']	= '';
			$this->data['breadcrumb']	= array(Router::link('agent_types') => 'Agent Types');
		}

		public function index(){
			$this->data['title']		= 'List';
			$this->data['var']			= array('records' => $this->model->getList());
		}

		public function add(){
			$this->data['title']	= 'Agent Types';

			if($_POST){
				$id	= (isset($_POST['id']) && $_POST['id'] > 0) ? $_POST['id'] : null;
				$result = $this->model->save($_POST, $id);

				if($result){
					$msg	= array('type' => 'success', 'message' => 'Data successfully saved.');
					Session::setMessage($msg);
				} else {
					$msg	= array('type' => 'danger', 'message' => 'Error while saving data.');
					Session::setMessage($msg);
				}

				if(isset($_POST['savecontbtn'])){
					Router::redirect('agent_types/add/'.$result);
				} else {
					Router::redirect('agent_types');
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
					$msg	= array('type' => 'success', 'message' => 'Agent Types was deleted');
					Session::setMessage($msg);
				} else {
					$msg	= array('type' => 'danger', 'message' => 'Error while delete agent_types');
					Session::setMessage($msg);
				}
			}

			Router::redirect('agent_types');
		}
	}
