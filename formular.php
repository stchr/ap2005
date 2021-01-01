<?php


/******************************************
** HTML-Header etc.
******************************************/

/*********************************************************************
    Auf die Navigation und das animierte Gif wurde verzichtet, da dem
    Nutzer nicht zu viele Klickmöglichkeiten geboten werden sollen.
    So ist die Versuchung, das Ausfüllen des relativ langen 
    Formulares abzubrechen, erheblich geringer.
    Außerdem bekommt man so zusätzlichen Platz, was die Übersicht-
    keit des Formulars fördert.
    Die fehlende Navigation wird durch den Link am Ende der Seite
    wieder kompensiert.
*********************************************************************/

echo '<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Weingut Vincent</title>

<link rel="stylesheet" type="text/css" href="style.css" media="screen, projection" />

</head>
<body>

<a href="#content" class="nodisplay">Direkt zum Inhalt springen</a>

<div id="wrapper-outer">
<div id="wrapper-inner">

  <h1>Weingut Vincent</h1>

  <div id="contentform">

    <h2>Ihre persönliche Weinberatung</h2>
';



/******************************************
** Content and Conditions
******************************************/

/*********************************************************************
    Hier wird geprüft, ob die Pflichtfelder ausgefüllt sind.
    Durch die Funktion trim() werden überflüssige Leerzeichen am
    Anfang und Ende entfernt.
    Ist ein Pflichtfeld nicht ausgefüllt, wird im Array $arr_errormsg
    ein entsprechender Eintrag mit arr_push() hinzugefügt.
    Die E-Mail-Adresse wird mit einem Regulären Ausdruck zusätzlich
    noch auf Gültigkeit überprüft.
*********************************************************************/
$arr_errormsg = array();
// Antwortmedium
if (trim(@$_POST['str_antwortmedium']) == '') array_push($arr_errormsg, "Wie dürfen wir Ihre Anfrage beantworten?");
// Anrede
if (trim(@$_POST['str_anrede']) == '') array_push($arr_errormsg, "Wie sollen wir Sie anreden?");
// Vorname
if (trim(@$_POST['str_vorname']) == '') array_push($arr_errormsg, "Wie ist Ihr Vorname?");
// Nachname
if (trim(@$_POST['str_nachname']) == '') array_push($arr_errormsg, "Wie ist Ihr Nachname?");
// Email (+ Gültigkeitsprüfung per RegEx
if (trim(@$_POST['str_email']) == '') array_push($arr_errormsg, "Wie ist Ihre E-Mail-Adresse?");
if (!eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}$",trim(@$_POST['str_email']))) array_push($arr_errormsg, "Ihre E-Mail-Adresse scheint ungültig zu sein.");

/*********************************************************************
    Hier wird geprüft, ob sich Daten in der Superglobalen $_POST 
    befinden, also das Formular abgeschickt wurde.
    Ist das nicht der Fall, der entsprechende Text ausgegeben.
*********************************************************************/
if (count($_POST) == 0) {
echo '
    <p>Informieren Sie sich über unsere Spitzenweine.</p>

    <p>Hier beantwortet Ihnen unser Herr Thomas Vincent alle Fragen rund um unsere edlen Tropfen.<br />
    Egal ob Aperitif, Essensbegleiter oder Digestiv - bei unserem Wein- und Spirituosenkenner bleibt keine Frage unbeantwortet.</p>
';
} 

/*********************************************************************
    Wurde das Formular abgeschickt -> count($_POST) != 0
    und Fehlermeldungen im Array $arr_errormsg gefunden, 
    wird ein entsprechender Hinweis mit allen Fehler-
    meldungen als Liste ausgegeben.
*********************************************************************/
elseif (count($arr_errormsg) != 0) {
  echo '<p class="error">HINWEIS: Wir benötigen noch folgende Informationen von Ihnen, um das Formular verarbeiten zu können:</p>';
  echo '<ul>';
  for ($i=0; $i < count($arr_errormsg); $i++) {
    echo '<li>'.$arr_errormsg[$i].'</li>';
  }
  echo '</ul>';
  echo '<p>Bitte korrigierten Sie diese Angaben (siehe rote Sternchen):</p>';
} 

