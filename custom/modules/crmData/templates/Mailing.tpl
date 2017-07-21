{foreach($searchFields as $searchFieldID => $searchFieldValue):}
{if($searchFieldValue != ""):}
<input type="hidden" name="searchFields[{$searchFieldID}]" value="{$searchFieldValue}" />
{endif;}
{endforeach;}


<div class="sidebarDesktop">
  <div class="collapse-container sidebarTile">
    <div class="collapse-header">
      <h3>{__(app5)}</h3>
    </div>
    <div class="collapse in">
      {number} Empfänger gewählt
    </div>
  </div>

</div>

<div class="main stripedFields">

  <fieldset>
    <h2><b>Kontakte</b>: Datensatz</h2>
  </fieldset>

  <!-- Feldgruppen -->

  <fieldset class="form-group lang1">
    <label class="col-sm-4 control-label listLabel">Absender-Email</label>
    <div class="col-sm-8 paddingRight">
      <input type="text" id="sender" name="sender" style="width:300px;" value="info@ehe-initiative.de" tabindex="2" maxlength="100">
      <span class="help-inline"></span>
    </div>
  </fieldset>

  <fieldset class="form-group lang1">
    <label class="col-sm-4 control-label listLabel">Absender-Name</label>
    <div class="col-sm-8 paddingRight">
      <input type="text" id="senderName" name="senderName" style="width:300px;" value="Ehe-Initiative e.V." tabindex="2" maxlength="100">
      <span class="help-inline"></span>
    </div>
  </fieldset>

  <fieldset class="form-group lang1">
    <label class="col-sm-4 control-label listLabel">Betreff</label>
    <div class="col-sm-8 paddingRight">
      <input type="text" id="subject" name="subject" style="width:300px;" value="Newsletter" tabindex="2" maxlength="100">
      <span class="help-inline"></span>
    </div>
  </fieldset>

  <fieldset class="form-group lang1  preview">
    <label class="col-sm-4 control-label listLabel">Anhang</label>
    <div class="col-sm-8 paddingRight">
      <div class="input-group actionIcon">
        <input type="text" id="attachment" name="attachment" style="width:300px;" value="" tabindex="24" maxlength="255"><span class="input-group-addon"><a href="javascript:$.brandbox.dataset.media.open('attachment','');"><i class="fa fa-folder"></i></a></span>
      </div>
      <span class="help-inline"></span>
    </div>
  </fieldset>

  <fieldset class="form-group lang1  preview">
    <label class="col-sm-4 control-label listLabel">Anhang 2</label>
    <div class="col-sm-8 paddingRight">
      <div class="input-group actionIcon">
        <input type="text" id="attachment2" name="attachment2" style="width:300px;" value="" tabindex="24" maxlength="255"><span class="input-group-addon"><a href="javascript:$.brandbox.dataset.media.open('attachment2','');"><i class="fa fa-folder"></i></a></span>
      </div>
      <span class="help-inline"></span>
    </div>
  </fieldset>

  <fieldset class="form-group lang1  preview">
    <label class="col-sm-4 control-label listLabel">Anhang 3</label>
    <div class="col-sm-8 paddingRight">
      <div class="input-group actionIcon">
        <input type="text" id="attachment3" name="attachment3" style="width:300px;" value="" tabindex="24" maxlength="255"><span class="input-group-addon"><a href="javascript:$.brandbox.dataset.media.open('attachment3','');"><i class="fa fa-folder"></i></a></span>
      </div>
      <span class="help-inline"></span>
    </div>
  </fieldset>

  <fieldset class="form-group lang1  preview">
    <label class="col-sm-4 control-label listLabel">E-Mail-Text</label>
    <div class="col-sm-8 paddingRight">
      <div class="input-group actionIcon">
         <textarea id="mailText" name="mailText" style="width: 300px; height: 126px; visibility: hidden; display: none;" cols="1" rows="10" tabindex="3" maxlength="65535" class="rte"></textarea>
      </div>
      <span class="help-inline"></span>
    </div>
  </fieldset>

  <fieldset class="form-group form-actions">
    <div class="col-sm-8 col-sm-offset-4">
      <button type="button" name="button" onclick="$.crmData.search.spoolTest();" class="btn btn-primary">
        <i class="fa fa-save"></i> Test-E-Mail erzeugen
      </button>
      <button type="button" name="button" onclick="$.crmData.search.spoolMails();" class="btn btn-primary">
        <i class="fa fa-save"></i> E-Mails erzeugen
      </button>
    </div>
  </fieldset>
</div>

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