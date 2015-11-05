<?php
	session_start();
	ini_set('display_errors', 1);
	
	require_once 'application/app.php';

	try
	{
		App::run();
	}
	catch(NotFoundExcetption $nfe)
	{
		header("Location: /site/notfound");
	}
	catch(DbException $dbe)
	{
		die($dbe->getMyMessage());
	}
	catch(Excetption $e)
	{
		die($e->message);
	}