<?php 
	/**
	* Базовый класс модели
	*
	* Описывает сущность - ТАБЛИЦА
	*/
	abstract class Model
	{
		protected $_connection = null;
		
		public function __construct()
		{
			$this->connectDb();
		}

		public function __destruct()
		{
			$this->disconnectDb();
		}
		
		/**
		* Устанвливает соединение с базой данной с параметрами указанными в конфигурации
		* @throws DbException в случае ошибки
		*/
		public function connectDb()
		{
			$this->_connection = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
			if (mysqli_connect_error())
			{
				throw new DbException("Не удалось соединиться с СУБД. Error № " 
					. mysqli_connect_errno()
					. " [ " . mysqli_connect_error() . "]" );
			}
			if(!mysqli_set_charset($this->_connection, "utf8"))
			{
				throw new DbException("Не удалось установить кодировку. Error № " 
					. mysqli_connect_errno()
					. " [ " . mysqli_connect_error() . "]" );
			}
		}

		public function disconnectDb()
		{
			if($this->_connection != false && $this->_connection != null)
			{
				mysqli_close($this->_connection);
			}
		}

		/**
		* Возвращает результат запроса в виде ассоциативного массива
		*
		* @param string - SQL запрос (SELECT)
		* @return array - Массив с результатом
		* @throws DbException - В случае ошибки СУБД
		*/
		public function getData($query)
		{
			$result = mysqli_query($this->_connection, $query);
			if($result == false)
			{
				throw new DbException("Ошибка при выполнении запроса. Error № " 
					. mysqli_connect_errno()
					. " [ " . mysqli_connect_error() . "] </n>Запрос: $query" );
			}

			//Формирование результата

			$cnt = mysqli_num_rows($result);
			$data = array();
			for($i = 0; $i < $cnt; $i++)
			{
				$row = mysqli_fetch_assoc($result);
				$data[] = $row;
			}

			//Особождение ресурсов
			
			mysqli_free_result($result);
			
			return $data;
		}

		/**
		* Выполняет запрос к базе данных (INSERT, UPDATE, DELETE и т.п)
		*
		* @param string - SQL запрос (INSERT, UPDATE, DELETE и т.п)
		* @throws DbException - В случае ошибки
		*/
		public function executeQuery($query)
		{
			if(mysqli_query($this->_connection, $query) == false)
			{
				throw new DbException("Ошибка при выполнении запроса. Error № " 
					. mysqli_connect_errno()
					. " [ " . mysqli_connect_error() . "] </n>Запрос: $query" );
			}
		}

		/**
		* Фильтр строк для SQL запроса
		*
		* @param string $data - Строка для фильтрации
		* @return string - Отфильтрованная строка
		*/
		protected function filterDataForSQL($data)
		{
			return mysqli_real_escape_string($this->_connection, $data);
		}

		/**
		* Фильтрует текст для добавления в базу
		*
		* Удаляет HTML теги, экранрует XML сущности
		*
		* @param string - Строка для фильтрации
		* @return string - Отфильтрованый текст
		*/
		protected function filterTextData($data)
		{
			$data = strip_tags($data);
			return htmlspecialchars($data);
		}
	}