<ol class="news">
  {foreach($news as $newsElement):}
  <li>
    <img src="image/0/{$newsElement->Newsbild}&amp;w=235&amp;h=171" alt="" />
    <div>
      <h2>{$newsElement->Newsheadline}</h2>
      <span class="date">Datum: {echo strftime('%d.%m.%Y', $newsElement->Newsdatum)}</span>
      <p>{$newsElement->Newsteaser}</p>
      <a href="{detail}?newsID={$newsElement->id}&offset={$offset}" class="proceed">&raquo; weiterlesen</a>
    </div>
  </li>
  {endforeach;}
</ol>

<div class="newsNavigation">
  {if($offset >= $limit):}
  <a href="{list}?offset=0"><img src="custom/extensions/News/images/first.png" align="absmiddle" alt="erste Seite" /></a>
  <a href="{list}?offset={$offset-$limit}" class="previous"><img src="custom/extensions/News/images/previous.png" align="absmiddle" alt="vorherige Seite" /></a>
  {endif;}

  <span>Seite <strong>{$offset/$limit+1}</strong> von <strong>{$count/$limit}</strong></span>

  {if($offset < $count-$limit):}
  <a href="{list}?offset={$offset+$limit}" class="next"><img src="custom/extensions/News/images/next.png" align="absmiddle" alt="n&auml;chste Seite" /></a>
  <a href="{list}?offset={$count-($count%$limit)-$limit}"><img src="custom/extensions/News/images/last.png" align="absmiddle" alt="letzte Seite" /></a>
{endif;}
</div>