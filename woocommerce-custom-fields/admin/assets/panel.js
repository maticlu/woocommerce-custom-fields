( function start( $ ) {
  function registerAddNewPanel() {
    function addNewPanel( event ) {
      var removeButton = $( '<a class="wccf_panels_remove" href="#">Remove</a>' ); // TODO STRINGS //TODO TEMPLATES
      var panels = $( '.wccf-panels-wrapper' );
      var panelFieldClone = panels.last().clone();
      event.preventDefault();
      panelFieldClone.find( '.wccf_panels' ).val( '' );
      panelFieldClone.find( '.wccf_panels_remove' ).remove();
      panelFieldClone.append( removeButton );
      panels.parent().append( panelFieldClone );
    }

    $( '.wccf-add-new-panel' ).on( 'click', addNewPanel );

    $( 'body' ).on( 'click', '.wccf_panels_remove', function( event ) {
      event.preventDefault();
      $( this ).closest( '.wccf-panels-wrapper' ).remove();
    });
  }


  registerAddNewPanel();
}( jQuery ) );
