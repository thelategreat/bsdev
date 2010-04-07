
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