/*********************************************************************
    Trifft auch diese Bedingung zu (also ein Formular wurde 
    abgeschickt und keine Fehler gefunden), wird die E-Mail generiert.
    Dabei wird die Mail im HTML-Format an info@vincent-weine.de 
    geschickt.
    Die Variablen werden alle mit der Funktion htmlspecialchars()
    behandelt, um Umlaute und Sonderzeichen in ihre jeweiligen 
    Entities umzuwandeln.
*********************************************************************/
else {
  //Betreff und Titel der Mail
  $str_subject = "Beratungsanfrage";
  //Empfänger
  $str_recipient = "info@vincent-weine.de";
  //Mailgenerierung
  $str_mail  = "<html><head><title>".$str_subject."</title>";
  $str_mail .= "<style type='text/css'><!-- ";
  $str_mail .= " html, body { font-family: 'Verdana', sans-serif; font-size: 11px; line-height: 20px; } ";
  $str_mail .= " .label { font-weight: bold; } ";
  $str_mail .= "--></style></head><body>\r\n";
  $str_mail .= "<span class='label'>Anlass:</span> ".htmlspecialchars($_POST['str_anlass'])."<br />\r\n";
  $str_mail .= "<span class='label'>Anzahl Gäste:</span> ".htmlspecialchars($_POST['str_gaeste'])."<br />\r\n";
  $str_mail .= "<span class='label'>Alter Gäste:</span> ".htmlspecialchars($_POST['str_alter'])."<br />\r\n";
  $str_mail .= "<span class='label'>Geschlecht Gäste:</span> ".htmlspecialchars($_POST['str_geschlecht'])."<br />\r\n";
  $str_mail .= "<span class='label'>Menü:</span> ".htmlspecialchars($_POST['str_menu'])."<br />\r\n";
  $str_mail .= "<span class='label'>Preislage:</span> ".htmlspecialchars($_POST['str_preislage'])."<br />\r\n";
  $str_mail .= "<span class='label'>Bemerkungen:</span> ".htmlspecialchars($_POST['str_bemerkungen'])."<br />\r\n";
  $str_mail .= "<span class='label'>Antwortmedium:</span> ".htmlspecialchars($_POST['str_antwortmedium'])."<br />\r\n";
  $str_mail .= "<span class='label'>Anrede:</span> ".htmlspecialchars($_POST['str_anrede'])."<br />\r\n";
  $str_mail .= "<span class='label'>Vorname:</span> ".htmlspecialchars($_POST['str_vorname'])."<br />\r\n";
  $str_mail .= "<span class='label'>Nachname:</span> ".htmlspecialchars($_POST['str_nachname'])."<br />\r\n";
  $str_mail .= "<span class='label'>Firma:</span> ".htmlspecialchars($_POST['str_firma'])."<br />\r\n";
  $str_mail .= "<span class='label'>Straße / Hausnummer:</span> ".htmlspecialchars($_POST['str_strasse'])." ".htmlspecialchars($_POST['str_hausnummer'])."<br />\r\n";
  $str_mail .= "<span class='label'>Land / PLZ / ORT:</span> ".htmlspecialchars($_POST['str_land'])." ".htmlspecialchars($_POST['num_plz'])." ".htmlspecialchars($_POST['str_ort'])."<br />\r\n";
  $str_mail .= "<span class='label'>E-Mail:</span> ".htmlspecialchars($_POST['str_email'])."<br />\r\n";
  $str_mail .= "<span class='label'>Telefon:</span> ".htmlspecialchars($_POST['num_telefon'])." / ".htmlspecialchars($_POST['num_telefon2'])."<br />\r\n";
  $str_mail .= "<span class='label'>Fax:</span> ".htmlspecialchars($_POST['num_fax'])." / ".htmlspecialchars($_POST['num_fax2'])."<br />\r\n";
  $str_mail .= "</body></html>";
  //Setzen der Mail-Header
  $str_headers  = "MIME-Version: 1.0\r\n";
  $str_headers .= "Content-type: text/html; charset=utf-8\r\n";
  $str_headers .= "To: Thomas Vincent <".$str_recipient.">\r\n";
  $str_headers .= "From: Homepage-Mailer <simon.stuecher@schaefer-shop.de>\r\n";

  //Versenden der Mail
  // Normalerweise sollte diese an die in der Variablen $str_recipient 
  // angegebene Adresse gehen. Zu Testzwecken wurde hier aber die
  // im Formular angegebene Adresse ($_POST['str_email']) verwendet.
  $str_recipient = $_POST['str_email'];
  mail($str_recipient, $str_subject, $str_mail, $str_headers);

  //Bestätigung
  echo '<p>Vielen Dank! <br />Ihre Anfrage wurde verschickt.</p>';
}


