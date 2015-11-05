<?php 
	/**
	* @var string $note - массив данных о заметке
	* @var string $error - описание ошибки, если она возникла
	* @var bool $isOwner - является ли пользователем владельцем заметки
	*/
?>

<?php if(isset($error)) :?>
	<div class="alert alert-danger" role="alert">Во время операции произошла ошибка.<br/><?= $error ?></div>
	<?php exit(); ?>
<?php endif ?>

<div class="row">
	<h3 class="col-sm-8 col-sm-offset-2">Детали заметки</h3>
</div>

<div class="row">
	<div class="panel panel-default col-sm-8 col-sm-offset-2" style="padding-bottom:10px; margin-top:5px">
	  <?php $style= ($note['color'] != null) ? "style='background:#" . $note['color'] . "'" : ""; ?>
	  <div class="panel-heading" <?= $style ?>>
	  	<strong><?= $note['title'] ?></strong>
	  	<span class="pull-right">
	  		<a href="/notes" class="btn btn-primary btn-xs" title="Назад" role="button"><span class="glyphicon glyphicon-circle-arrow-left"></a>
		  	<?php if($isOwner): ?>
				<a href="/notes/edit?id=<?= $note['id']?>" title="Редактировать" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-edit"></a>
				<a href="/notes/delete?id=<?= $note['id']?>" title="Удалить" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></a>
			<?php endif ?>
		</span>
	  </div>
	  <div class="panel-body"><?= $note['note'] ?></div>
	  <ul class="list-group">
		  <li class="list-group-item list-group-item-warning">
	  		<?php $tags = $note['tags']; ?>
			<?php if(!empty($tags)): ?>
				<?php for($j = 0; $j < count($tags); $j++): ?>
					<span class="label label-default"><?= $tags[$j]['name'] ?></span>
				<?php endfor ?>
			<?php else: ?>
				<span class="label label-info">Ярлыки отсутсвуют</span>
			<?php endif ?>
		  </li>
		  <li class="list-group-item list-group-item-info">
				<strong>Дата создания:</strong>
				<span class="pull-right label label-warning"><?= date('d.m.Y H:i:s', $note['datetime']) ?></span>
		  </li>
		  <li class="list-group-item list-group-item-info">
		 		<strong>Владелец:</strong>
		 		<a href="mailto:<?= $note['owner_mail']?>" class="pull-right label label-warning"><?= $note['owner'] ?></a>
		  </li>
		  <li class="list-group-item list-group-item-info">
		  		<strong>Общедоступная:</strong>
		  		<span class="pull-right label label-warning">
		  			<?= ($note['is_shared'] != 0) ? "Да": "Нет" ?>
		  		</span>
		  </li>
	  </ul>
	</div>
</div>