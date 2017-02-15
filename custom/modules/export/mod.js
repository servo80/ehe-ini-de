(function($) {

  $.exportJS = {

    fieldIDsOfTables: [],
    load: function(fieldIDsOfTables) {

      this.fieldIDsOfTables = eval('(' + fieldIDsOfTables + ')');

      $('#tbl_id, #child_tbl_ids').change(function() {
        var fieldIDsOfTables = $.exportJS.fieldIDsOfTables;
        var tableIDs = $('#child_tbl_ids').val();
        if(!tableIDs)
          tableIDs = [];

        tableIDs.push($('#tbl_id').val());

        $('li[name="field"]')
          .hide();

        for(var c in tableIDs){
          var tableID = tableIDs[c];
          for(var d in fieldIDsOfTables[tableID]) {
            var fieldID = fieldIDsOfTables[tableID][d];
            $('li[name="field"]#' + fieldID).show();
          }
        }
      });

      $('ol.linked li:gt(0)').click(function() {

        $(this).toggleClass('active');

        if($(this).hasClass('active')) {
          $(this).children('input').attr('checked', true);
        } else {
          $(this).children('input').attr('checked', false);
        }
      });
    },

    all: function(active) {

      if(active)
        $('li[name="field"] input').attr('checked', 'checked');
      else
        $('li[name="field"] input').removeAttr('checked');
    },

    change: function(ex_id) {

      $('#ex_name').val('');
      $('#ex_htmlAsPlain').attr('checked', false);
      $('#ex_IDAsPlain').attr('checked', false);
      $('#child_tbl_ids').val('');

      $('ol.linked li:gt(0)').removeClass('active');
      $('ol.linked li:gt(0) input').attr('checked', false);

      if(ex_id < 0)
        return;

      $.getJSON(
        'admin.php' +
          '?PHPSESSID=' + $.brandbox.session_id +
          '&mod=export' +
          '&tpl=Index' +
          '&action=LoadProfile' +
          '&ex_id=' + ex_id,
        function(data) {

          $('#ex_name').val(data.ex_name);
          $('#ex_htmlAsPlain').attr('checked', data.ex_htmlAsPlain == 1 ? true : false);
          $('#ex_IDAsPlain').attr('checked', data.ex_IDAsPlain == 1 ? true : false);
          $('#lan_id').val(data.lan_id);
          $('#tbl_id').val(data.tbl_id);
          $('#tbl_id').trigger('change');

          $('ol.linked li:gt(0)').removeClass('active');
          $('ol.linked li:gt(0) input').attr('checked', false);

          for(var c in data.f_id) {
            var f_id = data.f_id[c];
            $('#f_id_' + f_id).parent().addClass('active');
            $('#f_id_' + f_id).attr('checked', true);
          }

          $('#child_tbl_ids').val(data.child_tbl_ids);

        }
      );
    },

    start: function() {

      $('#tpl').val('Index');
      $('#action').val('Export');
      document.forms[0].submit();

    },

    deleteProfile: function() {

      $.alerts.confirm(
        $.i18n.__(8),
        function() {
          $.brandbox.get(
            $.brandbox.mod,
            $.brandbox.tpl,
            'Delete',
            '&ex_id=' + $('#ex_id').val()
          );
        }
      );
    }
  };

})(jQuery);