<div class="main">
  <ol class="striped">
    <li><h2>{__(89)}</h2></li>
    {foreach($dataset as $dataset_f_id => $dataset_value):}
    <li class="">
      <p class="w200px i1">{$dataset_value['f_name']}</p>
      <p class="padding">{$dataset_value['f_value']}</p>
    </li>
    {endforeach;}
  </ol>
  <ol class="selectable striped">
    <li><h2>{__(54)}</h2></li>
    {foreach($versions as $version):}
      <li class="h2">
        <h2>
          <span class="w200px">{$version['username']}</span>
          {if($version['cnv_version'] == 0):}
            -
          {else:}
            {echo strftime('%d.%m.%Y, %H:%M:%S', $version['cnv_version']);}
          {endif;}
        </h2>
      </li>
      {$c = 0;}
      {foreach($version['diff'] as $diffKey => $diffValue):}
        <li class="">
          <p class="labelVersions w200px i1">{$diffValue['fieldName']}</p>
          <p class="padding">{$diffValue['diff']}</p>
        </li>
      {endforeach;}
    {endforeach;}
  </ol>
</div>

<script>
  $('ol.selectable .labelVersions').disableSelection();
</script>