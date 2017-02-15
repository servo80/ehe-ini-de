<div id="edit_cell">
  <span id="editCellClose">
    <i class="fa fa-times"></i>
  </span>
  {field}
  <fieldset class="actions pull-right text-right">
    <button type="button" name="button" onclick="$.crmData.list.edit_cell_save();" id="editCell" class="btn btn-default">
      <i class="fa fa-save"></i>
      {__(6)}
    </button>
  </fieldset>
  <input type="hidden" name="tbl_id" id="tbl_id" value="{tbl_id}" />
  <input type="hidden" name="f_id" id="f_id" value="{f_id}" />
  <input type="hidden" name="cn_id" id="cn_id" value="{cn_id}" />
  <input type="hidden" name="lan_id" id="lan_id" value="{lan_id}" />
</div>
<script>
  $.brandbox.dataset.load();
  $('div#content form div#edit_cell input').keyup(function(e) {
    if(e.which != 13)
      return;
    $('#editCell').click();
  });
  $('div#content form div#edit_cell input:not(.hasDatepicker):first').focus();
</script>