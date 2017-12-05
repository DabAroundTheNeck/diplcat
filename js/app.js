const DONE = 4; // readyState 4 means the request is done.
const OK = 200; // status 200 is a successful return.

function login() {

    var loginRequest = new XMLHttpRequest();
    loginRequest.open('GET', './php/login.php');
    loginRequest.send("e=email&pw=password");
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
