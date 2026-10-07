<?php
/**
 * Plugin Name: Obydullah Magazine Core
 * Plugin URI: https://obydullah.com/project/chronicle-magazine-core-wordpress-plugin
 * Description: Core functionality for Chronicle Magazine theme
 * Version: 1.0.1
 * Author: Shaik Obydullah
 * Author URI: https://obydullah.com
 * Text Domain: obydullah-magazine-core
 * Requires at least: 6.2
 * Requires PHP: 8.0
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * 
 * ================================================================
 *                         INDEX
 * ================================================================
 * 1. Security & Constants
 * 2. Hero Slider CPT + Meta Boxes
 * 3. Featured Articles CPT + Meta Boxes (Single Instance)
 * 4. Articles CPT + Category Taxonomy + Meta Boxes
 * 5. Authors CPT + Meta Boxes
 * 6. Magazine Issues CPT + Meta Boxes
 * 7. News Ticker CPT + Meta Boxes
 * 8. Newsletter Subscriptions (custom DB table, AJAX handler)
 * 9. Advertisement Slots (Single Instance) + Meta Boxes
 * 10. Footer Settings (Single Instance) + Meta Boxes
 * 11. About Page (Single Instance) + Meta Boxes
 * 12. Contact Page (Single Instance) + Meta Boxes
 * 13. Contact Form 7 Support
 * ================================================================
 *
 * @package Obydullah_Magazine_Core
 */

/* ======================================================
   1. Security & Constants
====================================================== */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'OBMC_VERSION', '1.0.1' );
define( 'OBMC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'OBMC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'OBMC_NEWSLETTER_CACHE_GROUP', 'obmc_newsletter' );

require_once OBMC_PLUGIN_DIR . 'includes/obmc-newsletter-list-table.php';

function obmc_add_admin_menu() {
    add_menu_page(
        'Obydullah Magazine Core',
        'Obydullah Magazine Core',
        'manage_options',
        'obmc-magazine-core',
        'obmc_magazine_core_page',
        'dashicons-admin-post',
        65
    );
}
add_action( 'admin_menu', 'obmc_add_admin_menu', 9 );

function obmc_enqueue_dashboard_assets( $hook ) {
    if ( 'toplevel_page_obmc-magazine-core' === $hook ) {
        wp_enqueue_style( 'obmc-dashboard-css', OBMC_PLUGIN_URL . 'assets/css/obmc-admin-dashboard.css', array(), OBMC_VERSION );
    }

    if ( 'obmc_page_obmc-newsletter' === $hook ) {
        wp_enqueue_style( 'obmc-newsletter-css', OBMC_PLUGIN_URL . 'assets/css/obmc-newsletter-admin.css', array(), OBMC_VERSION );
    }
}
add_action( 'admin_enqueue_scripts', 'obmc_enqueue_dashboard_assets' );

function obmc_magazine_core_page() {
    $sections = array(
        'hero_slides' => array(
            'title' => __( 'Hero Slides', 'obydullah-magazine-core' ),
            'url'   => admin_url( 'edit.php?post_type=obmc_hero_slide' ),
            'icon'  => 'dashicons-slides',
        ),
        'featured_articles' => array(
            'title' => __( 'Featured Articles', 'obydullah-magazine-core' ),
            'url'   => admin_url( 'edit.php?post_type=obmc_featured' ),
            'icon'  => 'dashicons-star-filled',
        ),
        'articles' => array(
            'title' => __( 'Articles', 'obydullah-magazine-core' ),
            'url'   => admin_url( 'edit.php?post_type=obmc_article' ),
            'icon'  => 'dashicons-admin-post',
        ),
        'authors' => array(
            'title' => __( 'Authors', 'obydullah-magazine-core' ),
            'url'   => admin_url( 'edit.php?post_type=obmc_author' ),
            'icon'  => 'dashicons-admin-users',
        ),
        'magazine_issues' => array(
            'title' => __( 'Magazine Issues', 'obydullah-magazine-core' ),
            'url'   => admin_url( 'edit.php?post_type=obmc_magazine_issue' ),
            'icon'  => 'dashicons-book',
        ),
        'news_ticker' => array(
            'title' => __( 'News Ticker', 'obydullah-magazine-core' ),
            'url'   => admin_url( 'edit.php?post_type=obmc_news_ticker' ),
            'icon'  => 'dashicons-megaphone',
        ),
        'newsletter' => array(
            'title' => __( 'Newsletter Subscribers', 'obydullah-magazine-core' ),
            'url'   => admin_url( 'admin.php?page=obmc-newsletter' ),
            'icon'  => 'dashicons-email-alt',
        ),
        'advertisements' => array(
            'title' => __( 'Advertisement Slots', 'obydullah-magazine-core' ),
            'url'   => admin_url( 'edit.php?post_type=obmc_advertisement' ),
            'icon'  => 'dashicons-megaphone',
        ),
        'footer_settings' => array(
            'title' => __( 'Footer Settings', 'obydullah-magazine-core' ),
            'url'   => admin_url( 'edit.php?post_type=obmc_footer' ),
            'icon'  => 'dashicons-layout',
        ),
        'about_page' => array(
            'title' => __( 'About Page', 'obydullah-magazine-core' ),
            'url'   => admin_url( 'edit.php?post_type=obmc_about_page' ),
            'icon'  => 'dashicons-info',
        ),
        'contact_page' => array(
            'title' => __( 'Contact Page', 'obydullah-magazine-core' ),
            'url'   => admin_url( 'edit.php?post_type=obmc_contact_page' ),
            'icon'  => 'dashicons-email',
        ),
    );
    ?>
<div class="wrap obmc-dashboard">
    <h1><?php esc_html_e( 'Obydullah Magazine Core', 'obydullah-magazine-core' ); ?></h1>
    <p class="obmc-dashboard-description">
        <?php esc_html_e( 'Welcome to the Obydullah Magazine Core plugin. Use the links below to manage your magazine content.', 'obydullah-magazine-core' ); ?>
    </p>

    <div class="obmc-dashboard-grid">
        <?php foreach ( $sections as $section ) : ?>
        <div class="obmc-dashboard-card">
            <div class="dashicons <?php echo esc_attr( $section['icon'] ); ?>"></div>
            <h2><?php echo esc_html( $section['title'] ); ?></h2>
            <a href="<?php echo esc_url( $section['url'] ); ?>"
                class="button button-primary"><?php esc_html_e( 'Manage', 'obydullah-magazine-core' ); ?></a>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php
}

/* ======================================================
   2. Hero Slider CPT + Meta Boxes
====================================================== */

function obmc_register_hero_slide_cpt() {
    register_post_type( 'obmc_hero_slide', array(
        'labels' => array(
            'name'          => __( 'Hero Slides', 'obydullah-magazine-core' ),
            'singular_name' => __( 'Hero Slide', 'obydullah-magazine-core' ),
            'add_new_item'  => __( 'Add New Hero Slide', 'obydullah-magazine-core' ),
            'edit_item'     => __( 'Edit Hero Slide', 'obydullah-magazine-core' ),
        ),
        'public'        => true,
        'show_in_menu'  => 'obmc-magazine-core',
        'menu_icon'     => 'dashicons-slides',
        'supports'      => array( 'title', 'thumbnail', 'page-attributes' ),
        'show_in_rest'  => true,
        'has_archive'   => false,
        'rewrite'       => array( 'slug' => 'obmc-hero-slide' ),
    ) );
}
add_action( 'init', 'obmc_register_hero_slide_cpt' );

