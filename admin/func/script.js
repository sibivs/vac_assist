( function( $ ) {
$( document ).ready(function() {
$('#cssmenu > ul > li > a').click(function() {
  $('#cssmenu li').removeClass('active');
  $(this).closest('li').addClass('active');	
  var checkElement = $(this).next();
  if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
    $(this).closest('li').removeClass('active');
    checkElement.slideUp('normal');
  }
  if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
    $('#cssmenu ul ul:visible').slideUp('normal');
    checkElement.slideDown('normal');
  }
  if($(this).closest('li').find('ul').children().length == 0) {
    return true;
  } else {
    return false;	
  }		
});
});
} )( jQuery );


/**********************************************************/
//FOR New Mission PAGE//
/**********************************************************/

		

function unloadPopupBox() {	// TO Unload the Popupbox
	setTimeout(function(){$('#popup_box_new_mission').fadeOut("slow");
	$("#templatemo_container").fadeTo( "slow", 1);
	$("body").find('#opo1').remove();
	 window.location.reload(); }, 2000);
}	
		
function loadPopupBox() {
	$("body").append('<div id="opo1" class="modalOverlay" onclick="unloadByClick()">');	// To Load the Popupbox
	$('#popup_box_new_mission').fadeIn("slow");
	$("#templatemo_container").fadeTo( "slow", 0.1);
	return true;
}

function unloadByClick()
{
	$('#popup_box_new_mission').fadeOut("slow");
	$("#templatemo_container").fadeTo( "slow", 1);
	$("body").find('#opo1').remove();
}


/**********************************************************/


function unloadPopupBox1() {	// TO Unload the Popupbox
	setTimeout(function(){$('#popup_box_edit_mission').fadeOut("slow");
	$("#templatemo_container").fadeTo( "slow", 1);
	$("body").find('#opo1').remove();
	 window.location.reload(); }, 2000);
}	
		
function loadPopupBox1(id,mission,currencies) {
	$("body").append('<div id="opo1" class="modalOverlay" onclick="unloadByClick1()">');// To Load the Popupbox
	//$(":reset");
	//$('#form1').clearForm();
	$("#editcurrency").val("select");
	$("#editcurrency1").val("select");
	$('#popup_box_edit_mission').fadeIn("slow");
	$("#templatemo_container").fadeTo( "slow", 0.1);
	$("#editmission").val(mission);
	if( currencies.indexOf(',') != -1 )
	{
		var curr = currencies.split(',');
   		$("#editcurrency").val(curr[0]);
		$("#editcurrency1").val(curr[1]);
	}
	else
	{
		$("#editcurrency").val(currencies);	
	}
	
	$("#update_mission_values").click(function(){ submit_update_mission(id); });
	return true;
	
}

function unloadByClick1()
{
	$('#popup_box_edit_mission').fadeOut("slow");
	$("#templatemo_container").fadeTo( "slow", 1);
	$("body").find('#opo1').remove();
}
/**********************************************************/



/**********************************************************/
//FOR CURRENCY CONVERSION RATE

/**********************************************************/


function unloadPopupBox1_exch_rate() {	// TO Unload the Popupbox
	setTimeout(function(){$('#popup_box_exch_rate').fadeOut("slow");
	$("#templatemo_container").fadeTo( "slow", 1);
	$("body").find('#opo1').remove();
	 window.location.reload(); }, 2000);
}	
		
function loadPopupBox1_exch_rate(id,curr,exch_rate) {
	$("body").append('<div id="opo1" class="modalOverlay" onclick="unloadByClick1_exch_rate()">');// To Load the Popupbox
	$('#popup_box_exch_rate').fadeIn("slow");
	$("#templatemo_container").fadeTo( "slow", 0.1);
	$("#editcurr").val(curr);
	$("#editexch").val(exch_rate);
	$("#update_exch").click(function(){ submit_update_exch_rate(id); });
	return true;
	
}

function unloadByClick1_exch_rate()
{
	$('#popup_box_exch_rate').fadeOut("slow");
	$("#templatemo_container").fadeTo( "slow", 1);
	$("body").find('#opo1').remove();
}
/**********************************************************/
//END FOR CURRENCY CONVERSION RATE

/**********************************************************/






/**********************************************************/
//To Add New Service
/**********************************************************/

function unloadPopupBox2() {	// TO Unload the Popupbox
	setTimeout(function(){$('#popup-addnewservice').fadeOut("slow");
	$("#templatemo_container").fadeTo( "slow", 1);
	$("body").find('#opo1').remove();
	 window.location.reload(); }, 2000);
}	
		
function loadPopupBox2() {
	$("body").append('<div id="opo1" class="modalOverlay" onclick="unloadByClick2()">');// To Load the Popupbox
	$('#popup-addnewservice').fadeIn("slow");
	$("#templatemo_container").fadeTo( "slow", 0.1);
	$("#update_mission_values").click(function(){ submit_update_mission(id); });
	return true;
	
}

function unloadByClick2()
{
	$('#popup-addnewservice').fadeOut("slow");
	$("#templatemo_container").fadeTo( "slow", 1);
	$("body").find('#opo1').remove();
}
/**********************************************************/


/**********************************************************/
//FOR New Mission PAGE//
/**********************************************************/

		

function unloadPopupBox_add_exch() {	// TO Unload the Popupbox
	setTimeout(function(){$('#popup_box_add_exch').fadeOut("slow");
	$("#templatemo_container").fadeTo( "slow", 1);
	$("body").find('#opo1').remove();
	 window.location.reload(); }, 2000);
}	
		
function loadPopupBox_add_exch() {
	$("body").append('<div id="opo1" class="modalOverlay" onclick="unloadByClick_add_exch()">');	// To Load the Popupbox
	$('#popup_box_add_exch').fadeIn("slow");
	$("#templatemo_container").fadeTo( "slow", 0.1);
	return true;
}

function unloadByClick_add_exch()
{
	$('#popup_box_add_exch').fadeOut("slow");
	$("#templatemo_container").fadeTo( "slow", 1);
	$("body").find('#opo1').remove();
}


/**********************************************************/
//For Custom Permission Message
/**********************************************************/

		

function unloadPopupBox_custom_permission() {	// TO Unload the Popupbox
	$('#reset_pwd_div').fadeOut("slow");
	$("#templatemo_container").fadeTo( "slow", 1);
	$("body").find('#opo1').remove();
	window.location.reload();
}	
		
function loadPopupBox_custom_permission() {
	$("body").append('<div id="opo1" class="modalOverlay" onclick="unloadPopupBox_custom_permission()">');	// To Load the Popupbox
	$('#custom').fadeIn("slow");
	$("#templatemo_container").fadeTo( "slow", 0.1);
	//$("#ref_no").val(reference);
	return true;
	
}
/**********************************************************/
