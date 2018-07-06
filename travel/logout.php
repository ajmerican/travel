<?php
	session_start();
	unset($_SESSION['traveluser']);
	session_destroy();
	header("Location:.");
	exit;
