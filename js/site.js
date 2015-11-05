$(document).ready(function() {
	changeBackround(null);
});

// Изменение цвета фона для select выбора цвета заметки

function changeBackround(event) {
	var style = "font-weight:bold;";
	style += $("#color option:selected").attr("style");
	if(style !== 'undefinded')
	{
		$("#color").attr("style", style);
	}
}