/**
 * Footer Settings Admin Script
 *
 * @package ObydullahMagazineCore
 * @since   1.0.0
 */

( function ( $ ) {
    'use strict';

    var $repeater = $( '#obmc-footer-links-repeater' );

    if ( ! $repeater.length ) {
        return;
    }

    var l10n   = window.obmcFooterL10n || {};
    var $count = $repeater.find( '#obmc-footer-link-count' );
    var textTx = l10n.linkTextPlaceholder || 'Link text';
    var urlTx  = l10n.urlPlaceholder || 'URL';
    var rmTx   = l10n.removeText || 'Remove';

    /**
     * Renumbers every row so array indexes stay contiguous after add/remove.
     */
    function reindex() {
        var $rows = $repeater.find( '.obmc-repeater-row' );

        $rows.each( function ( index ) {
            var $row = $( this );

            $row.attr( 'data-index', index );

            $row.find( '[name]' ).each( function () {
                var $field = $( this );

                $field.attr( 'name', $field.attr( 'name' ).replace(
                    /obmc_footer_links\[\d+\]/,
                    'obmc_footer_links[' + index + ']'
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
            '<div class="obmc-footer-link-row obmc-repeater-row">',
            '<input type="text" name="obmc_footer_links[__i__][text]" class="obmc-link-text" value="" placeholder="' + textTx + '">',
            '<input type="text" name="obmc_footer_links[__i__][url]" class="obmc-link-url" value="" placeholder="' + urlTx + '">',
            '<button type="button" class="button obmc-remove-row">' + rmTx + '</button>',
            '</div>'
        ].join( '' ) );
    }

    $( '#obmc-add-footer-link' ).on( 'click', function () {
        var index = $repeater.find( '.obmc-repeater-row' ).length;
        var $row  = buildRow();

        $row.attr( 'data-index', index );
        $row.find( '[name]' ).each( function () {
            var $field = $( this );

            $field.attr( 'name', $field.attr( 'name' ).replace( '__i__', index ) );
        } );

        $repeater.append( $row );
        $row.find( '.obmc-link-text' ).trigger( 'focus' );

        reindex();
    } );

    $repeater.on( 'click', '.obmc-remove-row', function () {
        $( this ).closest( '.obmc-repeater-row' ).remove();
        reindex();
    } );

    reindex();
}( jQuery ) );