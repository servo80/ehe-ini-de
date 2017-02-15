<table class="ignore editable nowrap width">
  <colgroup>
    <col width="20" />
    <col width="*" />
    <col width="*" />
    {foreach($fields as $f_id => $f_name):}
      <col width="*" />
    {endforeach;}
  </colgroup>
  <thead>
    <tr>
      <th colspan="{count($fields)+6}" class="navigate">
        <p>{__(app69)}: {$limit}</p>
        <p class="border-left border-right">{__(app43)}: {echo ceil($offset/$limit)+1} {__(app39)} {ceil($max/$limit)}</p>
        <p>{$offset+1} - {echo min($max, $offset+$limit)} {__(app39)} {$max}</p>
      </th>
    </tr>
    <tr>
      <th class="right"><p class="w20px">#</p></th>
      {foreach($fields as $f_id => $f_name):}
        <th>
          {$f_name}
        </th>
      {endforeach;}
    </tr>
  </thead>
  {if(count($rows) != 0):}
    <tbody>
      {foreach($rows as $row):}
        <tr class="r{echo ($c++ % 2);}">
          <td class="right" title="ID: {$row['cnv_id']}">{$c}</td>
            {$relations[$row['cnv_id']]}
          </td>
          {foreach($fields as $f_id => $f_name):}
            <td rel="{$f_id}">{$row['cnv_'.$f_id]}</td>
          {endforeach;}
        </tr>
      {endforeach;}
    </tbody>
  {endif;}
</table>