function setThemaCookie(themaId) {
    setCookie("betreuer", "yes", 0);
    setCookie("thema", themaId, 0);
    window.location.pathname = "/user/diplom/main.html";
}

if (getCookie('cookiezi') == 1) {
    var themaRequest = new XMLHttpRequest();
    themaRequest.open('POST', './php/getThemas.php');
    themaRequest.send();
    themaRequest.onreadystatechange = function () {
        if (themaRequest.readyState === DONE) {
            if (themaRequest.status === OK) {
                let parsedResponse = JSON.parse(themaRequest.responseText);
                console.log(parsedResponse);

                if (parsedResponse.themas != null) {
                    for (var i = 0; i < parsedResponse.themas.length; i++) {
                        document.getElementById('container').insertAdjacentHTML('beforeend', '<div><button class="btn btn-primary" onclick="setThemaCookie('+
                                                                                                parsedResponse.themas[i].idthema+')">'+parsedResponse.themas[i].name+
                                                                                                '</button></div>');
                    }
                }
            } else {
                console.log('Error: ' + loginRequest.status); // An error occurred during the request.
            }
        }
    };
} else {
    window.location.pathname = "/user/diplom/index.html";
}
