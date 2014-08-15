window.addEvent('domready',function(){
	$('select-shift').addEvent('change',function(){
		
		this.setProperty('disabled',true);
		
		//load up the relevant times in the drop down box....
		start_hour = times_array[this.getProperty('value')]['start_time']['start_hour'];
		start_min = times_array[this.getProperty('value')]['start_time']['start_min'];
		end_hour = times_array[this.getProperty('value')]['end_time']['end_hour'];
		end_min = times_array[this.getProperty('value')]['end_time']['end_min'];
		
		//console.log('start_hour = '+start_hour);
		dtstart = new Date();
		dtend = new Date();
		dtstart.setHours(start_hour,start_min,0);
		dtend.setHours(end_hour,end_min,0);
		html = '<select name="treatment-time" id="treatment-time">';
		html += '<option value="0">'+time_prompt+'</option>';
		
		while(dtstart<=dtend) {
			//console.log(dtstart.toString());
			html+='<option value="'+dtstart.format('%H%M')+'">'+dtstart.format('%H:%M %p')+'</option>';
			dtstart.setMinutes(dtstart.getMinutes()+time_increment);
		}				
		html+='</select>';
		if (Browser.name == 'ie') {
			$('container-treatment-time').innerHTML = html;
		} else {
			$('container-treatment-time').setProperty('html',html);
		}
		
		this.setProperty('disabled',false);	
	})
})