function obmc_add_hero_slide_meta_box() {
    add_meta_box(
        'obmc_hero_slide_meta',
        __( 'Hero Slide Settings', 'obydullah-magazine-core' ),
        'obmc_render_hero_slide_meta_box',
        'obmc_hero_slide',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'obmc_add_hero_slide_meta_box' );

function obmc_render_hero_slide_meta_box( $post ) {
    $subtitle = get_post_meta( $post->ID, 'obmc_subtitle', true );
    $category = get_post_meta( $post->ID, 'obmc_category', true );
    wp_nonce_field( 'obmc_save_hero_slide_meta', 'obmc_hero_slide_nonce' );
    ?>
<p>
    <label
        for="obmc_subtitle"><strong><?php esc_html_e( 'Subtitle', 'obydullah-magazine-core' ); ?></strong></label><br>
    <input type="text" id="obmc_subtitle" name="obmc_subtitle" value="<?php echo esc_attr( $subtitle ); ?>"
        class="widefat">
</p>
<p>
    <label
        for="obmc_category"><strong><?php esc_html_e( 'Category Label', 'obydullah-magazine-core' ); ?></strong></label><br>
    <input type="text" id="obmc_category" name="obmc_category" value="<?php echo esc_attr( $category ); ?>"
        class="widefat" placeholder="<?php esc_attr_e( 'e.g., Breaking News', 'obydullah-magazine-core' ); ?>">
</p>
<?php
}

function obmc_save_hero_slide_meta( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
        return;
    }

    if ( ! isset( $_POST['obmc_hero_slide_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['obmc_hero_slide_nonce'] ) ), 'obmc_save_hero_slide_meta' ) ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( 'obmc_hero_slide' !== get_post_type( $post_id ) ) {
        return;
    }

    if ( isset( $_POST['obmc_subtitle'] ) ) {
        update_post_meta( $post_id, 'obmc_subtitle', sanitize_text_field( wp_unslash( $_POST['obmc_subtitle'] ) ) );
    }

    if ( isset( $_POST['obmc_category'] ) ) {
        update_post_meta( $post_id, 'obmc_category', sanitize_text_field( wp_unslash( $_POST['obmc_category'] ) ) );
    }
}
add_action( 'save_post_obmc_hero_slide', 'obmc_save_hero_slide_meta' );

/* ======================================================
   3. Featured Articles CPT + Meta Boxes (Single Instance)
====================================================== */

function obmc_register_featured_article_cpt() {
    // `obmc_featured_article` is 21 characters, one over WordPress' 20 character
    // post type limit, so 1.0.0 raised a _doing_it_wrong() notice on every
    // request. The rewrite slug stays `obmc_featured_article` on purpose so
    // permalinks created before 1.0.1 keep resolving.
    register_post_type( 'obmc_featured', array(
        'labels' => array(
            'name'          => __( 'Featured Articles', 'obydullah-magazine-core' ),
            'singular_name' => __( 'Featured Article', 'obydullah-magazine-core' ),
            'add_new_item'  => __( 'Edit Featured Article', 'obydullah-magazine-core' ),
            'edit_item'     => __( 'Edit Featured Article', 'obydullah-magazine-core' ),
            'new_item'      => __( 'Edit Featured Article', 'obydullah-magazine-core' ),
        ),
        'public'          => true,
        'show_ui'         => true,
        'show_in_menu'    => 'obmc-magazine-core',
        'menu_icon'       => 'dashicons-star-filled',
        'supports'        => array( 'title', 'editor', 'thumbnail' ),
        'show_in_rest'    => true,
        'capability_type' => 'post',
        'map_meta_cap'    => true,
        'has_archive'     => true,
        'rewrite'         => array( 'slug' => 'obmc_featured_article' ),
    ) );
}
add_action( 'init', 'obmc_register_featured_article_cpt' );

/**
 * Redirects away from post-new.php for single-instance post types so that only
 * one entry can ever exist. Read-only guard: it redirects and stores no data.
 *
 * @param string $post_type Post type to limit to a single entry.
 */
function obmc_limit_single_instance_cpt( $post_type ) {
    // phpcs:disable WordPress.Security.NonceVerification.Recommended -- Read-only navigation guard on post-new.php; no form data is processed or stored.
    if ( ! isset( $_GET['post_type'] ) ) {
        return;
    }

    if ( $post_type !== sanitize_key( wp_unslash( $_GET['post_type'] ) ) ) {
        return;
    }
    // phpcs:enable WordPress.Security.NonceVerification.Recommended

    $existing = get_posts( array(
        'post_type'      => $post_type,
        'posts_per_page' => 1,
        'post_status'    => 'publish',
        'fields'         => 'ids',
    ) );

    if ( empty( $existing ) ) {
        return;
    }

    $post_id = (int) $existing[0];

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    wp_safe_redirect( admin_url( 'post.php?post=' . $post_id . '&action=edit' ) );
    exit;
}

function obmc_limit_featured_article() {
    obmc_limit_single_instance_cpt( 'obmc_featured' );
}
add_action( 'load-post-new.php', 'obmc_limit_featured_article' );

function obmc_add_featured_article_meta_box() {
    add_meta_box(
        'obmc_featured_article_meta',
        __( 'Featured Article Settings', 'obydullah-magazine-core' ),
        'obmc_render_featured_article_meta_box',
        'obmc_featured',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'obmc_add_featured_article_meta_box' );

function obmc_render_featured_article_meta_box( $post ) {
    $excerpt = get_post_meta( $post->ID, 'obmc_excerpt', true );
    $author  = get_post_meta( $post->ID, 'obmc_author_name', true );
    $date    = get_post_meta( $post->ID, 'obmc_publish_date', true );
    wp_nonce_field( 'obmc_save_featured_article_meta', 'obmc_featured_article_nonce' );
    ?>
<p>
    <label for="obmc_excerpt"><strong><?php esc_html_e( 'Excerpt', 'obydullah-magazine-core' ); ?></strong></label><br>
    <textarea id="obmc_excerpt" name="obmc_excerpt" rows="4"
        class="large-text"><?php echo esc_textarea( $excerpt ); ?></textarea>
</p>
<p>
    <label
        for="obmc_author_name"><strong><?php esc_html_e( 'Author Name', 'obydullah-magazine-core' ); ?></strong></label><br>
    <input type="text" id="obmc_author_name" name="obmc_author_name" value="<?php echo esc_attr( $author ); ?>"
        class="widefat">
</p>
<p>
    <label
        for="obmc_publish_date"><strong><?php esc_html_e( 'Publish Date', 'obydullah-magazine-core' ); ?></strong></label><br>
    <input type="text" id="obmc_publish_date" name="obmc_publish_date" value="<?php echo esc_attr( $date ); ?>"
        class="widefat" placeholder="<?php esc_attr_e( 'e.g., January 15, 2026', 'obydullah-magazine-core' ); ?>">
</p>
<?php
}

function obmc_save_featured_article_meta( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
        return;
    }

    if ( ! isset( $_POST['obmc_featured_article_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['obmc_featured_article_nonce'] ) ), 'obmc_save_featured_article_meta' ) ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( 'obmc_featured' !== get_post_type( $post_id ) ) {
        return;
    }

    if ( isset( $_POST['obmc_excerpt'] ) ) {
        update_post_meta( $post_id, 'obmc_excerpt', sanitize_textarea_field( wp_unslash( $_POST['obmc_excerpt'] ) ) );
    }

    if ( isset( $_POST['obmc_author_name'] ) ) {
        update_post_meta( $post_id, 'obmc_author_name', sanitize_text_field( wp_unslash( $_POST['obmc_author_name'] ) ) );
    }

    if ( isset( $_POST['obmc_publish_date'] ) ) {
        update_post_meta( $post_id, 'obmc_publish_date', sanitize_text_field( wp_unslash( $_POST['obmc_publish_date'] ) ) );
    }
}
add_action( 'save_post_obmc_featured', 'obmc_save_featured_article_meta' );

/* ======================================================
   4. Articles CPT + Category Taxonomy + Meta Boxes
====================================================== */

function obmc_register_article() {
    register_post_type( 'obmc_article', array(
          'labels'      => array(
            'name'          => __( 'Articles', 'obydullah-magazine-core' ),
            'singular_name' => __( 'Article', 'obydullah-magazine-core' ),
            'add_new_item'  => __( 'Add New Article', 'obydullah-magazine-core' ),
            'edit_item'     => __( 'Edit Article', 'obydullah-magazine-core' ),
        ),
        'public'      => true,
        'show_in_menu'        => 'obmc-magazine-core',
        'menu_icon'   => 'dashicons-admin-post',
        'supports'    => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'=> true,
        'has_archive' => true,
        'rewrite'     => array( 'slug' => 'articles' ),
    ) );
}
add_action( 'init', 'obmc_register_article' );

function obmc_register_article_category() {
    register_taxonomy( 'obmc_article_category', 'obmc_article', array(
        'labels' => array(
            'name'              => __( 'Categories', 'obydullah-magazine-core' ),
            'singular_name'     => __( 'Category', 'obydullah-magazine-core' ),
            'add_new_item'      => __( 'Add New Category', 'obydullah-magazine-core' ),
            'new_item_name'     => __( 'New Category Name', 'obydullah-magazine-core' ),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
    ) );
}
add_action( 'init', 'obmc_register_article_category' );

function obmc_add_article_subtitle_meta_box() {
    add_meta_box( 'obmc_article_subtitle', __( 'Subtitle', 'obydullah-magazine-core' ), 'obmc_article_subtitle_callback', 'obmc_article', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'obmc_add_article_subtitle_meta_box' );

function obmc_article_subtitle_callback( $post ) {
    wp_nonce_field( 'obmc_article_meta', 'obmc_article_nonce' );
    $subtitle = get_post_meta( $post->ID, 'obmc_article_subtitle', true );
    echo '<input type="text" name="obmc_article_subtitle" value="' . esc_attr( $subtitle ) . '" class="widefat" placeholder="' . esc_attr__( 'Article subtitle', 'obydullah-magazine-core' ) . '">';
}

function obmc_add_article_author_meta_box() {
    add_meta_box( 'obmc_article_author', __( 'Author', 'obydullah-magazine-core' ), 'obmc_article_author_callback', 'obmc_article', 'side', 'default' );
}
add_action( 'add_meta_boxes', 'obmc_add_article_author_meta_box' );

function obmc_article_author_callback( $post ) {
    $author = get_post_meta( $post->ID, 'obmc_article_author', true );
    echo '<input type="text" name="obmc_article_author" value="' . esc_attr( $author ) . '" class="widefat" placeholder="' . esc_attr__( 'Author name', 'obydullah-magazine-core' ) . '">';
}

function obmc_add_article_read_time_meta_box() {
    add_meta_box( 'obmc_article_read_time', __( 'Read Time', 'obydullah-magazine-core' ), 'obmc_article_read_time_callback', 'obmc_article', 'side', 'default' );
}
add_action( 'add_meta_boxes', 'obmc_add_article_read_time_meta_box' );

function obmc_article_read_time_callback( $post ) {
    $read_time = get_post_meta( $post->ID, 'obmc_article_read_time', true );
    echo '<input type="text" name="obmc_article_read_time" value="' . esc_attr( $read_time ) . '" class="widefat" placeholder="' . esc_attr__( 'e.g., 5 min read', 'obydullah-magazine-core' ) . '">';
}

function obmc_save_article_meta( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
        return;
    }

    if ( ! isset( $_POST['obmc_article_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['obmc_article_nonce'] ) ), 'obmc_article_meta' ) ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( 'obmc_article' !== get_post_type( $post_id ) ) {
        return;
    }
    if ( isset( $_POST['obmc_article_subtitle'] ) ) {
        update_post_meta( $post_id, 'obmc_article_subtitle', sanitize_text_field( wp_unslash( $_POST['obmc_article_subtitle'] ) ) );
    }
    if ( isset( $_POST['obmc_article_author'] ) ) {
        update_post_meta( $post_id, 'obmc_article_author', sanitize_text_field( wp_unslash( $_POST['obmc_article_author'] ) ) );
    }
    if ( isset( $_POST['obmc_article_read_time'] ) ) {
        update_post_meta( $post_id, 'obmc_article_read_time', sanitize_text_field( wp_unslash( $_POST['obmc_article_read_time'] ) ) );
    }
}
add_action( 'save_post_obmc_article', 'obmc_save_article_meta' );

/* ======================================================
   5. Authors CPT + Meta Boxes
====================================================== */

function obmc_register_author_cpt() {
    register_post_type( 'obmc_author', array(
        'labels' => array(
            'name'          => __( 'Authors', 'obydullah-magazine-core' ),
            'singular_name' => __( 'Author', 'obydullah-magazine-core' ),
            'add_new_item'  => __( 'Add New Author', 'obydullah-magazine-core' ),
            'edit_item'     => __( 'Edit Author', 'obydullah-magazine-core' ),
        ),
        'public'          => false,
        'show_ui'         => true,
        'show_in_menu'    => 'obmc-magazine-core',
        'menu_icon'       => 'dashicons-admin-users',
        'supports'        => array( 'title', 'thumbnail' ),
        'show_in_rest'    => true,
        'capability_type' => 'post',
        'map_meta_cap'    => true,
    ) );
}
add_action( 'init', 'obmc_register_author_cpt' );

function obmc_add_author_meta_boxes() {
    add_meta_box(
        'obmc_author_details',
        __( 'Author Details', 'obydullah-magazine-core' ),
        'obmc_author_details_callback',
        'obmc_author',
        'normal',
        'high'
    );
    add_meta_box(
        'obmc_author_social',
        __( 'Social Media Links', 'obydullah-magazine-core' ),
        'obmc_author_social_callback',
        'obmc_author',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'obmc_add_author_meta_boxes' );

function obmc_author_details_callback( $post ) {
    wp_nonce_field( 'obmc_author_meta', 'obmc_author_nonce' );
    $bio      = get_post_meta( $post->ID, 'obmc_author_bio', true );
    $position = get_post_meta( $post->ID, 'obmc_author_position', true );
    $email    = get_post_meta( $post->ID, 'obmc_author_email', true );
    ?>
<p>
    <label for="obmc_author_bio"><strong><?php esc_html_e( 'Bio', 'obydullah-magazine-core' ); ?></strong></label><br>
    <textarea id="obmc_author_bio" name="obmc_author_bio" rows="5"
        class="large-text"><?php echo esc_textarea( $bio ); ?></textarea>
</p>
<p>
    <label
        for="obmc_author_position"><strong><?php esc_html_e( 'Position / Title', 'obydullah-magazine-core' ); ?></strong></label><br>
    <input type="text" id="obmc_author_position" name="obmc_author_position"
        value="<?php echo esc_attr( $position ); ?>" class="widefat"
        placeholder="<?php esc_attr_e( 'e.g., Senior Editor', 'obydullah-magazine-core' ); ?>">
</p>
<p>
    <label
        for="obmc_author_email"><strong><?php esc_html_e( 'Email', 'obydullah-magazine-core' ); ?></strong></label><br>
    <input type="email" id="obmc_author_email" name="obmc_author_email" value="<?php echo esc_attr( $email ); ?>"
        class="widefat">
</p>
<?php
}

function obmc_author_social_callback( $post ) {
    $twitter  = get_post_meta( $post->ID, 'obmc_author_twitter', true );
    $linkedin = get_post_meta( $post->ID, 'obmc_author_linkedin', true );
    $facebook = get_post_meta( $post->ID, 'obmc_author_facebook', true );
    ?>
<p>
    <label
        for="obmc_author_twitter"><strong><?php esc_html_e( 'X (Twitter) URL', 'obydullah-magazine-core' ); ?></strong></label><br>
    <input type="url" id="obmc_author_twitter" name="obmc_author_twitter" value="<?php echo esc_url( $twitter ); ?>"
        class="widefat">
</p>
<p>
    <label
        for="obmc_author_linkedin"><strong><?php esc_html_e( 'LinkedIn URL', 'obydullah-magazine-core' ); ?></strong></label><br>
    <input type="url" id="obmc_author_linkedin" name="obmc_author_linkedin" value="<?php echo esc_url( $linkedin ); ?>"
        class="widefat">
</p>
<p>
    <label
        for="obmc_author_facebook"><strong><?php esc_html_e( 'Facebook URL', 'obydullah-magazine-core' ); ?></strong></label><br>
    <input type="url" id="obmc_author_facebook" name="obmc_author_facebook" value="<?php echo esc_url( $facebook ); ?>"
        class="widefat">
</p>
<?php
}

function obmc_save_author_meta( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
        return;
    }

    if ( ! isset( $_POST['obmc_author_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['obmc_author_nonce'] ) ), 'obmc_author_meta' ) ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( 'obmc_author' !== get_post_type( $post_id ) ) {
        return;
    }

    $text_fields = array( 'obmc_author_bio', 'obmc_author_position', 'obmc_author_email' );
    foreach ( $text_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, sanitize_textarea_field( wp_unslash( $_POST[ $field ] ) ) );
        }
    }

    $url_fields = array( 'obmc_author_twitter', 'obmc_author_linkedin', 'obmc_author_facebook' );
    foreach ( $url_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, esc_url_raw( wp_unslash( $_POST[ $field ] ) ) );
        }
    }
}
add_action( 'save_post_obmc_author', 'obmc_save_author_meta' );

/* ======================================================
   6. Magazine Issues CPT + Meta Boxes
====================================================== */

function obmc_register_magazine_issue_cpt() {
    register_post_type( 'obmc_magazine_issue', array(
        'labels' => array(
            'name'          => __( 'Magazine Issues', 'obydullah-magazine-core' ),
            'singular_name' => __( 'Magazine Issue', 'obydullah-magazine-core' ),
            'add_new_item'  => __( 'Add New Magazine Issue', 'obydullah-magazine-core' ),
            'edit_item'     => __( 'Edit Magazine Issue', 'obydullah-magazine-core' ),
        ),
        'public'          => false,
        'show_ui'         => true,
        'show_in_menu'    => 'obmc-magazine-core',
        'menu_icon'       => 'dashicons-book',
        'supports'        => array( 'title', 'thumbnail' ),
        'show_in_rest'    => true,
        'capability_type' => 'post',
        'map_meta_cap'    => true,
    ) );
}
add_action( 'init', 'obmc_register_magazine_issue_cpt' );

function obmc_add_magazine_issue_meta_boxes() {
    add_meta_box(
        'obmc_magazine_issue_details',
        __( 'Issue Details', 'obydullah-magazine-core' ),
        'obmc_magazine_issue_details_callback',
        'obmc_magazine_issue',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'obmc_add_magazine_issue_meta_boxes' );

function obmc_magazine_issue_details_callback( $post ) {
    wp_nonce_field( 'obmc_magazine_issue_meta', 'obmc_magazine_issue_nonce' );
    $issue_number = get_post_meta( $post->ID, 'obmc_issue_number', true );
    $month        = get_post_meta( $post->ID, 'obmc_issue_month', true );
    $year         = get_post_meta( $post->ID, 'obmc_issue_year', true );
    $pdf_url      = get_post_meta( $post->ID, 'obmc_issue_pdf_url', true );
    ?>
<p>
    <label
        for="obmc_issue_number"><strong><?php esc_html_e( 'Issue Number', 'obydullah-magazine-core' ); ?></strong></label><br>
    <input type="text" id="obmc_issue_number" name="obmc_issue_number" value="<?php echo esc_attr( $issue_number ); ?>"
        class="widefat" placeholder="<?php esc_attr_e( 'e.g., Vol. 12, Issue 3', 'obydullah-magazine-core' ); ?>">
</p>
<p>
    <label
        for="obmc_issue_month"><strong><?php esc_html_e( 'Month', 'obydullah-magazine-core' ); ?></strong></label><br>
    <select id="obmc_issue_month" name="obmc_issue_month" class="widefat">
        <option value=""><?php esc_html_e( 'Select Month', 'obydullah-magazine-core' ); ?></option>
        <?php
        $months = array(
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        );
        foreach ( $months as $m ) :
        ?>
        <option value="<?php echo esc_attr( $m ); ?>" <?php selected( $month, $m ); ?>><?php echo esc_html( $m ); ?>
        </option>
        <?php endforeach; ?>
    </select>
</p>
<p>
    <label for="obmc_issue_year"><strong><?php esc_html_e( 'Year', 'obydullah-magazine-core' ); ?></strong></label><br>
    <input type="number" id="obmc_issue_year" name="obmc_issue_year" value="<?php echo esc_attr( $year ); ?>"
        class="widefat" placeholder="<?php esc_attr_e( 'e.g., 2026', 'obydullah-magazine-core' ); ?>" min="2000"
        max="2100">
</p>
<p>
    <label
        for="obmc_issue_pdf_url"><strong><?php esc_html_e( 'PDF Download URL', 'obydullah-magazine-core' ); ?></strong></label><br>
    <input type="url" id="obmc_issue_pdf_url" name="obmc_issue_pdf_url" value="<?php echo esc_url( $pdf_url ); ?>"
        class="widefat" placeholder="https://...">
</p>
<?php
}

function obmc_save_magazine_issue_meta( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
        return;
    }

    if ( ! isset( $_POST['obmc_magazine_issue_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['obmc_magazine_issue_nonce'] ) ), 'obmc_magazine_issue_meta' ) ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( 'obmc_magazine_issue' !== get_post_type( $post_id ) ) {
        return;
    }

    $text_fields = array( 'obmc_issue_number', 'obmc_issue_month', 'obmc_issue_year' );
    foreach ( $text_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
        }
    }

    if ( isset( $_POST['obmc_issue_pdf_url'] ) ) {
        update_post_meta( $post_id, 'obmc_issue_pdf_url', esc_url_raw( wp_unslash( $_POST['obmc_issue_pdf_url'] ) ) );
    }
}
add_action( 'save_post_obmc_magazine_issue', 'obmc_save_magazine_issue_meta' );

/* ======================================================
   7. News Ticker CPT + Meta Boxes
====================================================== */

function obmc_register_news_ticker_cpt() {
    register_post_type( 'obmc_news_ticker', array(
        'labels' => array(
            'name'          => __( 'News Ticker', 'obydullah-magazine-core' ),
            'singular_name' => __( 'News Ticker Item', 'obydullah-magazine-core' ),
            'add_new_item'  => __( 'Add New Ticker Item', 'obydullah-magazine-core' ),
            'edit_item'     => __( 'Edit Ticker Item', 'obydullah-magazine-core' ),
        ),
        'public'        => true,
        'show_in_menu'  => 'obmc-magazine-core',
        'menu_icon'     => 'dashicons-megaphone',
        'supports'      => array( 'title' ),
        'show_in_rest'  => true,
        'has_archive'   => false,
    ) );
}
add_action( 'init', 'obmc_register_news_ticker_cpt' );

function obmc_add_news_ticker_meta_box() {
    add_meta_box(
        'obmc_news_ticker_meta',
        __( 'Ticker Settings', 'obydullah-magazine-core' ),
        'obmc_render_news_ticker_meta_box',
        'obmc_news_ticker',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'obmc_add_news_ticker_meta_box' );

function obmc_render_news_ticker_meta_box( $post ) {
    $link    = get_post_meta( $post->ID, 'obmc_ticker_link', true );
    $is_breaking = get_post_meta( $post->ID, 'obmc_ticker_breaking', true );
    wp_nonce_field( 'obmc_save_news_ticker_meta', 'obmc_news_ticker_nonce' );
    ?>
<p>
    <label
        for="obmc_ticker_link"><strong><?php esc_html_e( 'Link URL', 'obydullah-magazine-core' ); ?></strong></label><br>
    <input type="url" id="obmc_ticker_link" name="obmc_ticker_link" value="<?php echo esc_url( $link ); ?>"
        class="widefat" placeholder="https://...">
</p>
<p>
    <label>
        <input type="checkbox" id="obmc_ticker_breaking" name="obmc_ticker_breaking" value="1"
            <?php checked( $is_breaking, '1' ); ?>>
        <strong><?php esc_html_e( 'Mark as Breaking News', 'obydullah-magazine-core' ); ?></strong>
    </label>
</p>
<?php
}

function obmc_save_news_ticker_meta( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
        return;
    }

    if ( ! isset( $_POST['obmc_news_ticker_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['obmc_news_ticker_nonce'] ) ), 'obmc_save_news_ticker_meta' ) ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( 'obmc_news_ticker' !== get_post_type( $post_id ) ) {
        return;
    }

    if ( isset( $_POST['obmc_ticker_link'] ) ) {
        update_post_meta( $post_id, 'obmc_ticker_link', esc_url_raw( wp_unslash( $_POST['obmc_ticker_link'] ) ) );
    }

    $breaking = isset( $_POST['obmc_ticker_breaking'] ) ? '1' : '0';
    update_post_meta( $post_id, 'obmc_ticker_breaking', $breaking );
}
add_action( 'save_post_obmc_news_ticker', 'obmc_save_news_ticker_meta' );

/* ===================================================================
   8. Newsletter Subscriptions (Custom DB table, AJAX handler)
======================================================================= */

function obmc_newsletter_table() {
    global $wpdb;

    return $wpdb->prefix . 'obmc_newsletter_subscribers';
}

/**
 * Columns the subscriber list may be sorted by.
 *
 * Single source of truth for two consumers: the sortable columns exposed by the
 * list table, and the ORDER BY whitelist in
 * obmc_newsletter_cache_get_subscribers(). Keeping one list means a column
 * cannot become sortable in the UI while staying unordered in the query, or
 * vice versa.
 *
 * @return array Column => array( orderby, is_default_desc ) map, in WP_List_Table format.
 */
function obmc_newsletter_sortable_columns() {
    return array(
        'id'         => array( 'id', false ),
        'email'      => array( 'email', false ),
        'created_at' => array( 'created_at', false ),
    );
}

/**
 * Cache generation counter for subscriber list queries.
 *
 * Paged/sorted result sets each need their own cache key, so writes cannot
 * enumerate and delete them. Bumping a version invalidates every variant at
 * once. Stored as an autoloaded option so reads come from the alloptions cache.
 *
 * @return int Current cache version.
 */
function obmc_newsletter_cache_version() {
    $version = get_option( 'obmc_newsletter_cache_version' );

    if ( false === $version ) {
        $version = 1;
        add_option( 'obmc_newsletter_cache_version', $version, '', 'yes' );
    }

    return (int) $version;
}

/**
 * Invalidates every cached subscriber query.
 */
function obmc_newsletter_cache_flush() {
    wp_cache_delete( 'count', OBMC_NEWSLETTER_CACHE_GROUP );

    // update_option() refreshes the autoloaded alloptions cache for us.
    update_option( 'obmc_newsletter_cache_version', obmc_newsletter_cache_version() + 1, 'yes' );
}

/**
 * Total number of newsletter subscribers.
 *
 * @return int Subscriber count.
 */
function obmc_newsletter_cache_get_count() {
    $count = wp_cache_get( 'count', OBMC_NEWSLETTER_CACHE_GROUP );

    if ( false === $count ) {
        global $wpdb;

        $count = (int) $wpdb->get_var(
            $wpdb->prepare( 'SELECT COUNT(*) FROM %i', obmc_newsletter_table() )
        );

        // Short TTL as a safety net in case a write path is ever missed.
        wp_cache_set( 'count', $count, OBMC_NEWSLETTER_CACHE_GROUP, 5 * MINUTE_IN_SECONDS );
    }

    return $count;
}

/**
 * A page of subscriber rows.
 *
 * @param int    $per_page Rows per page.
 * @param int    $offset   Row offset.
 * @param string $orderby  Whitelisted order column.
 * @param string $order    ASC or DESC.
 * @return array Row arrays.
 */
function obmc_newsletter_cache_get_subscribers( $per_page, $offset, $orderby, $order ) {
    $key = 'list_' . obmc_newsletter_cache_version() . '_' . md5( $per_page . '|' . $offset . '|' . $orderby . '|' . $order );
    $rows = wp_cache_get( $key, OBMC_NEWSLETTER_CACHE_GROUP );

    if ( false === $rows ) {
        global $wpdb;

        // The sort column is validated here rather than trusted from the caller,
        // then bound as an identifier. The direction cannot be bound at all, so
        // it selects between two literal queries instead of being interpolated.
        $columns = obmc_newsletter_sortable_columns();
        $orderby = array_key_exists( $orderby, $columns ) ? $orderby : 'id';

        if ( 'ASC' === strtoupper( $order ) ) {
            $rows = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT id, email, name, status, created_at
                     FROM %i
                     ORDER BY %i ASC
                     LIMIT %d OFFSET %d",
                    obmc_newsletter_table(),
                    $orderby,
                    $per_page,
                    $offset
                ),
                ARRAY_A
            );
        } else {
            $rows = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT id, email, name, status, created_at
                     FROM %i
                     ORDER BY %i DESC
                     LIMIT %d OFFSET %d",
                    obmc_newsletter_table(),
                    $orderby,
                    $per_page,
                    $offset
                ),
                ARRAY_A
            );
        }

        wp_cache_set( $key, $rows, OBMC_NEWSLETTER_CACHE_GROUP, 5 * MINUTE_IN_SECONDS );
    }

    return $rows;
}

function obmc_create_newsletter_table() {
    global $wpdb;
    $table_name = obmc_newsletter_table();
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        email varchar(100) NOT NULL,
        name varchar(100),
        status varchar(20) DEFAULT 'active',
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY email (email)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );
}

/**
 * Registers every post type and taxonomy the plugin owns.
 *
 * wp-admin/plugins.php calls activate_plugin() long after wp-settings.php has
 * fired `init`, so the add_action( 'init', ... ) callbacks below never run in the
 * activation request. Registering here means flush_rewrite_rules() writes the
 * rules for the archives and single posts instead of leaving them out.
 */
function obmc_register_plugin_post_types() {
    obmc_register_hero_slide_cpt();
    obmc_register_featured_article_cpt();
    obmc_register_article();
    obmc_register_article_category();
    obmc_register_author_cpt();
    obmc_register_magazine_issue_cpt();
    obmc_register_news_ticker_cpt();
    obmc_register_advertisement_cpt();
    obmc_register_footer_settings();
    obmc_register_about_page_cpt();
    obmc_register_contact_page_cpt();
}

/**
 * Moves featured article rows off the pre-1.0.1 post type name.
 *
 * 1.0.0 registered `obmc_featured_article`, which is 21 characters long: over the
 * limit core enforces in register_post_type(), and over `wp_posts.post_type`, which
 * is a varchar(20). On a strict MySQL server wp_insert_post() rejected those rows
 * outright, so most installs have none; where the server was not strict, the column
 * silently truncated the value to `obmc_featured_articl`. Both forms are moved here.
 *
 * @return int Number of rows moved.
 */
function obmc_migrate_featured_article_post_type() {
    global $wpdb;

    $old_types = array( 'obmc_featured_article', 'obmc_featured_articl' );
    $moved = 0;

    /* phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Post types live in a wp_posts column rather than an option, so get_posts() cannot reach rows of a type that is no longer registered. This runs once per version, on activation and on the first admin page load after an update. */
    foreach ( $old_types as $old_type ) {
        $ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type = %s", $old_type ) );
        $ids = is_array( $ids ) ? $ids : array();

        if ( ! $ids ) {
            continue;
        }

        $updated = $wpdb->update(
            $wpdb->posts,
            array( 'post_type' => 'obmc_featured' ),
            array( 'post_type' => $old_type ),
            array( '%s' ),
            array( '%s' )
        );

        foreach ( $ids as $id ) {
            clean_post_cache( (int) $id );
        }

        if ( false !== $updated ) {
            $moved += (int) $updated;
        }
    }
    /* phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared */

    return $moved;
}

/**
 * Runs the data migrations a version bump needs, once per stored version.
 */
function obmc_maybe_upgrade() {
    if ( OBMC_VERSION === get_option( 'obmc_db_version' ) ) {
        return;
    }

    obmc_migrate_featured_article_post_type();

    // No rewrite flush is needed: the rewrite slug stayed `obmc_featured_article`,
    // so the rules written by 1.0.0 already describe the renamed post type.
    update_option( 'obmc_db_version', OBMC_VERSION, false );
}
add_action( 'admin_init', 'obmc_maybe_upgrade' );

/**
 * Sets the plugin up on activation.
 *
 * Prints nothing: WordPress turns output during activation into an
 * "unexpected output" warning.
 */
function obmc_activate() {
    obmc_register_plugin_post_types();
    obmc_create_newsletter_table();
    obmc_maybe_upgrade();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'obmc_activate' );

function obmc_drop_newsletter_table() {
    global $wpdb;
    $table_name = obmc_newsletter_table();
    $wpdb->query( $wpdb->prepare( 'DROP TABLE IF EXISTS %i', $table_name ) );

    delete_option( 'obmc_newsletter_cache_version' );
}
register_uninstall_hook( __FILE__, 'obmc_drop_newsletter_table' );

function obmc_handle_newsletter_submission() {
    // Verify nonce - sanitize the input first
    $nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ?? '' ) );

    if ( ! wp_verify_nonce( $nonce, 'obmc_newsletter_nonce' ) ) {
        wp_send_json_error( array( 'error' => __( 'Security check failed.', 'obydullah-magazine-core' ) ), 403 );
    }

    $email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
    $name  = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );

    $errors = array();
    if ( ! is_email( $email ) ) {
        $errors[] = __( 'Valid email is required.', 'obydullah-magazine-core' );
    }

    if ( ! empty( $errors ) ) {
        wp_send_json_error( array( 'errors' => $errors ) );
    }

    global $wpdb;
    $table_name = obmc_newsletter_table();

    $existing = $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM %i WHERE email = %s', $table_name, $email ) );
    if ( $existing ) {
        wp_send_json_error( array( 'error' => __( 'This email is already subscribed.', 'obydullah-magazine-core' ) ) );
    }

    $result = $wpdb->insert(
        $table_name,
        array(
            'email'      => $email,
            'name'       => $name,
            'status'     => 'active',
            'created_at' => current_time( 'mysql' ),
        ),
        array( '%s', '%s', '%s', '%s' )
    );

    if ( false === $result ) {
        wp_send_json_error( array( 'error' => __( 'Database error. Please try again.', 'obydullah-magazine-core' ) ) );
    }

    obmc_newsletter_cache_flush();

    wp_send_json_success( array( 'message' => __( 'Thank you for subscribing to our newsletter!', 'obydullah-magazine-core' ) ) );
}
add_action( 'wp_ajax_obmc_newsletter', 'obmc_handle_newsletter_submission' );
add_action( 'wp_ajax_nopriv_obmc_newsletter', 'obmc_handle_newsletter_submission' );

