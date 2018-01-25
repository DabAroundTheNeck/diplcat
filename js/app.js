const DONE = 4; // readyState 4 means the request is done.
const OK = 200; // status 200 is a successful return.

function login(form) {

    var loginRequest = new XMLHttpRequest();
    loginRequest.open('POST', './php/login.php');
    console.log(form.email);
    loginRequest.send('{"e":"'+form.email.value+'","pw":"'+form.psw.value+'"}');
    loginRequest.onreadystatechange = function () {
        if (loginRequest.readyState === DONE) {
            if (loginRequest.status === OK) {
                let parsedResponse = JSON.parse(loginRequest.responseText);
                console.log(parsedResponse);
                if (form.email.value.split("@")[1] == "htldornbirn.at") {
                    window.location.pathname = "/diplom/choice.html";
                } else {
                    window.location.pathname = "/diplom/main.html";
                }
            } else {
                console.log('Error: ' + loginRequest.status); // An error occurred during the request.
            }
        }
    };
}

//Function is not used (There is no Registration)
function register() {

    var loginRequest = new XMLHttpRequest();
    loginRequest.open('POST', './php/createUser.php');
    loginRequest.send('{"e":"email","pw":"password"}');
    loginRequest.onreadystatechange = function () {
        if (loginRequest.readyState === DONE) {
            if (loginRequest.status === OK) {
                let parsedResponse = JSON.parse(loginRequest.responseText);
                console.log(parsedResponse);
            } else {
                console.log('Error: ' + loginRequest.status); // An error occurred during the request.
            }
        }
    };
}

function loadThemaChooser() {
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
                            document.getElementById('container').insertAdjacentHTML('beforeend', '<div><button onclick="setThemaCookie('+parsedResponse.themas[i].idthema+')">'+parsedResponse.themas[i].name+'</button></div>');
                        }
                    }
                } else {
                    console.log('Error: ' + loginRequest.status); // An error occurred during the request.
                }
            }
        };
    } else {
        window.location.pathname = "/diplom";
    }
}

function setThemaCookie(themaId) {
    setCookie("thema", themaId, 0);
    window.location.pathname = "/diplom/main.html";
}

function loadMain() {
    if (getCookie('cookiezi') == 1) {
        var emailS;
        if (getCookie('thema') == -1) {
            emailS = 0;
        } else {
            emailS = 1;
        }
        var data;
        var themaRequest = new XMLHttpRequest();
        themaRequest.open('POST', './php/getThemaById.php');
        themaRequest.send('{"id":"'+getCookie('thema')+'", "emailS":"'+emailS+'"}');
        themaRequest.onreadystatechange = function () {
            if (themaRequest.readyState === DONE) {
                if (themaRequest.status === OK) {
                    data = JSON.parse(themaRequest.responseText);
                    thema = data.themaRe;

                    document.getElementById('title').innerHTML = thema.name;

                    for (var i = 0; i < thema.mitarbeiter.length-1; i++) {
                        addWorker();
                    }

                    for (var i = 0; i < thema.mitarbeiter.length; i++) {
                        thema.mitarbeiter[i];
                    }

                } else {
                    console.log('Error: ' + loginRequest.status); // An error occurred during the request.
                }
            }
        };
        //Now we have the data


    } else {
        window.location.pathname = "/diplom";
    }
}

function save() {
    var plText = document.getElementById('projektleiterText').value;
    var pmTexts;
    var allWorkers = document.getElementById('workerCardContainer').children;
    for (var i = 0; i < allWorkers.length; i++) {
        console.log(allWorkers[i].children[2].value);
    }

}

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
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/diplom";
}

function addWorker() {
    var workerHTML = '<div class="card personcard"><img src="" alt="Image"><div class="cardStroke"></div><input type="text" name="" value=""></div>';
    document.getElementById('workerCardContainer').insertAdjacentHTML('beforeend', workerHTML);
}

function changeWorker(img, text, i) {
    var allWorkers = document.getElementById('workerCardContainer').children;

    if (i+1 > allWorkers.length) {
        console.log("Cant do shit");
    } else {
        var worker = allWorkers[i];
        var imgE = worker.getElementsByTagName('img');
        var textE = worker.getElementsByClassName('textfield')[0];
        if (img != "") {
            imgE.setAttribute('src', img);
        }
        if (text != "") {
            textE.setAttribute('value', text);
        }
    }
}
