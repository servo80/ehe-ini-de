<div class="Container_Start_Padding">
<div class="container ">

  {foreach($news as $newsElement):}
  <div class="column width33"><div class="newsTeaser">
      <img src="image/0/{$newsElement->Newsbild}&amp;w=215&amp;h=171" alt="" />
      <h2>{$newsElement->Newsheadline}</h2>
      <p>{echo nl2br($newsElement->Newsteaser);}</p>
      <a href="{detail}?newsID={$newsElement->id}">» weiterlesen</a>
    </div>
  </div>
  {endforeach;}

</div>
</div>