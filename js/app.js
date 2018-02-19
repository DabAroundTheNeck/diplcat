function save() {
    var plText = document.getElementById('projektleiterText').value;
    var pmTexts;
    var allWorkers = document.getElementById('workerCardContainer').children;
    for (var i = 0; i < allWorkers.length; i++) {
        console.log(allWorkers[i].children[2].value);
    }
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

function changeImage(event, imgId) {
    var tgt = event.target || window.event.srcElement,
        files = tgt.files;

    // FileReader support

    if (FileReader && files && files.length) {
        var fr = new FileReader();
        fr.onload = function () {
            document.getElementById(imgId).src = fr.result;
            var base64 = document.getElementById(imgId).src;

            var imageRequest = new XMLHttpRequest();
            imageRequest.open('POST', './php/imageChange.php');
            imageRequest.send('{"name":"'+imgId+'","image":"'+base64+'"}');
            imageRequest.onreadystatechange = function () {
                if (imageRequest.readyState === DONE) {
                    if (imageRequest.status === OK) {
                        let parsedResponse = JSON.parse(imageRequest.responseText);
                        console.log(parsedResponse);
                    } else {
                        console.log('Error: ' + loginRequest.status); // An error occurred during the request.
                    }
                }
            };

        }
        fr.readAsDataURL(files[0]);
    }
}


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

                console.log(thema);

                document.getElementById('title').innerHTML = thema.name;
                /*
                for (var i = 0; i < thema.mitarbeiter.length-1; i++) {
                    addWorker();
                }

                for (var i = 0; i < thema.mitarbeiter.length; i++) {
                    thema.mitarbeiter[i];
                }
                */
            } else {
                console.log('Error: ' + loginRequest.status); // An error occurred during the request.
            }
        }
    };
} else {
    window.location.pathname = "/user/diplom/index.html";
}
