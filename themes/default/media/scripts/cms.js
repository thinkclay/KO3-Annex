$(function(){

  $('form.model-form input.dependent').each(function() {
    var dep = $(this).data('dependent');

    $('form.model-form input[name='+dep+']').on('ifChecked', function() {
      $('form.model-form .dependent[data-dependent='+dep+']').fadeIn();
    });

    $('form.model-form input[name='+dep+']').on('ifUnchecked', function() {
      $('form.model-form .dependent[data-dependent='+dep+']').fadeOut();
    });
  });

  $('table.administration thead').click(function() {
    $(this).next('tbody').toggle('slow');
  });

  function insertTextAtCursor(text)
  {
      var sel, range, textNode;
      if (window.getSelection)
      {
          sel = window.getSelection();

          if (sel.getRangeAt && sel.rangeCount)
          {
              range = sel.getRangeAt(0);
              range.deleteContents();
              textNode = document.createTextNode(text);
              range.insertNode(textNode);

              // Move caret to the end of the newly inserted text node
              range.setStart(textNode, textNode.length);
              range.setEnd(textNode, textNode.length);
              sel.removeAllRanges();
              sel.addRange(range);
          }
      }
      else if (document.selection && document.selection.createRange)
      {
          range = document.selection.createRange();
          range.pasteHTML(text);
      }
  }

  var $admin_bar = $('<form id="admin_bar" method="POST"><input type="hidden" name="access" value="ce4c5680e66fe7e79a112c14a0ed1e3e" /></form>'),
      $admin_genie = $('<span class="admin_action admin_genie">Inline Edit</span>'),
      $admin_content = $('<span class="admin_action admin_content">Data Models</span>'),
      $admin_devmode = $('<span class="admin_action admin_devmode">Dev Mode</span>'),
      $admin_actions = [$admin_genie, $admin_content, $admin_devmode];

  // Bind a selected state to all the toolbar buttons
  for ( i=0; i<$admin_actions.length; i++ ) {
    $admin_bar.append($admin_actions[i]);

    $admin_actions[i].bind('click', function(event) {
      $($admin_actions).each(function() { $(this).removeClass('selected'); });

      $(this).addClass('selected');
    });
  }

  $('body').append($admin_bar);

  $admin_devmode.bind('click', function() {
    $admin_bar.submit();
  });

  $admin_content.bind('click', function() { window.location = '/admin/content'; });

  $admin_genie.bind('click', function(event) {

    /**
     * Variable Definitions
     */
    var $editable = $('.editable'),
        $wysiwyg = $('.wysiwyg'),
        model = 'brass_page',
        post_url = '/admin/content/update/'+model;

    $editable
      .attr('contenteditable', true)
      .addClass('cms-edit')
      .bind('keydown', function(e) {
        if ( e.keyCode == 32 ) {
            insertTextAtCursor('\u00A0'); // a hack for always inserting a whitespace properly
        }
      });

    $editable
      .bind('focus', function() {
        $(this).addClass('editing');
      })
      .bind('blur', function() {
        var $this = $(this);

        $this.removeClass('editing');

        $.ajax({
          type: "POST",
          url: post_url,
          data: {
            'controller': controller,
            'action': action,
            'ajax': true,
            'path': $this.attr('data-cms'),
            'data': $this.text()
          },
          success: function(response) {
            console.log(response);

            if ( response.status == 'success' ) {
              $this.addClass('success');

              var ovt = setTimeout(function(){ $this.removeClass('success'); clearTimeout(ovt);}, 1000);
            }
          },
          dataType: 'json'
        });
      });

      var buttons = [
        'html', '|',
        'formatting', 'deleted', 'fontcolor', 'backcolor', 'alignment', '|',
        'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
        'image', 'video', 'file', 'table', 'link', '|',
        'horizontalrule', '|', 'save']

      $wysiwyg.redactor({
        buttons: buttons,
        buttonsCustom: {
            save: {
              title: 'Save Changes',
              callback: function(obj, event, key) {
                save_data($(this)[0].$editor.attr('data-cms'), $(this).get()[0].$editor[0].innerHTML);
              }
            }
        },
      });

      function save_data(endpoint, data)
      {
        $.ajax({
          type: "POST",
          url: post_url,
          data: {
            'controller': controller,
            'action': action,
            'ajax': true,
            'path': endpoint,
            'data': data
          },
          success: function(response) {
            console.log(response);
            alert('saved');
          },
          dataType: 'json'
        });
      }
  });
});