function obmc_newsletter_admin_menu() {
    add_submenu_page(
        'obmc-magazine-core',
        __( 'Newsletter Subscribers', 'obydullah-magazine-core' ),
        __( 'Newsletter Subscribers', 'obydullah-magazine-core' ),
        'manage_options',
        'obmc-newsletter',
        'obmc_render_newsletter_page'
    );
}
add_action( 'admin_menu', 'obmc_newsletter_admin_menu' );

function obmc_toggle_subscriber_status() {
    if ( ! isset( $_GET['action'] ) || 'obmc_toggle_subscriber' !== $_GET['action'] || ! isset( $_GET['subscriber_id'] ) ) {
        return;
    }

    $subscriber_id = absint( $_GET['subscriber_id'] );

    check_admin_referer( 'obmc_toggle_subscriber_' . $subscriber_id );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Unauthorized.', 'obydullah-magazine-core' ) );
    }

    global $wpdb;
    $table_name = obmc_newsletter_table();

    // Read the stored status and flip it server-side rather than trusting a
    // status passed through the query string.
    $current = $wpdb->get_var(
        $wpdb->prepare( 'SELECT status FROM %i WHERE id = %d', $table_name, $subscriber_id )
    );

    if ( null === $current ) {
        wp_die( esc_html__( 'Subscriber not found.', 'obydullah-magazine-core' ), 404 );
    }

    $new_status = ( 'active' === $current ) ? 'inactive' : 'active';

    $updated = $wpdb->update(
        $table_name,
        array( 'status' => $new_status ),
        array( 'id' => $subscriber_id ),
        array( '%s' ),
        array( '%d' )
    );

    if ( false === $updated ) {
        wp_die( esc_html__( 'Could not update the subscriber status.', 'obydullah-magazine-core' ) );
    }

    obmc_newsletter_cache_flush();

    wp_safe_redirect(
        add_query_arg(
            array(
                'page'       => 'obmc-newsletter',
                'obmc_notice' => $new_status,
            ),
            admin_url( 'admin.php' )
        )
    );
    exit;
}
add_action( 'admin_init', 'obmc_toggle_subscriber_status' );

