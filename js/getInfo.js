function getForm(block){
	if (document.getElementById(block)) {
		var obj=document.getElementById(block);
		if (obj.style.display!='block') {
			obj.style.display='block';
		}else{obj.style.display='none';}
	};
}

window.onload = function(){
	var scrollUp = document.getElementById('scrollup');
	scrollUp.onmouseover=function(){
		scrollUp.style.opacity = 0.9;
		scrollUp.style.filter = 'alpha(opacity=90)';
	};
	scrollUp.onmouseout=function(){
		scrollUp.style.opacity = 0.7;
		scrollUp.style.filter = 'alpha(opacity=70)';
	};
	scrollUp.onclick=function(){
		window.scrollTo(0,0);
	};
	window.onscroll=function(){
		if (window.pageYOffset>200) {
			scrollUp.style.display='block';
		}
		else{
			scrollUp.style.display='none';
		}
	};
};
$(function() {
	$( "#slider_price" ).slider({
		range: true,
		min: 0,
		max: 100000,
		values: [1000, 20000],
		slide: function( event, ui ) {
			//Поле минимального значения
			$( "#price" ).val(ui.values[0]);
			//Поле максимального значения
			$("#price2").val(ui.values[1]);}
	});
	//Записываем значения ползунков в момент загрузки страницы
	//То есть значения по умолчанию
	$( "#price" ).val($( "#slider_price" ).slider( "values", 0 ));
	$("#price2").val($("#slider_price").slider( "values", 1));
});
// $(document).ready(function() {
//     $("#links").on("click", ".link", function(){
//     	$("#links .link").removeClass("active"); //удаляем класс во всех вкладках
//     	$(this).addClass("active"); //добавляем класс текущей (нажатой)
//     });
// });