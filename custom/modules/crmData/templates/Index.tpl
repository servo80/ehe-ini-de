  <div class="sidebarDesktop widerSidebar">

  <div class="toggleWiderSidebar" onclick="$.crmData.toggleWiderSidebar(this);">
    <i class="fa fa-caret-right"></i>
  </div>

  {if($tbl_id != 0):}
    <div class="collapse-container contentFilter searchTile sidebarTile">

      <div class="collapse-header">
        <h3>{__(13)} ({$max})</h3>
      </div>
      <div class="collapse in search">
        {foreach($searchFields as $searchFieldID => $searchFieldName):}
        <label style="margin-bottom:0;">{$searchFieldName}</label>
        <input class="q" style="margin-bottom:5px;" type="text" name="searchFields[{$searchFieldID}]" value="{$activeSearchFields[$searchFieldID]}" />
        {endforeach;}
        <!--
        {select:languages}
        {if($sf_id != 0):}
          <span id="q_wrap"></span>
        {endif;}
        -->
      </div>

    </div>
    
    <!-- PDF-Einstellungen
    <div class="collapse-container contentFilter sidebarTile">
    
      <div class="collapse-header">
        <h3>{__(105)}</h3>
      </div>
      <div class="collapse">
        {select:pdf:languages}
        <h3>{__(106)}</h3>
        <input type="text" name="page_number" id="page_number" value="1" />
        <input type="checkbox" name="low" id="low" value="1" /> {__(107)}
      </div>
    </div>
     -->
    <div class="collapse-container checkAllContainer sidebarTile">

      <div class="collapse-header">
        <h3>{__(110)} (<span id="numberOfActivated"></span>)</h3>
      </div>
      <div class="collapse in">
        <label class="checkbox" onclick="$.crmData.search.checkAll();">
          <input type="checkbox" name="checkAll" id="checkAll" value="0" />
          {__(109)}
        </label>
        <input type="button" value="Mailing starten" onclick="$.crmData.search.startMailing();">
      </div>

    </div>
  {endif;}
  
</div>

<div class="main fixed widerSidebar niceScrollContainer moduleContentIndex ctrlA">
  <table class="linked striped bordered editable nowrap width">
    <colgroup>
      <col width="20" />
      <col width="20" />
      <col width="20" />
      <col width="20" />
      {if($table_type == 'user'):}
        <col width="20" />
      {endif;}
      <col width="*" />
      <col width="*" />
      {foreach($fields as $f_id => $f_name):}
        <col width="*" />
      {endforeach;}
    </colgroup>
    <thead>
      <tr>
        <th colspan="{echo count($fields)+($table_type == 'user' ? 7 : 6);}" class="navigate">
          <p>{__(app69)}:</p> <input type="text" name="limit" id="limit" value="{$limit}" />
          <p class="border-left">{__(app43)}:</p> <input type="text" name="page" id="page" value="{echo ceil($offset/$limit)+1}" /> <p class="border-right">{__(app39)} {ceil($max/$limit)}</p>
          {if($offset != 0):}
            <i class="fa fa-fw fa-angle-double-up pointer" onclick="$.crmData.search.flip_offset({echo 0});"></i>
            <i class="fa fa-fw fa-angle-up pointer" onclick="$.crmData.search.flip_offset({echo $offset-$limit});"></i>
          {endif;}
          <p>{$offset+1} - {echo min($max, $offset+$limit)} {__(app39)} {$max}</p>
          {if($offset+$limit < $max):}
            <i class="fa fa-fw fa-angle-down pointer" onclick="$.crmData.search.flip_offset({echo $offset+$limit});"></i>
            <i class="fa fa-fw fa-angle-double-down pointer" onclick="$.crmData.search.flip_offset({echo $max-$limit});"></i>
          {endif;}
        </th>
      </tr>
      <tr>
        <th class="right pointer" onclick="$.crmData.search.switch_order('cn_id', '{echo $odirection == 'asc' ? 'desc' : 'asc';}');"><p class="w20px">#</p></th>
        <th class="center"><i class="fa fa-arrow-up pointer" title="{__(52)}"></i></th>
        <th class="center"><i class="fa fa-arrow-down pointer" title="{__(53)}"></i></th>
        <th class="center"><i class="fa fa-{echo $table_type == 'article' ? 'table' : 'folder';}" title="{__(1)}"></i></th>
        {if($table_type == 'user'):}
          <th class="center"><i class="fa fa-shopping-cart pointer" title="{__(104)}"></i></th>
        {endif;}
        <th class="pointer" onclick="$.crmData.search.switch_order('cn_id', '{echo $odirection == 'asc' ? 'desc' : 'asc';}');">
          {__(77)}
          {if($of_id == 'cn_id'):}
            <i class="fa fa-fw fa-caret-{echo $odirection == 'asc' ? 'down' : 'up';} sort_{$odirection}"></i>
          {endif;}
        </th>
        <th class="center"><p>{__(15)}</p></th>
        {foreach($fields as $f_id => $f_name):}
          <th class="pointer{echo !empty($filterFieldIDs) && !in_array($f_id, $filterFieldIDs) ? ' ignore' : '';}" onclick="$.crmData.search.switch_order({$f_id}, '{echo $odirection == 'asc' ? 'desc' : 'asc';}');">
            {$f_name}
            {if($f_id == $of_id):}
              <i class="fa fa-fw fa-caret-{echo $odirection == 'asc' ? 'down' : 'up';} sort_{$odirection}"></i>
            {endif;}
          </th>
        {endforeach;}
      </tr>
    </thead>
    {if(count($rows) != 0):}
      <tbody>
        {foreach($rows as $row):}
          <tr class="pointer" name="content" id="{$row['cnv_id']}">
            <td class="right multiselect" title="ID: {$row['cnv_id']}" rel="multiselect">
              <input type="checkbox" />
            </td>
            <td class="center" rel="parents">{$parents[$row['cnv_id']]}</td>
            <td class="center" rel="children">{$children[$row['cnv_id']]}</td>
            <td class="center" rel="{echo $table_type == 'article' ? 'fields' : 'collection';}">
              {$relations[$row['cnv_id']]}
            </td>
            {if($table_type == 'user'):}
              <td class="center" rel="user">{count($jobs[$row['cnv_id']])}</td>
            {endif;}
            <td>
              {echo strftime('%d.%m.%Y, %H:%M', $owner[$row['cnv_id']]['cn_create_time']);}
            </td>
            <td class="center" title="{echo trim($owner[$row['cnv_id']]['firstname'].' '.$owner[$row['cnv_id']]['lastname']);}">
              {echo substr($owner[$row['cnv_id']]['firstname'], 0, 1).substr($owner[$row['cnv_id']]['lastname'], 0, 1);}
            </td>
            {foreach($fields as $f_id => $f_name):}
              <td rel="{$f_id}">{$row['cnv_'.$f_id]}</td>
            {endforeach;}
          </tr>
        {endforeach;}
      </tbody>
    {endif;}
  </table>
</div>
<script>
  $.crmData.search.load();
  $.crmData.list.load({unselect});
</script>
{if($this->hasRightToEdit($module)):}
  <script>
    $('table.editable tbody tr td').click(function(e) {
      $.crmData.list.click_cell(e, this);
    });
  </script>
{endif;}

<input type="hidden" id="tbl_id" value="{$tbl_id}" />