function obmc_render_newsletter_page() {
    if ( isset( $_POST['action'] ) && 'delete' === $_POST['action'] && isset( $_POST['subscriber_ids'] ) ) {
        check_admin_referer( 'bulk-newsletter', 'obmc_bulk_nonce' );
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Unauthorized.', 'obydullah-magazine-core' ) );
        }

        global $wpdb;
        $table_name = obmc_newsletter_table();
        $ids        = array_map( 'intval', wp_unslash( $_POST['subscriber_ids'] ) );
        $ids        = array_filter( $ids );

        if ( ! empty( $ids ) ) {
            $deleted = 0;

            foreach ( $ids as $id ) {
                if ( false !== $wpdb->delete( $table_name, array( 'id' => $id ), array( '%d' ) ) ) {
                    $deleted++;
                }
            }

            if ( $deleted > 0 ) {
                obmc_newsletter_cache_flush();

                printf(
                    '<div class="notice notice-success"><p>%s</p></div>',
                    esc_html(
                        sprintf(
                            /* translators: %d: number of deleted subscribers. */
                            _n( '%d subscriber deleted.', '%d subscribers deleted.', $deleted, 'obydullah-magazine-core' ),
                            $deleted
                        )
                    )
                );
            } else {
                echo '<div class="notice notice-warning"><p>' . esc_html__( 'No subscribers were deleted.', 'obydullah-magazine-core' ) . '</p></div>';
            }
        }
    }

    if ( ! class_exists( 'OBMC_Newsletter_List_Table' ) ) {
        require_once OBMC_PLUGIN_DIR . 'includes/obmc-newsletter-list-table.php';
    }

    $newsletter_table = new OBMC_Newsletter_List_Table();
    $newsletter_table->prepare_items();

    if ( isset( $_GET['obmc_notice'] ) ) {
        if ( 'inactive' === $_GET['obmc_notice'] ) {
            echo '<div class="notice notice-success"><p>' . esc_html__( 'Subscriber deactivated.', 'obydullah-magazine-core' ) . '</p></div>';
        } elseif ( 'active' === $_GET['obmc_notice'] ) {
            echo '<div class="notice notice-success"><p>' . esc_html__( 'Subscriber activated.', 'obydullah-magazine-core' ) . '</p></div>';
        }
    }
    ?>
