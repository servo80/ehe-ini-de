<div class="sidebarDesktop">
  <div class="collapse-container sidebarTile">
    <div class="collapse-header">
      <h3>{__(7)}</h3>
    </div>
    <div class="collapse">
      {select:languages}
    </div>
  </div>
  {if(count($groups) != 0):}
    <ol>
      {foreach($groups as $gf_id => $gf_name):}
        <li class="pointer{echo $gf_id == $f_group ? ' r1' : '';}" onclick="$.crmData.relations.switch_group({$gf_id});">
          <p>{$gf_name}</p>
        </li>
      {endforeach;}
    </ol>
  {endif;}
</div>

<div class="main">
  {form}
  <fieldset class="form-group form-actions">
    <div class="col-sm-8 col-sm-offset-4">
      <right allow="edit">
        <button type="button" name="button" onclick="$.crmData.relations.save();" class="btn btn-primary">
          <i class="fa fa-save"></i>
          {__(37)}
        </button>
      </right>
      <button type="button" name="button" onclick="$.crmData.relations.close();" class="btn btn-default">
        <i class="fa fa-save"></i>
        {__(35)}
      </button>
    </div>
  </fieldset>
</div>

<input type="hidden" name="f_group" id="f_group" value="{$f_group}" />
<input type="hidden" name="cn_ids" id="cn_ids" value="{cn_id}" />
<input type="hidden" name="tbl_id" id="tbl_id" value="{tbl_id}" />

<script>
  $.brandbox.dataset.load();
</script>