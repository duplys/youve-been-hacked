<?

/*
		Funktionen fuer die Avatar-Nutzung fuer Hacking-Demos Beta:		
 */

function avatarFile($benutzerID) {
   // gibt den Dateinamen des Avatar-Bilds zurueck
   if( file_exists(AVATARPFAD.$benutzerID.".gif")) { 
     return $benutzerID.".gif";
	}
   elseif( file_exists(AVATARPFAD.$benutzerID.".jpg")) { 
     return $benutzerID.".jpg";
	}
   elseif( file_exists(AVATARPFAD.$benutzerID.".svg")) { 
     return $benutzerID.".svg";
	}

   else {
     return "";
   }
}

?>