

$(window).scroll(function(){
	hideOptionsMenu();
});

$(document).click(function(click){
	var tg = $(click.target);
	if(!tg.hasClass("item") && !tg.hasClass("optbutton")){
		hideOptionsMenu();
	}	
});

function showOptionsMenu(button) {
	var menu = $(".optMenu");
	var mw = menu.width();

	var scrollTop = $(window).scrollTop();
	var offset = $(button).offset().top; 

	var top = offset - scrollTop;
	var left = $(button).position().left;

	menu.css({ "top": top + "px", "left": left - mw + "px", "display": "inline" });

}

function hideOptionsMenu() {
	var menu = $(".optMenu");
	if(menu.css("display") != "none") {
		menu.css("display", "none"); 
	}
}