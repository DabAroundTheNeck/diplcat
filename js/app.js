function save(confirm) {
    var title = document.getElementById('title').innerHTML;
    var logoImageText = document.getElementById('logoImageText').value;
    var projektleiter = document.getElementById('projektLeiterText').value;
    var projektleiterImageText = document.getElementById('projektLeiterImageText').value;
    var mitarbeiter = [];
    var mitarbeiterImageText = [];
    var allWorkers = document.getElementById('workerCardContainer').children;
    for (var i = 0; i < allWorkers.length; i++) {
        mitarbeiter[i] = allWorkers[i].children[4].value;
        mitarbeiterImageText[i] = allWorkers[i].children[2].value;
    }

    var problemstellung = document.getElementById('problemstellung').value;
    var zielsetzung = document.getElementById('zielsetzung').value;

    var technologien = [];
    var technologienImageText = [];
    var allTech = document.getElementById('techCardContainer').children;
    for (var i = 0; i < allTech.length; i++) {
        technologien[i] = allTech[i].children[4].value;
        technologienImageText[i] = allTech[i].children[2].value;
    }

    var prototype = document.getElementById('prototypeText').value;
    var prototypeImageText = document.getElementById('prototypeImageText').value;
    var ergebnisse = document.getElementById('ergebnisse').value;

    var saveRequest = new XMLHttpRequest();
    saveRequest.open('POST', './php/save.php');
    saveRequest.send('{"confirm":"' + confirm
            +'","titel":"'+title
            +'","logoImageText":"'+logoImageText
            +'","projektleiter":"'+projektleiter
            +'","projektleiterImageText":"'+projektleiterImageText
            +'","mitarbeiter":"'+mitarbeiter
            +'","mitarbeiterImageText":"'+mitarbeiterImageText
            +'","problemstellung":"'+problemstellung
            +'","zielsetzung":"'+zielsetzung
            +'","technologien":"'+technologien
            +'","technologienImageText":"'+technologienImageText
            +'","prototype":"'+prototype
            +'","prototypeImageText":"'+prototypeImageText
            +'","ergebnisse":"'+ergebnisse
            +'"}');
    saveRequest.onreadystatechange = function () {
        if (saveRequest.readyState === DONE) {
            if (saveRequest.status === OK) {
                let parsedResponse = JSON.parse(saveRequest.responseText);
                console.log(parsedResponse);
            } else {
                console.log('Error: ' + loginRequest.status); // An error occurred during the request.
            }
        }
    };
}

function confirm() {
    save(true);
}


function addWorker(text, image, imageText) {
    var container = document.getElementById('workerCardContainer');
    var id = container.childElementCount;
    var split = image.split('/');
    var split = split[split.length-1];
    if (split == 'undefined') {
        image = '';
    }

    var workerHTML = '<div class="card personcard">'
                        +'<img src="'+image+'" alt="Image" onclick="document.getElementById(\'worker'+id+'Form\').click()" id="worker'+id+'">'
                        +'<input type="file" name="image" value="" id="worker'+id+'Form" onchange="changeImage(event, \'worker'+id+'\', \'worker_'+id+'\')">'
                        +'<input type="text" name="bildbeschriftung" value="'+imageText+'" id="worker'+id+'ImageText">'
                        +'<div class="cardStroke"></div>'
                        +'<textarea name="text">'+text+'</textarea>'
                    +'</div>';
    container.insertAdjacentHTML('beforeend', workerHTML);
}

function addTech(text, image, imageText) {
    var container = document.getElementById('techCardContainer');
    var id = container.childElementCount;
    var split = image.split('/');
    var split = split[split.length-1];
    if (split == 'undefined') {
        image = '';
    }

    var techHTML = '<div class="card personcard">'
                        +'<img src="'+image+'" alt="Image" onclick="document.getElementById(\'tech'+id+'Form\').click()" id="tech'+id+'">'
                        +'<input type="file" name="image" value="" id="tech'+id+'Form" onchange="changeImage(event, \'tech'+id+'\', \'tech_'+id+'\')">'
                        +'<input type="text" name="bildbeschriftung" value="'+imageText+'" id="tech'+id+'ImageText">'
                        +'<div class="cardStroke"></div>'
                        +'<textarea name="text">'+text+'</textarea>'
                    +'</div>';
    container.insertAdjacentHTML('beforeend', techHTML);
}

function changeImage(event, imgId, name) {
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
            imageRequest.send('{"name":"'+name+'","image":"'+base64+'"}');
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

                var content = JSON.parse(JSON.parse(data.data));

                console.log(content);

                document.getElementById('title').innerHTML = thema.name;

                var path = './data/' + thema.leiterEmail + '/';

                document.getElementById('logo').src = path + content.logo.image;
                document.getElementById('logoImageText').value = content.logo.imageText;
                document.getElementById('projektLeiterText').innerText = content.projektleiter.text;
                document.getElementById('projektLeiterImage').src = path + content.projektleiter.image;
                document.getElementById('projektLeiterImageText').value = content.projektleiter.imageText;
                document.getElementById('problemstellung').innerText = content.problemstellung;
                document.getElementById('zielsetzung').innerText = content.zielsetzung;
                document.getElementById('prototypeText').innerText = content.prototype.text;
                document.getElementById('prototypeImage').src = path + content.prototype.image;
                document.getElementById('prototypeImageText').value = content.prototype.imageText;
                document.getElementById('ergebnisse').innerText = content.ergebnisse;

                if (content.mitarbeiter.length > 0) {
                    for (var i = 0; i < content.mitarbeiter.length; i++) {
                        addWorker(content.mitarbeiter[i].text, path + content.mitarbeiter[i].image, content.mitarbeiter[i].imageText);
                    }
                } else {
                    addWorker("", "", "");
                }

                if (content.technologien.length > 0) {
                    for (var i = 0; i < content.technologien.length; i++) {
                        addTech(content.technologien[i].text, path + content.technologien[i].image, content.technologien[i].imageText);
                    }
                } else {
                    addTech("", "", "");
                }

            } else {
                console.log('Error: ' + loginRequest.status); // An error occurred during the request.
            }
        }
    };
    if (getCookie('betreuer') == "yes") {
        document.getElementById('buttons').insertAdjacentHTML('beforeend', '<button type="button" name="button" onclick="confirm()">Confirm</button>');
    }
} else {
    window.location.pathname = "/user/diplom/index.html";
}
