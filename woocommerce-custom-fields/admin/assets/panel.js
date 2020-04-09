(function start($) {

  function registerAddNewPanel() {

    function addNewPanel(event) {
      var removeButton = $('<a class="wccf_panels_remove" href="#">Remove</a>'); // TODO STRINGS //TODO TEMPLATES
      var panels = $('.wccf-panels-wrapper');
      var panelFieldClone = panels.last().clone();
      event.preventDefault();

      panelFieldClone.find('.wccf_panels').val('');
      console.log(panelFieldClone);
      panelFieldClone.find('.wccf_panels_remove').each(function(){
        $(this).remove();
      })
      panelFieldClone.append(removeButton);
      panels.parent().append(panelFieldClone);
      resetIndexes();
    }

    function resetIndexes(){
      var panelWrappers = $(".wccf-panels-wrapper");
      var index = 0;
      panelWrappers.each(function(){
          var fields = $(this).find("input");
          fields.each(function(){
              var nameAttribute = $(this).attr("name");
              var newNameAttribute = nameAttribute.replace(/wccf_panels\[\d*\]/, "wccf_panels["+index+"]");
              $(this).attr("name", newNameAttribute);
          });
          index++;
      });
    }

    $('.wccf-add-new-panel').on('click', addNewPanel);

    $('body').on('click', '.wccf_panels_remove', function(event) {
      event.preventDefault();
      $(this)
        .closest('.wccf-panels-wrapper')
        .remove();
    });

    $("body").on("focusout", ".wccf_input_name", function(){
      var keys = $(this).parent().find(".wccf_input_key");
      if(keys.val() != "") return false;
      var value = $(this).val();
      var sanitizedValue = wccfCreateSlug(value);
      
      keys.val(sanitizedValue);
    });
  }

  registerAddNewPanel();
})(jQuery);
