<div class="main">
  <ol class="striped">
    {foreach($report as $row):}
    <li class="icon">
      <i class="fa fa-fw {$row[0]}"></i>
      <p>{$row[1]}</p>
    </li>
    {endforeach;}
  </ol>
</div>