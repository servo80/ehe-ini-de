{foreach($searchFields as $searchFieldID => $searchFieldValue):}
{if($searchFieldValue != ""):}
<input type="hidden" name="searchFields[{$searchFieldID}]" value="{$searchFieldValue}" />
{endif;}
{endforeach;}


<div class="sidebarDesktop">
  <div class="collapse-container sidebarTile">
    <div class="collapse-header">
      <h3>Newsletter-Statistik</h3>
    </div>
    <br />
    <input type="button" value="zurück" onclick="$.brandbox.get($.brandbox.mod,'Index');">
  </div>

</div>

<div class="main stripedFields">

  <div class="fixed widerSidebar niceScrollContainer moduleContentIndex ctrlA" tabindex="5009" style="overflow: hidden; outline: none;">
    <table class="linked striped bordered editable nowrap width">
      <colgroup>
        <col width="*">
        <col width="*">
        <col width="*">
        <col width="*">
      </colgroup>
      <thead>
        <tr>
          <th class="pointer">
            Datum
          </th>
          <th class="center"><p>Betreff</p></th>
          <th class="pointer" onclick="$.crmData.search.switch_order(21, 'asc');">Text </th>
          <th class="pointer" onclick="$.crmData.search.switch_order(13, 'asc');">Gelesen</th>
        </tr>
      </thead>
      <tbody>
      {foreach($newsletters as $newsletter):}
        <tr class="pointer" name="content" id="1390">
          <td valign="top">{echo strftime('%d.%m.%Y %H:%M Uhr', $newsletter['timestamp']);}</td>
          <td valign="top" class="center" title="Friedrich Erhardt">{$newsletter['subject']}</td>
          <td>{$newsletter['text']}</td>
          <td  valign="top">{$newsletter['reads']}</td>
        </tr>
      {endforeach;}
      </tbody>
    </table>
  </div>

</div>