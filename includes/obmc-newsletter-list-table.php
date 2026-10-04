<?php
/**
 * Newsletter List Table Class
 *
 * @package ObydullahMagazineCore
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class OBMC_Newsletter_List_Table extends WP_List_Table {

    public function __construct() {
        parent::__construct(
            array(
                'singular' => 'obmc_newsletter_subscriber',
                'plural'   => 'obmc_newsletter_subscribers',
                'ajax'     => false,
            )
        );
    }

    public function get_columns() {
        return array(
            'cb'         => '<input type="checkbox" />',
            'id'         => __( 'ID', 'obydullah-magazine-core' ),
            'email'      => __( 'Email', 'obydullah-magazine-core' ),
            'name'       => __( 'Name', 'obydullah-magazine-core' ),
            'status'     => __( 'Status', 'obydullah-magazine-core' ),
            'created_at' => __( 'Subscribed', 'obydullah-magazine-core' ),
        );
    }

    public function get_sortable_columns() {
        return obmc_newsletter_sortable_columns();
    }

    public function prepare_items() {
        $per_page = 20;

        $columns  = $this->get_columns();
        $hidden   = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array( $columns, $hidden, $sortable );

        // phpcs:disable WordPress.Security.NonceVerification.Recommended -- Read-only list table sorting/pagination; no data is processed or stored.
        $paged = isset( $_GET['paged'] ) ? max( 1, absint( wp_unslash( $_GET['paged'] ) ) ) : 1;
        $offset = ( $paged - 1 ) * $per_page;

        $requested_orderby = isset( $_GET['orderby'] ) ? sanitize_key( wp_unslash( $_GET['orderby'] ) ) : 'id';

        // Only allow ordering by a column this table actually exposes.
        $orderby = array_key_exists( $requested_orderby, $sortable ) ? $requested_orderby : 'id';
        $order   = isset( $_GET['order'] ) && 'ASC' === strtoupper( sanitize_key( wp_unslash( $_GET['order'] ) ) ) ? 'ASC' : 'DESC';
        // phpcs:enable WordPress.Security.NonceVerification.Recommended

        $total_items = obmc_newsletter_cache_get_count();

        $this->items = obmc_newsletter_cache_get_subscribers( $per_page, $offset, $orderby, $order );

        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil( $total_items / $per_page ),
        ) );
    }

    public function column_default( $item, $column_name ) {
        return esc_html( $item[ $column_name ] );
    }

    public function column_cb( $item ) {
        return sprintf( '<input type="checkbox" name="subscriber_ids[]" value="%s" />', $item['id'] );
    }

    public function column_status( $item ) {
        $status = ( 'active' === $item['status'] ) ? 'active' : 'inactive';
        $id     = (int) $item['id'];

        // Keep the current page and sort order so toggling does not jump the
        // admin back to page 1.
        // phpcs:disable WordPress.Security.NonceVerification.Recommended -- Builds a nonce-protected link from read-only list table state; no data is processed or stored.
        $args = array_filter(
            array(
                'page'           => 'obmc-newsletter',
                'action'         => 'obmc_toggle_subscriber',
                'subscriber_id'  => $id,
                'paged'          => isset( $_GET['paged'] ) ? max( 1, absint( wp_unslash( $_GET['paged'] ) ) ) : '',
                'orderby'        => isset( $_GET['orderby'] ) ? sanitize_key( wp_unslash( $_GET['orderby'] ) ) : '',
                'order'          => isset( $_GET['order'] ) && 'ASC' === strtoupper( sanitize_key( wp_unslash( $_GET['order'] ) ) ) ? 'ASC' : '',
            )
        );
        // phpcs:enable WordPress.Security.NonceVerification.Recommended

        $toggle_url = wp_nonce_url(
            add_query_arg( $args, admin_url( 'admin.php' ) ),
            'obmc_toggle_subscriber_' . $id
        );

        $label = ( 'active' === $status )
            ? __( 'Deactivate', 'obydullah-magazine-core' )
            : __( 'Activate', 'obydullah-magazine-core' );

        return sprintf(
            '<span class="%s">%s</span> <a href="%s" class="obmc-toggle-status">%s</a>',
            esc_attr( 'status-' . $status ),
            esc_html( ucfirst( $status ) ),
            esc_url( $toggle_url ),
            esc_html( $label )
        );
    }

    public function get_bulk_actions() {
        return array(
            'delete' => __( 'Delete', 'obydullah-magazine-core' ),
        );
    }
}
