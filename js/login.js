<<<<<<< HEAD
function login(form) {

    var loginRequest = new XMLHttpRequest();
    loginRequest.open('POST', './php/login.php');
    loginRequest.send('{"e":"'+form.email.value+'","pw":"'+form.psw.value+'"}');
    loginRequest.onreadystatechange = function () {
      /*
       if (this.readyState < 4 ||  this.status != 200) {
          console.log("Verbindungsfehler!!");
          alert("Aktueller Zugangsfehler Email//Password!");
       } else
       */
       if (loginRequest.readyState === DONE) {
            if (loginRequest.status === OK) {
                let parsedResponse = JSON.parse(loginRequest.responseText);
                if (parsedResponse.response == 1) {
                    if (form.email.value.split("@")[1] == "htldornbirn.at") {   //If Email Ends on @htldornbirn.at its a teacher -> relocate to choice.html
                        window.location.pathname = "/user/diplom/choice.html";
                    } else {
                        window.location.pathname = "/user/diplom/main.html";
                    }
                } else {
                    console.log("Login Failed");
                    alert("User/Password ist falsch!");
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

deleteCookie('cookiezi');
deleteCookie('thema');
=======

function login(form) {

    var loginRequest = new XMLHttpRequest();
    loginRequest.open('POST', './php/login.php');
    loginRequest.send('{"e":"'+form.email.value+'","pw":"'+form.psw.value+'"}');
    loginRequest.onreadystatechange = function () {
        if (loginRequest.readyState === DONE) {
            if (loginRequest.status === OK) {
                let parsedResponse = JSON.parse(loginRequest.responseText);
                if (parsedResponse.response == 1) {
                    if (form.email.value.split("@")[1] == "htldornbirn.at") {   //If Email Ends on @htldornbirn.at its a teacher -> relocate to choice.html
                        window.location.pathname = "/user/diplom/choice.html";
                    } else {
                        setCookie("betreuer", 0, 0);
                        window.location.pathname = "/user/diplom/main.html";
                    }
                } else {
                    console.log("Login Failed");
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

deleteCookie('cookiezi');
deleteCookie('thema');
deleteCookie('betreuer');
>>>>>>> f6d292500d84e42a848486771036364ba5403620
