<?php
	/**
	* Ошибка 404
	*/
	class NotFoundExcetption extends Exception
	{
		public function __construct($message)
		{
			parent::__construct($message);
		}

		public function getMyMessage()
		{
			return $this->message;
		}
	}

	/**
	* Ошибка СУБД
	*/
	class DbException extends Exception
	{
		public function __construct($message)
		{
			parent::__construct($message);
		}

		public function getMyMessage()
		{
			return $this->message;
		}
	}