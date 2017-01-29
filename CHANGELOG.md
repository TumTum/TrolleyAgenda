Changelog
---------

- v0.5.1-beta
    Bugfix: Nächsten Monat berechnung war falsch. Weil es vom Relativen Tag ausging. 
    Beispiel: ``29. Jan 2017 + 1 Monat = 1. März 2017`` 
    Es gibt keinen ``29. Februar 2017``. Deshalb wurde der Februar ausgelassen.