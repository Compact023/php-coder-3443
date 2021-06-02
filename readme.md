# Ablauf für Projekt

## ER-Diagramm
### Erstellen

1. Tabellen nach Vorgabe erstellen -> Notiz mitführen warum welcher Table erstellt wurde (Beispiel Dokumentation Kino &#8595;&#8595;&#8595;)
1. Nach jeder Tabelle (wenn möglich) die Beziehung zur zuletzt erstellten Tabelle festlegen um bei m/n Beziehungen die möglichkeit der Weiterverwendung für eine zukünftig benötigte Tabelle festzustellen
1. Foreign Keys bestimmen (Achtung Update und Delete beziehen sich auf die Ids der bezogenen Tables)
1. Dokumentation der Begründung für die Foreign Keys (Format: Tabelle wie Excel, CSV, o.ä)
<br></br>

### SQL-Export

1. Edit > Preferences > Modelling(Dropdown) > MySQL > Model > <strong>Default Target MySQL Version = 5.7</strong>
1. File > Export > Forward Engineer > Next > Next > Copy to Clipboard
1. MySQL Workbecnch Home > Connect to localhost > Query öffnen (wenn nicht bereits geöffnet) > SQL Export (Clipboard) einfügen > SQL Statement auführen (Blitz Icon)
<br></br>

### Datenbank befüllen
1. ER-Digramm - Die Table von aussen nach innen in Datenbank befüllen (Besipiel berücksichtigen auf Angaben für die Befüllung)
<br></br>
<br></br>

# Dokumentation Beispiel Projekt Kino
## Begründungen
### Tabellen
|Table      |Angabe(Punkt)              |Begründung                                                                                                                                        |
|-------------|---------------------------|--------------------------------------------------------------------------------------------------------------------------------------------------|
|raueme       | 1                         |                                                                                                                                                  |
|filme        | 2(Beziehung); 3(Attribute)|                                                                                                                                                  |
|sprachen     | 3                         | eigener Table um zukünftige Erweiterungen zu ermöglichen                                                                                         |
|genres       | 3; 4(eigener Table)       |                                                                                                                                                  |
|kunden       | 5; 6(Attribute)           |                                                                                                                                                  |
|vorstellungen| 5(Beziehung lt. Angabe)   | Zwischentable von filme und raueme - aufgrund m/n Beziehung/ vorstellung_id angelegt und als Primary Key verwendet - da ein Film mehrfach in einem Raum abgespielt werden kann|
|tickets      | 5(Beziehung lt. Angabe)   | Zwischentable von vorstellungen und kunden - Kunde kann mehrere Tickets einer Vorstellung buchen                                                 |
<br></br>

### Foreign Keys
|Foreign Key name       |betroffene Id |ON UPDATE|GRUND                                                                                              |ON DELETE|GRUND2                                                          |
|-----------------------|--------------|---------|---------------------------------------------------------------------------------------------------|---------|----------------------------------------------------------------|
|fk_filme_sprachen1     |sprache_id    |RESTRICT |Eintrag für zugewiesene Sprache muss überprüft werden                                              |RESTRICT |Eintrag für zugewiesene Sprache muss geändert werden            |
|fk_filme_genres1       |genre_id      |RESTRICT |Eintrag für zugewiesenes Genre muss überprüft werden                                               |RESTRICT |Eintrag für zugewiesenes Genre muss geändert werden             |
|fk_tickets_kunden1     |kunde_id      |CASCADE  |Neue Kunden Id wird übernommen/ Für einfache Administration/ unwahrscheinliches Szenario           |RESTRICT |Ticket muss annuliert werden                                    |
|fk_tickets_vorstellung1|vorstellung_id|CASCADE  |Neue Vorstellung Id wird übernommen/ Für einfache Administration/ unwahrscheinliches Szenario      |RESTRICT |Ticket muss annuliert werden                                    |
|fk_vorstellung_filme1  |film_id       |RESTRICT |Vorstellung und weiter Abhängigkeiten müssen neu angelegt werden                                   |RESTRICT |Vorstellung und weiter Abhängigkeiten müssen neu angelegt werden|
|fk_vorstellung_raeume1 |raum_id       |CASCADE  |Raum für Vorstellung kann geändert werden/ Für einfache Administration/ unwahrscheinliches Szenario|RESTRICT |Neuer Raum muss der Vorstellung zugewiesen werden                            |
