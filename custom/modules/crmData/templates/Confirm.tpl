{foreach($searchFields as $searchFieldID => $searchFieldValue):}
{if($searchFieldValue != ""):}
<input type="hidden" name="searchFields[{$searchFieldID}]" value="{$searchFieldValue}" />
{endif;}
{endforeach;}


<div class="sidebarDesktop">
  <div class="collapse-container sidebarTile">
    <div class="collapse-header">
      <h3>{__(app5)}</h3>
    </div>
    <div class="collapse in">
      {number} Empfänger gewählt
    </div>
  </div>

</div>

<div class="main stripedFields">

  <fieldset>
    <h2><b>Spoolen erfolgreich</h2>
  </fieldset>

  <p>{number} Mails wurden erfolgreich erzeugt.</p>

</div>
