function Logout() {
	$.post('logout.php');
	window.location.href="login.php";
}

//Returns true if the given string contains numbers
function hasNum(val) {
    val = val.match(/\d+/);
    if (val != null) {
        return true;
    }
    else {
        return false;
    }
}
    
//Returns true if the given string contains anything except letters
function badName(val) {
    val = val.match(/[^a-zA-Z]/);
    if (val != null) {
        return true;
    }
    else {
        return false;
    }
}

//Returns true if the given string contains illegal characters
function badSpecial(val) {
    val = val.match(/[^a-zA-Z0-9 \- _ ' \.]/);
    if (val != null) {
        return true;
    }
    else {
        return false;
    }
}

//Returns true if the string contains characters that cannot be in an address
function badAddress(val) {
    val = val.match(/[^a-zA-Z0-9,#\-\/\ !@$%\^\*\(\)\{\}\|\[\]\\]/);
    if (val != null) {
        return true;
    }
    else {
        return false;
    }
}