<?php
	include_once("config.php");

	$page_title	= 'User Login';
	$msg	= array();

	$username	= (isset($_POST['username']) && $_POST['username'] != '') ? $_POST['username'] : '';
	$password	= $lib->getRequest('password');
	$remember	= $lib->getRequest('remember');
	$is_guest	= (isset($_POST['continue_guest']) && $_POST['continue_guest'] != '') ? $_POST['continue_guest'] : '';

	if($username != '' && $password != '')
	{
		$login->dologin($username, $password, $remember);
	}elseif($is_guest != ''){
		$login->doGuestlogin();
	}
?>

	  <div class="row">
			<div class="col-sm-6">
			  <form class="form-signin" method="post">
					<?php  echo $lib->GetMessage(); ?>
			    <h1 class="form-signin-heading">Login</h1>
			    <div class="form-group">
			      <label for="username" class="sr-only">Username</label>
			      <input id="username" class="form-control" placeholder="Username" required="" autofocus="" type="text" name="username">
			    </div>
			    <label for="inputPassword" class="sr-only">Password</label>
			    <input id="inputPassword" class="form-control" placeholder="Password" required="" type="password" name="password">
			    <div class="checkbox">
			      <label>
			        <input value="rememberme" type="checkbox" name="remember"> Remember me
			      </label>
			    </div>
			    <input class="btn btn-lg btn-primary btn-block" name="loginbtn" type="submit" value="Login">
			  </form>
			</div>
			<div class="col-sm-2">
				<h2>OR<h2>
			</div>
			<div class="col-sm-4">
				<form class="form-signin" method="post">
					<input type="submit" name="continue_guest" class="btn btn-lg btn-primary btn-block" value="Continue as guest" />
				</form>
			</div>
		</div>
	
<script src="js/jquery-1.12.0.min.js"></script>
<script src="js/validator/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(".form-signin").validate();
</script>
