<?php
	/** 
	* @var array $list - массив данных заметок
	* @var bool $isShared - вывод списока общедоступных заметок
	* @var string $error - описание ошибки, если она возникла
	*/
?>

<?php if(isset($error)): ?>
	<div class="alert alert-danger" role="alert">Внутренняя ошибка, попробуйте позже<br/><?= $error ?></div>
	<?php exit(); ?>
<?php endif ?>

<!-- Заголовок -->

<h3><?= (!$isShared) ? "МОИ ЗАМЕТКИ" : "ДОСТУПНЫЕ МНЕ ЗАМЕТКИ" ?></h3>
<?php if(!$isShared): ?>
	<a class="btn btn-primary btn-xs" href="/notes/add" role="button" title="Добавить заметку">Добавить</a>
<?php endif ?>

<!-- Контент -->

<?php if(empty($list)) :?>
	<h3 class="alert alert-info" role="alert"><?= (!$isShared) ? "У вас нет заметок" : "Нет доступных заметок" ?></h3>
	<?php exit(); ?>
<?php endif ?>

<!-- #################################################################### -->

<div class="row" style="padding-top: 5px">
<?php foreach ($list as $note): ?>
	<?php $style= ($note['color'] != null) ? "style='background:#" . $note['color'] . "'" : ""; ?>
	<div class="panel panel-default col-sm-4">
		<div class="panel-heading" <?= $style ?>>
			<h3 class="panel-title">
				<a href="/notes/details?id=<?= $note['id']?>" title="Детально">
					<?= (strlen($note['title']) <= MAX_LENGHT_NOTE_TITLE) ? $note['title'] : mb_substr($note['title'], 0, MAX_LENGHT_NOTE_TITLE, 'UTF-8') . "..." ?>
				</a>
				<span class="pull-right">
					<a href="/notes/details?id=<?= $note['id']?>" title="Детали" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-eye-open"></span></a>
			  		<?php if(!$isShared): ?>
						<a href="/notes/edit?id=<?= $note['id']?>" title="Редактировать" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-edit"></a>
						<a href="/notes/delete?id=<?= $note['id']?>" title="Удалить" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></a>
					<?php endif ?>
				</span>
			</h3>
		</div>
		<div class="panel-body">
			<div style="min-height:40px;">
				<?= (strlen($note['note']) <= MAX_LENGHT_NOTE_TEXT) ? 
					strip_tags($note['note']) : 
					mb_substr(strip_tags($note['note']), 0, MAX_LENGHT_NOTE_TEXT, 'UTF-8') . "..."; ?>
				</div>
			<ul class="list-group" style="padding-top: 10px">

				<!-- Ярлыки-->

				<li class="list-group-item list-group-item-warning">
					<div style="overflow:hidden; height:20px;">
					<?php $tags = $note['tags']; ?>
					<?php if(!empty($tags)): ?>
						<?php foreach($tags as $tag): ?>
							<span class="label label-default"><?= $tag['name'] ?></span>
						<?php endforeach ?>
					<?php else: ?>
						<span class="label label-info">Ярлыки отсутсвуют</span>
					<?php endif ?>
					</div>
				</li>
				
				<!-- Дата -->
				
				<li class="list-group-item list-group-item-info">
					<strong>Дата создания:</strong>
					<span class="pull-right label label-warning"><?= date('d.m.Y H:i:s', $note['datetime']) ?></span>
				</li>
				
				<!-- Владелец -->

				<?php if($isShared): ?>
					<li class="list-group-item list-group-item-info">
				 		<strong>Владелец:</strong>
				 		<a href="mailto:<?= $note['owner_mail']?>" class="pull-right label label-warning"><?= $note['owner'] ?></a>
					</li>
				<?php endif ?>

				<!-- Доступ -->

				<?php if(!$isShared): ?>
					<li class="list-group-item list-group-item-info">
				  		<strong>Общедоступная:</strong>
				  		<span class="pull-right label label-warning">
				  			<?= ($note['is_shared'] != 0) ? "Да": "Нет" ?>
				  		</span>
				 	</li>
			 	<?php endif ?>
			</ul>
		</div> <!-- End body -->
	</div>
<?php endforeach ?>
</div>

<!-- #################################################################### -->

<?php

/* ТАБЛИЧНОЕ ПРЕДСТАВЛЕНИЕ

<table class="table">
	<thead>
		<tr>
			<th>№</th>
			<th>Дата</th>
			<th>Название</th>
			<th>Текст</th>
			<th>Ярлыки</th>
			<th>Действия</th>
		</tr>
	</thead>
	<tbody>
		<?php for($i = 0; $i < count($list); $i++): ?>
			<?php 
				$style= ($list[$i]['color'] != null) ? "style='background:#" . $list[$i]['color'] . "'" : ""; 	//Цвет заметки
			?>
			<tr <?= $style ?>>
				<td><?= ($i + 1) ?></td>
				<td><em><?= date('d.m.Y H:i:s', $list[$i]['datetime']) ?></em></td>
				<td>
					<strong>
						<a href="/notes/details?id=<?= $list[$i]['id']?>">
							<?= (strlen($list[$i]['title']) <= MAX_LENGHT_NOTE_TITLE) ? $list[$i]['title'] : mb_substr($list[$i]['title'], 0, MAX_LENGHT_NOTE_TITLE, 'UTF-8') . "..." ?>
						</a>
					</strong>
				</td>
				<td>
					<?= (strlen($list[$i]['note']) <= MAX_LENGHT_NOTE_TEXT) ? $list[$i]['note'] : mb_substr($list[$i]['note'], 0, MAX_LENGHT_NOTE_TEXT, 'UTF-8') . "..."; ?>
				</td>
				<td>
					<?php $tags = $list[$i]['tags']; ?>
					<?php if(!empty($tags)): ?>
						<?php for($j = 0; $j < count($tags); $j++): ?>
							<span class="label label-default"><?= $tags[$j]['name'] ?></span>
						<?php endfor ?>
					<?php else: ?>
						<span class="label label-info">Ярлыки отсутсвуют</span>
					<?php endif ?>
				</td>
				<td>
					<a href="/notes/details?id=<?= $list[$i]['id']?>" title="Детали" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-eye-open"></span></a>
					<?php if(!$isShared): ?>
						<a href="/notes/edit?id=<?= $list[$i]['id']?>" title="Редактировать" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></a>
						<a href="/notes/delete?id=<?= $list[$i]['id']?>" title="Удалить" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></a>
					<?php endif ?>
				</td>
			</tr>
		<?php endfor ?>
	</tbody>
</table>
*/
?>