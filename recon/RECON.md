Cookies:
--------
PHPSESSID


Files:
------
style.css


Directories & Paths:
--------------------
admin/index.php?sprache=de        Administration area
backend/index.php?sprache=de      Backend

kontakt.php?datei=mail.txt        Contact form
kontakt-html5.php                 Contact form with email
empfehle.php                      Recommend function

angriffe/index.html               Attacks (just a description)


URL parameters in index.php:
----------------------------
Select language:
  sprache=de
  sprache=en

Select entry:
  aktion=anzeigen&id=1&sprache=de
  aktion=anzeigen&id=2&sprache=de
  aktion=anzeigen&id=3&sprache=de


Forms:
------
Search:
<form action="index.php" method="GET"> 
   <input type="hidden" name="sprache" value="de">
   <input type="text" name="nach" value="Suchbegriff" size="20" maxlength="250">
   <input name="aktion" value="suchen" type="submit">
</form>

Login:
<form action="index.php" method="POST">  
    <input type="hidden" name="sprache" value="de"><br> 
    Benutzername:<br>  
    <input type="text" name="benutzer" value="gast" size="50" maxlength="100"><br> 
    Passwort:<br> 
    <input type="text" name="passwort" value="gast" size="50" maxlength="100"><br> 
    <input type="submit" name="aktion" value="einloggen"> 
</form>

Register:
<form action="index.php" method="POST"> 
    <input type="hidden" name="sprache" value="de">     
    Realname:<br> 
    <input type="text" name="real" value="Realname" size="50" maxlength="100"><br>
    Benutzername:<br> 
    <input type="text" name="benutzer" value="Benutzername" size="50" maxlength="100"><br>
    Passwort:<br>
    <input type="text" name="passwort" value="Passwort" size="50" maxlength="100"><br>
    <input type="submit" name="aktion" value="registrieren">
</form>


Misc:
-----
In the individual entries (posts), there is a comment `<!-- ID: 1 -->`, `<!-- ID: 2 -->`, etc.


Parameters     Known values
---------------------------
aktion         anzeigen (with `id` & `sprache`)
aktion         einloggen (with `user` & `password` & `sprache`)
aktion         registrieren (with `real` & `benutzer` & `password` & `sprache`)
aktion         suchen (with `nach` & `sprache`)
benutzer       String, maxlength=100 (with `aktion=einloggen | registrieren`)
id             Number, 1 - 3 (with `aktion=anzeigen`)
nach           String, maxlength=250 (with `aktion=suchen`)
passwort       String, maxlength=100 (with `aktion=einloggen | registrieren`)
real           String, maxlength=100 (with `aktion=registrieren`)
sprache        `de`
sprache        `en`