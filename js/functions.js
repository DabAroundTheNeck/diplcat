const DONE = 4; // readyState 4 means the request is done.
const OK = 200; // status 200 is a successful return.


function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function setCookie(cname, cvalue, ex) {
    var expires = "expires="+ ex;
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/user/diplom";
}

function deleteCookie(cname) {
    document.cookie = cname + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}
