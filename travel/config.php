<?php
	ini_set("display_errors", "1");
	error_reporting(E_ALL);

	if(!isset($_SESSION))
	{
		session_start();
	}

	$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

	define('CURRENT_PATH','');
	define('SESSION_PREFIX','uLGOviytu2');

	//Includes required Library
	require_once(CURRENT_PATH.'class/db/db.class.php');
	require_once(CURRENT_PATH.'class/lib.class.php');
	require_once(CURRENT_PATH."class/user_login.class.php");

	//Common lib functions
	$lib= new lib();
	$db	= new MySqliDB();

	//Constants
	define('TBL_PREFIX','');
	define('DATE_TIME_FORMAT','m-d-Y h:i A');
	define('MYSQL_DATE_TIME_FORMAT','Y-m-d h:i:s');
	define('MYSQL_DATE_FORMAT','Y-m-d');
	define('DIRECT_ACCESS', 'no direct access');
	define('DATE_FORMAT','m-d-Y');
	define('SALT','t/PeR|)9fl59atZC');

	//Messages
	define('TECH_ERROR','The website encountered an unexpected error. Please try again later or contact site owner.');

	//Define all your objects here
	//login classes and objects
	$login	= new userLogin();

	//Variables
	$msg	= array();
