function save() {
    var title = document.getElementById('title').innerHTML;
    var projektleiter = document.getElementById('projektLeiterText').value;
    var mitarbeiter = [];
    var allWorkers = document.getElementById('workerCardContainer').children;
    for (var i = 0; i < allWorkers.length; i++) {
        mitarbeiter[i] = allWorkers[i].children[3].value;
    }

    var problemstellung = document.getElementById('problemstellung').value;
    var zielsetzung = document.getElementById('zielsetzung').value;

    var technologien = [];
    var allTech = document.getElementById('techCardContainer').children;
    for (var i = 0; i < allTech.length; i++) {
        technologien[i] = allTech[i].children[3].value;
    }

    var prototype = document.getElementById('prototypeText').value;
    var ergebnisse = document.getElementById('ergebnisse').value;

    var saveRequest = new XMLHttpRequest();
    saveRequest.open('POST', './php/save.php');
    saveRequest.send('{"titel":"'+title
            +'","projektleiter":"'+projektleiter
            +'","mitarbeiter":"'+mitarbeiter
            +'","problemstellung":"'+problemstellung
            +'","zielsetzung":"'+zielsetzung
            +'","technologien":"'+technologien
            +'","prototype":"'+prototype
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


function addWorker(text, image) {
    var container = document.getElementById('workerCardContainer');
    var id = container.childElementCount;

    var workerHTML = '<div class="card personcard">'
                        +'<img src="'+image+'" alt="Image" onclick="document.getElementById(\'worker'+id+'Form\').click()" id="worker'+id+'">'
                        +'<input type="file" name="image" value="" id="worker'+id+'Form" onchange="changeImage(event, \'worker'+id+'\', \'worker_'+id+'\')">'
                        +'<div class="cardStroke"></div>'
                        +'<input type="text" name="" value="'+text+'">'
                    +'</div>';
    container.insertAdjacentHTML('beforeend', workerHTML);
}

function addTech(text, image) {
    var container = document.getElementById('techCardContainer');
    var id = container.childElementCount;


    var techHTML = '<div class="card personcard">'
                        +'<img src="'+image+'" alt="Image" onclick="document.getElementById(\'tech'+id+'Form\').click()" id="tech'+id+'">'
                        +'<input type="file" name="image" value="" id="tech'+id+'Form" onchange="changeImage(event, \'tech'+id+'\', \'tech_'+id+'\')">'
                        +'<div class="cardStroke"></div>'
                        +'<input type="text" name="" value="'+text+'">'
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

                document.getElementById('logo').src = path + content.logo;
                document.getElementById('projektLeiterText').value = content.projektleiter.text;
                document.getElementById('projektLeiterImage').src = path + content.projektleiter.image;
                document.getElementById('problemstellung').value = content.problemstellung;
                document.getElementById('zielsetzung').value = content.zielsetzung;
                document.getElementById('prototypeText').value = content.prototype.text;
                document.getElementById('prototypeImage').src = path + content.prototype.image;
                document.getElementById('ergebnisse').value = content.ergebnisse;

                if (content.mitarbeiter.length > 0) {
                    for (var i = 0; i < content.mitarbeiter.length; i++) {
                        addWorker(content.mitarbeiter[i].text, path + content.mitarbeiter[i].image);
                    }
                } else {
                    addWorker("", "");
                }

                if (content.technologien.length > 0) {
                    for (var i = 0; i < content.technologien.length; i++) {
                        addTech(content.technologien[i].text, path + content.technologien[i].image);
                    }
                } else {
                    addTech("", "");
                }

            } else {
                console.log('Error: ' + loginRequest.status); // An error occurred during the request.
            }
        }
    };
} else {
    window.location.pathname = "/user/diplom/index.html";
}
