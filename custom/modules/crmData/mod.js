(function($) {

  $.crmData = {

    pdfSettings: {
      languageID: 0,
      pageNumber: 0,
      low: 0
    },

    search: {

      startMailing: function() {

        $.brandbox.post(
          $.brandbox.mod,
          'Mailing',
          ''
        );

      },

      spoolMails: function() {

        $.brandbox.post(
          $.brandbox.mod,
          'Confirm',
          'Spool'
        );

      },


      countActivated: function() {
        $('#numberOfActivated').html(($.crmData.list.active[$.crmData.list.tbl_id] ? $.crmData.list.active[$.crmData.list.tbl_id].length : 0));
      },

      checkAll: function(onlySelect) {
        if(typeof  onlySelect == 'undefined')
          onlySelect = false;

        if($.inArray($(this).attr('id'), $.crmData.list.active[$.crmData.list.tbl_id]) != -1)
          $(this).removeClass('active')

        var cn_id = $(this).attr('id');
        $.crmData.list.active[$.crmData.list.tbl_id] = $.grep($.crmData.list.active[$.crmData.list.tbl_id], function(value) {
          return value != cn_id;
        });
        var box = $(this).find('input[type="checkbox"]');
        if($(box).is(':checked')) {
          $(box).attr('checked', false);
        }

        var rows = $('tr[name="content"]');
        var $checkboxes = $(rows).find('input[type="checkbox"]');

        if($('#checkAll').is(':checked') || onlySelect) {
          $checkboxes.attr('checked', true);
          rows.addClass('active');

          $(rows).each(function() {
            var cn_id = $(this).attr('id');
            if($.inArray($(this).attr('id'), $.crmData.list.active[$.crmData.list.tbl_id]) == -1)
              $.crmData.list.active[$.crmData.list.tbl_id].push(cn_id);
          });

        } else {
          $checkboxes.attr('checked', false);
          rows.removeClass('active');

          $(rows).each(function() {
            var cn_id = $(this).attr('id');
            $.crmData.list.active[$.crmData.list.tbl_id] = $.grep($.crmData.list.active[$.crmData.list.tbl_id], function(value) {
              return value != cn_id;
            });
          });

        }

        if(onlySelect)
          $('#checkAll').attr('checked', true);

        $.crmData.search.countActivated();
      },

      load: function(sf_id) {

        var $languageID = $('#pdfLanguageID');
        var $pageNumber = $('#page_number');
        var $low = $('#low');

        $languageID.val($.crmData.pdfSettings.languageID);
        $pageNumber.val($.crmData.pdfSettings.pageNumber);
        $low.attr('checked', $.crmData.pdfSettings.low ? true : false);

        $languageID.change(function() {
          $.crmData.pdfSettings.languageID = $(this).val();
        });
        $pageNumber.change(function() {
          $.crmData.pdfSettings.pageNumber = $(this).val();
        });
        $low.change(function() {
          $.crmData.pdfSettings.low = $(this).is(':checked');
        });

        $('#limit').keyup(function(e) {
          if(e.which != 13)
            return;
          $.crmData.search.switch_limit();
        });
        $('#page').keyup(function(e) {
          if(e.which != 13)
            return;
          $.crmData.search.flip_page();
        });
        $.crmData.search.switch_field();
      },

      open_close_group: function(tblgr_id) {
        $.brandbox.get(
          $.brandbox.mod,
          $.brandbox.tpl,
          'OpenCloseGroup',
          '&tblgr_id=' + tblgr_id
        );
      },

      switch_table: function(tbl_id) {
        $.crmData.list.active = [];
        $.brandbox.get(
          $.brandbox.mod,
          $.brandbox.tpl,
          'SwitchTable',
          '&tbl_id=' + tbl_id
        );
      },

      isIndex: true,
      switch_field: function(sf_id) {
        $('.q').keyup(function(e) {
          if(e.which == 17 || e.ctrlKey || e.metaKey) // Ctrl
            return;
          if(e.which == 16) // Shift
            return;
          if(e.which == 37 || e.which == 38 || e.which == 39 || e.which == 10) // Pfeile
            return;
          $.crmData.search.isIndex = e.which == 13;
          $.doTimeout('text-type', 200, function() {
            $.crmData.search.submit();
          });
        });
        $('#q[type="checkbox"]').change(function() {
          $.crmData.search.submit();
        });
        $('select#q').change(function() {
          $.crmData.search.submit();
        });
      },

      switch_language: function(lan_id) {
        $.brandbox.get(
          $.brandbox.mod,
          $.brandbox.tpl,
          'SwitchLanguage',
          '&lan_id=' + lan_id
        );
      },

      switch_limit: function() {
        $.brandbox.get(
          $.brandbox.mod,
          $.brandbox.tpl,
          'SwitchLimit',
          '&limit=' + encodeURIComponent($('#limit').val())
        );
      },

      switch_order: function(of_id, odirection) {
        $.brandbox.get(
          $.brandbox.mod,
          $.brandbox.tpl,
          'SwitchOrder',
          '&of_id=' + of_id +
          '&odirection=' + odirection
        );
      },

      flip_page: function(page) {
        $.brandbox.get(
          $.brandbox.mod,
          $.brandbox.tpl,
          'FlipPage',
          '&page=' + (page ? page : $('#page').val())
        );
      },

      flip_offset: function(offset) {
        $.brandbox.get(
          $.brandbox.mod,
          $.brandbox.tpl,
          'FlipOffset',
          '&offset=' + offset
        );
      },

      searching: false,
      submit: function() {

        if($.crmData.search.searching == true)
          return;

        $.crmData.search.searching = true;

        if(!$.crmData.search.isIndex) {
          var callbackOfLiveSearch = function(data) {
            $('div.main').html(data);
            $.crmData.search.searching = false;
          };
        } else {
          var callbackOfLiveSearch = null;
          $.crmData.search.searching = false;
        }

        $.brandbox.silent();
        $.brandbox.get(
          $.brandbox.mod,
          $.crmData.search.isIndex ? $.brandbox.tpl : 'Search',
          'Search',
          '&' + $('.q').serialize() +
          '&useTpl=' + $.brandbox.tpl,
          callbackOfLiveSearch
        );
      }
    },

    bindCtrlA: function () {
      $('.ctrlA').on('keydown', function (event) {
        if (event.ctrlKey && event.keyCode == 65) {
          event.preventDefault();
          $.crmData.search.checkAll(true);
        }
      });
    },

    toggleWiderSidebar: function(sender) {
      $.brandbox.widerSidebar.toggle(sender);
      $('.main').getNiceScroll().resize();
    },

    list: {

      active: [[]],
      tbl_id: 0,

      load: function(unselect) {

        if(unselect)
          $.crmData.list.active = [];

        $.crmData.list.tbl_id = $('#tbl_id').val();

        if(!$.crmData.list.active[$.crmData.list.tbl_id])
          $.crmData.list.active[$.crmData.list.tbl_id] = [];

        $.each(
          $('div.main *[name="content"]'),
          function() {
            if($.inArray($(this).attr('id'), $.crmData.list.active[$.crmData.list.tbl_id]) != -1) {
              $(this).addClass('active')
              $(this).find('input[type="checkbox"]').attr('checked', true);
            }
          }
        );
        $.crmData.search.countActivated();

        $.crmData.bindCtrlA();
      },

      IDs: function(id) {

        var cn_ids = [];
        if(id) {
          cn_ids.push(id);
        } else if(
          $.crmData.list.active[$.crmData.list.tbl_id].length != 0 &&
          $.brandbox.element &&
          $.inArray($.brandbox.element.attr('id'), $.crmData.list.active[$.crmData.list.tbl_id]) != -1
        ) {
          cn_ids = $.crmData.list.active[$.crmData.list.tbl_id];
        } else if($.brandbox.element) {
          cn_ids.push($.brandbox.element.attr('id'));
        }

        return cn_ids;
      },

      copy_with_relations: function(cn_id) {
        $.brandbox.get(
          $.brandbox.mod,
          'Index',
          'CopyWithRelations',
          '&cn_id=' + (cn_id ? cn_id : $.brandbox.element.attr('id'))
        );
      },

      copy_no_relations: function(cn_id) {
        $.brandbox.get(
          $.brandbox.mod,
          'Index',
          'CopyNoRelations',
          '&cn_id=' + (cn_id ? cn_id : $.brandbox.element.attr('id'))
        );
      },

      versions: function() {
        $.brandbox.get(
          $.brandbox.mod,
          'Versions',
          '',
          '&cn_ids=' + $.brandbox.element.attr('id') +
          '&lan_id=' + $('#lan_id').val() +
          '&tbl_id=' + $('#tbl_id').val()
        );
      },

      edit: function(cn_id, lan_id, f_group, tbl_id, editContentID) {
        if(!lan_id)
          lan_id = 1;
        if(!f_group)
          f_group = 0;
        if(!tbl_id)
          tbl_id = $('#tbl_id').val();
        $.brandbox.get(
          $.brandbox.mod,
          'Edit',
          '',
          '&tbl_id=' + tbl_id +
          '&f_group=' + f_group +
          '&lan_id=' + lan_id +
          '&cn_ids=' + this.IDs(cn_id).join(',') +
          (editContentID ? '&editContentID=' + editContentID : '')
        );
      },

      lastActivated: null,

      click_cell: function(e, cell) {

        var f_id = $(cell).attr('rel');
        var row = $(cell).parent();
        var cn_id = row.attr('id');

        if(e.shiftKey) {

          var elements =  $('div.main *[name="content"]');

          $.each(
            elements,
            function() {
              if($.inArray($(this).attr('id'), $.crmData.list.active[$.crmData.list.tbl_id]) != -1)
                $(this).removeClass('active')

              var cn_id = $(this).attr('id');
              $.crmData.list.active[$.crmData.list.tbl_id] = $.grep($.crmData.list.active[$.crmData.list.tbl_id], function(value) {
                return value != cn_id;
              });
              var box = $(this).find('input[type="checkbox"]');
              if($(box).is(':checked')) {
                $(box).attr('checked', false);
              }
            }
          );

          var counter = 0;
          var toBeSelected = [];

          $(elements).each(function() {

            var isFrameElement = $(this).attr('id') == cn_id || $(this).attr('id') == $($.crmData.list.lastActivated).attr('id');
            if($(this).attr('id') == $(row).attr('id')) {
              counter += 1;
            }
            if($(this).attr('id') == $($.crmData.list.lastActivated).attr('id')) {
              counter += 1;
            }

            if(counter == 1 || isFrameElement) {
              toBeSelected.push(this);
            }
          });

          $(toBeSelected).addClass('active');
          $(toBeSelected).each(function() {
            $.crmData.list.active[$.crmData.list.tbl_id].push($(this).attr('id'));
          });
          $(toBeSelected).find('input[type="checkbox"]').attr('checked', true);

        } else if(e.ctrlKey || e.metaKey || f_id == 'multiselect') {

          row.toggleClass('active');
          var $checkBox = row.find('.multiselect input[type=checkbox]');

          if(row.hasClass('active')) {
            $checkBox.attr('checked', 'checked');
            $.crmData.list.lastActivated = row;
          } else {
            $checkBox.removeAttr('checked', 'checked');
          }

          if(row.hasClass('active') && $.inArray(cn_id, $.crmData.list.active[$.crmData.list.tbl_id]) == -1) {
            $.crmData.list.active[$.crmData.list.tbl_id].push(cn_id);
          } else {
            $.crmData.list.active[$.crmData.list.tbl_id] = $.grep($.crmData.list.active[$.crmData.list.tbl_id], function(value) {
              return value != cn_id;
            });
          }

        }  else if(!f_id) {

          return;

        } else if(f_id == 'children' || f_id == 'parents') {

          this.edit_relations(e, cell, f_id, cn_id);

        } else if(f_id == 'collection') {

          this.edit_collection(e, cell, cn_id);

        } else if(f_id == 'user') {

          this.edit_jobs(e, cell, cn_id);

        } else if(f_id == 'fields') {

          this.edit_fields(e, cell, cn_id);

        } else {

          this.edit_cell(e, cell, f_id, cn_id);

        }

        $.crmData.search.countActivated();
      },

      edit_relations: function(e, cell, type, cn_id) {

        $.brandbox.get(
          $.brandbox.mod,
          'Relations',
          '',
          '&type=' + type +
          '&cn_id=' + cn_id +
          '&tbl_id=' + $('#tbl_id').val()
        );
      },

      edit_collection: function(e, cell, cn_id) {

        $.brandbox.get(
          $.brandbox.mod,
          'Collection',
          '',
          '&cn_id=' + cn_id
        );
      },

      edit_jobs: function(e, cell, cn_id) {

        $.brandbox.get(
          'job',
          'Index',
          'CatchByUserID',
          '&user_id=' + cn_id
        );
      },

      edit_fields: function(e, cell, cn_id) {

        $.brandbox.get(
          $.brandbox.mod,
          'Fields',
          '',
          '&cn_id=' + cn_id
        );
      },

      edit_cell: function(e, cell, f_id, cn_id) {

        if($.brandbox.responsive.isMobileDevice != false)
          return;

        var $content = $('div#content');
        var scrollLeft, x, y;

        scrollLeft = $content.scrollLeft();
        x = mouseX + scrollLeft;
        y = mouseY + $content.scrollTop();

        this.edit_cell_off();

        $.brandbox.silent();
        $.brandbox.get(
          $.brandbox.mod,
          'EditCell',
          '',
          '&lan_id=' + $('#lan_id').val() +
          '&cn_id=' + cn_id +
          '&f_id=' + f_id,
          function(data) {
            $('div#content form').append(data);

            $('#editCellClose .fa').click(function(e) {
              $.crmData.list.edit_cell_off(e);
            });

            $('div#content').scrollLeft(scrollLeft);
            $(document).keyup(function(e) {
              if(e.which == 27 || e.keyCode == 27) {
                $('div#content form div#edit_cell').remove();
              } else if((e.ctrlKey || e.metaKey) && e.which == 83) {
                $.crmData.list.edit_cell_save();
              }
            });
          }
        );
      },

      edit_cell_off: function() {

        if($('div#content form div#edit_cell'))
          $('div#content form div#edit_cell').remove();

      },

      edit_cell_save: function() {
        $.brandbox.post(
          $.brandbox.mod,
          'Index',
          'EditCellSave',
          function(data) {
            $('table.editable tr#' + $('#cn_id').val() + ' td[rel="' + $('#f_id').val() + '"]').html(data);
            $('div#content form div#edit_cell').remove();
          }
        );
      },

      mails: function (cn_id) {
        $.brandbox.get(
          $.brandbox.mod,
          'Mails',
          '',
          '&cn_id=' + (cn_id ? cn_id : $.brandbox.element.attr('id'))
        );
      },

      move: function(cn_id) {
        $.brandbox.get(
          $.brandbox.mod,
          'Move',
          '',
          '&cn_id=' + (cn_id ? cn_id : $.brandbox.element.attr('id'))
        );
      },

      pdf: function(tpl_id) {
        $.brandbox.showBootstrapModal(
          'admin.php' +
          '?PHPSESSID=' + $.brandbox.session_id +
          '&mod=pdf' +
          '&tpl=Index' +
          '&engine=content' +
          '&lan_id=' + $('#pdfLanguageID').val() +
          '&tbl_id=' + $('#tbl_id').val() +
          '&cn_id=' + $.brandbox.element.attr('id') +
          '&tpl_id=' + tpl_id +
          '&page=' + $('#page_number').val() +
          ($('#low').is(':checked') != 0 ? '&low' : ''),
          '', 'small', true, true
        );
      },

      deleteDataset: function(cn_id) {

        var cn_ids = $.crmData.list.IDs(cn_id);

        for(var c in cn_ids) {
          var id = cn_ids[c];
          $('#' + id + ' td').addClass('delete');
          setTimeout("$('#" + id + " td').removeClass('delete');", 2000);
        }

        $.alerts.confirm(
          $.i18n.__(24),
          function() {
            $.brandbox.get(
              $.brandbox.mod,
              'Index',
              'Delete',
              '&cn_ids=' + cn_ids.join(',')
            );
            $.crmData.list.active = [];
          }
        );
      }
    },

    relations: {

      init: function(activeRows, type) {
        var activeRowsArray = activeRows.split(',');
        if(activeRows.length > 1) {
          $.crmData.relations.initActiveRows(activeRowsArray);
        }

        var $rows = $('.main ol li');
        $rows.on('click', function(e) {
          if($(e.target).hasClass('checkbox'))
            return;

          var $checkbox = $(this).find('input[type=checkbox]');
          if($checkbox.is(':checked')) {
            $checkbox.attr('checked', false);
          } else {
            $checkbox.attr('checked', true);
          }
        });

        if(type == 'children') {
          $.crmData.relations.attachSortable();
        }
      },

      initActiveRows: function(activeRows) {

        $(activeRows).each(function() {
          $('#'+this).find('input').attr('checked', true);
        });
      },

      attachSortable: function() {
        $('.sortable').sortable({
          handle: 'p',
          items: 'li',
          toleranceElement: '> p',
          placeholder: 'placeholder',

          start: function(event, ui) {
            $(ui.item).find('input').attr('checked', true);

            $('.sortable input').each(function () {
              if($(this).is(':checked')) {
                $(this).closest('li').hide();
              }
            });
          },
          stop: function(event, ui) {
            var $row = $(ui.item);
            var rows = [];
            var previousID = $row.prev().attr('id');
            var itemID = $row.attr('id');
            var rel = $row.attr('rel');
            var name = $row.attr('name');

            $('.sortable input').each(function () {
              if($(this).is(':checked')) {
                rows.push($(this).closest('li').attr('id'));
              }
            });

            $.brandbox.draggable.send(
              rows,
              previousID,
              rel,
              name
            );
          }
        });
      },

      checkAll: function(forceCheck) {
        var $checkboxes = $('.main').find('input[type="checkbox"]');

        if($('#checkAll').is(':checked')) {
          $checkboxes.attr('checked', true);
        }
        else {
          $checkboxes.attr('checked', false);
        }

        if(forceCheck) {
          $('#checkAll').attr('checked', true);
          $checkboxes.attr('checked', true);
        }
      },

      edit: function(cn_id, lan_id, f_group) {

        if(!lan_id)
          lan_id = 1;
        if(!f_group)
          f_group = 0;
        $.brandbox.get(
          $.brandbox.mod,
          'RelationEdit',
          '',
          '&f_group=' + f_group +
          '&lan_id=' + lan_id +
          '&cn_id=' + (cn_id ? cn_id : $.brandbox.element.attr('id'))
        );
      },

      editDataset: function() {
        var relation = $.brandbox.element.attr('rel').split('-');
        var tableID = $('#rtbl_id').val();
        var tableType = $('[data-tableID=' + tableID + ']').attr('data-tableType');
        var contentID = relation[1];
        var relationID = relation[0];

        if(tableType == 'media') {

          $.brandbox.getScript(
            'application/modules/media/mod.js',
            function() {
              $.media.file.edit(contentID);
            }
          );

        } else {
          $.crmData.list.edit(contentID, 1, 0, tableID, relationID);
        }
      },

      save: function() {
        $.brandbox.post($.brandbox.mod, 'Relations', 'Save');
      },

      close: function() {
        $.brandbox.post($.brandbox.mod, 'Relations', '');
      },

      switch_language: function(lan_id) {
        $.crmData.edit.check_changes(function() {
          $.crmData.relations.edit($('#cn_ids').val(), lan_id, $('#f_group').val());
        });
      },

      switch_group: function(f_group) {
        $.crmData.edit.check_changes(function() {
          $.crmData.relations.edit($('#cn_id').val(), $('#lan_id').val(), f_group);
        });
      },

      switch_table: function(rtbl_id, type, cn_id) {
        $.brandbox.get(
          $.brandbox.mod,
          'Relations',
          'SwitchRelationTable',
          '&tbl_id=' + $('#tableID').val() +
          '&rtbl_id=' + rtbl_id +
          '&type=' + type +
          '&cn_id=' + cn_id
        );
      },

      remove: function(cr_id, type, cn_id) {

        if(!$.crmData.relations.removeRelations()) {
          $.alerts.confirm(
            $.i18n.__(26),
            function() {
              var rel = $.brandbox.element.attr('rel').split('-');
              $.brandbox.get(
                $.brandbox.mod,
                'Relations',
                'RelationRemove',
                '&type=' + $('#type').val() +
                '&tbl_id=' + $('#tableID').val() +
                '&cn_id=' + (cn_id ? cn_id : rel[0]) +
                '&cr_id=' + (cr_id ? cr_id : $.brandbox.element.attr('id'))
              );
            }
          );
        }
      },

      removeRelations: function() {
        var selected = [];

        var $checkedRelations = $(".main input:checkbox:checked");
        $checkedRelations.each(function() {
          selected.push($(this).val());
        });

        if($checkedRelations.length == 0)
          return false;

        selected = selected.join("x");

        $.alerts.confirm(
          $.i18n.__(26),
          function() {
            $.brandbox.get(
              $.brandbox.mod,
              'Relations',
              'RelationsRemove',
              '&type=' + $('#type').val() +
              '&tbl_id=' + $('#tableID').val() +
              '&cn_id=' + (cn_id ? cn_id : 0) +
              '&cr_ids=' + selected
            );
          }
        );

        return $checkedRelations.length > 0;
      }
    },

    relations_add: {

      click: function(e, cell) {
        var row = $(cell).parent();
        var cn_id = row.attr('id');
        var checkbox = $('input[type="checkbox"][value="'+cn_id+'"]');

        if($(cell).hasClass('checkboxColumn')) {
          row.toggleClass('active');

          if(!$(e.target).hasClass('checkbox')) {
            if(checkbox.is(':checked')) {
              checkbox.attr('checked', false);
            } else {
              checkbox.attr('checked', true);
            }
          }

          return;
        }

        if(e.ctrlKey || e.metaKey) {
          row.toggleClass('active');

          if(checkbox.is(':checked')) {
            checkbox.attr('checked', false);
          } else {
            checkbox.attr('checked', true);
          }

          return;
        }

        var relationIDs = $('.main input[type="checkbox"]');
        var checkedRelationIDs = [];
        $(relationIDs).each(function() {
          if($(this).is(':checked')) {
            checkedRelationIDs.push($(this).val());
          }
        });

        $.alerts.confirm(
          $.i18n.__(checkedRelationIDs.length == 0 ? 63 : 62),
          function() {

            $.brandbox.get(
              $.brandbox.mod,
              'Relations',
              'RelationAdd',
              '&cn_ids=' + (checkedRelationIDs.length != 0 ? checkedRelationIDs : $.crmData.list.IDs(cn_id)) +
              '&tbl_id=' + $('#tableID').val()

            );
          }
        );
      }
    },

    collection: {

      open: function(co_id) {

        $.brandbox.get(
          'collection',
          'Index',
          'Open',
          '&co_id=' + co_id
        );
      }
    },

    collection_add: {

      latestRow: null,

      checkAllSimplified: function () {
        var rows = $('.main tbody tr');
        rows.addClass('active');

        rows.find('input[type="checkbox"]').attr('checked', true);
      },

      uncheckAllSimplified: function () {
        var rows = $('.main tbody tr');
        rows.removeClass('active');

        rows.find('input[type="checkbox"]').attr('checked', false);
      },

      bindCtrlASimplified: function () {
        $('.ctrlA').on('keydown', function (event) {
          if (event.ctrlKey && event.keyCode == 65) {
            event.preventDefault();

            $.crmData.collection_add.checkAllSimplified();
            $('#checkAll').attr('checked', true);

            $.crmData.collection_add.countActivatedSimplified();
          }
        });
      },

      countActivatedSimplified: function() {
        $('#numberOfActivated').html($('tr.active').length);
      },

      bindCheckAllSimplified: function() {
        $('#checkAll').on('click', function() {
          if($(this).is(':checked')) {
            $.crmData.collection_add.checkAllSimplified();
          } else {
            $.crmData.collection_add.uncheckAllSimplified();
          }

          $.crmData.collection_add.countActivatedSimplified();
        });
      },

      click: function(e, cell) {
        var
          row = $(cell).parent(),
          cn_id = row.attr('id');

        if(e.shiftKey) {
          var elements =  $('.main tbody tr');

          elements.removeClass('active')
          var boxes = elements.find('input[type="checkbox"]');
          $(boxes).attr('checked', false);

          var counter = 0;
          var toBeSelected = [];

          $(elements).each(function() {

            var isFrameElement = $(this).attr('id') == cn_id || $(this).attr('id') == $.crmData.collection_add.latestRow;
            if($(this).attr('id') == $(row).attr('id')) {
              counter += 1;
            }
            if($(this).attr('id') == $.crmData.collection_add.latestRow) {
              counter += 1;
            }

            if(counter == 1 || isFrameElement) {
              toBeSelected.push(this);
            }
          });

          $(toBeSelected).addClass('active');
          $(toBeSelected).find('input[type="checkbox"]').attr('checked', true);

          $.crmData.collection_add.countActivatedSimplified();

          return;

        } else if($(e.target).hasClass('multiselect')) {
          var checkbox = $(e.target).find('input');

          if(checkbox.is(':checked')) {
            checkbox.attr('checked', false);
            checkbox.closest('tr').removeClass('active');
          } else {
            checkbox.attr('checked', true);
            checkbox.closest('tr').addClass('active');
          }
          $.crmData.collection_add.countActivatedSimplified();
          $.crmData.collection_add.latestRow = cn_id;

          return;
        } else if($(e.target).attr('type') == 'checkbox') {
          var checkbox = $(e.target);

          if(checkbox.is(':checked')) {
            checkbox.closest('tr').addClass('active');
          } else {
            checkbox.closest('tr').removeClass('active');
          }
          $.crmData.collection_add.countActivatedSimplified();
          $.crmData.collection_add.latestRow = cn_id;

          return;
        }
        if(e.ctrlKey || e.metaKey) {
          row.toggleClass('active');
          var checkbox = row.find('.multiselect input');

          if(row.hasClass('active')) {
            checkbox.attr('checked', true);
          } else {
            checkbox.attr('checked', false);
          }
          $.crmData.collection_add.countActivatedSimplified();
          $.crmData.collection_add.latestRow = cn_id;

          return;
        }

        var activeRows =
          $('table.linked tr.active');

        $.alerts.confirm(
          $.i18n.__(activeRows.length == 0 ? 63 : 62),
          function() {

            var cn_ids = [];

            $.each(
              activeRows,
              function() {
                cn_ids.push($(this).attr('id'));
              }
            );

            if(cn_ids.length == 0)
              cn_ids.push(cn_id);

            $.brandbox.get(
              'collection',
              'Index',
              'AddDataset',
              '&cn_ids=' + cn_ids.join(',')
            );
          }
        );
      }
    },

    edit: {

      progressIDs: '',
      progress: function(cn_ids) {

        if(cn_ids)
          this.progressIDs = cn_ids;

        if(this.progressIDs == $('#cn_ids').val())
          setTimeout('$.crmData.edit.progress();', 60*1000);

        $.brandbox.get(
          $.brandbox.mod,
          'Index',
          'InProgress',
          '&cn_ids=' + $('#cn_ids').val() +
          '&tbl_id=' + $('#tbl_id').val(),
          function() {}
        );
      },

      switch_language: function(lan_id, f_group) {
        $.crmData.edit.check_changes(function() {
          $.brandbox.get(
            $.brandbox.mod,
            'Edit',
            'SwitchEditLanguage',
            '&f_group=' + (f_group || f_group == 0 ? f_group : $('#f_group').val()) +
            '&tbl_id=' + $('#tbl_id').val() +
            '&lan_ids=' + $('#lan_id').val() +
            '&lan_id=' + lan_id +
            '&cn_id=' + $('#cn_id').val() +
            '&cn_ids=' + $('#cn_ids').val()
          );
        });
      },

      copyFromLanguage: function(languageID) {

        var languageIDs = $('#lan_id').val().split(',');
        var selectors = [
          'div.main input[id^="f_' + languageID + '"]',
          'div.main textarea[id^="f_' + languageID + '"]',
          'div.main select[id^="f_' + languageID + '"]'
        ];

        var fieldNames = $(selectors.join(','));
        var fieldIDs = [];

        $.each(
					fieldNames,
					function() {
						var tagName = $(this).context.tagName.toLowerCase();
            var fieldID = $(this).attr('id').split('_')[2];
						var fieldType = tagName == 'input' ? $(this).attr('type') : tagName;
            var field = {
              'id': fieldID,
              'type': fieldType.toLowerCase()
            };
						fieldIDs.push(field);
					}
				);

				for(var c = 0; c < fieldIDs.length; c++) {

          var srcField = $('#f_' + languageID + '_' + fieldIDs[c].id);

          for(var d = 0; d < languageIDs.length; d++) {

            var languageIDOfThisRun = languageIDs[d];
            if(languageIDOfThisRun == languageID)
              continue;

            var fieldOfThisRun = $('#f_' + languageIDOfThisRun + '_' + fieldIDs[c].id);

            if(fieldOfThisRun.length == 0)
              continue;

            switch(fieldIDs[c].type) {

              case 'radio':
                var srcRadio = eval('document.forms[0].f_' + languageID + '_' + fieldIDs[c].id);
                var tgtRadio = eval('document.forms[0].f_' + languageIDOfThisRun + '_' + fieldIDs[c].id);
                for(var e = 0; e < srcRadio.length; e++)
                  tgtRadio[e].checked = srcRadio[e].checked;
                break;

              case 'checkbox':
                fieldOfThisRun.attr('checked', srcField.attr('checked'));
                break;

              default:
              case 'input':
              case 'textarea':
              case 'select':
                fieldOfThisRun.val(srcField.val());
                break;

            }
          }
        }
      },

      highlightedLanguage: 0,
      highlightLanguage: function(languageID) {
        $('fieldset').removeClass('markup');

        if($('.content-edit li').length < 2)
          return;

        if(!languageID) {
          var languageID = $.crmData.edit.highlightedLanguage;
          $.crmData.edit.highlightedLanguage = 0;
        }

        if(this.highlightedLanguage != languageID) {
          $('fieldset.lang' + languageID).addClass('markup');
          this.highlightedLanguage = languageID;
        } else {
          this.highlightedLanguage = 0;
        }
      },

      switch_group: function(f_group) {
        $.crmData.edit.check_changes(function() {
          $.crmData.edit.switch_language(0, f_group);
        });
      },

      showVersions: function(lan_id, tbl_id, cn_id) {
        $.brandbox.get(
          'content',
          'Versions',
          '',
          '&lan_id=' + (lan_id ? lan_id : $('#lan_id').val()) +
          '&tbl_id=' + (tbl_id ? tbl_id : $('#tbl_id').val()) +
          '&cn_ids=' + (cn_id ? cn_id : $('#cn_ids').val())
        );
      },

      show_exif: function(cn_id) {
        $.brandbox.get(
          'media',
          'Exif',
          '',
          '&cn_ids=' + (cn_id ? cn_id : $('#cn_ids').val()),
          function(data) {
            var $exif = $('div.exif');
            if($exif.length != 0)
              $exif.remove();

            $('div.main').append(data);
          }
        );
      },

      editContentID: '',
      save: function(tpl) {
        if(tpl == 'Edit') {
          var $editContentID = $('#editContentID');
          this.editContentID = $editContentID.val();
          $editContentID.val('');
        }

        $.brandbox.post(
          $.brandbox.mod, tpl, 'Save',
          function(data) {
            if(data == 'exists') {
              $.alerts.alert($.i18n.__(90));
            } else if(data == 'fileRename') {
              $.alerts.alert($.i18n.__(97));
            } else {
              if(tpl == 'Index') {
                $.brandbox.backToIndex();
              } else {
                $.brandbox.sidebar.setMovedMargin(false);
                var $html = $.brandbox.sidebar.moveMarginInRawHTML(data);
                $('#content').html($html);
                $('#editContentID').val($.crmData.edit.editContentID);
                $.crmData.edit.editContentID = '';
              }
            }
          }
        );
      },

      check_changes: function(callback) {
        var store = this.store();

        if(this.stored != '' && store != this.stored && !$.crmData.edit.ignoreCheckChanges) {

          $.alerts.confirm(
            $.i18n.__('errorChanges'),
            function() {
              $.crmData.edit.ignoreCheckChanges = true;
              callback();
              $.crmData.edit.stored = store;
              $.crmData.edit.ignoreCheckChanges = false;
            }
          );

        } else {
          callback();
          $.crmData.edit.stored = store;
          $.crmData.edit.ignoreCheckChanges = false;
        }
      },

      stored: '',
      ignoreCheckChanges: false,
      store: function() {

        var current = [];
        var not = '[class^=select2]';

        $.each(
          $('fieldset input:not(' + not + '), fieldset textarea, fieldset select'),
          function() {

            var $type = $(this).attr('type');

            if($type == 'button')
              return;
            if($type == 'submit')
              return;
            if(!$(this).is(':visible') && !$(this).hasClass('select2-offscreen'))
              return;

            current.push($(this).serialize());
          }
        );

        return current.join('&').toLowerCase();

      },

      storeLoad: function() {
        $.crmData.edit.stored = $.crmData.edit.store();
      },

      edit_dataset: function(cn_id, tbl_id) {

        $.crmData.edit.check_changes(function() {
          $.crmData.list.edit(cn_id, null, null, tbl_id);
        });
      },

      edit_website: function(page_id) {
        alert('@TODO');
      }
    },

    fields: {

      add: function(cn_id) {
        $.brandbox.field.add(
          'content',
          cn_id,
          function(cn_id) {
            $.brandbox.get(
              'content',
              'Fields',
              '',
              '&cn_id=' + cn_id
            );
          }
        );
      },

      editField: function(f_id) {
        $.brandbox.get(
          'field',
          'Edit',
          '',
          '&f_id=' + (f_id ? f_id : $.brandbox.element.attr('id'))
        );
      },

      deleteField: function(f_id, cn_id) {

        $.alerts.confirm(
          $.i18n.__(23),
          function() {
            $.brandbox.get(
              $.brandbox.mod,
              'Fields',
              'DeleteField',
              '&cn_id=' + (cn_id ? cn_id : $('#cn_id').val()) +
              '&f_id=' + (f_id ? f_id : $.brandbox.element.attr('id'))
            );
          }
        );
      }
    },

    mails: {

      load: function() {
        $('input[type="checkbox"]').click(function() {

          $.brandbox.silent();
          $.brandbox.get(
            $.brandbox.mod,
            'Index',
            'SetMail',
            '&rel=' + $(this).attr('rel') +
            '&u_id=' + $(this).parent().parent().attr('id') +
            '&u_tbl_id=' + $('#tbl_id').val() +
            '&article_id=' + $('#cn_id').val() +
            '&on=' + ($(this).attr('checked') ? 1 : 0),
            function() {}
          );
        });
      },

      switch_table: function(tbl_id) {
        $.brandbox.get(
          $.brandbox.mod,
          $.brandbox.tpl,
          'SwitchMailsTable',
          '&tbl_id=' + tbl_id +
          '&cn_id=' + $('#cn_id').val()
        );
      }
    },

    move: {

      select: function(tbl_id) {
        $.brandbox.get(
          $.brandbox.mod,
          'Index',
          'Move',
          '&tbl_id=' + tbl_id +
          '&cn_id=' + $('#cn_id').val()
        );
      }
    }
  };

})(jQuery);
