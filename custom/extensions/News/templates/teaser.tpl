<div class="Container_Start_Padding">
<div class="container ">

  {foreach($news as $newsElement):}
  <div class="column width33"><div class="newsTeaser">
      <img src="image/0/{$newsElement['image']}&amp;w=215&amp;h=171" alt="" />
      <h2>{$newsElement['headline']}</h2>
      <p>{echo $newsElement['text'];}</p>
      <a href="{$newsElement['link']}">» weiterlesen</a>
    </div>
  </div>
  {endforeach;}

</div>
</div>