<div class="register">

  {if(time() > $eventData->Veranstaltungsanmeldefrist):}

  <h1>Die Anmeldefrist für diese Veranstaltung ist leider verstrichen.</h1>

  {elseif($sent):}

  <h1>Vielen Dank für Ihre Anmeldung.</h1>
  <p>
    Sie erhalten demnächst eine E-Mail-Bestätigung mit Ihren Anmeldedaten.<br />
    Bei Fragen wenden Sie sich jederzeit gerne an das <a href="mailto:info@ehe-initiative.de">Büro der Ehe-Initiative</a>.<br />
    Wir freuen uns, Sie bei unserer Veranstaltung bald persönlich begrüßen zu dürfen.
  </p>

  {else:}

  <h1>Anmeldeformular</h1>
  <h2>{echo $eventData->Veranstaltungsart;}</h2>
  <h3>Thema: <span>{echo $eventData->Veranstaltungstitel;}</span></h3>
  <h3>Datum von: <span>{echo utf8_encode(strftime('%A, %d. %B %Y', $eventData->Veranstaltungsstartdatum));}</span></h3>
  <h3>Datum bis: <span>{echo utf8_encode(strftime('%A, %d. %B %Y', $eventData->Veranstaltungsenddatum));}</span></h3>

  <form method="post">
    <input type="hidden" name="eventID" value="{eventID}" />
    <input type="hidden" name="month" value="{month}" />
    <input type="hidden" name="year" value="{year}" />
    <input type="hidden" name="exec" value="register" />

    <fieldset class="clear">
      <label>{$salutation_label}:*</label>
      <input type="text" name="salutation" value="{$salutation}" />
    </fieldset>

    <fieldset class="clear">
      <label>{$firstname_label}:*</label>
      <input type="text" name="firstname" value="{$firstname}" />
    </fieldset>

    <fieldset>
      <label>{$lastname_label}:*</label>
      <input type="text" name="lastname" value="{$lastname}" />
    </fieldset>

    <fieldset>
      <label>{$birthdate_label}:*</label>
      <input type="text" name="birthdate" value="{$birthdate}" class="datepicker" />
    </fieldset>

    <fieldset class="clear">
      <label>{$street_label}:*</label>
      <input type="text" name="street" value="{$street}" />
    </fieldset>

    <fieldset>
      <label>{$city_label}:*</label>
      <input type="text" name="city" value="{$city}" class="wide" />
    </fieldset>

    <fieldset class="clear">
      <label>{$fon_label}:*</label>
      <input type="text" name="fon" value="{$fon}" />
    </fieldset>

    <fieldset>
      <label>{$email_label}:*</label>
      <input type="text" name="email" value="{$email}" class="wide" />
      <p>Hinweis: An diese E-Mail-Adresse wird automatisch eine Anmeldebestätigung versendet.</p>
    </fieldset>

    <fieldset class="clear">
      <label>{$salutation_partner_label}:*</label>
      <input type="text" name="salutation_partner" value="{$salutation_partner}" />
    </fieldset>

    <fieldset class="clear">
      <label>{$firstname_partner_label}:*</label>
      <input type="text" name="firstname_partner" value="{$firstname_partner}" />
    </fieldset>

    <fieldset>
      <label>{$lastname_partner_label}:*</label>
      <input type="text" name="lastname_partner" value="{$lastname_partner}" />
    </fieldset>

    <fieldset>
      <label>{$birthdate_partner_label}:*</label>
      <input type="text" name="birthdate_partner" value="{$birthdate_partner}" class="datepicker" />
    </fieldset>

    <fieldset class="clear">
      <label>{$weddingdate_label}:</label>
      <input type="text" name="weddingdate" value="{$weddingdate}" class="datepicker" />
    </fieldset>

    <fieldset>
      <label>{$engagementdate_label}:</label>
      <input type="text" name="engagementdate" value="{$engagementdate}" class="datepicker" />
    </fieldset>

    <p>
      Mit unserer Anmeldung melden wir uns auch verbindlich bei dem jeweiligen Tagungsort an (Vollpension).
      Bitte bucht für uns folgende Unterbringung:
    </p>

    {if($eventData->Veranstaltungsart == 'Ehe-Intensiv'):}

    <fieldset class="wide clear">
      <input type="radio" name="room" value="1"{if($room == 1):} checked="checked"{endif;} />
      <label>Doppelzimmer Variante 1<br />mit Waschbecken auf dem Zimmer (Dusche/WC auf der Etage)</label>
    </fieldset>

    <fieldset class="wide">
      <input type="radio" name="room" value="2"{if($room == 2):} checked="checked"{endif;} />
      <label>Doppelzimmer Variante 2<br />mit Waschbecken, Dusche, WC<br />auf dem Zimmer</label>
    </fieldset>

    {endif;}

    <fieldset class="clear">
      <label>{$remarks_label}:</label>
      <textarea name="remarks">{$remarks}</textarea>
    </fieldset>

    <fieldset class="clear">
      <input type="submit" class="register" value="Jetzt verbindlich anmelden">
    </fieldset>

  </form>
  {endif;}

</div>

<script>
  $(document).ready(function() {
    var mandatory = eval('{$mandatory}');
    for(c = 0; c < mandatory.length; c++) {
      $('input[name="'+mandatory[c]+'"], textarea[name="'+mandatory[c]+'"]').css('border', '1px solid red');
      $('input[name="'+mandatory[c]+'"], textarea[name="'+mandatory[c]+'"]').next('label').css('color', 'red');
    }
    $('.datepicker').datepicker({
      showButtonPanel : true,
      altField : "#datepicker_input",
      dateFormat : "dd.mm.yy"
    }, $.datepicker.regional['de']);
  });
</script>