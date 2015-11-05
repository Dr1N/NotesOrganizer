<?php
	require_once 'application/config/web.php';
	require_once 'core/model.php';
	require_once 'core/view.php';
	require_once 'core/controller.php';
	require_once 'core/exceptions.php';
	
	/**
	* Класс приложения
	*
	* Основной класс, выполняющий маршрутизацию, загрузку классов и выполнение действия
	*/
	class App
	{
		static public function run()
		{
			$controllerName = 'Site';
			$actionName = 'Index';

			$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
			$routes = explode('/', $path);

			//Контролёр и действие

			if(!empty($routes[1]))
			{
				$controllerName = App::getName($routes[1]);
			}

			if(!empty($routes[2]))
			{
				$actionName = App::getName($routes[2]);
			}
						
			//Префиксы

			$modelName = $controllerName . 'Model';
			$controllerName = $controllerName. 'Controller';
			$actionName = 'action' . $actionName;

			//Имена подключаемых файлов
			
			$modelPath = MODELS_PATH . $modelName . '.php';
			if(file_exists($modelPath))
			{
				require_once($modelPath);
			}

			$controllerPath = CONTROLLERS_PATH . $controllerName . '.php';
			if(file_exists($controllerPath))
			{
				require_once($controllerPath);
			}

			//Перенаправление в случае если контролёр не сущеуствует
			
			else
			{
				throw new NotFoundExcetption("Файл контроллера [$controllerPath] не найден");
			}

			//Вызов действия контроллёра

			$controllerClass = new $controllerName;
			if(method_exists($controllerClass, $actionName))
			{
				$controllerClass->$actionName();
			}
			else
			{
				throw new NotFoundExcetption("Действие [$actionName] в контроллере [" . get_class($controllerClass) . "] не найдено");
			}
		}

		/**
		* Нормализация имени/названия действи/контроллёра
		*
		* Приводит строку к нижнему регистру и заменяет первый символ на заглавный
		* @param string $str Входящая строка
		* @return string Результат преоброзования
		*/
		static private function getName($str)
		{
			$str = strtolower($str);
			$firstChar = strtoupper(substr($str, 0, 1));
			return substr_replace($str, $firstChar, 0, 1);
		}
	}