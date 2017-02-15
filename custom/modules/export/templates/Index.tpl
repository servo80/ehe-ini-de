<div class="main stripedFields">
  <fieldset class="form-group">
    <label class="col-sm-4 control-label listLabel">{__(5)}</label>
    <div class="col-sm-8 paddingRight">
      <div class="input-group">
        {select:profiles}
        <span class="input-group-addon">
          <right allow="delete">
            <a href="javascript:$.exportJS.deleteProfile();" class="float-none">
              <i class="fa fa-fw fa-trash-o"></i>
            </a>
          </right>
        </span>
      </div>
    </div>
  </fieldset>
  <fieldset class="form-group">
    <label class="col-sm-4 control-label listLabel">{__(6)}</label>
    <div class="col-sm-8 paddingRight">
      <input type="text" name="ex_name" id="ex_name" value="" />
    </div>
  </fieldset>
  <fieldset class="form-group">
    <label class="col-sm-4 control-label listLabel">{__(7)}</label>
    <div class="col-sm-8 paddingRight">
      <input type="checkbox" name="ex_overwrite" value="1" />
    </div>
  </fieldset>
  <fieldset class="form-group">
    <label class="col-sm-4 control-label listLabel">{__(3)}</label>
    <div class="col-sm-8 paddingRight">
      {select:tables}
    </div>
  </fieldset>
  <fieldset class="form-group">
    <label class="col-sm-4 control-label listLabel">{__(4)}</label>
    <div class="col-sm-8 paddingRight">
      {select:childtables}
    </div>
  </fieldset>
  <fieldset class="form-group">
    <label class="col-sm-4 control-label listLabel">{__(2)}</label>
    <div class="col-sm-8 paddingRight">
      {select:languages}
    </div>
  </fieldset>
  <fieldset class="form-group">
    <label class="col-sm-4 control-label listLabel">{__(12)}</label>
    <div class="col-sm-8 paddingRight">
      <input type="checkbox" name="ex_htmlAsPlain" id="ex_htmlAsPlain" value="1" />
    </div>
  </fieldset>
  <fieldset class="form-group">
    <label class="col-sm-4 control-label listLabel">{__(13)}</label>
    <div class="col-sm-8 paddingRight">
      <input type="checkbox" name="ex_IDAsPlain" id="ex_IDAsPlain" value="1" />
    </div>
  </fieldset>
  <fieldset class="form-group form-actions">
    <div class="col-sm-8 col-sm-offset-4">
      <button type="button" name="send" onclick="$.exportJS.start();" class="btn btn-default">
        <i class="fa fa-download"></i>
        {__(9)}
      </button>
    </div>
  </fieldset>
  <ol class="linked stripedAlt">
    <li class="icon pointer">
      <i class="fa fa-fw fa-check"></i>
      <input type="checkbox" name="all" id="all" value="1" onclick="$.exportJS.all(this.checked);" />
      <h2>{__(14)}</h2>
    </li>
    {foreach($fields as $field):}
      <li class="icon pointer hide" name="field" id="{$field['f_id']}">
        <i class="fa fa-fw {$field['f_icon']} pointer"></i>
        <input type="checkbox" name="f_id[]" id="f_id_{$field['f_id']}" value="{$field['f_id']}" />
        <p>{$field['f_name']} <i>[ID: {$field['f_id']}]</i></p>
      </li>
    {endforeach;}
  </ol>
</div>
<script>
  $.exportJS.load('{fieldIDsOfTables}');
</script>