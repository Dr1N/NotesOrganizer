<?php
	/**
	* Контроллер для управления пользователями
	*/
	class UsersController extends Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->model = new UsersModel;
		}

		public function actionIndex()
		{
			if(isset($_SESSION['isAuthenticated']) && $_SESSION['isAuthenticated'] == true)
			{
				header("Location: /");
			}
			else
			{
				header("Location: /users/login");
			}
		}

		public function actionLogin()
		{
			//Предотврашение повторной аутентификации

			$this->chechAuthentication();

			//Запрос аутентификации

			if(isset($_POST) && !empty($_POST))
			{
				$this->model->email = isset($_POST['email']) ? trim($_POST['email']) : "";
				$this->model->password = isset($_POST['password']) ? trim($_POST['password']) : "";

				try
				{
					$result = $this->model->login();
				}
				catch(DbException $dbe)
				{
					$result['error'] = "Внутренняя ошибка, попробуйте позже";
				}
				
				if(!empty($result))
				{
					$this->view->render("login.php", $result);
				}
				else
				{
					header("Location: /");
				}
			}
			else
			{
				//Запрос данных у пользователя
				
				$this->view->render("login.php");
			}
		}

		public function actionRegistration()
		{
			//Предотврашение повторной регистрации

			$this->chechAuthentication();

			//Регистрация

			if(isset($_POST) && !empty($_POST))
			{
				$this->model->email = isset($_POST['email']) ? trim($_POST['email']) : "";
				$this->model->password = isset($_POST['password']) ? trim($_POST['password']) : "";
				$this->model->confirm = isset($_POST['confirm']) ? trim($_POST['confirm']) : "";
				$this->model->name = isset($_POST['name']) ? trim($_POST['name']) : "";

				try
				{
					$result = $this->model->validate();
					if(isset($result['error']) && !empty($result['error']))
					{
						$this->view->render("registation.php", $result);
					}
					else
					{
						$this->model->register();
						header("Location: /users/login");
					}
				}
				catch(DbException $dbe)
				{
					$result['email'] = $this->model->email;
					$result['name'] = $this->model->name;
					$result['error'] = "Внутренняя ошибка, попробуйте позже"; //to-do
					$this->view->render("registation.php", $result);
				}
			}
			else
			{
				//Запрос данных у пользователя

				$this->view->render("registation.php");
			}
		}

		public function actionLogout()
		{
			if($_SESSION['isAuthenticated'] == true)
			{
				$this->model->logout();
			}
			header("Location: /users/login");
		}

		public function chechAuthentication()
		{
			if(isset($_SESSION['isAuthenticated']) && $_SESSION['isAuthenticated'] == true)
			{
				header("Location: /");
			}
		}
	}