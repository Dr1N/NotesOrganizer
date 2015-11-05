<?php
	/**
	* Класс вида
	*/
	class View
	{
		private $_template;

		public function __construct()
		{
			$this->_template = DEFAULT_TEMPLATE;
		}

		/**
		* Установка шаблона для вида
		*
		* В случае отсутвия шаблона используется шаблон по умолчанию
		* @param string $template - название шаблона
		* @return $this - ссылка на объект вида
		*/
		public function setTemplate($template)
		{
			if(file_exists(VIEWS_PATH . $template))
			{
				$this->_template = VIEWS_PATH . $template;
			}
			return $this;
		}

		/**
		* Рендеринг вида
		*
		* @param string $content - Название частичного вида
		* @param string $template - Название шаблона
		* @param array $data - Данные, передающиеся в вид
		*/
		public function render($content, $data = null)
		{
			if(!file_exists(VIEWS_PATH . $content))
			{
				throw new NotFoundExcetption("Файл вида не найден");
			}
			if(is_array($data)) 
			{
				extract($data);
			}
			require_once $this->_template;
		}
	}