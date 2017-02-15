
<div class="main stripedFields">
  <fieldset class="form-group">
    <!-- <p></p> -->
    <div class="col-sm-8 col-sm-offset-4 paddingRight">
      <div class="input-group">
        {select:profiles}
        <span class="input-group-addon">

          <right allow="delete">
            <a href="javascript:$.importJS.deleteProfile();" class="float-none">
              <i class="fa fa-fw fa-trash-o"></i>
            </a>
          </right>

        </span>
      </div>
    </div>
  </fieldset>
  <fieldset class="form-group">
    <label class="col-sm-4 control-label listLabel">{__(4)}</label>
    <div class="col-sm-8 paddingRight">
      <input type="text" name="im_name" id="im_name" value="" />
    </div>
  </fieldset>
  <fieldset class="form-group">
    <label class="col-sm-4 control-label listLabel">{__(5)}</label>
    <div class="col-sm-8 paddingRight">
      <input type="checkbox" name="im_overwrite" id="im_overwrite" value="1" />
    </div>
  </fieldset>
  <fieldset class="form-group">
    <label class="col-sm-4 control-label listLabel">{__(3)}</label>
    <div class="col-sm-8 paddingRight">
      {select:tables}
    </div>
  </fieldset>
  <fieldset class="form-group">
    <label class="col-sm-4 control-label listLabel">{__(6)}</label>
    <div class="col-sm-8 paddingRight">
      {select:parenttables}
    </div>
  </fieldset>
  <fieldset class="form-group">
    <label class="col-sm-4 control-label listLabel">{__(24)}</label>
    <div class="col-sm-8 paddingRight">
      {select:childtables}
    </div>
  </fieldset>
  <fieldset class="form-group">
    <label class="col-sm-4 control-label listLabel">{__(23)}</label>
    <div class="col-sm-8 paddingRight">
      <input type="checkbox" name="im_relate_parents" id="im_relate_parents" value="1" />
    </div>
  </fieldset>
  <fieldset class="form-group">
    <label class="col-sm-4 control-label listLabel">{__(27)}</label>
    <div class="col-sm-8 paddingRight">
      <input type="checkbox" name="im_version" id="im_version" value="1" />
    </div>
  </fieldset>
  <fieldset class="form-group">
    <label class="col-sm-4 control-label listLabel">{__(28)}</label>
    <div class="col-sm-8 paddingRight">
      <input type="checkbox" name="im_strip_tags" id="im_strip_tags" value="1" />
    </div>
  </fieldset>

  <table class="table striped">
    <thead>
      <tr>
        <th>{__(7)}</th>
        <th>{__(8)}</th>
        <th>{__(2)}</th>
        <th class="text-center"><i class="fa fa-search" title="{__(11)}"></i></th>
        <th class="text-center"><i class="fa fa-arrow-up" title="{__(12)}" /></th>
        <th class="text-center"><i class="fa fa-arrow-down" title="{__(12)}" /></th>
      </tr>
    </thead>
    {foreach($columns as $c => $column):}
      <tr class="" id="column{$c}">
        <td>{$column}</td>
        <td>{echo str_replace('#column#', $c, $fields);}</td>
        <td>{echo str_replace('#column#', $c, $languages);}</td>
        <td class="text-center"><input type="checkbox" name="update_{$c}" id="update_{$c}" value="1" /></td>
        <td class="parents text-center"><input type="checkbox" name="parent_{$c}" value="1" onclick="$.importJS.parents({$c}, this.checked);" class="hide" /></td>
        <td class="children text-center"><input type="checkbox" name="child_{$c}" value="1" onclick="$.importJS.children({$c}, this.checked);" class="hide" /></td>
      </tr>
    {endforeach;}
  </table>

  <fieldset class="form-group form-actions">
    <div class="col-sm-8 col-sm-offset-4">
      <button type="button" name="send" onclick="$.brandbox.post('import', 'Report', 'Import');" class="btn btn-default">
        <i class="fa fa-arrow-circle-o-down"></i>
        {__(10)}
      </button>
    </div>
  </fieldset>

</div>

<input type="hidden" name="filename" id="filename" value="{filename}" />
<input type="hidden" name="delimiter" id="delimiter" value="{delimiter}" />
<input type="hidden" name="encoding" id="encoding" value="{encoding}" />