<?php
	class UserController extends Controller{

		public function __construct($data = array()){
			parent::__construct($data);
			$this->model	= new UserModel();
			$this->data['sub-title']	= '';
			$this->data['breadcrumb']	= array(Router::link('user') => 'User');
		}

		public function index(){
			Router::redirect('dashboard');
			exit;
		}

		public function login(){
			$userName	= $this->getPost('username');
			$password	= $this->getPost('password');

			if($userName){
				$user = $this->model->getByLogin($userName);

				$modeSign	= isset($_POST['sign-btn']) ? true : false;
				$modeReset= isset($_POST['reset-btn']) ? true : false;

				if($modeSign && $password){
					$hash = md5(Config::get('salt').$password);

					Session::set('super_admin', false);
					Session::set('center_admin', false);
					Session::set('agent', false);

					if($user && $user['status'] && $user['password'] == $hash){

						if(isset($user['type']) && $user['type'] != ''){
							Session::set('role_name', $user['type']);
							if($user['type'] == 'super_admin'){
								Session::set('super_admin', true);
							} else if($user['type'] == 'admin'){
								Session::set('center_admin', true);
							} else if($user['type'] == 'agent'){
								Session::set('agent', true);
							}
						}

						if(Session::get('super_admin') || Session::get('center_admin') || Session::get('agent')){
							Session::set('user_id', $user['id']);
							Session::set('user_name',$userName);
							Session::set('name',ucfirst($user['first_name']).' '.ucfirst($user['last_name']));
						}

					} else {
						$msg	= array('type' => 'danger', 'message' => 'Incorrect username/password. Please try with correct credentials');
						Session::setMessage($msg);
						Router::redirect('user/login');
						exit;
					}

					Router::redirect('dashboard');
					exit;
				}
				elseif($modeReset){
					if(isset($user['email']) && $user['email'] != ''){
						$set = $this->model->resetPassword($user['id'],$user['email']);

						if($set){
							$msg	= array('type' => 'success', 'message' => 'Password reseted successfully. Please check your email for new password!');
							Session::setMessage($msg);
							Router::redirect('user/login');
							exit;
						} else {
							$msg	= array('type' => 'danger', 'message' => 'Associate Email address not found. Please contact admin for reset your password!');
							Session::setMessage($msg);
							Router::redirect('user/login');
							exit;
						}
					}else{
						$msg	= array('type' => 'danger', 'message' => 'Associate Email address not found. Please contact admin for reset your password!');
						Session::setMessage($msg);
						Router::redirect('user/login');
						exit;
					}
				}
				else {
					$msg	= array('type' => 'danger', 'message' => 'Incorrect username/password. Please try with correct credentials');
					Session::setMessage($msg);
					Router::redirect('user/login');
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
			$this->data['breadcrumb']	= array(Router::link('user') => 'User', '' => 'Profile');
			$user_id	= Session::get('user_id');

			if(!$user_id){
				$msg	= array('type' => 'danger', 'message' => 'Please login again to change profile.');
				Session::setMessage($msg);
				Router::redirect('user/login');
				exit;
			}

			if($_POST){
				$id	= (isset($user_id) && $user_id > 0) ? $user_id : null;

				if($id){
					$result = $this->model->saveProfile($_POST);
				} else {
					$msg	= array('type' => 'danger', 'message' => 'Please login again to change profile.');
					Session::setMessage($msg);
					Router::redirect('user/login');
					exit;
				}

				if($result){
					$msg	= array('type' => 'success', 'message' => 'Data was successfully saved.');
					Session::setMessage($msg);
				} else {
					$msg	= array('type' => 'danger', 'message' => 'Error while saving data.');
					Session::setMessage($msg);
				}

				Router::redirect('user/profile/');
				exit;
			}

			$this->data['var']	= $this->model->getById($user_id);
		}

		public function all(){
			$this->data['title']		= 'User List';
			$this->data['sub-title']	= '';
			$this->data['breadcrumb']	= array(Router::link('user') => 'User');
			$this->data['var']			= array('records' => $this->model->getList());
		}

		public function admin(){
			$this->data['title']		= 'Admin List';
			$this->data['sub-title']	= '';
			$this->data['breadcrumb']	= array(Router::link('user') => 'User');
			$this->data['var']			= array('records' => $this->model->getList(false,'admin'));
			//'super_admin','admin','agent','customer','guest'
		}

		public function agent(){
			$this->data['title']		= 'Agent List';
			$this->data['sub-title']	= '';
			$this->data['breadcrumb']	= array(Router::link('user') => 'User');
			$this->data['var']			= array('records' => $this->model->getList(false,'agent'));
		}

		public function customer(){
			$this->data['title']		= 'Customer List';
			$this->data['sub-title']	= '';
			$this->data['breadcrumb']	= array(Router::link('user') => 'User');
			$this->data['var']			= array('records' => $this->model->getList(false,'customer'));
			//'super_admin','admin','agent','customer','guest'
		}

		public function guest(){
			$this->data['title']		= 'Guest List';
			$this->data['sub-title']	= '';
			$this->data['breadcrumb']	= array(Router::link('user') => 'User');
			$this->data['var']			= array('records' => $this->model->getList(false,'guest'));
			//'super_admin','admin','agent','customer','guest'
		}

		public function add(){
			$this->data['title']	= 'New User';

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
					Router::redirect('user/add/'.$result);
					exit;
				} else {
					Router::redirect('user/add');
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
					$msg	= array('type' => 'success', 'message' => 'User was deleted');
					Session::setMessage($msg);
				} else {
					$msg	= array('type' => 'danger', 'message' => 'Error while delete user');
					Session::setMessage($msg);
				}
			}

			Router::redirect('user/all');
			exit;
		}
	}
