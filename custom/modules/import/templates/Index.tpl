<div class="main stripedFields">
  <fieldset class="form-group">
    <label class="col-sm-4 control-label listLabel">{__(13)}</label>
    <div class="col-sm-8 paddingRight">
      <input type="file" name="file" id="file" accept="*.csv"/>
    </div>
  </fieldset>
  <fieldset class="form-group">
    <label class="col-sm-4 control-label listLabel">{__(14)}</label>
    <div class="col-sm-8 paddingRight">
      <select name="delimiter" class="">
        <option value=";">;</option>
        <option value=",">,</option>
        <option value="|">|</option>
        <option value="  ">{__(15)}</option>
      </select>
    </div>
  </fieldset>
  <fieldset class="form-group">
    <label class="col-sm-4 control-label listLabel">Encoding</label>
    <div class="col-sm-8 paddingRight">
      {select:encoding}
    </div>
  </fieldset>
  <right allow="edit">
    <fieldset class="form-group form-actions">
      <div class="col-sm-8 col-sm-offset-4">
        <button type="button" name="send" onclick="$.importJS.upload();" class="btn btn-default">
          <i class="fa fa-upload"></i>
          {__(16)}
        </button>
      </div>
    </fieldset>
  </right>
</div>
  
<iframe src="" frameborder="0" name="uploadFrame"></iframe>
<input type="hidden" name="filename" value="" />