<div class="wrap">
    <h1><?php esc_html_e( 'Newsletter Subscribers', 'obydullah-magazine-core' ); ?></h1>
    <form method="post">
        <?php $newsletter_table->display(); ?>
        <?php wp_nonce_field( 'bulk-newsletter', 'obmc_bulk_nonce' ); ?>
    </form>
</div>
<?php
}

/* ======================================================
   9. Advertisement Slots (Single Instance) + Meta Boxes
====================================================== */

function obmc_register_advertisement_cpt() {
    register_post_type( 'obmc_advertisement', array(
        'labels' => array(
            'name'          => __( 'Advertisement Slots', 'obydullah-magazine-core' ),
            'singular_name' => __( 'Advertisement Slot', 'obydullah-magazine-core' ),
            'add_new_item'  => __( 'Edit Advertisement Slot', 'obydullah-magazine-core' ),
            'edit_item'     => __( 'Edit Advertisement Slot', 'obydullah-magazine-core' ),
        ),
        'public'           => false,
        'show_ui'          => true,
        'show_in_menu'     => 'obmc-magazine-core',
        'menu_icon'        => 'dashicons-megaphone',
        'supports'         => array( 'title' ),
        'show_in_rest'     => true,
        'capability_type'  => 'post',
        'map_meta_cap'     => true,
    ) );
}
add_action( 'init', 'obmc_register_advertisement_cpt' );

