<?php
	/**
	* @var $list - массив заметок
	* @var $error - описание ошибки, если она возникла
	*/
?>

<?php if(isset($error)): ?>
	<div class="alert alert-danger" role="alert">Внутренняя ошибка, попробуйте позже<br><?= $error?></div>
	<?php exit() ?>
<?php endif ?>

<h3>ЯРЛЫКИ</h3>
<a class="btn btn-primary btn-xs" href="/tags/add" role="button" title="Добавить ярлык">Добавить</a>

<?php if(empty($list)) :?>
	<div class="alert alert-info" role="alert">Ярлыки отсутвуют</div>
	<?php exit(); ?>
<?php endif ?>
<table class="table">
	<thead>
		<tr>
			<th>№</th>
			<th>Название</th>
			<th>Изображение</th>
			<th>Действия</th>
		</tr>
	</thead>
	<tbody>
		<?php for($i = 0; $i < count($list); $i++): ?>
			<tr <?php if($list[$i]['user_id'] == null) echo "class='success'"; ?>>
				<td><?= ($i + 1) ?></td>
				<td><?= $list[$i]['name'] ?></td>
				<td>
					<?php if($list[$i]['image'] == null): ?>
						<?= "Нет изображения" ?>
					<?php else: ?>
						<img src="<?= TAGS_IMAGE_PATH . $list[$i]['image'] ?>" alt="Ярлык" class="img-responsive">
					<?php endif ?>
				</td>
				<td>
					<?php if($list[$i]['user_id'] == null): ?>
						<span class="bg-warning">Системный ярлык</span>
					<?php else: ?>
						<a href="/tags/delete?id=<?= $list[$i]['id'] ?>" title="Удалить" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></a>
					<?php endif ?>
				</td>
			</tr>
		<?php endfor ?>
	</tbody>
</table>