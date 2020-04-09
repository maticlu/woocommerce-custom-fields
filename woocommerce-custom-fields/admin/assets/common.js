function wccfCreateSlug( string ) {
    var match;
    if ( 'string' != typeof string ) {
      return false;
    }
    match = string.match( /[A-Za-z0-9\s]/g );
    if ( null === match ) {
      return '';
    }
    return match
      .join( '' )
      .replace( /\s/g, '_' )
      .toLowerCase();
  }
