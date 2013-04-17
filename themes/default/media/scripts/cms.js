$(function(){
  var $editable = $('.editable');
  var model = 'brass_page';
  var post_url = '/annex/content/update/'+model;

  $editable.attr('contenteditable', true);

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
          if ( response.status == 'success' ) {
            $this.addClass('success');

            var ovt = setTimeout(function(){ $this.removeClass('success'); clearTimeout(ovt);}, 1000);
          }
        },
        dataType: 'json'
      });
    });

});