<?php 
	/**
	* Вспомогательная класс для работы с таблицей цветов
	*/
	class ColorsHelper
	{
		static public $tableName = "tbl_colors";

		/**
		* Получить данные о цветах в системе
		*
		* to-do (Повторяющеймя код, из-за неоптимального класса модели)
		*/
		static public function getColors($connection = null)
		{
			if($connection == null || $connection == false)
			{
				throw new DbException("Ошибка при получении цветов. Невалидный ресурс соединения");
			}
			$query = "SELECT * FROM " . ColorsHelper::$tableName;
			$result = mysqli_query($connection, $query);
			if($result == false)
			{
				throw new DbException("Ошибка при выполнении запроса. Error № " 
					. mysqli_connect_errno()
					. " [ " . mysqli_connect_error() . "] </n>Запрос: $query" );
			}

			//Формирование результата

			$cnt = mysqli_num_rows($result);
			$colors = array();
			for($i = 0; $i < $cnt; $i++)
			{
				$row = mysqli_fetch_assoc($result);
				$colors[] = $row;
			}

			//Особождение ресурсов
			
			mysqli_free_result($result);

			return $colors;
		}
	}