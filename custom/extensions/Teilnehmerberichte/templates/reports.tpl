<h1>Teilnehmerberichte</h1>
<ul class="reports">

  {foreach($reports as $c => $report):}

  <li style="float:{echo ($c%2 ? 'right' : 'left');};">

    <h2>{$report->Berichtueberschrift}</h2>
    {$report->Bericht}
    <span>{$report->Berichtautor}</span>

  </li>

  {endforeach;}

</ul>