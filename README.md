# diplcat
Diplomarbeitskatalog by Michael Blank und Leo Felix Katzengruber

Um diplcat zu verwenden muss die URL die auf Index verweißt diese Struktur haben:

/user/diplom/index.html

Zusätzlich muss ein Ornder namens config erstellt werden der ein file names config.ini enthält erstellt werden.
In dieser Datei wird die Datenbank connection kongfiguriert, hier ein Beispiel:

;This is the Config File
[dbConfig]
dbHost = "localhost"
dbName = "diplomkatalog"
dbUser = "root"
dbPass = ""



Um Daten in die Datenbank einzubringen und Passwörter für die User zu generieren muss das Script script.php ausgeführt werden welches im Ordner ./csv liegt. Diese Script verwendet Daten aus beiliegenden csv Datein die die Namen Benutzer.csv und Themen.csv haben müssen. Die Passwort erstellung ist normal ein 10 Zeichen langer String mit Random Buchstaben und Zahlen, dies kann im script.php verändert werden.

Die zu verwendenden Login-Adressen und Passwörter werden in der Datei login.csv abgespeichert. Außerdem wird ein File DBFiles.csv generiert welches alle Informationen enthält die in die Datenbank geschriben wurden. Überprüfen sie heir alle Lehrer die eine Anmerkung aufweißen -> Hier wurden zwei mögliche Lehrer für eine Diplomarbeit gefunden.

Nach dem Speichern einer Diplomarbeit auf der Webseite werden alle Daten in ein JSON File geschrieben und gleichzeitig wird ein txt file generiert welches die Daten in einer schöneren Form dargibt, hier werden auch die Filenames der Bilder angemerkt. Die Bilder selbst werden im Images Ordner der jeweiligen Arbeit abgepeichert.

