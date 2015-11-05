<?php
	class NotesModel extends Model
	{

		public static $tableName = "tbl_notes";
		public static $tableNoteTagsName = "tbl_note_tags";

		//Поля замекти

		public $id;
		public $userId;
		public $color;
		public $title;
		public $note;
		public $isShared;

		//Массив с данными о ярлыках заметки

		public $tags;

		public function __construct()
		{
			parent::__construct();

			//Дополнительные классы моделей
			
			require_once 'ColorsHelper.php';
			require_once 'UsersModel.php';
			require_once 'TagsModel.php';
		}

		/**
		* Получить массив данных о всех заметках пользователя
		* 
		* @param int $uid - ИД пользователя
		* @return array - Массив данных заметок пользователя
		* @throws DbException - В случае ошибки СУБД
		*/
		public function getUserNotes($uid)
		{
			//Заметки пользователя

			$uid = $this->filterDataForSQL($uid);
			$query = sprintf
			(
				"SELECT %s.id, %s.value as color, title, note, is_shared, datetime
				 FROM %s INNER JOIN %s ON %s.color_id=%s.id
				 WHERE user_id=%d
				 ORDER BY datetime DESC", 
				 NotesModel::$tableName,
				 ColorsHelper::$tableName,
				 NotesModel::$tableName, 
				 ColorsHelper::$tableName, 
				 NotesModel::$tableName, 
				 ColorsHelper::$tableName,
				 $uid
			);

			$result = $this->getData($query);

			//Получить ярлыки для каждой заметки

			$this->addTagsToNotes($result);

			return $result;
		}

		/**
		* Получить список общедоступных записок
		*
		* @param int $uid - ИД пользователя, чьи заметки надо исключить (текущий пользователь)
		* @return array - Массив общедоступных записок исключая заметки пользователя
		* @throws DbException - В случае ошибки
		*/
		public function getSharedNotes($uid)
		{
			$uid = $this->filterDataForSQL($uid);
			$query = sprintf
			(
				"SELECT %s.id, %s.name as owner, %s.mail as owner_mail, %s.value as color, title, note, datetime 
				 FROM (%s INNER JOIN %s ON %s.color_id=%s.id) INNER JOIN %s ON %s.user_id=%s.id
				 WHERE is_shared=1 AND user_id <> %d
				 ORDER BY datetime DESC", 
				 NotesModel::$tableName,
				 UsersModel::$tableName,
				 UsersModel::$tableName,
				 ColorsHelper::$tableName,
				 NotesModel::$tableName, 
				 ColorsHelper::$tableName, 
				 NotesModel::$tableName, 
				 ColorsHelper::$tableName,
				 UsersModel::$tableName,
				 NotesModel::$tableName,
				 UsersModel::$tableName,
				 intval($uid)
			);

			$result = $this->getData($query);

			//Получить ярлыки для заметки

			$this->addTagsToNotes($result);
			
			return $result;
		}

		/**
		* Получить заметку по ID
		*
		* @return array - массив данных о заметке
		* @throws DbException - В случае ошибки СУБД
		*/
		public function getNoteById($id)
		{
			$id = $this->filterDataForSQL($id);
			$query = sprintf
			(
				"SELECT %s.id, %s.id as owner_id, %s.name as owner, %s.mail as owner_mail, %s.id as color_id, %s.value as color, title, note, is_shared, datetime
				 FROM (%s INNER JOIN %s ON %s.color_id=%s.id) INNER JOIN %s ON %s.user_id=%s.id
				 WHERE %s.id=%d", 
				 NotesModel::$tableName,
				 UsersModel::$tableName,
				 UsersModel::$tableName,
				 UsersModel::$tableName,
				 ColorsHelper::$tableName,
				 ColorsHelper::$tableName,
				 NotesModel::$tableName, 
				 ColorsHelper::$tableName, 
				 NotesModel::$tableName, 
				 ColorsHelper::$tableName,
				 UsersModel::$tableName,
				 NotesModel::$tableName,
				 UsersModel::$tableName,
				 NotesModel::$tableName,
				 intval($id)
			);

			$result = $this->getData($query);
			if(!empty($result) && count($result) == 1)
			{
				$this->addTagsToNotes($result);
				return $result[0];
			}
			return array();
		}

		/**
		* Получить ИД владельца замекти
		*
		* @param int - ИД заметки
		* @return int - ИД владельца
		* @throws DbException в случае ошибки СУБД
		*/
		public function getOwnerId($id)
		{
			$query = "SELECT user_id FROM " . NotesModel::$tableName . " WHERE id=$id";
			$result = $this->getData($query);
			return intval($result[0]['user_id']);
		}

		/**
		* Получить список ярлыков доступных пользователю (Системные и личные)
		*
		* Метод повторяется, что-бы избежать создания нового соединения с СУБД to-do
		*/
		public function getUserTags($uid)
		{
			$uid = $this->filterDataForSQL($uid);
			$query = "SELECT id, name, image FROM " . TagsModel::$tableName . " WHERE user_id=$uid OR user_id IS NULL";
			$tags = $this->getData($query);

			return $tags;
		}

		/**
		* Валидация полей заметки
		*
		* @return array - Результат валидации(текущие поля и описание ошибок, если они есть)
		* @throws DbException - В случае ошибки СУБД
		*/
		public function validate()
		{
			//Данные для восстановления формы в случае ошибки

			$result = array();

			if(isset($this->id))
			{
				$result['id'] = $this->id;
			}
			$result['title'] = $this->title;
			$result['note'] = $this->note;
			$result['color'] = $this->color;
			$result['isShared'] = $this->isShared;
			$result['tags'] = $this->tags;

			//Валидация

			$this->title = $this->filterTextData($this->title);
			if(3 > strlen($this->title) || strlen($this->title) > 64)
			{
				$result['titleError'] = "Некорректная длина названия (допустимая длина имени 1-64 символа)";
			}

			//$this->note = $this->filterTextData($this->note); //to-do (TinyMCE)
			if(strlen($this->note) > 2048)
			{
				$result['noteError'] = "Некорректная длина заметки (максимальная длина 1024 символа)";
			}

			if(filter_var($this->color, FILTER_VALIDATE_INT) === false)
			{
				$result['colorError'] = "Некорректное значение цвета";
			}

			if(filter_var($this->isShared, FILTER_VALIDATE_INT) === false)
			{
				$result['sharedError'] = "Некорректное значение атрибута доступности";
			}

			if(isset($result['titleError']) || isset($result['noteError']) || isset($result['colorError']) || isset($result['sharedError']))
			{
				$result['error'] = "Во время добавленя заметки возникли ошибки";
			}
			
			return $result;
		}

		/**
		* Добавляет заметку в таблицу
		*
		* @throws DbException - В случае ошибки СУБД
		*/
		public function insert()
		{
			$query = sprintf
			(
				"INSERT INTO %s (user_id, color_id, title, note, is_shared, datetime) VALUES(%d, %d, '%s', '%s', %d, %d)",
				NotesModel::$tableName,
				$this->userId,
				intval($this->color),
				$this->filterDataForSQL($this->title),
				$this->filterDataForSQL($this->note),
				intval($this->isShared),
				time()
			);

			$this->executeQuery($query);

			//ID вставленной заметки

			$noteId = mysqli_insert_id($this->_connection);

			//Сохраняем ярлыки, если они есть

			if(empty($this->tags)) 
			{
				return;
			}

			foreach ($this->tags as $tag) 
			{
				$tagId = filter_var($tag, FILTER_VALIDATE_INT);
				if($tagId === false) continue;
				$query = sprintf
				(
					"INSERT INTO %s (note_id, tag_id) VALUES (%d, %d)",
					NotesModel::$tableNoteTagsName,
					intval($noteId),
					intval($tagId) 
				);
				$this->executeQuery($query);
			}
		}

		/**
		* Изменяет заметку в таблице
		*
		* @throws DbException - В случае ошибки СУБД
		*/
		public function edit()
		{
			//Сохранение заметки

			$query = sprintf
			(
				"UPDATE %s 
				SET color_id=%d, title='%s', note='%s', is_shared=%d
				WHERE id=%d",
				NotesModel::$tableName,
				intval($this->color),
				$this->filterDataForSQL($this->title),
				$this->filterDataForSQL($this->note),
				intval($this->isShared),
				intval($this->id)
			);

			$this->executeQuery($query);
			
			//Сохранение ярлыков заметки

			//Очистка старых

			$query = "DELETE FROM " . NotesModel::$tableNoteTagsName . " WHERE note_id=$this->id";
			$this->executeQuery($query);

			//Запись новых

			if(empty($this->tags)) 
			{
				return;
			}

			foreach ($this->tags as $tag) 
			{
				$tagId = filter_var($tag, FILTER_VALIDATE_INT);
				if($tagId === false) continue;
				$query = sprintf
				(
					"INSERT INTO %s (note_id, tag_id) VALUES (%d, %d)",
					NotesModel::$tableNoteTagsName,
					intval($this->id),
					intval($tagId) 
				);
				$this->executeQuery($query);
			}
		}

		/**
		* Удаляет заметку по ID
		*
		* @throws DbException - В случае ошибки в СУБД
		*/
		public function deleteById($id)
		{	
			//Удаляем заметку

			$id = $this->filterDataForSQL($id);
			$query = "DELETE FROM " . NotesModel::$tableName . " WHERE id=$id";
			$this->executeQuery($query);

			//Удаляем записи в таблице ярлыков относящиеся к заметке

			/*Ограничения в таблицах СУБД*/
		}

		/* ---- ХЕЛПЕРЫ ---- */

		/**
		* Вернуть ресурс соединения
		*
		* Используется для классов хелперов, не наследников модели, чтобы избежать создания ещё одного соединения к СУБД
		*/
		public function getConnection()
		{
			return $this->_connection;
		}

		/**
		* Получить массив ID ярлыков из записки
		*
		* Аналог array_column из PHP >= 5.5
		*/
		public function getTagsIdFromNote($note)
		{
			$tagsId = array();
			if(!empty($note['tags']))
			{
				foreach ($note['tags'] as $tag) 
				{
					$tagsId[] = $tag['id'];
				}
			}
			return $tagsId;
		}

		/**
		* Добавляет в массив заметок поле(массив) c информацией о ярлыках для каждой заметки
		*
		* Массив заметок передаётся по ссылке и модифицируется
		* 
		* @param array $result - массив данных заметок
		* @throws DbException - В случае ошибки
		*/
		private function addTagsToNotes(&$result)
		{
			$cnt = count($result);
			for($i = 0; $i < $cnt; $i++)
			{
				$query = sprintf
				(
					"SELECT %s.id, %s.name, %s.image
					FROM %s INNER JOIN %s ON %s.tag_id=%s.id
					WHERE note_id=" . $result[$i]['id'],
					TagsModel::$tableName,
					TagsModel::$tableName,
					TagsModel::$tableName,
					NotesModel::$tableNoteTagsName,
					TagsModel::$tableName,
					NotesModel::$tableNoteTagsName,
					TagsModel::$tableName
				);
				$result[$i]['tags'] = $this->getData($query);
			}
		}
	}