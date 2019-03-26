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

		

function unloadPopupBox() {	// TO Unload the Popupbox
	$('#popup_box').fadeOut("slow");
	$("#templatemo_container").fadeTo( "slow", 1);
	$("body").find('#opo1').remove();
}	
		
function loadPopupBox(reference) {
	$("body").append('<div id="opo1" class="modalOverlay">');	// To Load the Popupbox
	$('#popup_box').fadeIn("slow");
	$("#templatemo_container").fadeTo( "slow", 0.1);
	$("#old_ref").val(reference);
}
/**********************************************************/


/**********************************************************/
//FOR RO PAGE//
/**********************************************************/

		

function unloadPopupBox1() {	// TO Unload the Popupbox
	$('#popup_box_registration').fadeOut("slow");
	$("#templatemo_container").fadeTo( "slow", 1);
	$("body").find('#opo1').remove();
	localStorage.setItem('token_no',$("#token_no").val());
	window.location.reload();
}	
		
function loadPopupBox1(reference,token) {
	clearInterval(intr);
	$("body").append('<div id="opo1" class="modalOverlay" onclick="unloadPopupBox1()" >');	// To Load the Popupbox
	$('#popup_box_registration').fadeIn("slow");
	$("#templatemo_container").fadeTo( "slow", 0.1);
	if(reference !="noref" && (reference.substr(0,3)).toUpperCase()=="GWF")
	{
		$('#load_accept').contents().find('#gwf1').val(reference);
		$('#load_accept').contents().find("#gwf1").attr("readonly", true);  
		$('#load_accept').contents().find('#token').val(token);
		$('#load_accept').contents().find('#frm_warning').attr('style','display:none');
	}
	else if(reference !="noref" && (reference.substr(0,3)).toUpperCase()=="FRM")
	{
		$('#load_accept').contents().find('#gwf1').val("");
		$('#load_accept').contents().find("#gwf1").attr("readonly", false);  
		$('#load_accept').contents().find('#token').val(token);
		$('#load_accept').contents().find('#frm_warning').removeAttr('style');
		$('#load_accept').contents().find('#frm_show').html("Replace FRM Number - "+reference+" with Actual Ref. Number");
	}
	else
	{
		//alert(reference);
		$("#gwf1").attr("readonly", false); 
		$('#load_accept').contents().find('#frm_warning').attr('style','display:none');
	}
}


/**********************************************************/



/**********************************************************/
//FOR RO PAGE//
/**********************************************************/

		

function unloadPopupBox2() {	// TO Unload the Popupbox
	$('#popup_box_rejection').fadeOut("slow");
	$('#popup_box_rejection').html('<div class="text-info text-center h4"><u>Mark As Not Submitting</u></div>    <div style="height: 40px; display:none; border: 0px; vertical-align: top; padding-bottom: 30px;" class="form-control" align="center" id="response1"></div>    <table class="table h5">    	<tr>        	<td class="text-danger" style="border: 0px; border-color: none; text-align: left;" id="ref">Application Reference Number</td>            <td style="border: 0px; border-color: none; text-align: left;"><input type="text" id="ref_no" class="form-control" value="" style="width: 220px;" readonly></td>        </tr>        <tr>        	<td class="text-danger" style="text-align: left;">Reason For Not Submitting</td>            <td style="text-align: right;">            	<select id="reason" class="form-control" style="width: 220px;">                	<option value="Select">Select a reason</option>                    <option value="no_noc">Missing NOC</option>                    <option value="no_cash">No cash</option>                    <option value="missing_document">Missing documents</option>                    <option value="premium_not_wanted">Hesitated to pay for premium</option>                    <option value="applicant_not_present">Applicant is not present</option>                    <option value="medical_issues">Medical issues</option>            	</select>            </td>        </tr>        <tr>        	<td colspan="2" style="text-align:center; border: 0px; border-color: none; "><input type="button" class="btn btn-info" value="Mark As Not Submitting" id="reject_ro_token"></td>        </tr>    </table>       <a id="popupBoxClose"><img src="styles/images/closebox.png" style="width:30px; height:30px;" onClick="unloadPopupBox2()"></a>');
	$("#templatemo_container").fadeTo( "slow", 1);
	$("body").find('#opo1').remove();
	localStorage.setItem('token_no',$("#token_no").val());
	//window.location.reload();
}	
		
function loadPopupBox2(reference,type) {
	$("body").append('<div id="opo1" class="modalOverlay" onclick="unloadPopupBox2()">');	// To Load the Popupbox
	$('#popup_box_rejection').fadeIn("slow");
	$("#templatemo_container").fadeTo( "slow", 0.1);
	$('#reject_ro_token').removeAttr('onclick');
	
	if(type=="individual")
	{
		$("#ref_no").val(reference);
		$('#reject_ro_token').attr("onClick", "submit_reject('individual');");
	}
	else if(type=="group")
	{
		$("#ref").html("Applicant Group ID");
		$("#ref_no").val(reference);
		$('#reject_ro_token').attr("onClick", "submit_reject('group');");
	}
	
	
	return true;
	
}

function unload_success()
{
	setTimeout(function(){
		$('#popup_box_rejection').fadeOut("slow");
		$("#templatemo_container").fadeTo( "slow", 1);
		$("body").find('#opo1').remove();
		localStorage.setItem('token_no',$("#token_no").val());
		window.location.reload();
		}, 3000);
}
/**********************************************************/


/**********************************************************/
//FOR Appointment Details PAGE//
/**********************************************************/

		

