<?php
	/**
	* В случае ошибки с СУБД бросается DbException
	*/
	class TagsModel extends Model
	{
		public static $tableName = "tbl_tags";

		public $id;
		public $userId;
		public $name;
		public $image = null;	//to-do

		/**
		* Получить список всех ярлыков доступных пользователю
		* 
		* Системные и созданные пользователем
		*
		* @param int $uid - ИД пользователя
		* @return array - Массив данных ярлыков пользователя
		* @throws DbException - В случае ошибки СУБД
		*/
		public function getUserTags($uid)
		{
			$uid = $this->filterDataForSQL($uid);
			$query = "SELECT * FROM " . TagsModel::$tableName . " WHERE user_id=$uid OR user_id IS NULL";
			$result = $this->getData($query);

			return $result;
		}
		
		/**
		* Получить ID владельца ярлыка
		*
		* @return int - Ид пользователя создавшего ярлык, или null для системных, не существующих
		* или ярлыков с несколькими владельцами
		*/
		public function getUserIdById($id)
		{
			$id = $this->filterDataForSQL($id);
			$query = "SELECT user_id FROM " . TagsModel::$tableName . " WHERE id='$id'";
			$result = $this->getData($query);

			if(empty($result) || count($result) != 1) return null;

			return intval($result[0]['user_id']);
		}

		public function validate()
		{
			$result = array();
			$result['name'] = $this->name;

			//Длина названия
			
			$this->name = $this->filterTextData($this->name);
			if(3 > strlen($this->name) || strlen($this->name) > 64)
			{
				$result['nameError'] = "Некорректная длина названия (допустимая длина имени 3-64 символа)";
			}

			//Уникальность(для пользователя)

			$this->name = $this->filterDataForSQL($this->name);
			$query = "SELECT COUNT(*) as total 
					  FROM " . TagsModel::$tableName .  
					  " WHERE name='$this->name' AND (user_id=$this->userId OR user_id IS NULL)";
			$result = $this->getData($query);

			if($result[0]['total'] != 0)
			{
				$result['nameError'] = "Ярлык с таким названием уже существует";
			}

			if(isset($result['nameError']))
			{
				$result['error'] = "Во время добавления ярлыка возникли ошибки";
			}

			return $result;
		}

		public function insert()
		{
			$image = is_null($this->image) ? 'NULL' : $image;
			$query = "INSERT INTO " . TagsModel::$tableName . " (user_id, name, image) 
					  VALUES ($this->userId, '$this->name', $image)";
			$this->executeQuery($query);
		}

		public function deleteById($id)
		{
			$id = $this->filterDataForSQL($id);
			$query = "DELETE FROM " . TagsModel::$tableName . " WHERE id='$id'";
			$this->executeQuery($query);

			//Удаляем записи использующие ярлык

			//Ограничения в таблицах СУБД
		}
	}