<div class="main">
  <ol class="striped">
    <li><h2>{__(89)}</h2></li>
    {foreach($dataset as $value):}
    <li class="">
      <p class="w200px i1">{$value['f_name']}</p>
      <p class="padding">{$value['f_value']}</p>
    </li>
    {endforeach;}
  </ol>

  <ol class="linked striped">
    <li>
      <h2>{__(4)}</h2>
    </li>
    {foreach($tables as $table):}
      {if($table['tbl_id'] == -1) continue;}
      <li class="pointer icon" onclick="$.crmData.move.select({$table['tbl_id']});">
        <i class="fa fa-fw {$table['tbl_icon']}"></i>
        <p>{$table['tbl_name']} <i>[ID: {$table['tbl_id']}]</i></p>
      </li>
    {endforeach;}
  </ol>

  <input type="hidden" name="cn_id" id="cn_id" value="{cn_id}" />
</div>