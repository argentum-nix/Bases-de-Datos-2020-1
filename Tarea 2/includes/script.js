
$(document).click(function(click)
{
	var target = $(click.target);
	if(!target.hasClass("item") && !target.hasClass("optbutton")) {
		hideOptionsMenu();
	}

});

$(window).scroll(function(){
	hideOptionsMenu();
});


function addToAlbum(selectObject) {
	var album_id = selectObject.value;  
  	var songid = $(selectObject).prevAll(".cid").val();
  	$.post('/T2/includes/album.inc.php', {add_toalbum: '1', cid: songid, aid: album_id},
  	(data, status) => { console.log("Data: " + data + "\nStatus: " + status); });
  	hideOptionsMenu();
  	$(selectObject).val("");
}

function addToPlaylist(selectObject){
	var play_id = selectObject.value;  
  	var songid = $(selectObject).prevAll(".cid").val();
  	$.post('/T2/includes/playlist.inc.php', {add_toplay: '1', cid: songid, pid: play_id},
  	(data, status) => { console.log("Data: " + data + "\nStatus: " + status); });
  	hideOptionsMenu();
  	$(selectObject).val("");
}

function deleteFromPlaylist(selectObject, pid){
	var play_id = selectObject.value;  
	console.log(album_id);
	var songid = $(selectObject).prevAll(".cid").val();
		$.post('/T2/includes/playlist.inc.php', {delete_fromplay: '1', cid: songid, pid: play_id},
  	(data, status) => { console.log("Data: " + data + "\nStatus: " + status); });
  	location.reload();
  	hideOptionsMenu();
}

function addLike(songid){
	console.log("Hicistes click en laik crack");
	$.post('/T2/includes/likes.inc.php', {like: "1", cid: songid},
	(data, status) => { console.log("Data: " + data + "\nStatus: " + status);});
	hideOptionsMenu();
}

function deleteLike(songid){
	console.log("Hicistes click en dislaik crack");
	$.post('/T2/includes/likes.inc.php', {dislike: "1", cid: songid},
	(data, status) => { console.log("Data: " + data + "\nStatus: " + status);});
	hideOptionsMenu();
}

function checkMeGusta(songid){
	$.post('/T2/includes/likes.inc.php', {check: "1", cid: songid},
  	(data, status) => { console.log("Data: " + data + "\nStatus: " + status);
  	$('#like').html((data == "true") ? ("Quitar de tus Me Gusta") : ("Agregar a tus Me Gusta"));
  	$('#like').attr('onclick', (data == "false") ? ('addLike('+songid+')') : ('deleteLike('+songid+')'));
  	});

}
function editSong(selectObject){
	var songid = $(selectObject).prevAll(".cid").val();
  	(data, status) => { console.log("Data: " + data + "\nStatus: " + status); };
  	location.replace("/T2/edit_song.php?cid="+songid+"&&cur=1");
}

function deleteFromAlbum(selectObject, aid){
	var album_id = aid;
	console.log(album_id);
	var songid = $(selectObject).prevAll(".cid").val();
	$.post('/T2/includes/album.inc.php', {delete_fromalbum: '1', cid: songid, aid: album_id},
  	(data, status) => { console.log("Data: " + data + "\nStatus: " + status); });
  	location.reload();
  	hideOptionsMenu();
}

function showOptionsMenu(button) {
	var songid = $(button).prevAll(".cid").val();
	var menu = $(".optMenu");
	var mw = menu.width();
	menu.find(".cid").val(songid);
	var scrollTop = $(window).scrollTop();
	var offset = $(button).offset().top; 
	var top = offset - scrollTop;
	var left = $(button).position().left;
	menu.css({ "top": top + "px", "left": left - mw + "px", "display": "inline" });
	checkMeGusta(songid);
}

function hideOptionsMenu() {
	var menu = $(".optMenu");
	if(menu.css("display") != "none") {
		menu.css("display", "none"); 
	}
}