/*********************************************************************
   Wenn kein Formular abgeschickt wurde (die Seite also betreten wird) 
   oder ein fehlerhaftes Formular abgeschickt wurde, wird das Formular
   nochmal ausgegeben und die fehlenden Einträge mit einem roten
   Asterisk markiert. enthält das Feld gültige Daten, wird ein graues
   Asterisk ausgegeben, damit man noch weiß, dass es sich um ein 
   Pflichtfeld handelt.
   Außerdem werden alle Daten wieder übernommen und - nachdem sie die 
   Funktion htmlspecialchars() durchlafen haben als Wert des 
   jeweiligen Feldes ausgegeben. Die Radiobuttons werden auch korrekt
   mit einem checked="checked" markiert. Das hat den Vorteil, dass bei 
   einem Fehler nicht alle Felder nochmal ausgefüllt werden müssen.
*********************************************************************/
if ((count($_POST) == 0) OR (count($_POST) != 0 AND count($arr_errormsg) != 0)) {
  echo '
    <form action="'.$PHP_SELF.'" method="post">

    <fieldset>
      <legend>Für eine qualifizierte Beratung benötigen wir folgende Angaben von Ihnen:</legend>
      <ol>
        <li><label for="str_anlass">Zu welchem Anlass suchen Sie einen passenden Wein?</label><br />
          <input type="text" name="str_anlass" id="str_anlass" value="'.htmlspecialchars(@$_POST['str_anlass']).'" size="50" maxlength="50" style="width: 400px;" /></li>
        <li><label for="str_gaeste">Wie viele Gäste erwarten Sie?</label><br />
          <input type="text" name="str_gaeste" id="str_gaeste" value="'.htmlspecialchars(@$_POST['str_gaeste']).'" size="20" maxlength="20" style="width: 400px;" /></li>
        <li><label for="str_alter">Wie alt sind Ihre Gäste?</label><br />
          <input type="text" name="str_alter" id="str_alter" value="'.htmlspecialchars(@$_POST['str_alter']).'" size="20" maxlength="20" style="width: 400px;" /></li>
        <li><label for="str_geschlecht">Erwarten Sie nur Männer/nur Frauen oder handelt es sich um eine gemischte Gesellschaft?</label><br />
          <input type="text" name="str_geschlecht" id="str_geschlecht" value="'.htmlspecialchars(@$_POST['str_geschlecht']).'" size="30" maxlength="30" style="width: 400px;" /></li>
        <li><label for="str_menu">Zu welchem Essen/Menü suchen Sie einen passenden Wein?</label><br />
          <input type="text" name="str_menu" id="str_menu" value="'.htmlspecialchars(@$_POST['str_menu']).'" size="30" maxlength="30" style="width: 400px;" /></li>
        <li><label for="str_preislage">In welcher Preislage dürfen wir Ihnen einen passenden Wein empfehlen?</label><br />
          <input type="text" name="str_preislage" id="str_preislage" value="'.htmlspecialchars(@$_POST['str_preislage']).'" size="20" maxlength="20" style="width: 400px;" /></li>
        <li><label for="str_bemerkungen">Sonstige Bemerkungen</label><br />
          <textarea name="str_bemerkungen" id="str_bemerkungen" cols="50" rows="4" style="width: 400px;">'.htmlspecialchars(@$_POST['str_bemerkungen']).'</textarea></li>
      </ol>
    </fieldset>  

    <fieldset>
        <legend>Wie dürfen wir Ihre Anfrage beantworten? ';
  //Wurde ein Antwortmedium gewählt?
  if (trim(@$_POST['str_antwortmedium']) == '') echo '<span class="error">*</span>'; else echo '*';
  echo '</legend>
        <input type="radio" name="str_antwortmedium" id="antwortemail"';
  //Wurde "E-Mail" ausgewählt?
  if (trim(@$_POST['str_antwortmedium']) == 'E-Mail') echo ' checked="checked"';
  echo ' value="E-Mail" /> <label for="antwortemail">per E-Mail</label>
        <input type="radio" name="str_antwortmedium" id="antwortfax"';
  //Wurde "Fax" ausgewählt?
  if (trim(@$_POST['str_antwortmedium']) == 'Fax') echo ' checked="checked"'; 
  echo ' value="Fax" /> <label for="antwortfax">per Fax</label>
        <input type="radio" name="str_antwortmedium" id="antworttel"';
  //Wurde "Rückruf" ausgewählt?
  if (trim(@$_POST['str_antwortmedium']) == 'Rückruf') echo ' checked="checked"'; 
  echo ' value="Rückruf" /> <label for="antworttel">per Rückruf (Mo-Fr 08:00 - 17:00 Uhr)</label>
    </fieldset>

    <fieldset>
      <legend>Bitte geben Sie entsprechend der Auswahl Ihre Kontaktmöglichkeit an:</legend>
      <table>
        <tr>
          <td>Anrede ';
  //Ist die eine Anrede gewählt?
  if (trim(@$_POST['str_anrede']) == '') echo '<span class="error">*</span>'; else echo '*';
  echo '</td>
          <td><input type="radio" name="str_anrede" id="anredefrau"';
  //Wurde "Frau" ausgewählt?
  if (trim(@$_POST['str_anrede']) == 'Frau') echo ' checked="checked"'; 
  echo ' value="Frau" /> <label for="anredefrau">Frau</label>
          <input type="radio" name="str_anrede" id="anredeherr"';
  //Wurde "Herr" ausgewählt?
  if (trim(@$_POST['str_anrede']) == 'Herr') echo ' checked="checked"'; 
  echo ' value="Herr" /> <label for="anredeherr">Herr</label>
          <input type="radio" name="str_anrede" id="anredefirma"';
  //Wurde "Firma" ausgewählt?
  if (trim(@$_POST['str_anrede']) == 'Firma') echo ' checked="checked"'; 
  echo ' value="Firma" /> <label for="anredefirma">Firma</label></td>
        </tr>
        <tr>
          <td><label for="str_vorname">Vorname ';
  //Wurde der Vorname ausgefüllt?
  if (trim(@$_POST['str_vorname']) == '') echo '<span class="error">*</span>'; else echo '*';
  echo '</label</td>
          <td><input type="text" name="str_vorname"id="str_vorname" value="'.htmlspecialchars(@$_POST['str_vorname']).'" size="30" maxlength="30" style="width: 400px;" /></td>
        </tr>
        <tr>
          <td><label for="str_nachname">Nachname ';
  //Wurde der Nachname ausgefüllt?
  if (trim(@$_POST['str_nachname']) == '') echo '<span class="error">*</span>'; else echo '*';
  echo '</label</td>
          <td><input type="text" name="str_nachname" id="str_nachname" value="'.htmlspecialchars(@$_POST['str_nachname']).'" size="30" maxlength="30" style="width: 400px;" /></td>
        </tr>
        <tr>
          <td><label for="str_firma">Firma</label</td>
          <td><input type="text" name="str_firma" id="str_firma" value="'.htmlspecialchars(@$_POST['str_firma']).'" size="30" maxlength="30" style="width: 400px;" /></td>
        </tr>
        <tr>
          <td><label for="str_strasse">Straße</label> / <label for="str_hausnummer">Hausnr.</label</td>
          <td><input type="text" name="str_strasse" id="str_strasse" value="'.htmlspecialchars(@$_POST['str_strasse']).'" size="20" maxlength="30" style="width: 300px;" /> <input type="text" name="str_hausnummer" id="str_hausnummer" value="'.htmlspecialchars(@$_POST['str_hausnummer']).'" size="3" maxlength="4" style="width: 88px;" /></td>
        </tr>
        <tr>
          <td><label for="str_land">Land</label> / <label for="num_plz">PLZ</label> / <label for="str_ort">Ort</label></td>
          <td><input type="text" name="str_land" id="str_land" value="'.htmlspecialchars(@$_POST['str_land']).'" size="20" maxlength="20" style="width: 117px;" /> - <input type="text" name="num_plz" id="num_plz" value="'.htmlspecialchars(@$_POST['num_plz']).'" size="5" maxlength="5" style="width: 50px;" /> <input type="text" name="str_ort" id="str_ort" value="'.htmlspecialchars(@$_POST['str_ort']).'" size="20" maxlength="30" style="width: 200px;" /></td>
        </tr>
        <tr>
          <td><label for="str_email">E-Mail ';
  //Wurde eine gültige E-Mail-Adresse angegeben?
  if (trim(@$_POST['str_email'] == '' OR !eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}$",trim(@$_POST['str_email'])))) echo '<span class="error">*</span>'; else echo '*';
  echo '</label</td>
          <td><input type="text" name="str_email" id="str_email" value="'.htmlspecialchars(@$_POST['str_email']).'" size="50" maxlength="50" style="width: 400px;" /></td>
        </tr>
        <tr>
          <td><label for="num_telefon">Telefon tagsüber</label</td>
          <td><input type="text" name="num_telefon" id="num_telefon" value="'.htmlspecialchars(@$_POST['num_telefon']).'" size="6" maxlength="6" style="width: 179px;" /> / <input type="text" name="num_telefon2" value="'.htmlspecialchars(@$_POST['num_telefon2']).'" size="10" maxlength="10" style="width: 200px;" /></td>
        </tr>
        <tr>
          <td><label for="num_fax">Fax</label</td>
          <td><input type="text" name="num_fax" id="num_fax" value="'.htmlspecialchars(@$_POST['num_fax']).'" size="6" maxlength="6" style="width: 179px;" /> / <input type="text" name="num_fax2" value="'.htmlspecialchars(@$_POST['num_fax2']).'" size="10" maxlength="10" style="width: 200px;" /></td>
        </tr>
      </table>
    </fieldset>

    <p>*Bitte diese Felder unbedingt ausfüllen</p>

    <input type="submit" class="button" name="submit" value="Formular versenden" /> <input type="reset" class="button" value="Formular zurücksetzten" /> 

    </form>';
}



/******************************************
** HTML-Footer
******************************************/

/*********************************************************************
   Damit keine Sackgasse entsteht, wird ein Link zur Startseite
   ausgegeben.
*********************************************************************/

echo '

    <p><a href="index.html">Zurück zur Startseite</a></p>

  </div>

</div>
</div>

</body>
</html>
';

?>