<div class="sidebarDesktop">
  {if($type == 'children'):}
    <div class="collapse-container sidebarTile checkAllContainer">
      <div class="collapse-header">
        <h3>{__(110)}</h3>
      </div>
      <div class="collapse in">
        <label class="checkbox" onclick="$.crmData.relations.checkAll();">
          <input type="checkbox" name="checkAll" id="checkAll" value="0" />
          {__(109)}
        </label>
      </div>
    </div>
  {endif;}

  <ol>
    {foreach($tables as $table):}
      <li class="pointer icon{echo $rtbl_id == $table['tbl_id'] ? ' active' : ''}" onclick="$.crmData.relations.switch_table({$table['tbl_id']}, '{$type}', '{$cn_id}');">
        <i class="fa fa-fw {$table['tbl_icon']}"></i>
        <p data-tableID="{$table['tbl_id']}" data-tableType="{$table['tbl_type']}">{$table['tbl_name']}</p>
      </li>
    {endforeach;}
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
  <h2>{h2}</h2>
  <div class="overflowList">
    <ol class="sortable striped icon">
      {foreach($relations as $cr_id => $relation):}
        <li class="pointer {echo $relation['thumb'] != '' ? 'thumb' : ''}" id="{$cr_id}" name="{$type}" rel="{$cn_id}-{$relation['cn_id']}">
          {if($type == 'children'):}
            <input type="checkbox" class="checkbox" value="{$cr_id}" />
          {else:}
            <i class="fa fa-arrow-up"></i>
          {endif;}

          {if($relation['thumb'] != ''):}
            <img src="{$relation['thumb']}" />
          {endif;}
          <p class="i1">
            <b>{$d}</b> {$relation['identification']} <i>[ID: {$relation['cn_id']}]</i>
          </p>
        </li>
      {endforeach;}
    </ol>
  </div>
</div>

<input type="hidden" id="type" value="{$type}" />
<input type="hidden" id="rtbl_id" value="{$rtbl_id}" />
<input type="hidden" id="tableID" value="{$tableID}" />
<input type="hidden" id="cn_id" value="{$cn_id}" />

<script>
  $('body').on('keydown', function(event) {
    if(event.ctrlKey && event.keyCode == 65) {
      event.preventDefault();
      {if($type == 'children'):}
        $.crmData.relations.checkAll(true);
      {endif;}
    }
  });

  if($.brandbox.responsive.isMobileDevice == false)
    $.crmData.relations.init("{$activeRows}", "{$type}");
</script>