( function start( $ ) {
  function registerAddNewPanel() {
    function addNewPanel( event ) {
      var panels = $( '.wccf-panels-outer-wrapper' );
      var noPanels = $( '.wccf-no-panels' );
      event.preventDefault();

      panels.parent().append( $( panel_template ) );
      noPanels.hide();
      resetIndexes();
    }

    function removePanel( event ) {
      var noPanels = $( '.wccf-no-panels' );
      var panels;
      event.preventDefault();

      $( this )
        .closest( '.wccf-panels-wrapper' )
        .remove();

      panels = $( '.wccf-panels-wrapper' );
      if ( ! panels.length ) {
        noPanels.show();
      }
    }

    function resetIndexes() {
      var panelWrappers = $( '.wccf-panels-wrapper' );
      var index = 0;
      panelWrappers.each( function() {
        var fields = $( this ).find( 'input' );
        fields.each( function() {
          var nameAttribute = $( this ).attr( 'name' );
          var newNameAttribute = nameAttribute.replace( /wccf_panels\[\d*\]/, 'wccf_panels[' + index + ']' );
          $( this ).attr( 'name', newNameAttribute );
        });
        $( this )
          .find( '.wccf-icon' )
          .attr( 'data-index', index );
        index++;
      });
    }

    $( '.wccf-add-new-panel' ).on( 'click', addNewPanel );

    $( 'body' ).on( 'click', '.wccf-panels-remove', removePanel );

    $( 'body' ).on( 'focusout', '.wccf_input_name', function() {
      var value = $( this ).val();
      var sanitizedValue = wccfCreateSlug( value );
      var keys = $( this )
        .parent()
        .find( '.wccf_input_key' );
      if ( '' != keys.val() ) {
        return false;
      }

      keys.val( sanitizedValue );
    });

    $( '.wccf-icon-selector-bg' ).on( 'click', function( e ) {
      $( this )
        .parent()
        .removeClass( 'visible' );
    });

    $( 'body' ).on( 'click', '.wccf-panels-wrapper .wccf-icon', function( e ) {
      $( '.wccf-icon-selector' ).addClass( 'visible' );
      $( '.wccf-icon-selector' ).attr( 'data-index', $( this ).attr( 'data-index' ) );
    });

    $( 'body' ).on( 'click', '.wccf-icon-selector .wccf-icon', function() {
      var index = $( '.wccf-icon-selector' ).attr( 'data-index' );
      var className = $( this ).attr( 'class' ) + ' hide-text';
      $( '.wccf-icon[data-index="' + index + '"]' ).attr( 'class', className );
      $( 'input[name="wccf_panels[' + index + '][icon]"]' ).val( className );
      $( '.wccf-icon-selector' ).removeClass( 'visible' );
    });
  }

  registerAddNewPanel();
}( jQuery ) );
