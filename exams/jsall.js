function checkall()
{
	var count = document.ttablefrm.elements.length;
	var toset;
	if( document.ttablefrm.elements[ 0 ].checked == true ) {
		toset = true;
	} else {
		toset = false;
	}
	for( i = 1; i < count; ++i ) {
		document.ttablefrm.elements[ i ].checked = toset;
	}
}

