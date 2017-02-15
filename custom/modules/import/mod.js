(function($) {

  $.importJS = {

    upload: function() {

      if ($('#file').val() == '') {
        $.alerts.alert($.i18n.__(17));
        return;
      }

      $('#tpl').val('Upload');
      $('#action').val('Upload');
      $('#form').attr('target', 'uploadFrame');
      document.forms[0].submit();
    },

    change: function(im_id) {

      var $filename = $('#filename');
      var $delimiter = $('#delimiter');
      var $encoding = $('#encoding');

      var filename = $filename.val();
      var delimiter = $delimiter.val();
      var encoding = $encoding.val();

      $('select').val('');
      $('input[type="text"]').val('');
      $('input[type="hidden"]').val('');
      $('input[type="checkbox"]').attr('checked', false);
      $('#im_id').val(im_id);

      $filename.val(filename);
      $delimiter.val(delimiter);
      $encoding.val(encoding);

      for(var c = 0; c < $('div.main ol li').length-1; c++) {
        $('#parent' + c).remove();
        $('#child' + c).remove();
      }

      if(im_id < 0)
        return;

      $.getJSON(
        'admin.php' +
          '?PHPSESSID=' + $.brandbox.session_id +
          '&mod=import' +
          '&tpl=Index' +
          '&action=LoadProfile' +
          '&im_id=' + im_id,
        function(data) {

          $('#im_name').val(data.im_name);
          $('#im_relate_parents').attr('checked', data.im_relate_parents);
          $('#im_version').attr('checked', data.im_version);
          $('#im_strip_tags').attr('checked', data.im_strip_tags);
          $('#tbl_id').val(data.tbl_id).trigger('change');
          $('#ptbl_id').val(data.ptbl_id).trigger('change');
          $('#ctbl_id').val(data.ctbl_id).trigger('change');

          $.importJS.parents_load(data.ptbl_id);
          $.importJS.children_load(data.ctbl_id);

          for(var c = 0; c < $('div.main ol li').length-1; c++) {

            $('#f_id_' + c).val(data['f_id_' + c]).trigger('change');
            $('#lan_id_' + c).val(data['lan_id_' + c]).trigger('change');

            if(data['update_' + c] == 1)
              $('#update_' + c).attr('checked', 1);

            if(data['pf_id_' + c] && data['pf_id_' + c] != '') {
              $('input[name="parent_' + c + '"]').attr('checked', true);
              $.importJS.parents(c, true, data['pf_id_' + c], data['pcmp_' + c]);
            } else {
              $.importJS.parents(c, false);
            }

            if(data['cf_id_' + c] && data['cf_id_' + c] != '') {
              $('input[name="child_' + c + '"]').attr('checked', true);
              $.importJS.children(c, true, data['cf_id_' + c], data['ccmp_' + c]);
            } else {
              $.importJS.children(c, false);
            }
          }
        }
      );

    },

    parents_load: function(tbl_id) {

      if(tbl_id != '') {
        $('table tr td.parents input').show();
        for(var c = 0; c < $('div.main ol li').length-1; c++) {
          if($('input[name="parent_' + c + '"]').attr('checked'))
            $.importJS.parents(c, true);
        }
      } else {
        $('table tr td.parents input').hide();
        for(var c = 0; c < $('div.main ol li').length-1; c++) {
          $.importJS.parents(c, false);
        }
      }
    },

    children_load: function(tbl_id) {

      if(tbl_id != '') {
        $('table tr td.children input').show();
        for(var c = 0; c < $('div.main ol li').length-1; c++) {
          if($('input[name="child_' + c + '"]').attr('checked'))
            $.importJS.children(c, true);
        }
      } else {
        $('table tr td.children input').hide();
        for(var c = 0; c < $('div.main ol li').length-1; c++) {
          $.importJS.children(c, false);
        }
      }
    },

    parents: function(column, checked, pf_id, cmp) {
      $('#parent' + column).remove();

      if(!checked)
        return;

      $.brandbox.get(
        $.brandbox.mod,
        'ParentFieldIDs',
        '',
        '&ptbl_id=' + $('#ptbl_id').val() +
        '&f_id=' + (pf_id ? pf_id : '') +
        '&cmp=' + (cmp ? cmp : '') +
        '&column=' + column,
        function(data) {
          var $column = $('#column' + column);
          $column.after(data);

          $('li.parent')
            .find('select:not(.select2-offscreen):not(.noselect2)')
            .on('change', function(){
              $(this).select2('val', $(this).val());
            })
            .select2();
          setTimeout('$.brandbox.load.off();', 500);
        }
      );
    },

    children: function(column, checked, pf_id, cmp) {
      $('#child' + column).remove();

      if(!checked)
        return;

      $.brandbox.get(
        $.brandbox.mod,
        'ChildFieldIDs',
        '',
        '&ctbl_id=' + $('#ctbl_id').val() +
        '&f_id=' + (pf_id ? pf_id : '') +
        '&cmp=' + (cmp ? cmp : '') +
        '&column=' + column,
        function(data) {
          var $column = $('#column' + column);
          $column.after(data);


          $('li.child')
            .find('select:not(.select2-offscreen):not(.noselect2)')
            .on('change', function(){
              $(this).select2('val', $(this).val());
            })
            .select2();
          setTimeout('$.brandbox.load.off();', 500);
        }
      );
    },

    deleteProfile: function() {

      $.alerts.confirm(
        $.i18n.__(9),
        function() {
          $.brandbox.get(
            $.brandbox.mod,
            'Index',
            'Delete',
            '&im_id=' + $('#im_id').val()
          );
        }
      );
    }
  };

})(jQuery);