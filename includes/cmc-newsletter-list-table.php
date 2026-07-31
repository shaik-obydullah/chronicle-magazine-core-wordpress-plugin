<?php
/**
 * Newsletter List Table Class
 *
 * @package ChronicleMagazineCore
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class CMC_Newsletter_List_Table extends WP_List_Table {

    public function get_columns() {
        return array(
            'cb'         => '<input type="checkbox" />',
            'id'         => __( 'ID', 'chronicle-magazine-core' ),
            'email'      => __( 'Email', 'chronicle-magazine-core' ),
            'name'       => __( 'Name', 'chronicle-magazine-core' ),
            'status'     => __( 'Status', 'chronicle-magazine-core' ),
            'created_at' => __( 'Subscribed', 'chronicle-magazine-core' ),
        );
    }

    public function get_sortable_columns() {
        return array(
            'id'         => array( 'id', false ),
            'email'      => array( 'email', false ),
            'created_at' => array( 'created_at', false ),
        );
    }

    public function prepare_items() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cmc_newsletter_subscribers';
        $per_page = 20;

        $columns  = $this->get_columns();
        $hidden   = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array( $columns, $hidden, $sortable );

        $paged   = isset( $_GET['paged'] ) ? max( 1, intval( $_GET['paged'] ) ) : 1;
        $offset  = ( $paged - 1 ) * $per_page;

        $orderby = isset( $_GET['orderby'] ) ? sanitize_sql_orderby( $_GET['orderby'] ) : 'id';
        $order   = isset( $_GET['order'] ) && 'ASC' === strtoupper( $_GET['order'] ) ? 'ASC' : 'DESC';

        $total_items = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );

        $this->items = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d",
                $per_page,
                $offset
            ),
            ARRAY_A
        );

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
        $status = $item['status'];
        $class  = ( 'active' === $status ) ? 'status-active' : 'status-inactive';
        return '<span class="' . esc_attr( $class ) . '">' . esc_html( ucfirst( $status ) ) . '</span>';
    }

    public function get_bulk_actions() {
        return array(
            'delete' => __( 'Delete', 'chronicle-magazine-core' ),
        );
    }
}
