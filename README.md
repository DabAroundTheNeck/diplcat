# diplcat
Diplomarbeitskatalog by Michael Blank und Leo Felix Katzengruber

Um diplcat zu verwenden muss die URL die auf Index verweißt diese Struktur haben:

/user/diplom/index.html

Um Daten in die Datenbank einzubringen und Passwörter für die User zu generieren muss das Script script.php ausgeführt werden welches im Ordner ./csv liegt. Diese Script verwendet Daten aus beiliegenden csv Datein die die Namen Benutzer.csv und Themen.csv haben müssen.

Die zu verwendenden Login-Adressen und Passwörter werden in der Datei login.csv abgespeichert. Außerdem wird ein File DBFiles.csv generiert welches alle Informationen enthält die in die Datenbank geschriben wurden. Überprüfen sie heir alle Lehrer die eine Anmerkung aufweißen -> Hier wurden zwei mögliche Lehrer für eine Diplomarbeit gefunden.

Nach dem Speichern einer Diplomarbeit auf der Webseite werden alle Daten in ein JSON File geschrieben und gleichzeitig wird ein txt file generiert welches die Daten in einer schöneren Form dargibt, hier werden auch die Filenames der Bilder angemerkt. Die Bilder selbst werden im Images Ordner der jeweiligen Arbeit abgepeichert.
