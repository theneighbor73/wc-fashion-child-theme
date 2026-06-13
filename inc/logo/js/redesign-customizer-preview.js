( function () {
    'use strict';

    if ( typeof wp === 'undefined' || ! wp.customize ) {
        return;
    }

    function applyLogoScaleToPreview( scale ) {
        scale = Math.max( -100, Math.min( 100, parseInt( scale, 10 ) || 0 ) );
        var multiplier = ( 100 + scale ) / 100;
        var logo = document.querySelector( '.custom-logo' );

        if ( ! logo ) {
            return;
        }

        logo.style.transform = 'scale(' + multiplier + ')';
        logo.style.transformOrigin = 'left center';
        logo.style.transition = 'transform 0.15s ease-out';
    }

    wp.customize( 'logo_resize', function ( value ) {
        value.bind( function ( newScale ) {
            applyLogoScaleToPreview( newScale );
        } );
    } );

    wp.customize.bind( 'preview-ready', function () {
        var current = wp.customize( 'logo_resize' )();
        applyLogoScaleToPreview( current );
    } );
} )();
