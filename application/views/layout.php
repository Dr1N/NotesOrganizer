<?php
	$isAuthenticated = isset($_SESSION['isAuthenticated']) && $_SESSION['isAuthenticated'] == true;
	$user = isset($_SESSION['name']) ? $_SESSION['name'] : "";
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

	<title>Заметки</title>

    <link href="/vendor/bootstrap/css/bootstrap.css" rel="stylesheet" />
	<link href="/css/site.css" rel="stylesheet"  />

	<script src="/vendor/jquery/jquery-1.11.3.js" type="text/javascript"></script>
	<script src="/vendor/bootstrap/js/bootstrap.js" type="text/javascript"></script>
	<script src="/js/site.js" type="text/javascript"></script>
	
</head>
<body>
	<div class="container">

		<!-- Static navbar -->

		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
			            <span class="sr-only">Переключатель навигации</span>
			            <span class="icon-bar"></span>
			            <span class="icon-bar"></span>
			            <span class="icon-bar"></span>
	            	</button>
	            	<a class="navbar-brand" href="<?= quotemeta('/') ?>">Органайзер заметок</a>
				</div> <!-- /navbar-header -->
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li <?php if($_SERVER['REQUEST_URI'] == quotemeta('/')) echo "class='active'";?>><a href="<?= quotemeta('/') ?>">Задание</a></li>
						<?php if($isAuthenticated): ?>

							<!-- Заметки -->

							<li class="dropdown <?= (stripos($_SERVER['REQUEST_URI'], '/notes') !== false) ? " active" : "" ?>">
								<a href="tags" class="dropdown-toggle" data-toggle="dropdown">Заметки<b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="/notes">Мои заметки</a></li>
									<li><a href="/notes/shared">Доступные заметки</a></li>
									<li class="divider"></li>
								<li><a href="/notes/add">Добавить</a></li>
								</ul>
							</li>
							
							<!-- Ярлыки -->

							<li class="dropdown <?= (stripos($_SERVER['REQUEST_URI'], '/tags') !== false) ? " active" : "" ?>">
								<a href="tags" class="dropdown-toggle" data-toggle="dropdown">Ярлыки<b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="/tags">Список</a></li>
									<li class="divider"></li>
								<li><a href="/tags/add">Добавить</a></li>
								</ul>
							</li>
						<?php endif ?>
						<li <?php if(stripos($_SERVER['REQUEST_URI'], '/site/contacts') !== false) echo "class='active'";?>><a href="<?= '/site/contacts' ?>">Контакты</a></li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<?php if($isAuthenticated):?>
							<li><p class="navbar-text">Привет, <strong class="text-primary"><?= $user ?></strong>!</p></li>
							<li><a href="<?= '/users/logout' ?>" role="button" >Выход</a></li>
						<?php else: ?> 
							<li <?php if(stripos($_SERVER['REQUEST_URI'], '/users/login') !== false) echo "class='active'";?>><a href="<?= '/users/login' ?>">Вход</a></li>
			            	<li <?php if(stripos($_SERVER['REQUEST_URI'], '/users/registration') !== false) echo "class='active'";?>><a href="<?= '/users/registration' ?>">Регистрация</a></li>
			            <?php endif ?>
			    	</ul>
				</div> <!-- /navbar-collapse collapse -->
			</div> <!-- /container-fluid -->
		</nav>

		<!-- Content -->

		<div class="jumbotron">
			<?php require_once VIEWS_PATH . $content; ?>
		</div>

		<!-- Footer -->

		<footer class="footer">
			<p class="pull-right">
				Тестовое задание. Исполнитель:
				<a href="mailto:drn.exp@gmail.com">Андрей</a>
			</p>
			<p>© 2015 <a href="http://light-it.net/" target="_blank">LightIT</a></p>
		</footer>
	</div> <!-- /container -->
</body>
</html>