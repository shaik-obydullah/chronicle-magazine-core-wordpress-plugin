/**
 * About Page Admin Script
 *
 * @package ObydullahMagazineCore
 * @since   1.0.0
 */

( function ( $ ) {
    'use strict';

    var $repeater = $( '#obmc-about-slides-repeater' );

    if ( ! $repeater.length ) {
        return;
    }

    var l10n       = window.obmcAboutL10n || {};
    var $count     = $repeater.find( '#obmc-about-slide-count' );
    var titleTx    = l10n.titlePlaceholder || 'Title';
    var subTx      = l10n.subtitlePlaceholder || 'Subtitle';
    var imageTx    = l10n.imagePlaceholder || 'Background Image';
    var selectTx   = l10n.selectImage || 'Select Image';
    var removeTx   = l10n.removeImage || 'Remove Image';
    var removeRow  = l10n.removeText || 'Remove Slide';

    /**
     * Renumbers every row so array indexes stay contiguous after add/remove.
     */
    function reindex() {
        var $rows = $repeater.find( '.obmc-slide-row' );

        $rows.each( function ( index ) {
            var $row = $( this );

            $row.attr( 'data-index', index );

            $row.find( '[name]' ).each( function () {
                var $field = $( this );

                $field.attr( 'name', $field.attr( 'name' ).replace(
                    /obmc_about_slides\[\d+\]/,
                    'obmc_about_slides[' + index + ']'
                ) );
            } );
        } );

        $count.val( $rows.length );
    }

    /**
     * Builds a blank row. Markup is generated rather than cloned so that adding
     * still works after every existing row has been removed.
     */
    function buildRow() {
        return $( [
            '<div class="obmc-slide-row">',
            '<p>',
            '<label>' + titleTx + '</label><br>',
            '<input type="text" name="obmc_about_slides[__i__][title]" class="widefat" value="">',
            '</p>',
            '<p>',
            '<label>' + subTx + '</label><br>',
            '<input type="text" name="obmc_about_slides[__i__][subtitle]" class="widefat" value="">',
            '</p>',
            '<div class="slide-image-wrapper">',
            '<label>' + imageTx + '</label><br>',
            '<input type="hidden" name="obmc_about_slides[__i__][image]" class="slide-image-url" value="">',
            '<div class="image-preview"></div>',
            '<button type="button" class="button select-slide-image">' + selectTx + '</button>',
            '<button type="button" class="button remove-slide-image hidden">' + removeTx + '</button>',
            '</div>',
            '<button type="button" class="button obmc-remove-slide-row mt-1">' + removeRow + '</button>',
            '</div>'
        ].join( '' ) );
    }

    /**
     * Opens the media library and writes the chosen attachment URL into the
     * row's hidden field, then refreshes the thumbnail preview.
     */
    function selectImage( $row ) {
        if ( ! window.wp || ! window.wp.media ) {
            return;
        }

        var frame = window.wp.media( {
            title: selectTx,
            multiple: false,
            library: { type: 'image' }
        } );

        frame.on( 'select', function () {
            var attachment = frame.state().get( 'selection' ).first().toJSON();
            var url        = attachment.url;

            if ( attachment.sizes && attachment.sizes.medium ) {
                url = attachment.sizes.medium.url;
            }

            $row.find( '.slide-image-url' ).val( attachment.url );
            $row.find( '.image-preview' ).html(
                $( '<img/>', { src: url, alt: '' } )
            );
            $row.find( '.remove-slide-image' ).removeClass( 'hidden' );
        } );

        frame.open();
    }

    /**
     * Clears the hidden field and preview for a row.
     */
    function clearImage( $row ) {
        $row.find( '.slide-image-url' ).val( '' );
        $row.find( '.image-preview' ).empty();
        $row.find( '.remove-slide-image' ).addClass( 'hidden' );
    }

    $( '#obmc-add-about-slide' ).on( 'click', function () {
        var index = $repeater.find( '.obmc-slide-row' ).length;
        var $row  = buildRow();

        $row.attr( 'data-index', index );
        $row.find( '[name]' ).each( function () {
            var $field = $( this );

            $field.attr( 'name', $field.attr( 'name' ).replace( '__i__', index ) );
        } );

        $repeater.append( $row );
        $row.find( 'input[type="text"]' ).first().trigger( 'focus' );

        reindex();
    } );

    $repeater.on( 'click', '.select-slide-image', function () {
        selectImage( $( this ).closest( '.obmc-slide-row' ) );
    } );

    $repeater.on( 'click', '.remove-slide-image', function () {
        clearImage( $( this ).closest( '.obmc-slide-row' ) );
    } );

    $repeater.on( 'click', '.obmc-remove-slide-row', function () {
        $( this ).closest( '.obmc-slide-row' ).remove();
        reindex();
    } );

    reindex();
}( jQuery ) );