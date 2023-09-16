! function( $ ) {
	
	"use strict";

	$( document ).ready(function() {

		$( document ).on( 'click', '.litho_upload_button_category', function( event ) {
				var file_frame;
				var button = $( this );
				var button_parent = $( this ).parent();
				var id = button.attr('id').replace( '_button_category', '' );
				
				event.preventDefault();
			  

				// If the media frame already exists, reopen it.
				if ( file_frame ) {
					file_frame.open();
					return;
				}

				// Create the media frame.
				file_frame = wp.media.frames.file_frame = wp.media({
					title: $( this ).data( 'uploader_title' ),
					button: {
					  text: $( this ).data( 'uploader_button_text' ),
					},
					multiple: false  // Set to true to allow multiple files to be selected
				});

			  // When an image is selected, run a callback.
			  file_frame.on( 'select', function() {
					// We set multiple to false so only get one image from the uploader
					var full_attachment = file_frame.state().get( 'selection' ).first().toJSON();

					var attachment = file_frame.state().get('selection').first();

					var thumburl = attachment.attributes.sizes.thumbnail;
					var thumb_hidden = button_parent.find( '.upload_field' ).attr('name');

					if ( thumburl || full_attachment ) {
						button_parent.find( '#' + id ).val( full_attachment.url );
						button_parent.find( '.' + thumb_hidden + '_thumb' ).val( full_attachment.url );
						
						button_parent.find( '.upload_image_screenshort' ).attr( 'src', full_attachment.url );
						button_parent.find( '.upload_image_screenshort' ).slideDown();
					}
				});

				// Finally, open the modal
				file_frame.open();
		 });

		// Remove button function to remove attach image and hide screenshort Div.
		$( document ).on( 'click', '.litho_remove_button_category', function( event ) {
			var remove_parent = $( this ).parent();
			remove_parent.find( '.upload_field' ).val( '' );
			remove_parent.find( 'input[type="hidden"]' ).val( '' );
			remove_parent.find( '.upload_image_screenshort' ).slideUp();
		});

		// On page load add all image url to show in screenshort.
		if ( $( '.upload_field' ).length > 0 ) {
			$( '.upload_field' ).each( function() {
			if ( $( this ).val() ) {
				$( this ).parent().find( '.upload_image_screenshort' ).attr( 'src', $( this ).val() );
			} else {
				$( this ).parent().find( '.upload_image_screenshort' ).hide();
			}
			});
		}

		/* multiple image upload */
		$( document ).on( 'click', '.litho_upload_button_multiple_category', function( event ) {
			var file_frame;
			var button = $( this );
			var button_parent = $( this ).parent();
			var id = button.attr( 'id' ).replace( '_button_category', '' );
			var app=[];
			event.preventDefault();

			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				file_frame.open();
				return;
			}

			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				title: $( this ).data( 'uploader_title' ),
				button: {
					text: $( this ).data( 'uploader_button_text' ),
				},
				multiple: true  // Set to true to allow multiple files to be selected
			});

			// When an image is selected, run a callback.
			file_frame.on( 'select', function() {

				var thumb_hidden = button_parent.find( '.upload_field_multiple' ).attr( 'name' );
			 
				var selection = file_frame.state().get( 'selection' );
				var app=[];
					selection.map( function( attachment ) {
					var attachment = attachment.toJSON();
					var imageHTML = '';
						imageHTML += '<div id="';
						imageHTML += attachment.id;
						imageHTML += '">';
						imageHTML += '<img src="';
						imageHTML += attachment.url;
						imageHTML += '" class="upload_image_screenshort_multiple" alt="" style="width:100px;"/>';
						imageHTML += '<a href="javascript:void(0)" class="remove">remove</a>';
						imageHTML += '</div>';
					button_parent.find( '.multiple_images' ).append( imageHTML );
				});
			});
			// Finally, open the modal
			file_frame.open();
		});

		$( '.button-primary' ).on( 'click', function() {
			var pr_div;
			if ( $( '.multiple_images' ).length > 0 ) {
			  	$( '.multiple_images' ).each( function() {
					if ( $( this ).children().length > 0 ) {
					  var attach_id = [];
					  var pr_div = $( this ).parent();
					  $( this ).children( 'div' ).each( function() {
							attach_id.push( $( this ).attr( 'id' ) );
					  });
					  
						pr_div.find( '.upload_field_multiple' ).val( attach_id );
					}else{
					  	$( this ).parent().find( '.upload_field_multiple' ).val( '' );
					}
				});
			}
		});

		$( '.multiple_images' ).on( 'click', '.remove', function() {
		 	$( this ).parent().slideUp();
		  	$( this ).parent().remove();
		});
		
        /* Licence - START CODE */
        $( document ).on( 'click', '#litho_license_btn', function(e) {
            e.preventDefault();

            var _this           = $( this ),
            	unregisterObj 	= _this.parents( '.litho-license-form-wrapper' ).find( '#litho_purchase_code_unregister' ),
                purchaseCodeObj = _this.parents( '.litho-license-form-wrapper' ).find( '#litho_purchase_code' ),
                responseMsgObj  = _this.parents( '.litho-license-form-wrapper' ).find( '#litho_response_msg' ),
                purchaseCodeVal = purchaseCodeObj.val(),
                unregisterVal 	= unregisterObj.val();

            if ( _this.attr( 'disabled' ) ) {
                return false;
            }

            // Confirm for deactivate license
            if ( '1' == unregisterVal ) {
				var r = confirm( LithoCustomAdmin.confirm_deactivate );
				if ( r == false ) {
					return false;
				}
            }

            if ( '' == purchaseCodeVal ) {
                responseMsgObj.html( LithoCustomAdmin.empty_purchase_code );
                purchaseCodeObj.addClass( 'error' );
                return false;
            }

            // Show loader
            _this.addClass( 'loading' ).attr( 'disabled' );

            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data : {
                    action 			: 'litho_active_theme_license',
                    purchase_code 	: purchaseCodeVal,
                    unregister 		: unregisterVal
                },
                success: function( response ) {

                    response = JSON.parse( response );

                    if ( response && response.status ) {

                    	window.location = response.url
                    	
                    } else {

                    	alert( response.message );

						// Hide loader
						_this.removeClass( 'loading' ).removeAttr( 'disabled' );
                    }
                },
                fail: function( jqXHR, textStatus ) {
                    // Hide loader
                    _this.removeClass( 'loading' ).removeAttr( 'disabled' );

                    alert( 'Request failed: ' + textStatus );
                }
            });
        });
        /* Licence - END CODE */
	});

}( window.jQuery );