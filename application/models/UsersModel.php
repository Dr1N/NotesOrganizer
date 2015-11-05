<?php
	class UsersModel extends Model 
	{
		public static $tableName = "tbl_users";

		public $email;
		public $password;
		public $confirm;
		public $name;

		/**
		* Проверка существования пользователя с указанными почтой и паролем
		*
		* В случае успеха аутентификация в системе
		*
		* @return array - Массив с ошибками (в случае удачной аутентификации пустой массив)
		* @throws DbException - В случае ошибки с СУБД
		*/
		public function login()
		{
			$errors = array();

			if(empty($this->email) || empty($this->password))
			{
				$errors['error'] = 'Введите почту и пароль';
				return $errors;
			}
			
			if(!filter_var($this->email, FILTER_VALIDATE_EMAIL))
			{
				$errors['error'] = 'Невадилный email';
				$errors['email'] = $this->email;
				return $errors;
			}
			
			$this->email = $this->filterDataForSQL($this->email);
			$this->password = $this->filterDataForSQL($this->password);

			$query = "SELECT * FROM " . UsersModel::$tableName . " WHERE mail='$this->email' AND password = '$this->password'";
			$result = $this->getData($query);
			if(count($result) == 1)
			{
				$this->setSession($result[0]['id'], $result[0]['name']);
			}
			else
			{
				$errors['error'] = 'Неверный email или пароль';
				$errors['email'] = $this->email;
			}
			return $errors;
		}

		public function logout()
		{
			session_destroy();
		}

		/**
		* Регистрация пользователя
		*
		* Запись текущей модели в базу данных
		* @throws DbException - В случае ошибки СУБД
		*/
		public function register()
		{
			$this->email = $this->filterDataForSQL($this->email);
			$this->password = $this->filterDataForSQL($this->password);
			$this->name = $this->filterDataForSQL($this->name);
			$query = "INSERT INTO " . UsersModel::$tableName. " (mail, password, name) VALUES ('$this->email', '$this->password', '$this->name')";
			$this->executeQuery($query);
		}

		/**
		* Валидация модели
		*
		* Валидирует все поля модели. 
		* @return array - Ассоциативный массив. Включает введённые пользователем данные и описание ошибок, если они были
		* @throws DbException - В случае ошибки СУБД
		*/
		public function validate()
		{
			$result = array();
			$result['email'] = $this->email;
			$result['name'] = $this->name;

			if(empty($this->email) || empty($this->password) || empty($this->confirm) || empty($this->name))
			{
				$result['error'] = "Заполните все поля";
				return $result;
			}

			if(!filter_var($this->email, FILTER_VALIDATE_EMAIL))
			{
				$result['emailError'] = "Невадилный email";
			}

			if(!$this->isUniqueMail($this->email))
			{
				$result['emailError'] = "Данный email уже используется";
			}

			if(strlen($this->password) < 6 || strlen($this->password) > 32)
			{
				$result['passwordError'] = "Некорректная длина пароля (допустимая длина пароля 6-32 символа)";
			}
			
			if($this->password !== $this->confirm)
			{			
				$result['confirmError'] = "Пароль и подтверждение не сопадают";
			}
			
			$this->name = $this->filterTextData($this->name);
			if(3 > strlen($this->name) || strlen($this->name) > 32)
			{	
				$result['nameError'] = "Некорректная длина имени (допустимая длина имени 3-32 символа)";
			}

			if( isset($result['emailError']) || 
				isset($result['passwordError']) || 
				isset($result['confirmError']) || 
				isset($result['nameError']))
			{
				$result['error'] = "Во время регистрации вознкли ошибки";	
			}

			return $result;
		}

		/**
		* Запись данных пользователя в сессию
		*
		* @param string $name - имя пользователя
		*/
		private function setSession($id, $name)
		{
			$_SESSION['isAuthenticated'] = true;
			$_SESSION['name'] = $name;
			$_SESSION['id'] = $id;
		}

		/**
		* Проверка почты на уникальность в базе данных
		*
		* @param string $mail - Проверямая почта
		* @return boolean - true, если почта уникальна
		* @throws DbException - В случае ошибки СУБД
		*/
		private function isUniqueMail($mail)
		{
			$mail = $this->filterDataForSQL($mail);
			$query = "SELECT COUNT(*) as total FROM " . UsersModel::$tableName . " WHERE mail='$mail'";
			$result = $this->getData($query);
			return $result[0]['total'] == 0;
		}
	}