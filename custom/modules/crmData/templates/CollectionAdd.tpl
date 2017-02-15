<div class="sidebarDesktop">
  {if($tbl_id != 0):}
    <div class="collapse-container sidebarTile">
      <div class="collapse-header">
        <h3>{__(13)}</h3>
      </div>
      <div class="collapse in">
        {if($sf_id != 0):}
          {select:fields}<br />
        {endif;}
        {select:languages}
        {if($sf_id != 0):}
          <br />
          <span id="q_wrap"></span>
        {endif;}
      </div>
    </div>

    <div class="collapse-container checkAllContainer sidebarTile">
      <div class="collapse-header">
        <h3>{__(110)} (<span id="numberOfActivated"></span>)</h3>
      </div>
      <div class="collapse in">
        <label class="checkbox">
          <input type="checkbox" name="checkAll" id="checkAll" value="0" />
          {__(109)}
        </label>
      </div>
    </div>
  {endif;}

  <ol>
    {foreach($table_groups as $table_group):}
      <li class="pointer icon arrow" onclick="$.crmData.search.open_close_group({$table_group['tblgr_id']});">
        <i class="fa fa-fw fa-caret-{echo $table_group['active'] ? 'down' : 'right'}"></i>
        <p>{$table_group['tblgr_name']}</p>
      </li>
      {foreach($table_group['tables'] as $table):}
        <li class="pointer icon{echo $tbl_id == $table['tbl_id'] ? ' active' : ''}" onclick="$.crmData.search.switch_table({$table['tbl_id']});">
          <i class="fa fa-fw {$table['tbl_icon']}"></i>
          <p>{$table['tbl_name']}</p>
        </li>
      {endforeach;}
    {endforeach;}
  </ol>
</div>

<div class="main fixed niceScrollContainer moduleContentCollectionAdd ctrlA">
  {if(count($rows) != 0):}
  <table class="linked editable nowrap widthb striped">
    <colgroup>
      <col width="20" />
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
            <i class="pointer fa fa-angle-double-up" onclick="$.crmData.search.flip_offset(0);"></i>
            <i class="pointer fa fa-angle-up" onclick="$.crmData.search.flip_offset({echo $offset-$limit});"></i>
          {endif;}
          <p>{$offset+1} - {echo min($max, $offset+$limit)} {__(app39)} {$max}</p>
          {if($offset+$limit < $max):}
            <i class="pointer fa fa-angle-down" onclick="$.crmData.search.flip_offset({echo $offset+$limit});"></i>
            <i class="pointer fa fa-angle-double-down" onclick="$.crmData.search.flip_offset({echo $max-($offset+$limit)});"></i>
          {endif;}
        </th>
      </tr>
      <tr>
        <th class="right"><p class="w20px"></p></th>
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
          <td class="right multiselect" title="ID: {$row['cnv_id']}" rel="multiselect">
            <input type="checkbox" />
          </td>
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

<script>
  $.crmData.search.load({$sf_id});
  $('table.editable tbody tr td').click(function(e) {
    $.crmData.collection_add.click(e, this);
  });

  $.crmData.search.countActivated();
  $.crmData.collection_add.bindCtrlASimplified();
  $.crmData.collection_add.bindCheckAllSimplified();
</script>
