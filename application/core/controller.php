<?php
	/**
	* Базовый класс контроллера
	*/
	abstract class Controller
	{
		public $model;
		public $view;

		public function __construct()
		{
			$this->view = new View();
		}

		abstract public function actionIndex();

		/**
		* Проверка аутентифицирован ли пользователь
		*
		* В случае если пользователь не аутентифицирован - редирект на логин
		*/
		public function chechAuthentication()
		{
			if(!isset($_SESSION['isAuthenticated']) || $_SESSION['isAuthenticated'] == false)
			{
				header("Location: /users/login");
			}
		}
	}