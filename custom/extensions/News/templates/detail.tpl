<div class="newsDetail">
  <img src="image/0/{$newsElement->Newsbild}&amp;w=235&amp;h=171" alt="" class="newsImage" />

  <h2>{$newsElement->Newsheadline}</h2>
  <span class="date">Datum: {echo strftime('%d.%m.%Y', $newsElement->Newsdatum)}</span>
  <p>{$newsElement->Newstext}</p>

  <a href="javascript:window.print();" class="previous" style="margin-bottom:30px;"><img src="custom/extensions/News/images/print.png" align="absmiddle" alt="Druckansicht" /> Druckansicht</a>
  <a href="{list}?offset={offset}" class="previous"><img src="custom/extensions/News/images/previous.png" align="absmiddle" alt="vorherige Seite" /> zurück</a>
</div>