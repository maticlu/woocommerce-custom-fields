'use strict';

( function( $ ) {

  // Clickable fields

  var fields = {
    addField: '.wccf-add-field',
    selectField: '.wccf-select-field',
    saveField: '#wccf-fields'
  };

  // Static elements
  var wrappers = {
    global: '.wccf-global-field-wrapper'
  };

  var descriptions = {
    title: 'Main field label',
    key: 'Key is unique ID for fields. Be careful when creating keys. Duplicate keys will cause problems.',
    description: 'Description will guide user how to fill this input',
    options: 'Write field options in key value pairs seperated by : . Each key value pair should be in new line.'
  };

  // Available html fields for Woocommerce
  var fieldText = {
    name: 'Textbox',
    type: 'textbox',
    fields: [
      {
        type: 'text',
        name: 'Title',
        key: 'title',
        value: '',
        description: descriptions.title
      },
      {
        type: 'text',
        name: 'Key',
        key: 'key',
        value: '',
        description: descriptions.key
      },
      {
        type: 'text',
        name: 'Description',
        key: 'description',
        value: '',
        description: descriptions.description
      }
    ]
  };

  var fieldTextarea = {
    name: 'Textarea',
    type: 'textarea',
    fields: [
      {
        type: 'text',
        name: 'Title',
        key: 'title',
        value: '',
        description: descriptions.title
      },
      {
        type: 'text',
        name: 'Key',
        key: 'key',
        value: '',
        description: descriptions.key
      },
      {
        type: 'text',
        name: 'Description',
        key: 'description',
        value: '',
        description: descriptions.description
      }
    ]
  };

  var fieldRadiobuttons = {
    name: 'Radio button',
    type: 'radiobutton',
    fields: [
      {
        type: 'text',
        name: 'Title',
        key: 'title',
        value: '',
        description: descriptions.title
      },
      {
        type: 'text',
        name: 'Key',
        key: 'key',
        value: '',
        description: descriptions.key
      },
      {
        type: 'text',
        name: 'Description',
        key: 'description',
        value: '',
        description: descriptions.description
      },
      {
        type: 'options',
        name: 'Options',
        key: 'options',
        value: '',
        description: descriptions.options,
        options: [],
        optionIndex: 1
      }
    ]
  };

  var fieldDropdown = {
    name: 'Dropdown',
    type: 'dropdown',
    fields: [
      {
        type: 'text',
        name: 'Title',
        key: 'title',
        value: '',
        description: descriptions.title
      },
      {
        type: 'text',
        name: 'Key',
        key: 'key',
        value: '',
        description: descriptions.key
      },
      {
        type: 'options',
        name: 'Options',
        key: 'options',
        value: '',
        description: descriptions.options,
        options: [],
        optionIndex: 1
      }
    ]
  };

  var fieldCheckboxes = {
    name: 'Checkbox',
    type: 'checkbox',
    fields: [
      {
        type: 'text',
        name: 'Title',
        key: 'title',
        value: '',
        description: descriptions.title
      },
      {
        type: 'text',
        name: 'Key',
        key: 'key',
        value: '',
        description: descriptions.key
      }
    ]
  };

  var availableHtmlElements = {
    textbox: fieldText,
    textarea: fieldTextarea,
    radiobutton: fieldRadiobuttons,
    checkbox: fieldCheckboxes,
    dropdown: fieldDropdown
  };

  // This is our main object, which rerenders evertytime we make a change.
  var wccfReactiveForm = {
    index: 1,
    saveField: $( fields.saveField ),
    wrapper: wrappers.global,
    fields: [],
    add: function( field ) {
      var fieldIn = wccfCopyObject( field );
      fieldIn.id = this.index;
      this.index++;
      this.fields.push( fieldIn );
      this.render();
    },
    remove: function( index ) {
      var arrayIndex = this.findFieldGroup( index );
      console.log( arrayIndex );
      if ( false !== arrayIndex ) {
        this.fields.splice( arrayIndex, 1 );
        this.render();
      }
    },
    addOption: function( element ) {
      var fieldUpdateData = this.findField( element );
      var index;

      if ( fieldUpdateData ) {
        index = this.fields[fieldUpdateData.fieldGroup].fields[fieldUpdateData.field].optionIndex;
        this.fields[fieldUpdateData.fieldGroup].fields[fieldUpdateData.field].options.push({ index: index, value: '', text: '' });
        this.fields[fieldUpdateData.fieldGroup].fields[fieldUpdateData.field].optionIndex++;
      }
      this.render();
    },
    removeOption: function( element ) {
      var fieldRemoveData = this.findField( element );
      var fieldRemoveOptionIndex = this.findOption( fieldRemoveData, element.attr( 'data-option' ) );

      if ( fieldRemoveData && fieldRemoveOptionIndex ) {
        this.fields[fieldRemoveData.fieldGroup].fields[fieldRemoveData.field].options.splice( fieldRemoveOptionIndex, 1 );
        this.render();
      }
    },
    changeField: function( id, value ) {
      var i;
      for ( i in this.fields ) {
        if ( this.fields[i].id === parseInt( id ) ) {
          this.fields[i] = wccfCopyField( this.fields[i], value );
        }
      }
      this.render();
    },
    updateValue: function( element ) {
      var fieldUpdateData = this.findField( element );
      if ( fieldUpdateData ) {
        this.fields[fieldUpdateData.fieldGroup].fields[fieldUpdateData.field].value = element.val();
        this.syncWithInput();
      }
    },

    updateOptionValue: function( element ) {
      var fieldData = this.findField( element );
      var fieldOptionIndex = this.findOption( fieldData, element.attr( 'data-option' ) );
      var fieldType = element.attr( 'name' );

      if ( fieldData && fieldOptionIndex && fieldType ) {
        this.fields[fieldData.fieldGroup].fields[fieldData.field].options[fieldOptionIndex][fieldType] = element.val();
        this.syncWithInput();
      }
    },
    findFieldGroup: function( id ) {
      var i;
      var currentField;

      for ( i in this.fields ) {
        currentField = this.fields[i];
        if ( currentField.id == id ) {
          return i;
        }
      }
      return false;
    },
    findField: function( element ) {
      return this.findFieldLoop( element );
    },
    findFieldLoop: function( element ) {
      var index = parseInt( element.attr( 'data-index' ) );
      var key = element.attr( 'data-key' );
      var currentField;
      var currentInput;
      var i;
      var j;
      for ( i in this.fields ) {
        currentField = this.fields[i];

        if ( currentField.id !== index ) {
          continue;
        }

        for ( j in currentField.fields ) {
          currentInput = currentField.fields[j];

          if ( currentInput.key !== key ) {
            continue;
          }

          return {
            fieldGroup: i,
            field: j
          };
        }
      }

      return false;
    },
    findOption: function( fieldData, index ) {
      var options = this.fields[fieldData.fieldGroup].fields[fieldData.field].options;
      var currentOption;
      var i;

      if ( ! options ) {
        return false;
      }

      for ( i in options ) {
        currentOption = options[i];
        if ( currentOption.index == index ) {
          return i;
        }
      }
      return false;
    },
    syncWithInput: function() {
      if ( ! this.saveField.length ) {
        console.error( 'Save field is missing.' );
        return false;
      }
      this.saveField.val( JSON.stringify( this.fields ) );
    },
    render: function() {
      var i;
      var content;
      var messages = $( '.wccf-message' );
      $( this.wrapper ).empty();
      if ( this.fields.length ) {
        for ( i in this.fields ) {
          content = this.renderField( this.fields[i]);
          $( this.wrapper ).append( content );
        }
      } else {
        messages.show();
      }
      this.syncWithInput();
    },
    read: function() {

      // todo reading is wrong, dependencies between objects.
      var readIndex = 0;
      var i;
      var currentId;
      var fields;
      var saveFieldValue;

      if ( ! this.saveField.length ) {
        console.error( 'Save field is missing.' );
        return false;
      }

      saveFieldValue = this.saveField.val();

      if ( 0 < saveFieldValue.length ) {
        fields = JSON.parse( saveFieldValue );

        // Get highest ID so we can start from there. We don't want them to collide.
        for ( i in fields ) {
          currentId = fields[i].id;

          if ( currentId > readIndex ) {
            readIndex = currentId;
          }
        }

        this.index = readIndex + 1;
        this.fields = fields;
        this.render();
      }
    },
    renderField: function( field ) {
      var fields = field.fields;
      var content = '';
      var i;
      var currentField;
      var $functionName;
      for ( i in fields ) {
        currentField = fields[i];
        $functionName = 'renderField' + capitalizeString( currentField.type );

        try {
          content += this.renderFieldLineWrapper( this[$functionName]( currentField, field.id ) );
        } catch ( error ) {
          console.error( error, $functionName );
          console.error( 'Field ' + currentField.type + ' implementation is missing.' );
        }
      }

      return this.renderFieldWrapper( content, field );
    },
    renderFieldLineWrapper: function( fieldContent ) {
      return '<div class="wccf-field-line">' + fieldContent + '</div>';
    },
    renderFieldWrapper: function( fieldContent, field ) {
      var fieldWrapper = $( '<div class="wcc-postbox-border wccf-space-wrapper wccf-wrapper">' );
      var fieldRemove = $( '<div data-index="' + field.id + '" class="wccf-field-remove"></div>' );
      var fieldInnerWrapper = $( '<div class="wccf-wrapper wcc-postbox-border">' );
      var fieldSelector = wccfFieldSelector( field );

      fieldWrapper.append( fieldRemove ).append( fieldSelector );
      fieldInnerWrapper.append( fieldContent );
      fieldWrapper.append( fieldInnerWrapper );

      return fieldWrapper;
    },
    renderFieldText: function( field, id ) {
      return this.renderFieldLabel( field ) + this.renderFieldInput( '<input ' + this.getFieldAttributes( field, id ) + ' type="text" value="' + field.value + '" />' );
    },
    renderFieldTextarea: function( field, id ) {
      return this.renderFieldLabel( field ) + this.renderFieldInput( '<textarea ' + this.getFieldAttributes( field, id ) + '>' + field.value + '</textarea>' );
    },
    renderFieldOptions: function( field, id ) {
      var label = this.renderFieldLabel( field );
      var content;
      var i;
      var currentOption;
      var buttonAdd = '<a ' + this.getFieldAttributes( field, id ) + ' class="wccf-add-new-option" href="#">Add new</a>';

      if ( 0 === field.options.length ) {
        return label + '<div class="wccf-input-block-outer-wrapper">' + buttonAdd + '</div>';
      } else {
        content = label;
        content += '<div class="wccf-input-block-outer-wrapper">';
        content += '<div class="wccf-input-block-wrapper">';
        for ( i in field.options ) {
          currentOption = field.options[i];
          content += this.renderFieldOptionInputs( field, id, currentOption );
        }

        content += '</div>';
        content += buttonAdd;
        content += '</div>';
        return content;
      }
    },

    renderFieldOptionInputs: function( field, id, currentOption ) {
      var attributes = this.getFieldAttributes( field, id ) + ' data-option="' + currentOption.index + '"';
      return (
        '<div class="wccf-input-block">' +
        this.renderFieldInput( '<input class="input-half wccf-option-field" ' + attributes + ' name="value" placeholder="Value" type="text" value="' + currentOption.value + '" />' ) +
        this.renderFieldInput( '<input class="input-half wccf-option-field" ' + attributes + ' name="text" placeholder="Text" type="text" value="' + currentOption.text + '" />' ) +
        '<a href="#" ' +
        attributes +
        ' class="wccf-remove-option">Remove</a>' +
        '</div>'
      );
    },

    renderFieldLabel: function( field ) {
      return '<div class="wccf-label"><label>' + field.name + ': </label><div "wccf-description">' + field.description + '</div></div>';
    },
    renderFieldInput: function( content ) {
      return '<div class="wccf-input">' + content + '</div>';
    },
    getFieldAttributes: function( field, id ) {
      return 'data-index="' + id + '" data-key="' + field.key + '"';
    }
  };

  wccfConditions();
  wccfConditionsToggle();
  wccfFieldEvents();
  wccfReactiveForm.read();

  // Events
  function wccfFieldEvents() {
    $( document.body ).on( 'click', fields.addField, function( event ) {
      event.preventDefault();
      wccfReactiveForm.add( availableHtmlElements.textbox );
    });
    $( document.body ).on( 'change', fields.selectField, function( event ) {
      var index = parseInt( $( this ).attr( 'data-index' ) );
      var value = $( this )
        .find( 'option:selected' )
        .val();
      wccfReactiveForm.changeField( index, value );
    });
    $( document.body ).on( 'focusout', '.wccf-wrapper input, .wccf-wrapper textarea', function( event ) {
      var slug;
      var parent;
      var keyField;
      wccfReactiveForm.updateValue( $( this ) );

      // Create field slug
      if ( 'title' === $( this ).attr( 'data-key' ) ) {
        slug = wccfCreateSlug( $( this ).val() );
        if ( 0 === slug.length ) {
          return false;
        }

        parent = $( this ).closest( '.wccf-wrapper' );
        keyField = parent.find( 'input[data-key="key"]' );

        if ( 0 === keyField.length ) {
          return false;
        }
        if ( 0 < keyField.val().length ) {
          return false;
        }

        keyField.val( slug );
        wccfReactiveForm.updateValue( keyField );
      }
    });
    $( document.body ).on( 'click', '.wccf-add-new-option', function( event ) {
      event.preventDefault();
      wccfReactiveForm.addOption( $( this ) );
    });

    $( document.body ).on( 'click', '.wccf-remove-option', function( event ) {
      event.preventDefault();
      wccfReactiveForm.removeOption( $( this ) );
    });

    $( document.body ).on( 'focusout', '.wccf-option-field', function( event ) {
      event.preventDefault();
      wccfReactiveForm.updateOptionValue( $( this ) );
    });

    $( document.body ).on( 'click', '.wccf-field-remove', function( event ) {
      event.preventDefault();
      wccfReactiveForm.remove( $( this ).attr( 'data-index' ) );
    });
  }

  // Function that copies title and key when switch happens
  function wccfCopyField( field, newFieldType ) {
    var fieldsToCopy = [ 'title', 'key' ];
    var i, j;
    var currentField;
    var currentFieldType;
    var currentNewField;
    var currentNewFieldType;

    var newField = wccfCopyObject( availableHtmlElements[newFieldType]);

    for ( i in field.fields ) {
      currentField = field.fields[i];
      currentFieldType = currentField.key;

      if ( -1 < fieldsToCopy.indexOf( currentFieldType ) ) {
        for ( j in newField.fields ) {
          currentNewField = newField.fields[j];
          currentNewFieldType = currentNewField.key;

          if ( currentFieldType === currentNewFieldType ) {
            newField.fields[j].value = currentField.value;
          }
        }
      }
    }
    newField.id = field.id;
    return newField;
  }

  function wccfCopyObject( object ) {
    return JSON.parse( JSON.stringify( object ) );
  }

  // Basic field selector
  function wccfFieldSelector( field ) {
    var htmlArrayKeys = Object.keys( availableHtmlElements );

    var content = '<div class="wccf-space-bottom wccf-main-select"><label>Select field type: </label><select data-index="' + field.id + '" class="wccf-select-field">';

    content += htmlArrayKeys.reduce( function( innerContent, htmlElementKey ) {
      var selected = htmlElementKey == field.type ? 'selected' : '';
      innerContent += '<option ' + selected + ' value="' + htmlElementKey + '">' + availableHtmlElements[htmlElementKey].name + '</option>';
      return innerContent;
    }, '' );

    content += '</select></div>';

    return content;
  }

  // Capitalize string
  function capitalizeString( string ) {
    return string.charAt( 0 ).toUpperCase() + string.slice( 1 );
  }

  // Conditions
  function wccfConditions() {
    $( '.wccf-select-category' ).select2({
      ajax: {
        url: ajaxurl,
        dataType: 'json',
        data: function( params ) {
          return {
            q: params.term, // search term
            action: 'wccf_search_category',
            nonce: $( 'input[name="wccf_nonce"]' ).val()
          };
        }
      }
    });
  }

  //Toggle wccfConditions
  function wccfConditionsToggle() {
    $( 'input[type="radio"][name="incex"]' ).on( 'change', function() {
        var radioValue = $( this ).val();
        var wccfToggleCondition = $( '.wccf-toggle-condition' );
        wccfToggleCondition.addClass( 'hidden' );
        $( 'div[data-show="wccf-' + radioValue + '"]' ).removeClass( 'hidden' );
    });
  }

}( jQuery ) );
