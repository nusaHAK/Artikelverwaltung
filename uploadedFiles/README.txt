Dieser Ordner (uploadedFiles) ist ein Ordner, der am Webserver liegt und alle Abbildungen zu den Artikeln in der Datenbank speichert. 
Sobald ein neuer Eintrag in der DB gespeichert wird, wird das passende Foto dazu hier abgelegt. Wird ein Artikel aus der DB gelöscht, wird das Foto auch
aus diesem Ordner wieder entfernt.

Achtung!
Jedes Bild im uploadedFiles-Ordner gehört genau zu einem Eintrag in der Datenbank und wird gelöscht, wenn der betreffende Artikel aus der DB gelöscht wird. 
Hast Du das Bild für mehrere Artikel verwendet, dann fehlt das Bild bei allen anderen Einträgen in der DB!
Deshalb muss es je Artikel genau eine Abbildung geben. 

In realen Projekten kann ein Symbolfoto natürlich für mehrere Artikel verwendet werden. Es wird dann allerdings nicht vom Server gelöscht, wenn der Artikel aus der Datenbank gelöscht wird.
Das wäre auch hier möglich - Du kannst gerne die betreffenden Codezeilen abändern ;)