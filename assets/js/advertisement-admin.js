/**
 * Advertisement Slots Admin Script
 *
 * @package ObydullahMagazineCore
 * @since   1.0.0
 */

( function ( $ ) {
    'use strict';

    var $repeater = $( '#obmc-ad-slots-repeater' );

    if ( ! $repeater.length ) {
        return;
    }

    var l10n     = window.obmcAdL10n || {};
    var $count   = $repeater.find( '#obmc-ad-slot-count' );
    var removeTx = l10n.removeText || 'Remove';
    var nameTx   = l10n.slotNameLabel || 'Slot Name';
    var codeTx   = l10n.codeLabel || 'Ad Code / HTML';
    var activeTx = l10n.activeLabel || 'Active';

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
                    /obmc_ad_slots\[\d+\]/,
                    'obmc_ad_slots[' + index + ']'
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
            '<div class="obmc-ad-slot-row obmc-repeater-row">',
            '<p>',
            '<label><strong>' + nameTx + '</strong></label><br>',
            '<input type="text" name="obmc_ad_slots[__i__][name]" class="widefat" value="">',
            '</p>',
            '<p>',
            '<label><strong>' + codeTx + '</strong></label><br>',
            '<textarea name="obmc_ad_slots[__i__][code]" rows="4" class="large-text"></textarea>',
            '</p>',
            '<p>',
            '<label>',
            '<input type="checkbox" name="obmc_ad_slots[__i__][active]" value="1" checked>',
            ' ' + activeTx,
            '</label>',
            '</p>',
            '<button type="button" class="button obmc-remove-row">' + removeTx + '</button>',
            '<hr>',
            '</div>'
        ].join( '' ) );
    }

    $( '#obmc-add-ad-slot' ).on( 'click', function () {
        var index = $repeater.find( '.obmc-repeater-row' ).length;
        var $row  = buildRow();

        $row.attr( 'data-index', index );
        $row.find( '[name]' ).each( function () {
            var $field = $( this );

            $field.attr( 'name', $field.attr( 'name' ).replace( '__i__', index ) );
        } );

        $repeater.append( $row );
        $row.find( 'input[type="text"]' ).trigger( 'focus' );

        reindex();
    } );

    $repeater.on( 'click', '.obmc-remove-row', function () {
        $( this ).closest( '.obmc-repeater-row' ).remove();
        reindex();
    } );

    reindex();
}( jQuery ) );