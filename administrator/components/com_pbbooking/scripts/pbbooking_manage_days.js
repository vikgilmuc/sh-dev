//is jquery loaded?
try { if(jQuery) {
	//do stuff
} else {
	loadJquery();
} 
} catch (e) {
	loadJquery();
}
finally {
	//do nothing
}





function deleteBlock(id)
{
	console.log('delete block '+id);
	console.log($path);
	jQuery.get($path+'administrator/index.php?option=com_pbbooking&controller=manage&task=delete_block&format=raw&id='+id,function(data){
		console.log('data = '+data)
		if (data == "success") {
			jQuery('tr#pbbooking-blocked-day-id-'+id).css({'display':'none'});
		} else {
			alert('Something went wrong with the delete');
		}
	})
}


function loadJquery()
{
	var script = document.createElement('script');
	script.setAttribute('type','text/javascript');
	script.setAttribute('src','../components/com_pbbooking/scripts/jquery.js');
	head = document.getElementsByTagName('head');
	head[0].appendChild(script);
}