function obmc_add_advertisement_meta_boxes() {
    add_meta_box(
        'obmc_advertisement_slots',
        __( 'Advertisement Slots', 'obydullah-magazine-core' ),
        'obmc_advertisement_slots_callback',
        'obmc_advertisement',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'obmc_add_advertisement_meta_boxes' );

function obmc_advertisement_slots_callback( $post ) {
    wp_nonce_field( 'obmc_advertisement_meta', 'obmc_advertisement_nonce' );
    
    $slots = get_post_meta( $post->ID, 'obmc_ad_slots', true );
    if ( ! is_array( $slots ) ) {
        $slots = array(
            array( 'name' => 'Header Banner', 'code' => '', 'active' => '1' ),
            array( 'name' => 'Sidebar', 'code' => '', 'active' => '1' ),
            array( 'name' => 'In-Article', 'code' => '', 'active' => '1' ),
            array( 'name' => 'Footer', 'code' => '', 'active' => '1' ),
        );
    }
    ?>
<div id="obmc-ad-slots-repeater" class="obmc-repeater">
    <input type="hidden" id="obmc-ad-slot-count" name="obmc_ad_slot_count"
        value="<?php echo esc_attr( count( $slots ) ); ?>">
    <?php foreach ( $slots as $index => $slot ) : ?>
    <div class="obmc-ad-slot-row obmc-repeater-row" data-index="<?php echo esc_attr( $index ); ?>">
        <p>
            <label><strong><?php esc_html_e( 'Slot Name', 'obydullah-magazine-core' ); ?></strong></label><br>
            <input type="text" name="obmc_ad_slots[<?php echo esc_attr( $index ); ?>][name]"
                value="<?php echo esc_attr( $slot['name'] ); ?>" class="widefat">
        </p>
        <p>
            <label><strong><?php esc_html_e( 'Ad Code / HTML', 'obydullah-magazine-core' ); ?></strong></label><br>
            <textarea name="obmc_ad_slots[<?php echo esc_attr( $index ); ?>][code]" rows="4"
                class="large-text"><?php echo esc_textarea( $slot['code'] ); ?></textarea>
        </p>
        <p>
            <label>
                <input type="checkbox" name="obmc_ad_slots[<?php echo esc_attr( $index ); ?>][active]" value="1"
                    <?php checked( $slot['active'], '1' ); ?>>
                <?php esc_html_e( 'Active', 'obydullah-magazine-core' ); ?>
            </label>
        </p>
        <button type="button"
            class="button obmc-remove-row"><?php esc_html_e( 'Remove', 'obydullah-magazine-core' ); ?></button>
        <hr>
    </div>
    <?php endforeach; ?>
</div>
<button type="button" id="obmc-add-ad-slot"
    class="button"><?php esc_html_e( 'Add New Slot', 'obydullah-magazine-core' ); ?></button>
<?php
}

function obmc_enqueue_advertisement_assets( $hook ) {
    global $post_type;
    if ( 'obmc_advertisement' === $post_type && in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
        wp_enqueue_style( 'obmc-advertisement-css', OBMC_PLUGIN_URL . 'assets/css/advertisement-admin.css', array(), OBMC_VERSION );
        wp_enqueue_script( 'obmc-advertisement-js', OBMC_PLUGIN_URL . 'assets/js/advertisement-admin.js', array( 'jquery' ), OBMC_VERSION, true );
        wp_localize_script( 'obmc-advertisement-js', 'obmcAdL10n', array(
            'removeText'    => __( 'Remove', 'obydullah-magazine-core' ),
            'slotNameLabel' => __( 'Slot Name', 'obydullah-magazine-core' ),
            'codeLabel'     => __( 'Ad Code / HTML', 'obydullah-magazine-core' ),
            'activeLabel'   => __( 'Active', 'obydullah-magazine-core' ),
        ) );
    }
}
add_action( 'admin_enqueue_scripts', 'obmc_enqueue_advertisement_assets' );

function obmc_save_advertisement_meta( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
        return;
    }

    if ( ! isset( $_POST['obmc_advertisement_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['obmc_advertisement_nonce'] ) ), 'obmc_advertisement_meta' ) ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( 'obmc_advertisement' !== get_post_type( $post_id ) ) {
        return;
    }

    if ( isset( $_POST['obmc_ad_slots'] ) && is_array( $_POST['obmc_ad_slots'] ) ) {
        $slots = array();
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Rows are unslashed as a group; each field is sanitized individually below.
        foreach ( wp_unslash( $_POST['obmc_ad_slots'] ) as $slot ) {
            $name   = isset( $slot['name'] ) ? sanitize_text_field( $slot['name'] ) : '';
            $code   = isset( $slot['code'] ) ? wp_kses_post( $slot['code'] ) : '';
            $active = isset( $slot['active'] ) ? '1' : '0';
            if ( ! empty( $name ) ) {
                $slots[] = array( 'name' => $name, 'code' => $code, 'active' => $active );
            }
        }
        update_post_meta( $post_id, 'obmc_ad_slots', $slots );
    } else {
        update_post_meta( $post_id, 'obmc_ad_slots', array() );
    }
}
add_action( 'save_post_obmc_advertisement', 'obmc_save_advertisement_meta' );

/* ======================================================
   10. Footer Settings (Single Instance) + Meta Boxes
====================================================== */

function obmc_register_footer_settings() {
    register_post_type( 'obmc_footer', array(
        'labels' => array(
            'name'          => __( 'Footer Settings', 'obydullah-magazine-core' ),
            'singular_name' => __( 'Footer Settings', 'obydullah-magazine-core' ),
            'add_new_item'  => __( 'Edit Footer Settings', 'obydullah-magazine-core' ),
            'edit_item'     => __( 'Edit Footer Settings', 'obydullah-magazine-core' ),
        ),
        'public'           => false,
        'show_ui'          => true,
        'show_in_menu'     => 'obmc-magazine-core',
        'menu_icon'        => 'dashicons-layout',
        'supports'         => array( 'title' ),
        'show_in_rest'     => true,
        'capability_type'  => 'post',
        'map_meta_cap'     => true,
    ) );
}
add_action( 'init', 'obmc_register_footer_settings' );

function obmc_limit_footer_settings() {
    obmc_limit_single_instance_cpt( 'obmc_footer' );
}
add_action( 'load-post-new.php', 'obmc_limit_footer_settings' );

function obmc_add_footer_meta_boxes() {
    add_meta_box( 'obmc_footer_logo', __( 'Logo & Tagline', 'obydullah-magazine-core' ), 'obmc_footer_logo_callback', 'obmc_footer', 'normal', 'high' );
    add_meta_box( 'obmc_footer_social', __( 'Social Media URLs', 'obydullah-magazine-core' ), 'obmc_footer_social_callback', 'obmc_footer', 'normal', 'high' );
    add_meta_box( 'obmc_footer_quick_links', __( 'Quick Links (repeater)', 'obydullah-magazine-core' ), 'obmc_footer_links_callback', 'obmc_footer', 'normal', 'high' );
    add_meta_box( 'obmc_footer_contact', __( 'Contact Information', 'obydullah-magazine-core' ), 'obmc_footer_contact_callback', 'obmc_footer', 'normal', 'high' );
    add_meta_box( 'obmc_footer_copyright', __( 'Copyright Text', 'obydullah-magazine-core' ), 'obmc_footer_copyright_callback', 'obmc_footer', 'side', 'default' );
}
add_action( 'add_meta_boxes', 'obmc_add_footer_meta_boxes' );

function obmc_footer_logo_callback( $post ) {
    wp_nonce_field( 'obmc_footer_meta', 'obmc_footer_nonce' );
    $logo_text   = get_post_meta( $post->ID, 'obmc_footer_logo_text', true );
    $logo_accent = get_post_meta( $post->ID, 'obmc_footer_logo_accent', true );
    $tagline     = get_post_meta( $post->ID, 'obmc_footer_tagline', true );
    ?>
<p>
    <label for="obmc_footer_logo_text"><?php esc_html_e( 'Logo Base Text', 'obydullah-magazine-core' ); ?></label><br>
    <input type="text" name="obmc_footer_logo_text" id="obmc_footer_logo_text"
        value="<?php echo esc_attr( $logo_text ); ?>" class="widefat">
</p>
<p>
    <label
        for="obmc_footer_logo_accent"><?php esc_html_e( 'Logo Accent Text (highlighted)', 'obydullah-magazine-core' ); ?></label><br>
    <input type="text" name="obmc_footer_logo_accent" id="obmc_footer_logo_accent"
        value="<?php echo esc_attr( $logo_accent ); ?>" class="widefat">
</p>
<p>
    <label
        for="obmc_footer_tagline"><?php esc_html_e( 'Tagline / Description', 'obydullah-magazine-core' ); ?></label><br>
    <textarea name="obmc_footer_tagline" id="obmc_footer_tagline" rows="3"
        class="large-text"><?php echo esc_textarea( $tagline ); ?></textarea>
</p>
<?php
}

function obmc_footer_social_callback( $post ) {
    $social = get_post_meta( $post->ID, 'obmc_footer_social', true );
    if ( ! is_array( $social ) ) $social = array();
    ?>
<p>
    <label
        for="obmc_footer_social_twitter"><?php esc_html_e( 'X (Twitter) URL', 'obydullah-magazine-core' ); ?></label><br>
    <input type="text" name="obmc_footer_social[twitter]" id="obmc_footer_social_twitter"
        value="<?php echo esc_attr( $social['twitter'] ?? '' ); ?>" class="widefat">
</p>
<p>
    <label
        for="obmc_footer_social_facebook"><?php esc_html_e( 'Facebook URL', 'obydullah-magazine-core' ); ?></label><br>
    <input type="text" name="obmc_footer_social[facebook]" id="obmc_footer_social_facebook"
        value="<?php echo esc_attr( $social['facebook'] ?? '' ); ?>" class="widefat">
</p>
<p>
    <label
        for="obmc_footer_social_instagram"><?php esc_html_e( 'Instagram URL', 'obydullah-magazine-core' ); ?></label><br>
    <input type="text" name="obmc_footer_social[instagram]" id="obmc_footer_social_instagram"
        value="<?php echo esc_attr( $social['instagram'] ?? '' ); ?>" class="widefat">
</p>
<p>
    <label
        for="obmc_footer_social_linkedin"><?php esc_html_e( 'LinkedIn URL', 'obydullah-magazine-core' ); ?></label><br>
    <input type="text" name="obmc_footer_social[linkedin]" id="obmc_footer_social_linkedin"
        value="<?php echo esc_attr( $social['linkedin'] ?? '' ); ?>" class="widefat">
</p>
<?php
}

function obmc_footer_links_callback( $post ) {
    $links = get_post_meta( $post->ID, 'obmc_footer_links', true );
    if ( ! is_array( $links ) ) $links = array();
    $next_index = count( $links );
    ?>
<div id="obmc-footer-links-repeater" class="obmc-repeater">
    <input type="hidden" id="obmc-footer-link-count" name="obmc_footer_link_count"
        value="<?php echo esc_attr( $next_index ); ?>">
    <?php foreach ( $links as $index => $link ) : ?>
    <div class="obmc-footer-link-row obmc-repeater-row" data-index="<?php echo esc_attr( $index ); ?>">
        <input type="text" name="obmc_footer_links[<?php echo esc_attr( $index ); ?>][text]" class="obmc-link-text"
            value="<?php echo esc_attr( $link['text'] ); ?>"
            placeholder="<?php esc_attr_e( 'Link text', 'obydullah-magazine-core' ); ?>">
        <input type="text" name="obmc_footer_links[<?php echo esc_attr( $index ); ?>][url]" class="obmc-link-url"
            value="<?php echo esc_attr( $link['url'] ); ?>"
            placeholder="<?php esc_attr_e( 'URL', 'obydullah-magazine-core' ); ?>">
        <button type="button"
            class="button obmc-remove-row"><?php esc_html_e( 'Remove', 'obydullah-magazine-core' ); ?></button>
    </div>
    <?php endforeach; ?>
</div>
<button type="button" id="obmc-add-footer-link"
    class="button"><?php esc_html_e( 'Add Link', 'obydullah-magazine-core' ); ?></button>
<?php
}

function obmc_footer_contact_callback( $post ) {
    $address = get_post_meta( $post->ID, 'obmc_footer_address', true );
    $phone   = get_post_meta( $post->ID, 'obmc_footer_phone', true );
    $email   = get_post_meta( $post->ID, 'obmc_footer_email', true );
    ?>
<p>
    <label for="obmc_footer_address"><?php esc_html_e( 'Address', 'obydullah-magazine-core' ); ?></label><br>
    <textarea name="obmc_footer_address" id="obmc_footer_address" rows="3"
        class="large-text"><?php echo esc_textarea( $address ); ?></textarea>
</p>
<p>
    <label for="obmc_footer_phone"><?php esc_html_e( 'Phone', 'obydullah-magazine-core' ); ?></label><br>
    <input type="tel" name="obmc_footer_phone" id="obmc_footer_phone" value="<?php echo esc_attr( $phone ); ?>"
        class="widefat">
</p>
<p>
    <label for="obmc_footer_email"><?php esc_html_e( 'Email', 'obydullah-magazine-core' ); ?></label><br>
    <input type="email" name="obmc_footer_email" id="obmc_footer_email" value="<?php echo esc_attr( $email ); ?>"
        class="widefat">
</p>
<?php
}

function obmc_footer_copyright_callback( $post ) {
    $copyright = get_post_meta( $post->ID, 'obmc_footer_copyright', true );
    ?>
<p>
    <label for="obmc_footer_copyright"><?php esc_html_e( 'Copyright text', 'obydullah-magazine-core' ); ?></label><br>
    <input type="text" name="obmc_footer_copyright" id="obmc_footer_copyright"
        value="<?php echo esc_attr( $copyright ); ?>" class="widefat">
</p>
<?php
}

function obmc_enqueue_footer_assets( $hook ) {
    global $post_type;
    if ( 'obmc_footer' === $post_type && in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
        wp_enqueue_style( 'obmc-footer-css', OBMC_PLUGIN_URL . 'assets/css/footer-admin.css', array(), OBMC_VERSION );
        wp_enqueue_script( 'obmc-footer-js', OBMC_PLUGIN_URL . 'assets/js/footer-admin.js', array( 'jquery' ), OBMC_VERSION, true );
        wp_localize_script( 'obmc-footer-js', 'obmcFooterL10n', array(
            'linkTextPlaceholder' => __( 'Link text', 'obydullah-magazine-core' ),
            'urlPlaceholder'      => __( 'URL', 'obydullah-magazine-core' ),
            'removeText'          => __( 'Remove', 'obydullah-magazine-core' ),
        ) );
    }
}
add_action( 'admin_enqueue_scripts', 'obmc_enqueue_footer_assets' );

function obmc_save_footer_meta( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
        return;
    }

    if ( ! isset( $_POST['obmc_footer_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['obmc_footer_nonce'] ) ), 'obmc_footer_meta' ) ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( 'obmc_footer' !== get_post_type( $post_id ) ) {
        return;
    }

    $logo_fields = array( 'obmc_footer_logo_text', 'obmc_footer_logo_accent', 'obmc_footer_tagline' );
    foreach ( $logo_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
        }
    }

    if ( isset( $_POST['obmc_footer_social'] ) && is_array( $_POST['obmc_footer_social'] ) ) {
        $allowed_networks = array( 'twitter', 'facebook', 'instagram', 'linkedin' );
        $social           = array();

        // Only the known networks are read, so a posted key outside this list
        // cannot reach post meta.
        foreach ( $allowed_networks as $network ) {
            if ( isset( $_POST['obmc_footer_social'][ $network ] ) ) {
                $social[ $network ] = esc_url_raw( wp_unslash( $_POST['obmc_footer_social'][ $network ] ) );
            }
        }

        update_post_meta( $post_id, 'obmc_footer_social', $social );
    }

    if ( isset( $_POST['obmc_footer_links'] ) && is_array( $_POST['obmc_footer_links'] ) ) {
        $links = array();
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Rows are unslashed as a group; each field is sanitized individually below.
        foreach ( wp_unslash( $_POST['obmc_footer_links'] ) as $link ) {
            $text = isset( $link['text'] ) ? sanitize_text_field( $link['text'] ) : '';
            $url  = isset( $link['url'] )  ? esc_url_raw( $link['url'] ) : '';
            if ( ! empty( $text ) && ! empty( $url ) ) {
                $links[] = array( 'text' => $text, 'url' => $url );
            }
        }
        update_post_meta( $post_id, 'obmc_footer_links', $links );
    } else {
        update_post_meta( $post_id, 'obmc_footer_links', array() );
    }

    $contact_fields = array( 'obmc_footer_address', 'obmc_footer_phone', 'obmc_footer_email', 'obmc_footer_copyright' );
    foreach ( $contact_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
        }
    }
}
add_action( 'save_post_obmc_footer', 'obmc_save_footer_meta' );

/* ======================================================
   11. About Page (Single Instance) + Meta Boxes
====================================================== */

function obmc_register_about_page_cpt() {
    register_post_type( 'obmc_about_page', array(
        'labels' => array(
            'name'          => __( 'About Page', 'obydullah-magazine-core' ),
            'singular_name' => __( 'About Page', 'obydullah-magazine-core' ),
            'add_new_item'  => __( 'Edit About Page', 'obydullah-magazine-core' ),
            'edit_item'     => __( 'Edit About Page', 'obydullah-magazine-core' ),
        ),
        'public'           => false,
        'show_ui'          => true,
        'show_in_menu'     => 'obmc-magazine-core',
        'menu_icon'        => 'dashicons-info',
        'menu_position'    => 65,
        'supports'         => array( 'title' ),
        'show_in_rest'     => true,
    ) );
}
add_action( 'init', 'obmc_register_about_page_cpt' );

function obmc_limit_about_page() {
    obmc_limit_single_instance_cpt( 'obmc_about_page' );
}
add_action( 'load-post-new.php', 'obmc_limit_about_page' );

function obmc_add_about_page_meta_boxes() {

    add_meta_box(
        'obmc_about_header',
        __( 'Header', 'obydullah-magazine-core' ),
        'obmc_about_header_callback',
        'obmc_about_page'
    );

    add_meta_box(
        'obmc_about_text',
        __( 'Content', 'obydullah-magazine-core' ),
        'obmc_about_text_callback',
        'obmc_about_page'
    );

    add_meta_box(
        'obmc_about_slider',
        __( 'Slider (repeatable)', 'obydullah-magazine-core' ),
        'obmc_about_slider_callback',
        'obmc_about_page'
    );
}
add_action( 'add_meta_boxes', 'obmc_add_about_page_meta_boxes' );

function obmc_about_header_callback( $post ) {
    wp_nonce_field( 'obmc_about_page_meta', 'obmc_about_page_nonce' );

    $kicker = get_post_meta( $post->ID, 'obmc_about_kicker', true );
    $title  = get_post_meta( $post->ID, 'obmc_about_title', true );
    ?>
<p>
    <label for="obmc_about_kicker"><?php esc_html_e( 'Kicker', 'obydullah-magazine-core' ); ?></label><br>
    <input type="text" name="obmc_about_kicker" id="obmc_about_kicker" value="<?php echo esc_attr( $kicker ); ?>"
        class="widefat">
</p>
<p>
    <label for="obmc_about_title"><?php esc_html_e( 'Main Title', 'obydullah-magazine-core' ); ?></label><br>
    <input type="text" name="obmc_about_title" id="obmc_about_title" value="<?php echo esc_attr( $title ); ?>"
        class="widefat">
</p>
<?php
}

function obmc_about_text_callback( $post ) {

    $mission    = get_post_meta( $post->ID, 'obmc_about_mission', true );
    $team       = get_post_meta( $post->ID, 'obmc_about_team', true );
    $history    = get_post_meta( $post->ID, 'obmc_about_history', true );
    ?>
<p>
    <label for="obmc_about_mission"><?php esc_html_e( 'Mission Statement', 'obydullah-magazine-core' ); ?></label><br>
    <textarea name="obmc_about_mission" id="obmc_about_mission" rows="6"
        class="large-text"><?php echo esc_textarea( $mission ); ?></textarea>
</p>
<p>
    <label for="obmc_about_team"><?php esc_html_e( 'Our Team', 'obydullah-magazine-core' ); ?></label><br>
    <textarea name="obmc_about_team" id="obmc_about_team" rows="6"
        class="large-text"><?php echo esc_textarea( $team ); ?></textarea>
</p>
<p>
    <label for="obmc_about_history"><?php esc_html_e( 'History', 'obydullah-magazine-core' ); ?></label><br>
    <textarea name="obmc_about_history" id="obmc_about_history" rows="6"
        class="large-text"><?php echo esc_textarea( $history ); ?></textarea>
</p>
<?php
}

function obmc_about_slider_callback( $post ) {

    $slides = get_post_meta( $post->ID, 'obmc_about_slides', true );
    if ( ! is_array( $slides ) ) {
        $slides = array();
    }
    $next_index = count( $slides );
    ?>
<div id="obmc-about-slides-repeater" class="obmc-slides-repeater">
    <input type="hidden" id="obmc-about-slide-count" name="obmc_about_slide_count"
        value="<?php echo esc_attr( $next_index ); ?>">
    <?php foreach ( $slides as $index => $slide ) : ?>
    <div class="obmc-slide-row" data-index="<?php echo esc_attr( $index ); ?>">
        <p>
            <label><?php esc_html_e( 'Title', 'obydullah-magazine-core' ); ?></label><br>
            <input type="text" name="obmc_about_slides[<?php echo esc_attr( $index ); ?>][title]"
                value="<?php echo esc_attr( $slide['title'] ); ?>" class="widefat">
        </p>
        <p>
            <label><?php esc_html_e( 'Subtitle', 'obydullah-magazine-core' ); ?></label><br>
            <input type="text" name="obmc_about_slides[<?php echo esc_attr( $index ); ?>][subtitle]"
                value="<?php echo esc_attr( $slide['subtitle'] ); ?>" class="widefat">
        </p>
        <div class="slide-image-wrapper">
            <label><?php esc_html_e( 'Background Image', 'obydullah-magazine-core' ); ?></label><br>
            <input type="hidden" name="obmc_about_slides[<?php echo esc_attr( $index ); ?>][image]"
                class="slide-image-url" value="<?php echo esc_url( $slide['image'] ); ?>">
            <div class="image-preview">
                <?php if ( ! empty( $slide['image'] ) ) : ?>
                <img src="<?php echo esc_url( $slide['image'] ); ?>" class="preview-thumb">
                <?php endif; ?>
            </div>
            <button type="button"
                class="button select-slide-image"><?php esc_html_e( 'Select Image', 'obydullah-magazine-core' ); ?></button>
            <button type="button"
                class="button remove-slide-image <?php echo empty( $slide['image'] ) ? 'hidden' : ''; ?>"><?php esc_html_e( 'Remove Image', 'obydullah-magazine-core' ); ?></button>
        </div>
        <button type="button"
            class="button obmc-remove-slide-row mt-1"><?php esc_html_e( 'Remove Slide', 'obydullah-magazine-core' ); ?></button>
    </div>
    <?php endforeach; ?>
</div>
<button type="button" id="obmc-add-about-slide"
    class="button"><?php esc_html_e( 'Add Slide', 'obydullah-magazine-core' ); ?></button>
<?php
}

function obmc_enqueue_about_assets( $hook ) {
    global $post_type;
    if ( 'obmc_about_page' === $post_type && in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
        wp_enqueue_media();
        wp_enqueue_style( 'obmc-about-css', OBMC_PLUGIN_URL . 'assets/css/about-admin.css', array(), OBMC_VERSION );
        wp_enqueue_script( 'obmc-about-js', OBMC_PLUGIN_URL . 'assets/js/about-admin.js', array( 'jquery' ), OBMC_VERSION, true );
        wp_localize_script( 'obmc-about-js', 'obmcAboutL10n', array(
            'titlePlaceholder'    => __( 'Title', 'obydullah-magazine-core' ),
            'subtitlePlaceholder' => __( 'Subtitle', 'obydullah-magazine-core' ),
            'imagePlaceholder'    => __( 'Background Image', 'obydullah-magazine-core' ),
            'selectImage'         => __( 'Select Image', 'obydullah-magazine-core' ),
            'removeImage'         => __( 'Remove Image', 'obydullah-magazine-core' ),
            'removeText'          => __( 'Remove Slide', 'obydullah-magazine-core' ),
        ) );
    }
}
add_action( 'admin_enqueue_scripts', 'obmc_enqueue_about_assets' );

function obmc_save_about_page_meta( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
        return;
    }

    if ( ! isset( $_POST['obmc_about_page_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['obmc_about_page_nonce'] ) ), 'obmc_about_page_meta' ) ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( 'obmc_about_page' !== get_post_type( $post_id ) ) {
        return;
    }

    $text_fields = array( 'obmc_about_kicker', 'obmc_about_title' );
    foreach ( $text_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
        }
    }

    $textarea_fields = array( 'obmc_about_mission', 'obmc_about_team', 'obmc_about_history' );
    foreach ( $textarea_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, sanitize_textarea_field( wp_unslash( $_POST[ $field ] ) ) );
        }
    }

    if ( isset( $_POST['obmc_about_slides'] ) && is_array( $_POST['obmc_about_slides'] ) ) {

        $slides = array();

        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Rows are unslashed as a group; each field is sanitized individually below.
        foreach ( wp_unslash( $_POST['obmc_about_slides'] ) as $slide ) {

            $title    = isset( $slide['title'] ) ? sanitize_text_field( $slide['title'] ) : '';
            $subtitle = isset( $slide['subtitle'] ) ? sanitize_text_field( $slide['subtitle'] ) : '';
            $image    = isset( $slide['image'] ) ? esc_url_raw( $slide['image'] ) : '';

            if ( empty( $title ) && empty( $subtitle ) && empty( $image ) ) {
                continue;
            }

            $slides[] = array(
                'title'    => $title,
                'subtitle' => $subtitle,
                'image'    => $image,
            );
        }

        update_post_meta( $post_id, 'obmc_about_slides', $slides );

    } else {
        update_post_meta( $post_id, 'obmc_about_slides', array() );
    }
}
add_action( 'save_post_obmc_about_page', 'obmc_save_about_page_meta' );

