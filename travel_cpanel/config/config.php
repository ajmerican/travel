<?php
	//Site Info
	Config::set('site_name','Travel App');
	Config::set('site_short_name','Travel App');
	Config::set('site_logo','logo.png');
	Config::set('site_favicon','logo.png');
	
	//Framework Settings
	Config::set('languages',array('en'));
	Config::set('routes',array(
		'default'	=> '',
		'login'		=> ''
	));

	//Set Defaults
	Config::set('default_route','default');
	Config::set('default_language','en');
	Config::set('default_controller','dashboard');
	Config::set('default_action','index');

	//Local ips to run site on localhost or local ip
	Config::set('local_ips', array(
					'127.0.0.1',
					'::1'
	));

	//Database Info
	Config::set('db.host','localhost');
	Config::set('db.user','kachiyai_dev');
	Config::set('db.password','-0G%=8H;Kc5*');
	Config::set('db.db_name','kachiyai_travel');

	//Date
	Config::set('date_format','d/m/Y');
	Config::set('date_time_format','d/m/Y h:i A');

	//Miscellaneous
	Config::set('base_dir','/travel_cpanel/');
	Config::set('salt','t/PeR|)9fl59atZC');
