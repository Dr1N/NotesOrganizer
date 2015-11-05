<?php
	/**
	* @var array $colors - Доступные цвета
	* @var array $alltags - Доступные ярлыки
	* @var string $error - Описание ошибки в случае её возникновения
	*
	* @var int $id - Ид редактируемой заметки
	* @var string $title - Название
	* @var string $note - Заметка
	* @var array $tags - Выбранные ярлыки
	* @var int $color - ID выбранного цвета
	* @var int isShared - Общедоступная заметка
	*/

	$title = isset($title) ? $title : "";
	$note = isset($note) ? $note : "";
?>

<script type="text/javascript" src="/vendor/tinymce/tinymce.min.js"></script>

<form method="POST" class="form-horizontal" role="form" enctype="multipart/form-data">
	
	<!-- Ид редактируемой заметки -->

	<?php if(isset($id)): ?>
		<input type="hidden" name="id" value="<?= $id ?>">
	<?php endif ?>

	<div class="form-group">
		<h2 class="col-sm-5 col-sm-offset-4" style="padding-left:12px"><?= isset($id) ? "Редактироване " : "Создание " ?>заметки</h2>
	</div>

	<?php if(isset($error)): ?>
		<div class="form-group">
			<div class="alert alert-danger col-sm-4 col-sm-offset-4" role="alert"><?= $error ?></div>
		</div>
	<?php endif ?>

	<!-- Заголовок -->

	<div class="form-group <?php if(isset($titleError)) echo "has-error";?>">
		<label for="title" class="col-sm-2 col-sm-offset-2 control-label">Название*</label>
		<div class="col-sm-6">
	  		<input name="title" type="text" class="form-control" id="title" placeholder="Title" value="<?= $title ?>" required autofocus>
		  	<?php if(isset($titleError)): ?>
	  			<span class="text-danger"><?= $titleError ?></span>
	  		<?php endif ?>
		</div>
	</div>

	<!-- Текст -->
	
	<div class="form-group <?php if(isset($noteError)) echo "has-error";?>">
		<label for="note" class="col-sm-2 col-sm-offset-2 control-label">Заметка</label>
		<div class="col-sm-6">
			<textarea name="note" id="note" class="form-control" rows="5" placeholder="Note"><?= $note ?></textarea>
	  		<?php if(isset($noteError)): ?>
	  			<span class="text-danger"><?= $noteError ?></span>
	  		<?php endif ?>
		</div>
	</div>

	<!-- Цвет -->

	<div class="form-group <?php if(isset($colorError)) echo "has-error";?>">
		<label for="color" class="col-sm-2 col-sm-offset-2 control-label">Цвет</label>
		<div class="col-sm-6">
			<select name="color" id="color" class="form-control" style="font-weight:bold;" onchange="changeBackround(event)">
				<?php foreach ($colors as $clr): ?>
					<?php 
						
						// Цвет фона
						
						$style = ($clr['value'] != null) ? "style='background:#" . $clr['value'] . "'" : "";
						
						//Выбранный цвет

						$selected = (isset($color) && $clr['id'] == $color) ? "selected" : "";
					?>
					<option value="<?= $clr['id']?>" <?= $style ?> <?= $selected ?>><?= $clr['name'] ?></option>
				<?php endforeach ?>
			</select>
	  		<?php if(isset($colorError)): ?>
	  			<span class="text-danger"><?= $colorError ?></span>
	  		<?php endif ?>
		</div>
	</div>

	<!-- Ярлыки -->

	<div class="form-group <?php if(isset($tagsError)) echo "has-error";?>">
		<label for="tags" class="col-sm-2 col-sm-offset-2 control-label">Ярлыки</label>
		<div class="col-sm-6">
			<?php foreach ($alltags as $tag): ?>
				<label class="checkbox-inline" for="check<?= $tag['id'] ?>">
					<?php
						//Выбран ли ярлык

						$checked = (isset($tags) && in_array($tag['id'], $tags)) ? "checked" : "";
					?>
					<input name="tags[]" type="checkbox" id="check<?= $tag['id'] ?>" value="<?= $tag['id'] ?>" <?= $checked ?>>
					<span class="label label-default"><?= $tag['name'] ?></span> 
				</label>
			<?php endforeach ?>
	  		<?php if(isset($tagsError)): ?>
	  			<span class="text-danger"><?= $tagsError ?></span>
	  		<?php endif ?>
		</div>
	</div>

	<!-- Доступность -->

	<div class="form-group <?php if(isset($sharedError)) echo "has-error";?>">
		<label class="col-sm-2 col-sm-offset-2 control-label">Доступ</label>
		<div class="col-sm-6">
			<label class="checkbox-inline" for="shared">
				<input name="shared" type="checkbox" id="shared" <?= (isset($isShared) && $isShared == 1) ? "checked" : "" ?>>
				<span class="label label-warning">Общедоступная заметка</span> 
			</label><br/>
			<?php if(isset($sharedError)): ?>
  				<span class="text-danger"><?= $sharedError ?></span>
  			<?php endif ?>
		</div>
	</div>

	<!-- Файл -->

	<div class="form-group <?php if(isset($fileError)) echo "has-error";?>">
		<label for="file" class="col-sm-2  col-sm-offset-2 control-label">Прикреплённый файл</label>
		<div class="col-sm-6">
			<input name="file" type="file" id="file" disabled>
			<?php if(isset($fileError)): ?>
				<span class="text-danger"><?= $fileError ?></span>
			<?php endif ?>
		</div>
  	</div>

  <div class="form-group">
    <div class="col-sm-offset-4 col-sm-10">
      <button type="submit" class="btn btn-primary"><?= isset($id) ? "Сохранить " : "Создать " ?></button>
    </div>
  </div>
</form>

<script type="text/javascript">
	tinymce.init({
	    selector: "#note",
	    mode: "textareas",
		plugins: "table, preview, textcolor",
		language: "ru"
	});
</script>