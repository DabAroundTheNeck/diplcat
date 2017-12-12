const DONE = 4; // readyState 4 means the request is done.
const OK = 200; // status 200 is a successful return.

function login() {

    var loginRequest = new XMLHttpRequest();
    loginRequest.open('POST', './php/login.php');
    loginRequest.send('{"e":"email","pw":"password"}');
    loginRequest.onreadystatechange = function () {
        if (loginRequest.readyState === DONE) {
            if (loginRequest.status === OK) {
                console.log(loginRequest.responseText);
                let parsedResponse = JSON.parse(loginRequest.responseText);
                console.log(parsedResponse);
            } else {
                console.log('Error: ' + loginRequest.status); // An error occurred during the request.
            }
        }
    };
}

function register() {

    var loginRequest = new XMLHttpRequest();
    loginRequest.open('POST', './php/createUser.php');
    loginRequest.send('{"e":"email","pw":"password"}');
    loginRequest.onreadystatechange = function () {
        if (loginRequest.readyState === DONE) {
            if (loginRequest.status === OK) {
                console.log(loginRequest.responseText);
                let parsedResponse = JSON.parse(loginRequest.responseText);
                console.log(parsedResponse);
            } else {
                console.log('Error: ' + loginRequest.status); // An error occurred during the request.
            }
        }
    };
}

function addCard(id) {
    document.getElementById(id).insertAdjacentHTML('beforeend', '<div class="card personcard"><img src="" alt="Image"><div class="cardStroke"></div><input type="text" name="" value=""></div>');
}