/* ======================================================
   12. Contact Page (Single Instance) + Meta Boxes
====================================================== */

function obmc_register_contact_page_cpt() {
    register_post_type( 'obmc_contact_page', array(
        'labels' => array(
            'name'          => __( 'Contact Page', 'obydullah-magazine-core' ),
            'singular_name' => __( 'Contact Page', 'obydullah-magazine-core' ),
            'add_new_item'  => __( 'Edit Contact Page', 'obydullah-magazine-core' ),
            'edit_item'     => __( 'Edit Contact Page', 'obydullah-magazine-core' ),
        ),
        'public'           => false,
        'show_ui'          => true,
        'show_in_menu'     => 'obmc-magazine-core',
        'menu_icon'        => 'dashicons-email',
        'menu_position'    => 66,
        'supports'         => array( 'title' ),
        'show_in_rest'     => true,
        'capability_type'  => 'post',
        'map_meta_cap'     => true,
    ) );
}
add_action( 'init', 'obmc_register_contact_page_cpt' );

function obmc_limit_contact_page() {
    obmc_limit_single_instance_cpt( 'obmc_contact_page' );
}
add_action( 'load-post-new.php', 'obmc_limit_contact_page' );

function obmc_add_contact_page_meta_boxes() {
    add_meta_box(
        'obmc_contact_page_settings',
        __( 'Contact Page Content', 'obydullah-magazine-core' ),
        'obmc_contact_page_meta_callback',
        'obmc_contact_page',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'obmc_add_contact_page_meta_boxes' );

function obmc_contact_page_meta_callback( $post ) {
    wp_nonce_field( 'obmc_contact_page_meta', 'obmc_contact_page_nonce' );

    $address   = get_post_meta( $post->ID, 'obmc_contact_address', true );
    $phone     = get_post_meta( $post->ID, 'obmc_contact_phone', true );
    $email     = get_post_meta( $post->ID, 'obmc_contact_email', true );
    $map_embed = get_post_meta( $post->ID, 'obmc_contact_map_embed', true );
    $form_shortcode = get_post_meta( $post->ID, 'obmc_contact_form_shortcode', true );
    ?>
<p>
    <label for="obmc_contact_address"><?php esc_html_e( 'Address', 'obydullah-magazine-core' ); ?></label><br>
    <textarea name="obmc_contact_address" id="obmc_contact_address" rows="3"
        class="large-text"><?php echo esc_textarea( $address ); ?></textarea>
    <span class="description"><?php esc_html_e( 'Full office address.', 'obydullah-magazine-core' ); ?></span>
</p>
<p>
    <label for="obmc_contact_phone"><?php esc_html_e( 'Phone Number', 'obydullah-magazine-core' ); ?></label><br>
    <input type="tel" name="obmc_contact_phone" id="obmc_contact_phone" value="<?php echo esc_attr( $phone ); ?>"
        class="widefat">
</p>
<p>
    <label for="obmc_contact_email"><?php esc_html_e( 'Email Address', 'obydullah-magazine-core' ); ?></label><br>
    <input type="email" name="obmc_contact_email" id="obmc_contact_email" value="<?php echo esc_attr( $email ); ?>"
        class="widefat">
</p>
<p>
    <label
        for="obmc_contact_map_embed"><?php esc_html_e( 'Google Maps Embed URL', 'obydullah-magazine-core' ); ?></label><br>
    <input type="url" name="obmc_contact_map_embed" id="obmc_contact_map_embed"
        value="<?php echo esc_url( $map_embed ); ?>" class="widefat"
        placeholder="https://www.google.com/maps/embed?...">
    <span
        class="description"><?php esc_html_e( 'Paste the embed URL from Google Maps.', 'obydullah-magazine-core' ); ?></span>
</p>
<p>
    <label
        for="obmc_contact_form_shortcode"><?php esc_html_e( 'Contact Form Shortcode', 'obydullah-magazine-core' ); ?></label><br>
    <input type="text" name="obmc_contact_form_shortcode" id="obmc_contact_form_shortcode"
        value="<?php echo esc_attr( $form_shortcode ); ?>" class="widefat" placeholder="[contact-form-7 id=...]">
    <span
        class="description"><?php esc_html_e( 'If using a plugin like Contact Form 7, paste the shortcode here.', 'obydullah-magazine-core' ); ?></span>
</p>
<?php
}

function obmc_save_contact_page_meta( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
        return;
    }

    if ( ! isset( $_POST['obmc_contact_page_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['obmc_contact_page_nonce'] ) ), 'obmc_contact_page_meta' ) ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( 'obmc_contact_page' !== get_post_type( $post_id ) ) {
        return;
    }

    if ( isset( $_POST['obmc_contact_address'] ) ) {
        update_post_meta( $post_id, 'obmc_contact_address', sanitize_textarea_field( wp_unslash( $_POST['obmc_contact_address'] ) ) );
    }

    if ( isset( $_POST['obmc_contact_phone'] ) ) {
        update_post_meta( $post_id, 'obmc_contact_phone', sanitize_text_field( wp_unslash( $_POST['obmc_contact_phone'] ) ) );
    }

    if ( isset( $_POST['obmc_contact_email'] ) ) {
        update_post_meta( $post_id, 'obmc_contact_email', sanitize_email( wp_unslash( $_POST['obmc_contact_email'] ) ) );
    }

    if ( isset( $_POST['obmc_contact_map_embed'] ) ) {
        update_post_meta( $post_id, 'obmc_contact_map_embed', esc_url_raw( wp_unslash( $_POST['obmc_contact_map_embed'] ) ) );
    }

    if ( isset( $_POST['obmc_contact_form_shortcode'] ) ) {
        update_post_meta( $post_id, 'obmc_contact_form_shortcode', sanitize_text_field( wp_unslash( $_POST['obmc_contact_form_shortcode'] ) ) );
    }
}
add_action( 'save_post_obmc_contact_page', 'obmc_save_contact_page_meta' );

/* ======================================================
   13. Contact Form 7 Support
====================================================== */

function obmc_get_first_cf7_shortcode() {
    if ( ! defined( 'WPCF7_VERSION' ) ) {
        return '';
    }

    $forms = get_posts( array(
        'post_type'      => 'wpcf7_contact_form',
        'posts_per_page' => 1,
        'post_status'    => 'publish',
    ) );

    if ( empty( $forms ) ) {
        return '';
    }

    $form = $forms[0];
    return '[contact-form-7 id="' . (int) $form->ID . '" title="' . esc_attr( $form->post_title ) . '"]';
}