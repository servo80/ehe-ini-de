<div class="sidebarDesktop">
  <ol>
    <li class="pointer icon" onclick="$.crmData.fields.add({cn_id});">
      <i class="fa fa-fw fa-square-o"></i>
      <p>{__(61)}</p>
    </li>
  </ol>
</div>

<div class="main">
  <h2>{__(89)}</h2>
  <table class="table striped">
    {foreach($dataset as $value):}
      <tr>
        <td>{$value['f_name']}</td>
        <td>{$value['f_value']}</td>
      </tr>
    {endforeach;}
  </table>
  <h2>{__(3)}</h2>
  <ol class="linked sortable striped">
    {foreach($fields as $f_id => $f_name):}
    <li class="pointer" id="{$f_id}" rel="{cn_id}" name="field">
      <p>{$f_name} <i>[ID: {$f_id}]</i></p>
    </li>
    {endforeach;}
  </ol>
</div>

<input type="hidden" id="cn_id" value="{cn_id}" />

<script>
  $.brandbox.draggable.init();
</script>