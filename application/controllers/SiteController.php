<?php
	class SiteController extends Controller
	{
		public function __construct()
		{
			parent::__construct();
		}

		public function actionIndex()
		{
			$this->view->render("home.php");
		}

		public function actionContacts()
		{
			$this->view->render("contacts.php");
		}

		public function actionNotfound()
		{
			$this->view->render("notfound.php");
		}
	}