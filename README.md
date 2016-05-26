TrolleyAgenda
=============

Ein Planung Tool um den Trolley Dienst mit Zeugen Jehovas zu verwalten.
Die Verkündiger können sich am Dienst Anmelden und sich wieder Abmelden. Jeder bekommt eine gute Übersicht 
über die Monate.

Vorwort
--------

Dieses Programm ist noch in der Anfangs Phase und für die einfache Planung erstmal gedacht. Verkündiger können sich 
Anmelden und Abmelden. Ein Dienstaufseher kann zustimmen oder Verkündiger hinzufügen und entfernen vom Trolley Dienst.

Demo
----

Eine Demo Version findet man unter [www.trolleyagenda.de](http://www.trolleyagenda.de/).
Es gibt zwei zugänge, einen In der Ansicht eines Verkündiger und eine in der Ansicht des Dienstauseher. Dieser kann
Benutzer hinzufügen und entfernen.

##### Zugangsdaten:

- __Verküngiger__:  
     _Benutzername_: verkuendiger  
     _Passwort_: demoseite  

- __Dienstaufseher__:  
     _Benutzername_: dienstaufseher  
     _Passwort_: demoseite  

Installation
------------

##### Voraussetzungen:

Vorteil wäre es von Terminal kenntisse auf einen Linx Rechner zu haben.

- MySQL Datenbank
   - sollte fertig eingerichtet, mit Username und Passwort
- Webserver
    - apache
    - PHP <5.6
    - SSL Zugang
    - PHP composer muss [Installiert](https://getcomposer.org/download/) werden.
    - [Git](https://git-scm.com)

### Anweisungen

1. Code downloaden

     ``$: git clone https://github.com/TumTum/TrolleyAgenda.git``

2. Apache Document Root auf

    ``TrolleyAgenda/web/``

3. Mit dem [composer](https://getcomposer.org/) die Restlichen PHP Classen hohlen

   ``$: composer.phar install --no-dev``

   Bei diesem Prozess werden Einstellungs Parameter für die Datenbank, Mail und der Trolley Agenda abgefragt.
   Sie werden später unter ``app/config/parameters.yml`` gespeichert.
   Ich denke für die Datenbank/Mail ist es selbst erklärent.

   ##### Erleuterung zur Trolley Agenda Einstellungen

   ###### An Welchen Wochentage geht der Trolley heraus?

   ``trolley_days ([Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday]): _``

   Dort gibt man die Englische Bezeichnung ein. __Achte darauf__ das du mit **'\['**-Klammer anfängst und mit  **'\]'**-Klammer endest.
   z.b. ``[Saturday, Sunday]`` Damit der trolley nur Samstag und Sonntags raus geht.

   ###### Vorausplaung: Wie viele Monate im Voraus sollen im Kalendar angezeit werden?

   ``trolley_month_ahead (3): _``

   Drei Monate Voraus ist Standard. Vom heutigem Monat gezählt.


4. Dienstaufseher Anlegen

    ``$: php bin/console fos:user:create <DEIN_USERNAME> --super-admin``

5. Fertig



RoadMap
-------

Welche Featuers noch gewünscht werden.

- Email benachrichtiung
- Schichtleiter einstellen
- Verkündiger kann sein eigenes Profil bearbeiten

Autor
-----
Tobias Matthaiou <develop@tobimat.eu>
