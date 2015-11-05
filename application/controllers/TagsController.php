<?php
	class TagsController extends Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->model = new TagsModel;
		}

		/**
		* Выводит список ярлыков доступных текущему пользователю
		*/
		public function actionIndex()
		{	
			$this->chechAuthentication();

			try 
			{
				$userId = intval($_SESSION['id']);
				$list = $this->model->getUserTags($userId);
				$this->view->render("tags.php", array("list" => $list));
			} 
			catch (DbException $dbe) 
			{
				$errors['error'] = $dbe->getMyMessage();
				$this->view->render("tags.php", $errors);	
			}	
		}

		public function actionAdd()
		{
			$this->chechAuthentication();

			if(isset($_POST) && !empty($_POST))
			{
				$this->model->userId = intval($_SESSION['id']);
				$this->model->name = isset($_POST['name']) ? trim($_POST['name']) : "";

				try
				{
					$validationResult = $this->model->validate();
					if(!isset($validationResult['error']))
					{
						$this->model->insert();
						header("Location: /tags");
					}
					else
					{
						$this->view->render("tagform.php", $validationResult);
					}
				}
				catch(DbException $dbe)
				{
					$errors['error'] = "Внутренняя ошибка, попробуйте позже<br/>" . $dbe->getMyMessage();
					$this->view->render("tagform.php", $errors);
				}
			}
			else
			{
				$this->view->render("tagform.php");
			}
		}

		public function actionDelete()
		{
			$this->chechAuthentication();

			$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
			if ($id == false || $id == null) 
			{
				header("Location: /tags");
				return;
			}

			//Удаляются только свои ярлыки

			$userId = $this->model->getUserIdById($id);
			if($userId != null && $userId == $_SESSION['id'])
			{
				try
				{
					$this->model->deleteById($id);
					header("Location: /tags");
				}
				catch(DbException $dbe)
				{
					$errors['error'] = $dbe->getMyMessage();
					$this->view->render("error.php", $errors);
				}
			}
			else
			{
				header("Location: /tags");
			}
		}
	}