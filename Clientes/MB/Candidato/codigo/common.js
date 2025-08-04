function lsetup(target)
{
	try {
		if (!target)
			target = this;

		var o_set = target.document.getElementById('loaderContainerWH');
		var o_getH = target.document.getElementsByTagName('BODY')[0];

		o_set.style.height = o_getH.scrollHeight;
	} catch (e) {
	}
}

function lon(target)
{
	try {
		if (!target)
			target = this;

		lsetup(target);

		if (!target._lon_disabled_arr){
			target._lon_disabled_arr = new Array();
		}else if (target._lon_disabled_arr.length > 0){
			return true;
		}

		target.document.getElementById("loaderContainer").style.display = "";
		var select_arr = target.document.getElementsByTagName("select");

	} catch (e) {
		return false;
	}
	return true;
}

function loff(target)
{
	try {   
		if (!target)
			target = this;
		target.document.getElementById("loaderContainer").style.display = "none";
	} catch (e) {
		return false;
	}
	return true;
}