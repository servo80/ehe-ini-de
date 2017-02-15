<div class="sidebarDesktop">
  <div class="collapse-container sidebarTile">
    <div class="collapse-header">
      <h3>{__(app5)}</h3>
    </div>
    <div class="collapse in">
      <input type="hidden" id="lan_id" name="lan_id" value="{echo implode(',', $activeLanguageIDs);}" />
      <ol class="content-edit">
        {foreach($languageIDsAsNames as $languageID => $languageName):}
          <li class="pointer {echo in_array($languageID, $activeLanguageIDs) ? 'active' : '';}">
            <a onclick="$.crmData.edit.switch_language({$languageID});">{$languageName}</a>
            {if(in_array($languageID, $activeLanguageIDs)):}
              <i class="fa fa-fw fa-clipboard"  onclick="$.crmData.edit.copyFromLanguage({$languageID});" id="{$languageID}" title="{__(101)}"></i>
              <i class="fa fa-fw fa-bookmark" onclick="$.crmData.edit.highlightLanguage({$languageID});" title="{__(103)}"></i>
            {endif;}
          </li>
        {endforeach;}
      </ol>
      <br />
      <ol>
        {if(!empty($contentIDs) && $contentIDs[0] != 0):}
          <li class="pointer" onclick="$.crmData.edit.showVersions();">
            {__(54)}
          </li>
        {endif;}
      </ol>
    </div>
  </div>


  <!-- Dateien in dieser Gruppe -->
  {if(!empty($mediaGroupedFiles)):}
    <div class="collapse-container sidebarTile">
      <div class="collapse-header">
        <h3>{__(media:56)}</h3>
      </div>
      <div class="collapse">
        <ol class="linked pointer">
          {foreach($mediaGroupedFiles as $mcn_id => $groupedFile):}
            <li name="file" id="{$mcn_id}" data-path="{$groupedFile}" title="{basename($groupedFile)}" class="groupedFile">
              {basename($groupedFile)}
            </li>
          {endforeach;}
        </ol>
      </div>
    </div>
  {endif;}

  <!-- Kategorien -->
  {if(!empty($mediaCategories)):}
    <div class="collapse-container sidebarTile">
      <div class="collapse-header">
        <h3>{__(media:101)}</h3>
      </div>
      <div class="collapse">
        <ol class="linked pointer">
          {foreach($mediaCategories as $cat_id => $category):}
            <li id="{$cat_id}" name="category" title="{$category}">
              {$category}
            </li>
          {endforeach;}
        </ol>
      </div>
    </div>
  {endif;}

  <!-- Verknüpfungen zu Datensätzen -->
  {if(!empty($mediaRelations)):}
    <div class="collapse-header box">
      <div class="collapse-header">
        <h3>{__(media:64)}</h3>
      </div>
      <div class="collapse">
        <ol class="linked pointer">
          {foreach($mediaRelations as $relationType => $relationCategories):}
            {if($relationType == 'content'):}
              {foreach($relationCategories as $cn_id => $relation):}
                <li id="{$cn_id}" title="{$relation['cn_name']}" onclick="$.crmData.edit.edit_dataset({$cn_id}, {$relation['tbl_id']});">
                  {$relation['cn_name']}
                </li>
              {endforeach;}
            {elseif($relationType == 'website'):}
              {foreach($relationCategories as $page_id => $page_name):}
                <li id="{$cn_id}" title="{$page_name}" onclick="$.crmData.edit.edit_website({$page_id});">
                  {$page_name}
                </li>
              {endforeach;}
            {endif;}
          {endforeach;}
        </ol>
      </div>
    </div>
  {endif;}
</div>

<div class="main stripedFields">

  {if(!empty($users)):}
    <fieldset>
      <h2>{__(51)}</h2>
    </fieldset>
    {foreach($users as $user):}
      <fieldset>
        <p class="w650px highlight">{$user['firstname']} {$user['lastname']}</p>
      </fieldset>
    {endforeach;}
  {endif;}

  <fieldset>
    <h2><b>{tbl_name}</b>: {__(89)}</h2>
  </fieldset>

  <!-- Feldgruppen -->
  {if(count($groups) > 1):}
  <ul class="nav nav-tabs jsTabNavigation">
    {foreach($groups as $gf_id => $gf_name):}
      <li class="{echo $gf_id == $f_group ? 'active' : '';}">
        <a href="javascript:$.crmData.edit.switch_group({$gf_id});">
          {$gf_name}
        </a>
      </li>
    {endforeach;}
  </ul>
  {endif;}

  {form}
  {if($this->hasRightToEdit('content')):}
    <fieldset class="form-group form-actions">
      <div class="col-sm-8 col-sm-offset-4">
        <button type="button" name="button" onclick="$.crmData.edit.save('Edit');" class="btn btn-primary">
          <i class="fa fa-save"></i> {__(6)}
        </button>
        <button type="button" name="button" onclick="$.crmData.edit.save('Index');" class="btn btn-default">
          <i class="fa fa-save"></i>
          {__(37)}
        </button>
      </div>
    </fieldset>
  {endif;}
</div>

<input type="hidden" name="f_group" id="f_group" value="{$f_group}" />
<input type="hidden" name="rcn_id" id="rcn_id" value="{rcn_id}" />
<input type="hidden" name="rtbl_id" id="rtbl_id" value="{rtbl_id}" />
<input type="hidden" name="cn_ids" id="cn_ids" value="{cn_ids}" />
<input type="hidden" name="editContentID" id="editContentID" value="{editContentID}" />
<input type="hidden" name="tbl_id" id="tbl_id" value="{tbl_id}" />

<script>
  $.brandbox.getScript(
    'application/modules/content/mod.js',
    function() {
      $.brandbox.dataset.loadCallback($.crmData.edit.storeLoad);
      $.crmData.edit.highlightLanguage();
      $.crmData.edit.progress('{cn_ids}');
    }
  );
</script>