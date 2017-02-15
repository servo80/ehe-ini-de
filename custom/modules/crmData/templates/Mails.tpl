<div class="sidebarDesktop">

  <ol>
    {foreach($tables as $table):}
      <li class="pointer icon{echo $tbl_id == $table['tbl_id'] ? ' active' : ''}" onclick="$.crmData.mails.switch_table({$table['tbl_id']});">
        <i class="fa fa-fw {$table['tbl_icon']}"></i>
        <p>{$table['tbl_name']}</p>
      </li>
    {endforeach;}
  </ol>
</div>

<div class="main">
  <table class="table striped">
    <thead>
      <tr>
        <th class="text-bold" colspan="2">{__(89)}</th>
      </tr>
    </thead>
    <tbody>
      {foreach($dataset as $dataset_f_id => $dataset_value):}
        <tr>
          <td>{$dataset_value['f_name']}</td>
          <td>{$dataset_value['f_value']}</td>
        </tr>
      {endforeach;}
    </tbody>
  </table>
  <table class="linked editable nowrap width striped">
    <colgroup>
      <col width="40" />
      <col width="300" />
      {foreach($states as $stateKey => $stateName):}
        <col width="100" />
      {endforeach;}
    </colgroup>
    <thead>
      <tr>
        <th></th>
        <th><p>{__(app29)}</p></th>
        {foreach($states as $stateKey => $stateName):}
          <th class="center">{$stateName}</th>
        {endforeach;}
      </tr>
    </thead>
    <tbody>
    {foreach($users as $user):}
      <tr class="pointer" id="{$user['u_id']}">
        <td class="center"><i class="fa fa-fw glyphicon-pushpin"></i></td>
        <td>{$user['u_name']} <i>[ID: {$user['u_id']}]</i></td>
        {foreach($states as $stateKey => $stateName):}
          <td class="center"><input type="checkbox" rel="{$stateKey}"{echo in_array($stateKey, $user['rights']) ? ' checked="checked"' : '';} /></td>
        {endforeach;}
      </tr>
    {endforeach;}
    </tbody>
  </table>
</div>

<input type="hidden" id="tbl_id" value="{$tbl_id}" />
<input type="hidden" id="cn_id" value="{cn_id}" />

<script>
  $.crmData.mails.load();
</script>