function unloadPopupBox3() {	// TO Unload the Popupbox
	$('#popup_box_ao_status').fadeOut("slow");
	$("#templatemo_container").fadeTo( "slow", 1);
	$("body").find('#opo1').remove();
	window.location.reload();
}	
		
function loadPopupBox3() {
	$("body").append('<div id="opo1" class="modalOverlay" onclick="unloadPopupBox3()">');	// To Load the Popupbox
	$('#popup_box_ao_status').fadeIn("slow");
	$("#templatemo_container").fadeTo( "slow", 0.1);
	//$("#ref_no").val(reference);
	return true;
	
}
/**********************************************************/



/**********************************************************/
//FOR Charts//
/**********************************************************/

		

function unloadPopupBox_chart() {	// TO Unload the Popupbox
	$('#popup_box_chart').fadeOut("slow");
	$("#templatemo_container").fadeTo( "slow", 1);
	$("body").find('#opo1').remove();
	//window.location.reload();
}	
		
function loadPopupBox_chart() {
	$("body").append('<div id="opo1" class="modalOverlay" onclick="unloadPopupBox_chart()">');	// To Load the Popupbox
	$('#popup_box_chart').fadeIn("slow");
	$("#templatemo_container").fadeTo( "slow", 0.1);
	//$("#ref_no").val(reference);
	return true;
	
}
/**********************************************************/



/**********************************************************/
//FOR Users Page//
/**********************************************************/

		

function unloadPopupBox_update_pwd() {	// TO Unload the Popupbox
	$('#reset_pwd_div').fadeOut("slow");
	$("#templatemo_container").fadeTo( "slow", 1);
	$("body").find('#opo1').remove();
	window.location.reload();
}	
		
function loadPopupBox_update_pwd() {
	$("body").append('<div id="opo1" class="modalOverlay" onclick="unloadPopupBox_update_pwd()">');	// To Load the Popupbox
	$('#reset_pwd_div').fadeIn("slow");
	$("#templatemo_container").fadeTo( "slow", 0.1);
	//$("#ref_no").val(reference);
	return true;
	
}
/**********************************************************/




/**********************************************************/
//FOR Pending Tokens//
/**********************************************************/

		

function unloadPopupBox_pending_token() {	// TO Unload the Popupbox
	$('#popup_box_token_pending').fadeOut("slow");
	$("#templatemo_container").fadeTo( "slow", 1);
	$("body").find('#opo1').remove();
	//clearInterval(intr);
	//window.location.reload();
}	
		
function loadPopupBox_pending_token() {
	$("body").append('<div id="opo1" class="modalOverlay" onclick="unloadPopupBox_pending_token()">');	// To Load the Popupbox
	$('#popup_box_token_pending').fadeIn("slow");
	$("#templatemo_container").fadeTo( "slow", 0.1);
	play_sound();
				var oldTitle = document.title;
                var msg = "Token Pending!";
                var timeoutId = false;

                var blink = function() {
                    document.title = document.title == msg ? oldTitle : msg;//Modify Title in case a popup

                    if(document.hasFocus())//Stop blinking and restore the Application Title
                    {
                        document.title = oldTitle;
                        clearInterval(timeoutId);
                    }                       
                };

                if (!timeoutId) {
                    timeoutId = setInterval(blink, 1000);//Initiate the Blink Call
                };//Blink logic 				
	//$("#ref_no").val(reference);
	return true;
	
}

function play_sound() {
        var audioElement = document.createElement('audio');
        audioElement.setAttribute('src', 'assets/beep_sound/beep.mp3');
        audioElement.setAttribute('autoplay', 'autoplay');
        audioElement.load();
        audioElement.play();
    }
/**********************************************************/


/**********************************************************/
//For AO creation
/**********************************************************/

		

function unloadPopupBox_ao_msg() {	// TO Unload the Popupbox
	$('#PopupBox_ao_msg').fadeOut("slow");
	$("#templatemo_container").fadeTo( "slow", 1);
	$("body").find('#opo1').remove();
	window.location.reload();
}	
		
function loadPopupBox_ao_msg() {
	$("body").append('<div id="opo1" class="modalOverlay" onclick="unloadPopupBox_ao_msg()">');	// To Load the Popupbox
	$('#PopupBox_ao_msg').fadeIn("slow");
	$("#templatemo_container").fadeTo( "slow", 0.1);
	//$("#ref_no").val(reference);
	return true;
	
}
/**********************************************************/

function unloadPopupBox_manage_submission() {	// TO Unload the Popupbox
	$('#PopupBox_manage_submission').fadeOut("slow");
	$("#templatemo_container").fadeTo( "slow", 1);
	$("body").find('#opo1').remove();
	window.location.href="edit_outscanned.php";
}	
		
function loadPopupBox_manage_submission() {
	$("body").append('<div id="opo1" class="modalOverlay" onclick="unloadPopupBox_manage_submission()">');	// To Load the Popupbox
	$('#PopupBox_manage_submission').fadeIn("slow");
	$("#templatemo_container").fadeTo( "slow", 0.1);
	return true;
	
}



function unloadPopupBox_rect() {	// TO Unload the Popupbox
	$('#PopupBox_manage_submission').fadeOut("slow");
	$("#templatemo_container").fadeTo( "slow", 1);
	$("body").find('#opo1').remove();
	window.location.reload();
}	
		
function loadPopupBox_rect() {
	$("body").append('<div id="opo1" class="modalOverlay" onclick="unloadPopupBox_rect()">');	// To Load the Popupbox
	$('#PopupBox_manage_submission').fadeIn("slow");
	$("#templatemo_container").fadeTo( "slow", 0.1);
	return true;
	
}