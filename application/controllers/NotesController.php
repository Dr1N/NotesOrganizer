<?php
	class NotesController extends Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->model = new NotesModel;
		}

		public function actionIndex()
		{
			$this->chechAuthentication();

			$data = array('isShared' => false);
			try 
			{
				$userId = intval($_SESSION['id']);
				$list = $this->model->getUserNotes($userId);
				$data['list'] = $list;
			} 
			catch (DbException $dbe) 
			{
				$data['error'] = $dbe->getMyMessage();
			}
			$this->view->render("notes.php", $data);	
		}

		public function actionShared()
		{
			$this->chechAuthentication();

			$data = array('isShared' => true);
			try 
			{
				$userId = intval($_SESSION['id']);
				$list = $this->model->getSharedNotes($userId);
				$data['list'] = $list;
			} 
			catch (DbException $dbe) 
			{
				$data['error'] = $dbe->getMyMessage();
			}
			$this->view->render("notes.php", $data);	
		}

		public function actionDetails()
		{
			//Проверки

			$this->chechAuthentication();

			$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
			if ($id === false) 
			{
				header("Location: /notes");
				return;
			}

			//Получаем данные заметки
			
			try 
			{
				$noteId = intval($id);
				$note = $this->model->getNoteById($noteId);
				if(!empty($note))
				{
					$isOwner = $note['owner_id'] == $_SESSION['id'];
					$this->view->render("notesdetails.php", array("note" => $note, 'isOwner' => $isOwner));
				}
				else
				{
					$errors['error'] = "Невозможно отобразить детальную информацию о заметке, возможно её не существует";
					$this->view->render("notesdetails.php", $errors);
				}
			} 
			catch (DbException $dbe) 
			{
				$errors['error'] = "Внутренняя ошибка. Повторите позже<br/>" . $dbe->getMyMessage();
				$this->view->render("notesdetails.php", $errors);	
			}	
		}

		public function actionAdd()
		{
			$this->chechAuthentication();
			
			try
			{
				//Ярлыки для формы

				$userId = intval($_SESSION['id']);
				$alltags = $this->model->getUserTags($userId);

				//Цвета для формы

				$colors = ColorsHelper::getColors($this->model->getConnection());
				
				//Данные для формы
				
				$data = array
				(
					'colors' => $colors, 
					'alltags' => $alltags
				);
				
				//Добавление

				if(isset($_POST) && !empty($_POST))
				{
					//Инициализация полей модели to-do (перенести в класс модели)

					$this->model->userId = $userId;
					$this->model->color = isset($_POST['color']) ? trim($_POST['color']) : 0;
					$this->model->title = isset($_POST['title']) ? trim($_POST['title']) : "";
					$this->model->note = isset($_POST['note']) ? trim($_POST['note']) : "";
					$this->model->isShared = isset($_POST['shared']) ? 1 : 0;
					$this->model->tags = isset($_POST['tags']) ? $_POST['tags'] : array();

					//Валидация модели
					
					$validationResult = $this->model->validate();
					if(!isset($validationResult['error']))
					{
						//Вставка модели в таблицу

						$this->model->insert();
						header("Location: /notes");
					}
					else
					{
						//Показ формы с ошибками в случае ошибки

						$this->view->render("notesform.php", array_merge($data, $validationResult));
					}
	 			}

	 			//Форма создания заметки
				
				else
				{
					$this->view->render("notesform.php", $data);
				}
			}
			catch(DbException $dbe)
			{
				$errors['error'] = "Внутренняя ошибка, попробуйте позже<br/>" . $dbe->getMyMessage();
				$this->view->render("notesform.php", $errors);
			}
		}

		public function actionEdit()
		{
			//Проверки

			$this->chechAuthentication();
			
			$noteId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
			if($noteId === false)
			{
				header("Location: /notes");
				return;
			}

			//Получаем заметку
			
			try
			{
				//Владелец заметки

				$userId = intval($_SESSION['id']);
				$note = $this->model->getNoteById($noteId);
				if($note['owner_id'] != $userId)
				{
					header("Location: /notes");
					return;
				}
				
				//Данные для select
				
				$alltags = $this->model->getUserTags($userId);
				$colors = ColorsHelper::getColors($this->model->getConnection());
			}
			catch(DbException $dbe)
			{
				$errors['error'] = "Внутренняя ошибка, попробуйте позже<br/>" . $dbe->getMyMessage();
				$this->view->render("notesform.php", $errors);
				return;
			}

			$data = array
			(
				'colors' => $colors,
				'alltags' => $alltags
			);

			//Сохранение заметки
			
			if(isset($_POST) && !empty($_POST))
			{
				//Проверка на владельца

				$notePostId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
				$ownerId = $this->model->getOwnerId($notePostId);
				if($notePostId == false || $ownerId != $userId || $noteId != $notePostId)
				{
					header("Location: /notes");
					return;
				}

				//Инициализация полей модели //to-do
				
				$this->model->id = $notePostId;
				$this->model->userId = $userId;
				$this->model->color = isset($_POST['color']) ? trim($_POST['color']) : 0;
				$this->model->title = isset($_POST['title']) ? trim($_POST['title']) : "";
				$this->model->note = isset($_POST['note']) ? trim($_POST['note']) : "";
				$this->model->isShared = isset($_POST['shared']) ? 1 : 0;
				$this->model->tags = isset($_POST['tags']) ? $_POST['tags'] : array();

				//Валидация
				
				$validationResult = $this->model->validate();
				if(!isset($validationResult['error']))
				{	
					//Сохранение заметки

					$this->model->edit();
					header("Location: /notes");
				}
				else
				{
					$this->view->render("notesform.php", array_merge($data, $validationResult));
				}
			}
			else
			{
				//Форма редактирования

				$noteData = array
				(
					'id' => intval($note['id']),
					'title' => $note['title'],
					'note' => $note['note'],
					'tags'=> $this->model->getTagsIdFromNote($note),
					'color' => $note['color_id'],
					'isShared' => $note['is_shared'] 
				);
				$this->view->render("notesform.php", array_merge($data, $noteData));
			}
		}

		public function actionDelete()
		{
			//Проверки

			$noteId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
			if($noteId === false)
			{
				header("Location: /notes");
				return;
			}
			try
			{
				$userId = intval($_SESSION['id']);
				$ownerId = $this->model->getOwnerId($noteId);
				if($userId == $ownerId)
				{
					$this->model->deleteById($noteId);
				}
				header("Location: /notes");
			}
			catch(DbException $dbe)
			{
				$errors['error'] = $dbe->getMyMessage();
				$this->view->render("error.php", $errors);
			}
		}
	}