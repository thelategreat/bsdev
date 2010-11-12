
// trimmer
String.prototype.trim = function(){
	return this.replace(/^\s+|\s+$/g,'')
}

/* Finds the index of the first occurence of item in the array, 
 * or -1 if not found */
Array.prototype.indexOf = function(item) {
    for (var i = 0; i < this.length; i++) {
        if (this[i] == item) {
            return i;
        }
    }
    return -1;
};

Array.prototype.contains = function(value)
{
    var i = this.length;
    if( i > 0 ) {
        do {
            if( this[i] === value ) {
                return true;
            }
        } while( i-- );
    }
    return false;
}

/* like PHP print_r kinda, its 1970 again! w00t! 
 * alert( dump(some_shit));
 */
function dump(arr,level) {
	var dumped_text = "";
	if(!level) level = 0;
	
	//The padding given at the beginning of the line.
	var level_padding = "";
	for(var j=0;j<level+1;j++) level_padding += "    ";
	
	if(typeof(arr) == 'object') { //Array/Hashes/Objects 
		for(var item in arr) {
			var value = arr[item];
			
			if(typeof(value) == 'object') { //If it is an array,
				dumped_text += level_padding + "'" + item + "' ...\n";
				dumped_text += dump(value,level+1);
			} else {
				dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
			}
		}
	} else { //Stings/Chars/Numbers etc.
		dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
	}
	return dumped_text;
}
