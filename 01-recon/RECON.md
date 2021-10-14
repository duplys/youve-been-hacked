Cookies:
-------------------------------------------------------------------------------
`PHPSESSID`


Files:
-------------------------------------------------------------------------------
`style.css`


Directories & Paths:
-------------------------------------------------------------------------------
`admin/index.php?sprache=de`              Administration area
`backend/index.php?sprache=de`            Backend

`kontakt.php`                             Contact form
`kontakt.php?datei=mail.txt`              Contact form
`kontakt-html5.php`                       Contact form with email
`empfehle.php`                            Recommend function

`angriffe/index.html`                     Attacks (just a description)

`index.php?sprache=de`                    Starting page

`index.php?plugin=plugin&sprache=de`      Included plugin
`index.php?aktion=plugin2&sprache=de`     Included plugin

URL parameters in index.php:
-------------------------------------------------------------------------------
Select language:
  sprache=de
  sprache=en

Select entry:
  aktion=anzeigen&id=1&sprache=de
  aktion=anzeigen&id=2&sprache=de
  aktion=anzeigen&id=3&sprache=de


Forms:
-------------------------------------------------------------------------------
Search:
```html
<form action="index.php" method="GET"> 
   <input type="hidden" name="sprache" value="de">
   <input type="text" name="nach" value="Suchbegriff" size="20" maxlength="250">
   <input name="aktion" value="suchen" type="submit">
</form>
```

Login:
```html
<form action="index.php" method="POST">  
    <input type="hidden" name="sprache" value="de"><br> 
    Benutzername:<br>  
    <input type="text" name="benutzer" value="gast" size="50" maxlength="100"><br> 
    Passwort:<br> 
    <input type="text" name="passwort" value="gast" size="50" maxlength="100"><br> 
    <input type="submit" name="aktion" value="einloggen"> 
</form>
```

Register:
```html
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
```

Upload avatar:
```html
<form action="index.php" method="post" enctype="multipart/form-data"> 
   <input type="hidden" name="MAX_FILE_SIZE" value="128000"> 
   <input type="file" name="avatarupload"> 
   <input type="submit" name="aktion" value="upload"> 
</form>
```

Create new entry:
```html
<form action="index.php" method="POST"> 
   <input type="hidden" name="sprache" value="de"> 
   <input type="text" name="titel" value="Der Titel" size="80" maxlength="250"> 
   <br><br>  
   <textarea rows="10" cols="80" name="text">Der Text</textarea> 
   <br><br> 
   <input type="submit" name="aktion" value="eintragen"> 
</form>
```

Delete a user (in Admin area):
```html
<form action="index.php" method="POST"> 
    <input type="hidden" name="sprache" value="de"> 
    <input type="hidden" name="bid" value="1"> 
    <input type="submit" name="aktion" value="loeschen"> 
</form> 
```

Turn into user (in Admin area):
```html
<form action="index.php" method="POST"> 
    <input type="hidden" name="sprache" value="de"> 
    <input type="hidden" name="bid" value="1"> 
    <input type="submit" name="aktion" value="wirdBenutzer"> 
</form> 
```

Lock user (in Admin area):
```html
<form action="index.php" method="POST"> 
    <input type="hidden" name="sprache" value="de"> 
    <input type="hidden" name="bid" value="1"> 
    <input type="submit" name="aktion" value="sperren"> 
</form>
```

Create new author (in Admin area):
```html
<form action="index.php" method="POST"> 
    <input type="hidden" name="sprache" value="de">
    Benutzername: <br>
    <input type="text" name="bname" value="Benutzername" size="20" maxlength="250"> <br>
    Realname: <br>
    <input type="text" name="rname" value="Realname" size="20" maxlength="250"> <br>
    Passwort: <br>
    <input type="text" name="pass" value="Passwort" size="20" maxlength="250"> <br>
    <input type="submit" name="aktion" value="eintragen">
</form>
```

Misc:
-------------------------------------------------------------------------------
In the individual entries (posts), there is a comment `<!-- ID: 1 -->`, `<!-- ID: 2 -->`, etc.


Parameters     Known values
-------------------------------------------------------------------------------
aktion            `anzeigen` (with `id` & `sprache`)
aktion            `einloggen` (with `user` & `password` & `sprache`)
aktion            `registrieren` (with `real` & `benutzer` & `password` & `sprache`)
aktion            `suchen` (with `nach` & `sprache`)
aktion            `ausloggen`
aktion            `plugin2` (with `sprache=de`)
aktion            `einloggen` (with `benutzer`, `passwort`, `sprache`)
aktion            `upload`
aktion            `loeschen`
aktion            `wirdBenutzer`
aktion            `wirdAutor`
aktion            `sperren`
benutzer          String, maxlength=100 (with `aktion=einloggen | registrieren`)
id                Number, 1 - 3 (with `aktion=anzeigen`)
nach              String, maxlength=250 (with `aktion=suchen`)
passwort          String, maxlength=100 (with `aktion=einloggen | registrieren`)
real              String, maxlength=100 (with `aktion=registrieren`)
MAX_FILE_SIZE     A constant 128000 (with `aktion=upload`)
sprache           `de`
sprache           `en`
plugin            `plugin` (with `sprache=de`)



Actions and Special Cases
-------------------------------------------------------------------------------
suchen         the value of the parameter `nach` appears on the web page
anzeigen       in case of a wrong ID, no entries (or warnings) appear in the source code and on the web page
anzeigen       `id`=<letter> => SQL error: 1054: Unknown column 'a' in 'where clause'
anzeigen       `id`=' => SQL error: 1064: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''' at line 1
einloggen      contradictory statements: "Sie sind nicht angemeldet!" and "Anmeldung als gast erfolgreich!"
einloggen      the user name from the logon form is potentially used in the web page output (i.e., used to generate the html code)
einloggen      identical error message with correct user name/wrong password and wrong user name/correct password
ausloggen      potentially no protection against CSRF
registrieren   trying to register with an existing user name (gast) => SQL error: 1062: Duplicate entry 'gast' for key 'Benutzer_Name'
`upload`       are only GIF and JPEG files allowed?       


SQL Database
-------------------------------------------------------------------------------
Table          'Benutzer_Name'
