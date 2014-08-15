window.addEvent('domready',function(){


		//override the submit button
		$('pbbooking-submit').addEvent('click',function(e){	
			
			e.stop();

			var error = validate_input();
			if (!error)
				document.getElementById('pbbooking-reservation-form').submit();
		});

})


/*
validate input - revised method for validating the input just to do validation and return true or false
*/

function validate_input()
{
	error = false;
	//alert('validate input');
	for(var i=0;i<customfields.length;i++) {
		if (customfields[i].fieldtype=="text") {
			val = $$('input[name='+customfields[i].varname+']').getProperty('value');
			if (val == "" && customfields[i].is_required == 1) {
				$$('input[name='+customfields[i].varname+']').setProperty('class','error-field');
				error = true;
			}
		}
		
		if (customfields[i].fieldtype=="textarea") {
			val  = $$('textarea[name='+customfields[i].varname+']').getProperty('value');
			if (val == "" && customfields[i].is_required == 1) {
				$$('textarea[name='+customfields[i].varname+']').setProperty('class','error-field');
				error = true;
			}
		}
		
		if (MooTools.version != "1.12") {
			//MooTools 1.12 doesn't support pseudo selectors.
			if (customfields[i].fieldtype=="radio" && customfields[i].is_required == 1) {
				if ($$('input[name='+customfields[i].varname+']:checked').length==0) {
					error = true;
					$$('.'+customfields[i].varname+'-label').addClass('error-label');
				}
			}
			if (customfields[i].fieldtype=="checkbox" && customfields[i].is_required == 1) {
				if ($$('input[name='+customfields[i].varname+'[]]:checked').length==0) {
					error = true;
					$$('.'+customfields[i].varname+'-label').addClass('error-label');
				}
			}
		}
	}
	
	//do i have a service type?
	if ($$('select[name=treatment_id] :selected').getLast().getProperty('value') == 0) {
		error = true;
		$('service-error-msg').innerHTML = error_msg_service;
		$('service-error-msg').addClass('error-message');
	}

	return error;

}