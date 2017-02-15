<div class="main">
  <ol class="striped">
    <li><h2>{__(89)}</h2></li>
    <table class="table striped">
      {foreach($dataset as $dataset_f_id => $dataset_value):}
        <tr>
          <td>{$dataset_value['f_name']}</td>
          <td>{$dataset_value['f_value']}</td>
        </tr>
      {endforeach;}
    </table>
  </ol>
  <ol class="linked striped">
    <li>
      <h2>{__(1)}</h2>
    </li>
    {foreach($collections as $co_id => $covalue):}
    <li class="pointer" onclick="$.crmData.collection.open({$co_id});">
      <p>{$covalue}</p>
    </li>
    {endforeach;}
  </ol>
</div>