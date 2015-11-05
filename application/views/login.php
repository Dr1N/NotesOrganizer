<?php
	/**
	* @var string $error - Описание ошибки
	* @var string $email - Введенный пользователем Email
	*/ 
	$email = isset($email) ? $email : "";
?>

<form class="form-signin" method="POST">

	<?php if(isset($error)): ?>
		<div class="alert alert-danger" role="alert"><?= $error ?></div>
	<?php endif ?>

	<h2 class="form-signin-heading">Вход</h2>

	<label for="email" class="sr-only">Email</label>
	<input name="email" type="email" id="email" class="form-control" placeholder="Email" required autofocus value="<?= $email ?>">

	<label for="password" class="sr-only">Password</label>
	<input name="password" type="password" id="password" class="form-control" placeholder="Password" required>
	
	<button class="btn btn-lg btn-primary btn-block" type="submit">Войти</button>
</form>