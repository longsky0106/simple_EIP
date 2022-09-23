$(document).ready(function(){
	$('#load_content').load('include/index_load_content.php');
	window_width = $( window ).width();
	window_height = $( window ).height();
	index_menu_width = $('#index_menu').outerWidth();
	index_menu_height = $('#index_menu').outerHeight();
	draggable_limit_x = window_width - index_menu_width;
	draggable_limit_y = window_height - index_menu_height;
	// draggable_limit_y = $('#load_content').height() - index_menu_height;
	draggable('#index_menu',draggable_limit_x, draggable_limit_y);
	
	
	
	
});



function draggable(element, draggable_limit_x, draggable_limit_y){
	$(element).draggable({
		// containment: "parent"

		containment: [ 0, 0, draggable_limit_x - 40, draggable_limit_y],
		start: function(event,ui) {

		},
		drag: function(event,ui) {
			window_width = $( window ).width();
			window_height = $( window ).height();
			var pos = ui.helper.offset();
			// alert(`${pos.left}`);
			if(`${pos.left}`> $(element).width()){
				$(element).css("width",index_menu_width);
				$('#index_main').css("display","initial");
			}else{
				$('#index_main').css("display","flex");
			}
			
			if(`${pos.top}`> $(element).height() && `${pos.left}`> $(element).width()){
				$('#load_content').css("margin-top",index_menu_height * -1);
			}else{
				$('#load_content').css("margin-top",0);
			}
		},
		stop: function(event,ui) {
			// var pos = ui.helper.offset();
			// alert(`${pos.left}`);
		}
	});
}

function renewDraggable(){
	window_width = $( window ).width();
	window_height = $( window ).height();
	draggable_limit_x = window_width - index_menu_width;
	draggable_limit_y = window_height - index_menu_height;
	// draggable('#index_menu',draggable_limit_x, draggable_limit_y);
}


$( window ).resize(function() {
	renewDraggable();
	draggable('#index_menu',draggable_limit_x, draggable_limit_y);
});

function load_content(n){
	switch (n){
		case 1:
			$('#load_content').load('http://192.168.1.56/positest/input_new2.php');
			break;
		case 2:
			$('#load_content').load('input_update.php');
			break;
		
		case 3:
			$('#load_content').load('system/show_online_user.php');
			break;
		
		default:
			
			break;
	}

}