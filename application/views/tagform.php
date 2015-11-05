<?php
	/**
	* @var $error - Описание ошибки в случае её возникновения
	* @var $name - Введённое имя пользователем
	* @var $nameError - Ошибка в имени
	* @var $fileError - Ошибка в изображении
	*/
	$name = isset($name) ? $name : "";
?>

<form method="POST" class="form-horizontal" role="form" enctype="multipart/form-data">

	<?php if(isset($error)): ?>
		<div class="form-group">
			<div class="alert alert-danger col-sm-4 col-sm-offset-5" role="alert"><?= $error ?></div>
		</div>
	<?php endif ?>
	
	<div class="form-group">
		<h2 class="col-sm-4 col-sm-offset-5" style="padding-left:12px">Создание ярлыка</h2>
	</div>

	<div class="form-group <?php if(isset($nameError)) echo "has-error";?>">
		<label for="name" class="col-sm-2 col-sm-offset-3 control-label">Название*</label>
		<div class="col-sm-4">
	  		<input name="name" type="text" class="form-control" id="name" placeholder="Title" value="<?= $name ?>" required autofocus>
		  	<?php if(isset($nameError)): ?>
	  			<span class="text-danger"><?= $nameError ?></span>
	  		<?php endif ?>
		</div>
	</div>

	<div class="form-group <?php if(isset($fileError)) echo "has-error";?>">
		<label for="image" class="col-sm-2  col-sm-offset-3 control-label" >Изображение</label>
		<div class="col-sm-4">
			<input name="image" type="file" id="image" disabled>
			<?php if(isset($fileError)): ?>
				<span class="text-danger"><?= $fileError ?></span>
			<?php endif ?>
		</div>
  	</div>

  <div class="form-group">
    <div class="col-sm-offset-5 col-sm-10">
      <button type="submit" class="btn btn-primary">Создать</button>
    </div>
  </div>

</form>