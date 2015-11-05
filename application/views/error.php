<?php
	$error = isset($error) ? $error : "Нет описания ошибки"; 
?>

<div class="alert alert-danger" role="alert">
	В процессе операции произошла ошибка. <br/> <?= $error ?>
</div>