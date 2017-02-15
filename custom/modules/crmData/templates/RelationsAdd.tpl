<div class="sidebarDesktop">
  {if($tbl_id != 0):}
    <div class="collapse-container sidebarTile">
      <div class="collapse-header">
        <h3>{__(13)}</h3>
      </div>
      <div class="collapse in">
        {if($sf_id != 0):}
          {select:fields}
        {endif;}
        {select:languages}
        {if($sf_id != 0):}
          <span id="q_wrap"></span>
        {endif;}
      </div>
    </div>
  {endif;}
</div>

<div class="main fixed">
  {if(count($rows) != 0):}
  <table class="linked striped editable nowrap width">
    <colgroup>
      <col width="20" />
      {foreach($fields as $f_id => $f_name):}
        <col width="*" />
      {endforeach;}
    </colgroup>
    <thead>
      <tr>
        <th colspan="{count($fields)+2}" class="navigate">
          <p>{__(app69)}:</p> <input type="text" name="limit" id="limit" value="{$limit}" />
          <p class="border-left">{__(app43)}:</p> <input type="text" name="page" id="page" value="{echo ceil($offset/$max)+1}" /> <p class="border-right">{__(app39)} {ceil($max/$limit)}</p>
          {if($offset != 0):}
            <i class="fa fa-fw fa-angle-double-up pointer" onclick="$.crmData.search.flip_offset(0);"></i>
            <i class="fa fa-fw fa-angle-up pointer" onclick="$.crmData.search.flip_offset({echo $offset-$limit});"></i>
          {endif;}
          <p>{$offset+1} - {echo min($max, $offset+$limit)} {__(app39)} {$max}</p>
          {if($offset+$limit < $max):}
            <i class="fa fa-fw fa-angle-down pointer" onclick="$.crmData.search.flip_offset({echo $offset+$limit});"></i>
            <i class="fa fa-fw fa-angle-double-down pointer" onclick="$.crmData.search.flip_offset({echo $max - ($offset+$limit)});"></i>
          {endif;}
        </th>
      </tr>
      <tr>
        <th ></th>
        <th class="right"><p class="w20px">#</p></th>
        {foreach($fields as $f_id => $f_name):}
          <th class="pointer" onclick="$.crmData.search.switch_order({$f_id}, '{echo $odirection == 'asc' ? 'desc' : 'asc';}');">
            {$f_name}
            {if($f_id == $of_id):}
              <i class="fa fa-fw fa-caret-{echo $odirection == 'asc' ? 'down' : 'up';} sort_{$odirection}"></i>
            {endif;}
          </th>
        {endforeach;}
      </tr>
    </thead>
    <tbody>
      {foreach($rows as $row):}
        <tr class="pointer r{echo ($c++ % 2);}" name="relations_add" id="{$row['cnv_id']}">
          <td class="checkboxColumn"><input class="checkbox" type="checkbox" value="{$row['cnv_id']}" /></td>
          <td class="right" title="ID: {$row['cnv_id']}">{$c}</td>
          {foreach($fields as $f_id => $f_name):}
            <td rel="{$f_id}">{$row['cnv_'.$f_id]}</td>
          {endforeach;}
        </tr>
      {endforeach;}
    </tbody>
  </table>
  {endif;}
</div>

<input type="hidden" id="tableID" value="{$relationTableID}" />

<script>
  $.crmData.search.load({$sf_id});
  $('table.editable tbody tr td').click(function(e) {
    $.crmData.relations_add.click(e, this);
  });

  $('body').on('keydown', function(event) {
    if(event.ctrlKey && event.keyCode == 65) {
      event.preventDefault();
      $.crmData.relations.checkAll(true);
    }
  });
</script>