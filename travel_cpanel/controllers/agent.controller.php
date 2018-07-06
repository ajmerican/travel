<?php
	class AgentController extends Controller{

		public function __construct($data = array()){
			parent::__construct($data);
			$this->model	= new AgentModel();
			$this->data['sub-title']	= '';
			$this->data['breadcrumb']	= array(Router::link('agent') => 'Agent');
		}

		public function index(){
			Router::redirect('dashboard');
			exit;
		}

		public function login(){
			$agentName	= $this->getPost('agentname');
			$password	= $this->getPost('password');

			if($agentName){
				$agent = $this->model->getByLogin($agentName);

				$modeSign	= isset($_POST['sign-btn']) ? true : false;
				$modeReset= isset($_POST['reset-btn']) ? true : false;

				if($modeSign && $password){
					$hash = md5(Config::get('salt').$password);

					Session::set('super_admin', false);
					Session::set('center_admin', false);

					if($agent && $agent['status'] && $agent['password'] == $hash){
						$uRoles	= $this->model->getAgentRoleById($agent['agent_id']);
						$roles = new RoleModel();

						if(isset($uRoles)){
							foreach ($uRoles as $key => $value) {
								if(isset($value) && $value['role_id'] > 0){ //Dont change if you dont get
									$uRole = $roles->getById($value['role_id']);
									Session::set('role_name', $uRole['name']);
									if(isset($uRole) && $uRole['code'] == 'level-99'){
										Session::set('super_admin', true);
										break;
									} else if(isset($uRole) && $uRole['code'] == 'level-98'){
										Session::set('center_admin', true);
										break;
									}
								}
							}
						}

						if(Session::get('super_admin') || Session::get('center_admin')){
							Session::set('agent_id', $agent['agent_id']);
							Session::set('agent_name',$agentName);
							Session::set('name',ucfirst($agent['first_name']).' '.ucfirst($agent['last_name']));
							Session::set('center_id', $center_id);
						}

					} else {
						$msg	= array('type' => 'danger', 'message' => 'Incorrect agentname/password. Please try with correct credentials');
						Session::setMessage($msg);
						Router::redirect('agent/login');
						exit;
					}

					Router::redirect('dashboard');
					exit;
				}
				elseif($modeReset){
					if(isset($agent['email']) && $agent['email'] != ''){
						$set = $this->model->resetPassword($agent['agent_id'],$agent['email']);

						if($set){
							$msg	= array('type' => 'success', 'message' => 'Password reseted successfully. Please check your email for new password!');
							Session::setMessage($msg);
							Router::redirect('agent/login');
							exit;
						} else {
							$msg	= array('type' => 'danger', 'message' => 'Associate Email address not found. Please contact admin for reset your password!');
							Session::setMessage($msg);
							Router::redirect('agent/login');
							exit;
						}
					}else{
						$msg	= array('type' => 'danger', 'message' => 'Associate Email address not found. Please contact admin for reset your password!');
						Session::setMessage($msg);
						Router::redirect('agent/login');
						exit;
					}
				}
				else {
					$msg	= array('type' => 'danger', 'message' => 'Incorrect agentname/password. Please try with correct credentials');
					Session::setMessage($msg);
					Router::redirect('agent/login');
					exit;
				}
			}
		}

		public function logout(){
			Session::destroy();
			Router::redirect('dashboard');
			exit;
		}

		public function profile(){
			$this->data['title']		= 'Profile';
			$this->data['sub-title']	= '';
			$this->data['breadcrumb']	= array(Router::link('agent') => 'Agent', '' => 'Profile');
			$agent_id	= Session::get('agent_id');

			if(!$agent_id){
				$msg	= array('type' => 'danger', 'message' => 'Please login again to change profile.');
				Session::setMessage($msg);
				Router::redirect('agent/login');
				exit;
			}

			if($_POST){
				$id	= (isset($agent_id) && $agent_id > 0) ? $agent_id : null;

				if($id){
					$result = $this->model->saveProfile($_POST);
				} else {
					$msg	= array('type' => 'danger', 'message' => 'Please login again to change profile.');
					Session::setMessage($msg);
					Router::redirect('agent/login');
					exit;
				}

				if($result){
					$msg	= array('type' => 'success', 'message' => 'Data was successfully saved.');
					Session::setMessage($msg);
				} else {
					$msg	= array('type' => 'danger', 'message' => 'Error while saving data.');
					Session::setMessage($msg);
				}

				Router::redirect('agent/profile/');
				exit;
			}

			$this->data['var']	= $this->model->getById($agent_id);
		}

		public function all(){
			$this->data['title']		= 'Agent List';
			$this->data['sub-title']	= '';
			$this->data['breadcrumb']	= array(Router::link('agent') => 'Agent');
			$this->data['var']			= array('records' => $this->model->getList());
		}

		public function add(){
			$this->data['title']	= 'New Agent';

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

				if(isset($result)){
					Router::redirect('agent/add/'.$result);
					exit;
				} else {
					Router::redirect('agent/add');
					exit;
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
					$msg	= array('type' => 'success', 'message' => 'Agent was deleted');
					Session::setMessage($msg);
				} else {
					$msg	= array('type' => 'danger', 'message' => 'Error while delete agent');
					Session::setMessage($msg);
				}
			}

			Router::redirect('agent/all');
			exit;
		}
	}
