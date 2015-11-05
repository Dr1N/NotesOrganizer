<?php
	/**
	* @var string $error - Описание ошибки
	* @var string $email - Введенный пользователем Email
	* @var string $name - Введенное пользователем имя
	* @var string $emailError - Ошибка почты
	* @var string $passwordError - Ошибка пороля
	* @var string $confirmError - Ошибка подтверждения
	* @var string $nameError - Ошибка имени
	*/ 
	
	$email = isset($email) ? $email : "";
	$name = isset($name) ? $name : "";
?>

<form method="POST" class="form-horizontal" role="form">

	<?php if(isset($error)): ?>
		<div class="form-group">
			<div class="alert alert-danger col-sm-4 col-sm-offset-5" role="alert"><?= $error ?></div>
		</div>
	<?php endif ?>
	
	<div class="form-group">
		<h2 class="col-sm-4 col-sm-offset-5" style="padding-left:12px">Регистрация</h2>
	</div>

	<div class="form-group <?php if(isset($emailError)) echo "has-error";?>">
		<label for="email" class="col-sm-2 col-sm-offset-3 control-label">Email*</label>
		<div class="col-sm-4">
			<div class="input-group">
		  		<input name="email" type="email" class="form-control" id="email" placeholder="Email" value="<?= $email ?>" required autofocus>
		  		<span class="input-group-addon">@</span>
	  		</div>
	  		<?php if(isset($emailError)): ?>
	  			<span class="text-danger"><?= $emailError ?></span>
	  		<?php endif ?>
		</div>
	</div>

  <div class="form-group <?php if(isset($passwordError)) echo "has-error";?>">
    <label for="password" class="col-sm-2 col-sm-offset-3 control-label">Пароль*</label>
    <div class="col-sm-4">
		<input name="password" type="password" class="form-control" id="password" placeholder="Password" required>
		<?php if(isset($passwordError)): ?>
  			<span class="text-danger"><?= $passwordError ?></span>
  		<?php endif ?>
    </div>
  </div>

  <div class="form-group <?php if(isset($confirmError)) echo "has-error";?>">
    <label for="confirm" class="col-sm-2 col-sm-offset-3 control-label">Подтвеждение*</label>
    <div class="col-sm-4">
		<input name="confirm" type="password" class="form-control" id="confirm" placeholder="Password" required>
		<?php if(isset($confirmError)): ?>
  			<span class="text-danger"><?= $confirmError ?></span>
  		<?php endif ?>
    </div>
  </div>

  <div class="form-group <?php if(isset($nameError)) echo "has-error";?>">
    <label for="name" class="col-sm-2  col-sm-offset-3 control-label">Ваше имя*</label>
    <div class="col-sm-4">
		<input name="name" type="text" class="form-control" id="name" placeholder="Name" value="<?= $name?>" required>
		<?php if(isset($nameError)): ?>
  			<span class="text-danger"><?= $nameError ?></span>
  		<?php endif ?>
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-5 col-sm-10">
      <button type="submit" class="btn btn-primary">Регистрация</button>
    </div>
  </div>

</form>