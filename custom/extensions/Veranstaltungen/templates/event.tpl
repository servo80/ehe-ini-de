<h2 class="event">{echo $eventData->Veranstaltungsart;}<br />{echo $eventData->Veranstaltungstitel;}</h2>

<div class="event">

  {if(!empty($eventData->Veranstaltungsstartdatum2)):}
  <h3>Termine</h3>
  {else:}
  <h3>Termin</h3>
  {endif;}
  <div>
    {if(!empty($eventData->Veranstaltungsstartdatum2)):}
    <p><strong><i><u>1. Termin</u></i></strong></p>
    {endif;}

    <p><strong>Datum von:</strong> {echo strftime('%A, %d. %B %Y', $eventData->Veranstaltungsstartdatum);}</p>
    {if(strftime('%H:%M', $eventData->Veranstaltungsstartdatum) != "00:00"):}
    <p><strong>Beginn:</strong> {echo strftime('%H:%M Uhr', $eventData->Veranstaltungsstartdatum);}</p>
    {endif;}
    <p><strong>Datum bis:</strong> {echo strftime('%A, %d. %B %Y', $eventData->Veranstaltungsenddatum);}</p>
    {if(strftime('%H:%M', $eventData->Veranstaltungsenddatum) != "00:00"):}
    <p><strong>Ende:</strong> {echo strftime('%H:%M Uhr', $eventData->Veranstaltungsenddatum);}</p>
    {endif;}

    {if(!empty($eventData->Veranstaltungsstartdatum2)):}
    <br />
    <p><strong><i><u>2. Termin</u></i></strong></p>

    <p><strong>Datum von:</strong> {echo strftime('%A, %d. %B %Y', $eventData->Veranstaltungsstartdatum2);}</p>
    {if(strftime('%H:%M', $eventData->Veranstaltungsstartdatum2) != "00:00"):}
    <p><strong>Beginn:</strong> {echo strftime('%H:%M Uhr', $eventData->Veranstaltungsstartdatum2);}</p>
    {endif;}
    <p><strong>Datum bis:</strong> {echo strftime('%A, %d. %B %Y', $eventData->Veranstaltungsenddatum2);}</p>
    {if(strftime('%H:%M', $eventData->Veranstaltungsenddatum2) != "00:00"):}
    <p><strong>Ende:</strong> {echo strftime('%H:%M Uhr', $eventData->Veranstaltungsenddatum2);}</p>
    {endif;}

    {endif;}
  </div>

  <h3>Referenten</h3>
  <div>
    {echo $eventData->Veranstaltungsreferenten;}
  </div>

  <h3>Ort</h3>
  <div>
    {echo $eventData->Veranstaltungsort;}
  </div>

  <h3>Preis</h3>
  <div>
    {echo $eventData->Veranstaltungspreis;}
  </div>

  <h3>Anmeldung bis</h3>
  <div>
    {if(!empty($eventData->Veranstaltungsanmeldefrist)):}
    <p>{echo strftime('%A, %d. %B %Y', $eventData->Veranstaltungsanmeldefrist);}</p>
    {else:}
    <p>steht noch nicht fest</p>
    {endif;}
  </div>

  <h3>Beschreibung</h3>
  <div>
    {echo $eventData->Veranstaltungsbeschreibung;}
  </div>

  <h3>Allgemeine Hinweise</h3>
  <div>
    {echo $eventData->Veranstaltungshinweise;}
  </div>

</div>

<div class="eventImage">
  {if(!empty($eventData->Veranstaltungsflyerbild)):}
  <img src="image/0/{$eventData->Veranstaltungsflyerbild}?w=150&h=500" alt="{$eventData->Veranstaltungstitel}" />
  {endif;}
  {if(!empty($eventData->Veranstaltungsflyer)):}
  <a class="download" href="../download/public/{echo $eventData->Veranstaltungsflyer;}">Download Flyer {echo $eventData->Veranstaltungsart;} {echo strftime('%d.%m.%Y', $eventData->Veranstaltungsstartdatum);}</a>
  {endif;}
</div>

{if(time() < $eventData->Veranstaltungsanmeldefrist && $eventData->Veranstaltungsanmeldelink == ""):}
<a href="{pageRegister}?month={month}&year={year}&eventID={eventID}" class="register">Jetzt anmelden!</a>
{endif;}
{if($eventData->Veranstaltungsanmeldelink != ""):}
<a href="{$eventData->Veranstaltungsanmeldelink}" class="register">Jetzt anmelden!</a>
{endif;}

<a href="{pageRegister}?month={month}&year={year}&eventID={eventID}" class="linkButton">

</a>

<a href="mailto:?subject=Interessanter Link&body=Hallo, ich habe eine interessante Seite gefunden: {pageAbsolute}&eventID={eventID} /" class="linkButton clear">
  <img src="custom/themes/ehe-ini.de/images/mail.png" />
  <span>per E-Mail weiterempfehlen</span>
</a>

<a href="https://www.facebook.com/sharer/sharer.php?u={pageAbsolute}&eventID={eventID}" class="linkButton">
  <img src="custom/themes/ehe-ini.de/images/facebook.png" />
  <span>auf Facebook teilen</span>
</a>

<script>

  $(document).ready(function() {
    $('.event').accordion({
      heightStyle: 'content'
    });
  });

</script>