window.addEvent('domready',function(){
	
	$$('.time-input').addEvent('blur',function(){

		input = this.getProperty('value');

		if (input != '') {
			hours = input.replace(/(^\d\d).*/,'$1');
			minutes = input.replace(/.*(\d\d)$/,'$1');
		
			this.setProperty('value',hours+':'+minutes);		
		}
		
	})
	
	$('adminForm').addEvent('submit',function(){
		//loop through all the time inputs and replace the colons...
		$$('.time-input').each(function(el,index){
			el.setProperty('value',el.getProperty('value').replace(/\:/,''));
		})
	})
	
	
	
	
	
})
