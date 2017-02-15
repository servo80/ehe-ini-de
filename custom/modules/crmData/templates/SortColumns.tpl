<div class="main">
  <ol class="linked striped">
    <li>
      <h2>{__(100)}</h2>
    </li>
    <ol class="sortable">
    {foreach($fieldIDsAsNames as $fieldID => $fieldName):}
      <li class="pointer icon" name="column" id="{$fieldID}" rel="{$tableID}">
        <i class="fa fa-fw fa-square-o"></i>
        <p>{$fieldName} <i>[ID: {$fieldID}]</i></p>
      </li>
    {endforeach;}
    </ol>
  </ol>
</div>

<input type="hidden" id="tableID" value="{$tableID}" />

<script>
  $.brandbox.draggable.init({
    frame: $('ol.linked'),
    rows: $('ol.linked li:gt(0)')
  });
</script>