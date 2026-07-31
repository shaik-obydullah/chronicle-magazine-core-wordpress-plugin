<?php
/**
 * Plugin Name: Chronicle Magazine Core
 * Description: Core functionality for Chronicle Magazine theme
 * Version:     1.0.0
 * Author:      Shaik Obydullah
 * Author URI:  https://obydullah.com
 * License:     GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: chronicle-magazine-core
 * Domain Path: /languages
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
 */

/* ======================================================
   1. Security & Constants
====================================================== */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'CMC_VERSION', '1.0.0' );
define( 'CMC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CMC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once CMC_PLUGIN_DIR . 'includes/cmc-newsletter-list-table.php';

function cmc_add_admin_menu() {
    add_menu_page(
        'Magazine Theme Core',
        'Magazine Theme Core',
        'manage_options',
        'cmc-magazine-core',
        'cmc_magazine_core_page',                          
        'dashicons-admin-post',      
        59
    );
}
add_action( 'admin_menu', 'cmc_add_admin_menu', 9 );

function cmc_enqueue_dashboard_assets( $hook ) {
    if ( 'toplevel_page_cmc-magazine-core' === $hook ) {
        wp_enqueue_style( 'cmc-dashboard-css', CMC_PLUGIN_URL . 'assets/css/admin-dashboard.css', array(), CMC_VERSION );
    }
}
add_action( 'admin_enqueue_scripts', 'cmc_enqueue_dashboard_assets' );

function cmc_magazine_core_page() {
    $sections = array(
        'hero_slides' => array(
            'title' => __( 'Hero Slides', 'chronicle-magazine-core' ),
            'url'   => admin_url( 'edit.php?post_type=cmc_hero_slide' ),
            'icon'  => 'dashicons-slides',
        ),
        'featured_articles' => array(
            'title' => __( 'Featured Articles', 'chronicle-magazine-core' ),
            'url'   => admin_url( 'edit.php?post_type=cmc_featured_article' ),
            'icon'  => 'dashicons-star-filled',
        ),
        'articles' => array(
            'title' => __( 'Articles', 'chronicle-magazine-core' ),
            'url'   => admin_url( 'edit.php?post_type=cmc_article' ),
            'icon'  => 'dashicons-admin-post',
        ),
        'authors' => array(
            'title' => __( 'Authors', 'chronicle-magazine-core' ),
            'url'   => admin_url( 'edit.php?post_type=cmc_author' ),
            'icon'  => 'dashicons-admin-users',
        ),
        'magazine_issues' => array(
            'title' => __( 'Magazine Issues', 'chronicle-magazine-core' ),
            'url'   => admin_url( 'edit.php?post_type=cmc_magazine_issue' ),
            'icon'  => 'dashicons-book',
        ),
        'news_ticker' => array(
            'title' => __( 'News Ticker', 'chronicle-magazine-core' ),
            'url'   => admin_url( 'edit.php?post_type=cmc_news_ticker' ),
            'icon'  => 'dashicons-megaphone',
        ),
        'newsletter' => array(
            'title' => __( 'Newsletter Subscribers', 'chronicle-magazine-core' ),
            'url'   => admin_url( 'admin.php?page=cmc-newsletter' ),
            'icon'  => 'dashicons-email-alt',
        ),
        'advertisements' => array(
            'title' => __( 'Advertisement Slots', 'chronicle-magazine-core' ),
            'url'   => admin_url( 'edit.php?post_type=cmc_advertisement' ),
            'icon'  => 'dashicons-megaphone',
        ),
        'footer_settings' => array(
            'title' => __( 'Footer Settings', 'chronicle-magazine-core' ),
            'url'   => admin_url( 'edit.php?post_type=cmc_footer' ),
            'icon'  => 'dashicons-layout',
        ),
        'about_page' => array(
            'title' => __( 'About Page', 'chronicle-magazine-core' ),
            'url'   => admin_url( 'edit.php?post_type=cmc_about_page' ),
            'icon'  => 'dashicons-info',
        ),
        'contact_page' => array(
            'title' => __( 'Contact Page', 'chronicle-magazine-core' ),
            'url'   => admin_url( 'edit.php?post_type=cmc_contact_page' ),
            'icon'  => 'dashicons-email',
        ),
    );
    ?>
<div class="wrap cmc-dashboard">
    <h1><?php esc_html_e( 'Magazine Theme Core', 'chronicle-magazine-core' ); ?></h1>
    <p class="cmc-dashboard-description">
        <?php esc_html_e( 'Welcome to the Chronicle Magazine Core plugin. Use the links below to manage your magazine content.', 'chronicle-magazine-core' ); ?>
    </p>

    <div class="cmc-dashboard-grid">
        <?php foreach ( $sections as $section ) : ?>
        <div class="cmc-dashboard-card">
            <div class="dashicons <?php echo esc_attr( $section['icon'] ); ?>"></div>
            <h2><?php echo esc_html( $section['title'] ); ?></h2>
            <a href="<?php echo esc_url( $section['url'] ); ?>"
                class="button button-primary"><?php esc_html_e( 'Manage', 'chronicle-magazine-core' ); ?></a>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php
}

/* ======================================================
   2. Hero Slider CPT + Meta Boxes
====================================================== */

function cmc_register_hero_slide_cpt() {
    register_post_type( 'cmc_hero_slide', array(
        'labels' => array(
            'name'          => __( 'Hero Slides', 'chronicle-magazine-core' ),
            'singular_name' => __( 'Hero Slide', 'chronicle-magazine-core' ),
            'add_new_item'  => __( 'Add New Hero Slide', 'chronicle-magazine-core' ),
            'edit_item'     => __( 'Edit Hero Slide', 'chronicle-magazine-core' ),
        ),
        'public'        => true,
        'show_in_menu'  => 'cmc-magazine-core',
        'menu_icon'     => 'dashicons-slides',
        'supports'      => array( 'title', 'thumbnail', 'page-attributes' ),
        'show_in_rest'  => true,
        'has_archive'   => false,
        'rewrite'       => array( 'slug' => 'cmc-hero-slide' ),
    ) );
}
add_action( 'init', 'cmc_register_hero_slide_cpt' );

function cmc_add_hero_slide_meta_box() {
    add_meta_box(
        'cmc_hero_slide_meta',
        __( 'Hero Slide Settings', 'chronicle-magazine-core' ),
        'cmc_render_hero_slide_meta_box',
        'cmc_hero_slide',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'cmc_add_hero_slide_meta_box' );

function cmc_render_hero_slide_meta_box( $post ) {
    $subtitle = get_post_meta( $post->ID, 'cmc_subtitle', true );
    $category = get_post_meta( $post->ID, 'cmc_category', true );
    wp_nonce_field( 'cmc_save_hero_slide_meta', 'cmc_hero_slide_nonce' );
    ?>
<p>
    <label
        for="cmc_subtitle"><strong><?php esc_html_e( 'Subtitle', 'chronicle-magazine-core' ); ?></strong></label><br>
    <input type="text" id="cmc_subtitle" name="cmc_subtitle" value="<?php echo esc_attr( $subtitle ); ?>"
        class="widefat">
</p>
<p>
    <label
        for="cmc_category"><strong><?php esc_html_e( 'Category Label', 'chronicle-magazine-core' ); ?></strong></label><br>
    <input type="text" id="cmc_category" name="cmc_category" value="<?php echo esc_attr( $category ); ?>"
        class="widefat" placeholder="<?php esc_attr_e( 'e.g., Breaking News', 'chronicle-magazine-core' ); ?>">
</p>
<?php
}

function cmc_save_hero_slide_meta( $post_id ) {
    if ( ! isset( $_POST['cmc_hero_slide_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cmc_hero_slide_nonce'] ) ), 'cmc_save_hero_slide_meta' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( 'cmc_hero_slide' !== get_post_type( $post_id ) ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['cmc_subtitle'] ) ) {
        update_post_meta( $post_id, 'cmc_subtitle', sanitize_text_field( wp_unslash( $_POST['cmc_subtitle'] ) ) );
    }

    if ( isset( $_POST['cmc_category'] ) ) {
        update_post_meta( $post_id, 'cmc_category', sanitize_text_field( wp_unslash( $_POST['cmc_category'] ) ) );
    }
}
add_action( 'save_post_cmc_hero_slide', 'cmc_save_hero_slide_meta' );

/* ======================================================
   3. Featured Articles CPT + Meta Boxes (Single Instance)
====================================================== */

function cmc_register_featured_article_cpt() {
    register_post_type( 'cmc_featured_article', array(
        'labels' => array(
            'name'          => __( 'Featured Articles', 'chronicle-magazine-core' ),
            'singular_name' => __( 'Featured Article', 'chronicle-magazine-core' ),
            'add_new_item'  => __( 'Edit Featured Article', 'chronicle-magazine-core' ),
            'edit_item'     => __( 'Edit Featured Article', 'chronicle-magazine-core' ),
            'new_item'      => __( 'Edit Featured Article', 'chronicle-magazine-core' ),
        ),
        'public'          => true,
        'show_ui'         => true,
        'show_in_menu'    => 'cmc-magazine-core',
        'menu_icon'       => 'dashicons-star-filled',
        'supports'        => array( 'title', 'editor', 'thumbnail' ),
        'show_in_rest'    => true,
        'capability_type' => 'post',
        'map_meta_cap'    => true,
        'has_archive'     => true,
        'rewrite'         => array( 'slug' => 'cmc_featured_article' ),
    ) );
}
add_action( 'init', 'cmc_register_featured_article_cpt' );

function cmc_limit_featured_article() {
    global $pagenow;
    if ( $pagenow === 'post-new.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] === 'cmc_featured_article' ) {
        $existing = get_posts( array(
            'post_type'      => 'cmc_featured_article',
            'posts_per_page' => 1,
            'post_status'    => 'publish',
            'fields'         => 'ids',
        ) );
        if ( ! empty( $existing ) ) {
            $post_id = $existing[0];
            if ( current_user_can( 'edit_post', $post_id ) ) {
                wp_redirect( admin_url( 'post.php?post=' . $post_id . '&action=edit' ) );
                exit;
            }
        }
    }
}
add_action( 'admin_init', 'cmc_limit_featured_article' );

function cmc_add_featured_article_meta_box() {
    add_meta_box(
        'cmc_featured_article_meta',
        __( 'Featured Article Settings', 'chronicle-magazine-core' ),
        'cmc_render_featured_article_meta_box',
        'cmc_featured_article',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'cmc_add_featured_article_meta_box' );

function cmc_render_featured_article_meta_box( $post ) {
    $excerpt = get_post_meta( $post->ID, 'cmc_excerpt', true );
    $author  = get_post_meta( $post->ID, 'cmc_author_name', true );
    $date    = get_post_meta( $post->ID, 'cmc_publish_date', true );
    wp_nonce_field( 'cmc_save_featured_article_meta', 'cmc_featured_article_nonce' );
    ?>
<p>
    <label
        for="cmc_excerpt"><strong><?php esc_html_e( 'Excerpt', 'chronicle-magazine-core' ); ?></strong></label><br>
    <textarea id="cmc_excerpt" name="cmc_excerpt" rows="4"
        class="large-text"><?php echo esc_textarea( $excerpt ); ?></textarea>
</p>
<p>
    <label
        for="cmc_author_name"><strong><?php esc_html_e( 'Author Name', 'chronicle-magazine-core' ); ?></strong></label><br>
    <input type="text" id="cmc_author_name" name="cmc_author_name" value="<?php echo esc_attr( $author ); ?>"
        class="widefat">
</p>
<p>
    <label
        for="cmc_publish_date"><strong><?php esc_html_e( 'Publish Date', 'chronicle-magazine-core' ); ?></strong></label><br>
    <input type="text" id="cmc_publish_date" name="cmc_publish_date" value="<?php echo esc_attr( $date ); ?>"
        class="widefat" placeholder="<?php esc_attr_e( 'e.g., January 15, 2026', 'chronicle-magazine-core' ); ?>">
</p>
<?php
}

function cmc_save_featured_article_meta( $post_id ) {
    if ( ! isset( $_POST['cmc_featured_article_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cmc_featured_article_nonce'] ) ), 'cmc_save_featured_article_meta' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( 'cmc_featured_article' !== get_post_type( $post_id ) ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['cmc_excerpt'] ) ) {
        update_post_meta( $post_id, 'cmc_excerpt', sanitize_textarea_field( wp_unslash( $_POST['cmc_excerpt'] ) ) );
    }

    if ( isset( $_POST['cmc_author_name'] ) ) {
        update_post_meta( $post_id, 'cmc_author_name', sanitize_text_field( wp_unslash( $_POST['cmc_author_name'] ) ) );
    }

    if ( isset( $_POST['cmc_publish_date'] ) ) {
        update_post_meta( $post_id, 'cmc_publish_date', sanitize_text_field( wp_unslash( $_POST['cmc_publish_date'] ) ) );
    }
}
add_action( 'save_post_cmc_featured_article', 'cmc_save_featured_article_meta' );

/* ======================================================
   4. Articles CPT + Category Taxonomy + Meta Boxes
====================================================== */

function cmc_register_article() {
    register_post_type( 'cmc_article', array(
          'labels'      => array(
            'name'          => __( 'Articles', 'chronicle-magazine-core' ),
            'singular_name' => __( 'Article', 'chronicle-magazine-core' ),
            'add_new_item'  => __( 'Add New Article', 'chronicle-magazine-core' ),
            'edit_item'     => __( 'Edit Article', 'chronicle-magazine-core' ),
        ),
        'public'      => true,
        'show_in_menu'        => 'cmc-magazine-core',
        'menu_icon'   => 'dashicons-admin-post',
        'supports'    => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'=> true,
        'has_archive' => true,
        'rewrite'     => array( 'slug' => 'articles' ),
    ) );
}
add_action( 'init', 'cmc_register_article' );

function cmc_register_article_category() {
    register_taxonomy( 'cmc_article_category', 'cmc_article', array(
        'labels' => array(
            'name'              => __( 'Categories', 'chronicle-magazine-core' ),
            'singular_name'     => __( 'Category', 'chronicle-magazine-core' ),
            'add_new_item'      => __( 'Add New Category', 'chronicle-magazine-core' ),
            'new_item_name'     => __( 'New Category Name', 'chronicle-magazine-core' ),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
    ) );
}
add_action( 'init', 'cmc_register_article_category' );

function cmc_add_article_subtitle_meta_box() {
    add_meta_box( 'cmc_article_subtitle', __( 'Subtitle', 'chronicle-magazine-core' ), 'cmc_article_subtitle_callback', 'cmc_article', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'cmc_add_article_subtitle_meta_box' );

function cmc_article_subtitle_callback( $post ) {
    wp_nonce_field( 'cmc_article_meta', 'cmc_article_nonce' );
    $subtitle = get_post_meta( $post->ID, 'cmc_article_subtitle', true );
    echo '<input type="text" name="cmc_article_subtitle" value="' . esc_attr( $subtitle ) . '" class="widefat" placeholder="' . esc_attr__( 'Article subtitle', 'chronicle-magazine-core' ) . '">';
}

function cmc_add_article_author_meta_box() {
    add_meta_box( 'cmc_article_author', __( 'Author', 'chronicle-magazine-core' ), 'cmc_article_author_callback', 'cmc_article', 'side', 'default' );
}
add_action( 'add_meta_boxes', 'cmc_add_article_author_meta_box' );

function cmc_article_author_callback( $post ) {
    wp_nonce_field( 'cmc_article_meta', 'cmc_article_nonce' );
    $author = get_post_meta( $post->ID, 'cmc_article_author', true );
    echo '<input type="text" name="cmc_article_author" value="' . esc_attr( $author ) . '" class="widefat" placeholder="' . esc_attr__( 'Author name', 'chronicle-magazine-core' ) . '">';
}

function cmc_add_article_read_time_meta_box() {
    add_meta_box( 'cmc_article_read_time', __( 'Read Time', 'chronicle-magazine-core' ), 'cmc_article_read_time_callback', 'cmc_article', 'side', 'default' );
}
add_action( 'add_meta_boxes', 'cmc_add_article_read_time_meta_box' );

function cmc_article_read_time_callback( $post ) {
    wp_nonce_field( 'cmc_article_meta', 'cmc_article_nonce' );
    $read_time = get_post_meta( $post->ID, 'cmc_article_read_time', true );
    echo '<input type="text" name="cmc_article_read_time" value="' . esc_attr( $read_time ) . '" class="widefat" placeholder="' . esc_attr__( 'e.g., 5 min read', 'chronicle-magazine-core' ) . '">';
}

function cmc_save_article_meta( $post_id ) {
    if ( ! isset( $_POST['cmc_article_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cmc_article_nonce'] ) ), 'cmc_article_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( 'cmc_article' !== get_post_type( $post_id ) ) {
        return;
    }
    if ( isset( $_POST['cmc_article_subtitle'] ) ) {
        update_post_meta( $post_id, 'cmc_article_subtitle', sanitize_text_field( wp_unslash( $_POST['cmc_article_subtitle'] ) ) );
    }
    if ( isset( $_POST['cmc_article_author'] ) ) {
        update_post_meta( $post_id, 'cmc_article_author', sanitize_text_field( wp_unslash( $_POST['cmc_article_author'] ) ) );
    }
    if ( isset( $_POST['cmc_article_read_time'] ) ) {
        update_post_meta( $post_id, 'cmc_article_read_time', sanitize_text_field( wp_unslash( $_POST['cmc_article_read_time'] ) ) );
    }
}
add_action( 'save_post_cmc_article', 'cmc_save_article_meta' );

/* ======================================================
   5. Authors CPT + Meta Boxes
====================================================== */

function cmc_register_author_cpt() {
    register_post_type( 'cmc_author', array(
        'labels' => array(
            'name'          => __( 'Authors', 'chronicle-magazine-core' ),
            'singular_name' => __( 'Author', 'chronicle-magazine-core' ),
            'add_new_item'  => __( 'Add New Author', 'chronicle-magazine-core' ),
            'edit_item'     => __( 'Edit Author', 'chronicle-magazine-core' ),
        ),
        'public'          => false,
        'show_ui'         => true,
        'show_in_menu'    => 'cmc-magazine-core',
        'menu_icon'       => 'dashicons-admin-users',
        'supports'        => array( 'title', 'thumbnail' ),
        'show_in_rest'    => true,
        'capability_type' => 'post',
        'map_meta_cap'    => true,
    ) );
}
add_action( 'init', 'cmc_register_author_cpt' );

function cmc_add_author_meta_boxes() {
    add_meta_box(
        'cmc_author_details',
        __( 'Author Details', 'chronicle-magazine-core' ),
        'cmc_author_details_callback',
        'cmc_author',
        'normal',
        'high'
    );
    add_meta_box(
        'cmc_author_social',
        __( 'Social Media Links', 'chronicle-magazine-core' ),
        'cmc_author_social_callback',
        'cmc_author',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'cmc_add_author_meta_boxes' );

function cmc_author_details_callback( $post ) {
    wp_nonce_field( 'cmc_author_meta', 'cmc_author_nonce' );
    $bio      = get_post_meta( $post->ID, 'cmc_author_bio', true );
    $position = get_post_meta( $post->ID, 'cmc_author_position', true );
    $email    = get_post_meta( $post->ID, 'cmc_author_email', true );
    ?>
<p>
    <label
        for="cmc_author_bio"><strong><?php esc_html_e( 'Bio', 'chronicle-magazine-core' ); ?></strong></label><br>
    <textarea id="cmc_author_bio" name="cmc_author_bio" rows="5"
        class="large-text"><?php echo esc_textarea( $bio ); ?></textarea>
</p>
<p>
    <label
        for="cmc_author_position"><strong><?php esc_html_e( 'Position / Title', 'chronicle-magazine-core' ); ?></strong></label><br>
    <input type="text" id="cmc_author_position" name="cmc_author_position" value="<?php echo esc_attr( $position ); ?>"
        class="widefat" placeholder="<?php esc_attr_e( 'e.g., Senior Editor', 'chronicle-magazine-core' ); ?>">
</p>
<p>
    <label
        for="cmc_author_email"><strong><?php esc_html_e( 'Email', 'chronicle-magazine-core' ); ?></strong></label><br>
    <input type="email" id="cmc_author_email" name="cmc_author_email" value="<?php echo esc_attr( $email ); ?>"
        class="widefat">
</p>
<?php
}

function cmc_author_social_callback( $post ) {
    wp_nonce_field( 'cmc_author_meta', 'cmc_author_nonce' );
    $twitter  = get_post_meta( $post->ID, 'cmc_author_twitter', true );
    $linkedin = get_post_meta( $post->ID, 'cmc_author_linkedin', true );
    $facebook = get_post_meta( $post->ID, 'cmc_author_facebook', true );
    ?>
<p>
    <label
        for="cmc_author_twitter"><strong><?php esc_html_e( 'X (Twitter) URL', 'chronicle-magazine-core' ); ?></strong></label><br>
    <input type="url" id="cmc_author_twitter" name="cmc_author_twitter" value="<?php echo esc_url( $twitter ); ?>"
        class="widefat">
</p>
<p>
    <label
        for="cmc_author_linkedin"><strong><?php esc_html_e( 'LinkedIn URL', 'chronicle-magazine-core' ); ?></strong></label><br>
    <input type="url" id="cmc_author_linkedin" name="cmc_author_linkedin" value="<?php echo esc_url( $linkedin ); ?>"
        class="widefat">
</p>
<p>
    <label
        for="cmc_author_facebook"><strong><?php esc_html_e( 'Facebook URL', 'chronicle-magazine-core' ); ?></strong></label><br>
    <input type="url" id="cmc_author_facebook" name="cmc_author_facebook" value="<?php echo esc_url( $facebook ); ?>"
        class="widefat">
</p>
<?php
}

function cmc_save_author_meta( $post_id ) {
    if ( ! isset( $_POST['cmc_author_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cmc_author_nonce'] ) ), 'cmc_author_meta' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( 'cmc_author' !== get_post_type( $post_id ) ) {
        return;
    }

    $text_fields = array( 'cmc_author_bio', 'cmc_author_position', 'cmc_author_email' );
    foreach ( $text_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, sanitize_textarea_field( wp_unslash( $_POST[ $field ] ) ) );
        }
    }

    $url_fields = array( 'cmc_author_twitter', 'cmc_author_linkedin', 'cmc_author_facebook' );
    foreach ( $url_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, esc_url_raw( wp_unslash( $_POST[ $field ] ) ) );
        }
    }
}
add_action( 'save_post_cmc_author', 'cmc_save_author_meta' );

/* ======================================================
   6. Magazine Issues CPT + Meta Boxes
====================================================== */

function cmc_register_magazine_issue_cpt() {
    register_post_type( 'cmc_magazine_issue', array(
        'labels' => array(
            'name'          => __( 'Magazine Issues', 'chronicle-magazine-core' ),
            'singular_name' => __( 'Magazine Issue', 'chronicle-magazine-core' ),
            'add_new_item'  => __( 'Add New Magazine Issue', 'chronicle-magazine-core' ),
            'edit_item'     => __( 'Edit Magazine Issue', 'chronicle-magazine-core' ),
        ),
        'public'          => false,
        'show_ui'         => true,
        'show_in_menu'    => 'cmc-magazine-core',
        'menu_icon'       => 'dashicons-book',
        'supports'        => array( 'title', 'thumbnail' ),
        'show_in_rest'    => true,
        'capability_type' => 'post',
        'map_meta_cap'    => true,
    ) );
}
add_action( 'init', 'cmc_register_magazine_issue_cpt' );

function cmc_add_magazine_issue_meta_boxes() {
    add_meta_box(
        'cmc_magazine_issue_details',
        __( 'Issue Details', 'chronicle-magazine-core' ),
        'cmc_magazine_issue_details_callback',
        'cmc_magazine_issue',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'cmc_add_magazine_issue_meta_boxes' );

function cmc_magazine_issue_details_callback( $post ) {
    wp_nonce_field( 'cmc_magazine_issue_meta', 'cmc_magazine_issue_nonce' );
    $issue_number = get_post_meta( $post->ID, 'cmc_issue_number', true );
    $month        = get_post_meta( $post->ID, 'cmc_issue_month', true );
    $year         = get_post_meta( $post->ID, 'cmc_issue_year', true );
    $pdf_url      = get_post_meta( $post->ID, 'cmc_issue_pdf_url', true );
    ?>
<p>
    <label
        for="cmc_issue_number"><strong><?php esc_html_e( 'Issue Number', 'chronicle-magazine-core' ); ?></strong></label><br>
    <input type="text" id="cmc_issue_number" name="cmc_issue_number" value="<?php echo esc_attr( $issue_number ); ?>"
        class="widefat" placeholder="<?php esc_attr_e( 'e.g., Vol. 12, Issue 3', 'chronicle-magazine-core' ); ?>">
</p>
<p>
    <label
        for="cmc_issue_month"><strong><?php esc_html_e( 'Month', 'chronicle-magazine-core' ); ?></strong></label><br>
    <select id="cmc_issue_month" name="cmc_issue_month" class="widefat">
        <option value=""><?php esc_html_e( 'Select Month', 'chronicle-magazine-core' ); ?></option>
        <?php
        $months = array(
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        );
        foreach ( $months as $m ) :
        ?>
        <option value="<?php echo esc_attr( $m ); ?>" <?php selected( $month, $m ); ?>><?php echo esc_html( $m ); ?></option>
        <?php endforeach; ?>
    </select>
</p>
<p>
    <label
        for="cmc_issue_year"><strong><?php esc_html_e( 'Year', 'chronicle-magazine-core' ); ?></strong></label><br>
    <input type="number" id="cmc_issue_year" name="cmc_issue_year" value="<?php echo esc_attr( $year ); ?>"
        class="widefat" placeholder="<?php esc_attr_e( 'e.g., 2026', 'chronicle-magazine-core' ); ?>" min="2000" max="2100">
</p>
<p>
    <label
        for="cmc_issue_pdf_url"><strong><?php esc_html_e( 'PDF Download URL', 'chronicle-magazine-core' ); ?></strong></label><br>
    <input type="url" id="cmc_issue_pdf_url" name="cmc_issue_pdf_url" value="<?php echo esc_url( $pdf_url ); ?>"
        class="widefat" placeholder="https://...">
</p>
<?php
}

function cmc_save_magazine_issue_meta( $post_id ) {
    if ( ! isset( $_POST['cmc_magazine_issue_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cmc_magazine_issue_nonce'] ) ), 'cmc_magazine_issue_meta' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( 'cmc_magazine_issue' !== get_post_type( $post_id ) ) {
        return;
    }

    $text_fields = array( 'cmc_issue_number', 'cmc_issue_month', 'cmc_issue_year' );
    foreach ( $text_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
        }
    }

    if ( isset( $_POST['cmc_issue_pdf_url'] ) ) {
        update_post_meta( $post_id, 'cmc_issue_pdf_url', esc_url_raw( wp_unslash( $_POST['cmc_issue_pdf_url'] ) ) );
    }
}
add_action( 'save_post_cmc_magazine_issue', 'cmc_save_magazine_issue_meta' );

/* ======================================================
   7. News Ticker CPT + Meta Boxes
====================================================== */

function cmc_register_news_ticker_cpt() {
    register_post_type( 'cmc_news_ticker', array(
        'labels' => array(
            'name'          => __( 'News Ticker', 'chronicle-magazine-core' ),
            'singular_name' => __( 'News Ticker Item', 'chronicle-magazine-core' ),
            'add_new_item'  => __( 'Add New Ticker Item', 'chronicle-magazine-core' ),
            'edit_item'     => __( 'Edit Ticker Item', 'chronicle-magazine-core' ),
        ),
        'public'        => true,
        'show_in_menu'  => 'cmc-magazine-core',
        'menu_icon'     => 'dashicons-megaphone',
        'supports'      => array( 'title' ),
        'show_in_rest'  => true,
        'has_archive'   => false,
    ) );
}
add_action( 'init', 'cmc_register_news_ticker_cpt' );

function cmc_add_news_ticker_meta_box() {
    add_meta_box(
        'cmc_news_ticker_meta',
        __( 'Ticker Settings', 'chronicle-magazine-core' ),
        'cmc_render_news_ticker_meta_box',
        'cmc_news_ticker',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'cmc_add_news_ticker_meta_box' );

function cmc_render_news_ticker_meta_box( $post ) {
    $link    = get_post_meta( $post->ID, 'cmc_ticker_link', true );
    $is_breaking = get_post_meta( $post->ID, 'cmc_ticker_breaking', true );
    wp_nonce_field( 'cmc_save_news_ticker_meta', 'cmc_news_ticker_nonce' );
    ?>
<p>
    <label
        for="cmc_ticker_link"><strong><?php esc_html_e( 'Link URL', 'chronicle-magazine-core' ); ?></strong></label><br>
    <input type="url" id="cmc_ticker_link" name="cmc_ticker_link" value="<?php echo esc_url( $link ); ?>"
        class="widefat" placeholder="https://...">
</p>
<p>
    <label>
        <input type="checkbox" id="cmc_ticker_breaking" name="cmc_ticker_breaking" value="1" <?php checked( $is_breaking, '1' ); ?>>
        <strong><?php esc_html_e( 'Mark as Breaking News', 'chronicle-magazine-core' ); ?></strong>
    </label>
</p>
<?php
}

function cmc_save_news_ticker_meta( $post_id ) {
    if ( ! isset( $_POST['cmc_news_ticker_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cmc_news_ticker_nonce'] ) ), 'cmc_save_news_ticker_meta' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( 'cmc_news_ticker' !== get_post_type( $post_id ) ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['cmc_ticker_link'] ) ) {
        update_post_meta( $post_id, 'cmc_ticker_link', esc_url_raw( wp_unslash( $_POST['cmc_ticker_link'] ) ) );
    }

    $breaking = isset( $_POST['cmc_ticker_breaking'] ) ? '1' : '0';
    update_post_meta( $post_id, 'cmc_ticker_breaking', $breaking );
}
add_action( 'save_post_cmc_news_ticker', 'cmc_save_news_ticker_meta' );

/* ===================================================================
   8. Newsletter Subscriptions (Custom DB table, AJAX handler)
======================================================================= */

function cmc_create_newsletter_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'cmc_newsletter_subscribers';
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
register_activation_hook( __FILE__, 'cmc_create_newsletter_table' );

function cmc_drop_newsletter_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'cmc_newsletter_subscribers';
    $wpdb->query( "DROP TABLE IF EXISTS $table_name" );
}
register_uninstall_hook( __FILE__, 'cmc_drop_newsletter_table' );

function cmc_handle_newsletter_submission() {
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'cmc_newsletter_nonce' ) ) {
        wp_send_json_error( array( 'error' => __( 'Security check failed.', 'chronicle-magazine-core' ) ), 403 );
    }

    $email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
    $name  = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );

    $errors = array();
    if ( ! is_email( $email ) ) {
        $errors[] = __( 'Valid email is required.', 'chronicle-magazine-core' );
    }

    if ( ! empty( $errors ) ) {
        wp_send_json_error( array( 'errors' => $errors ) );
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'cmc_newsletter_subscribers';

    $existing = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $table_name WHERE email = %s", $email ) );
    if ( $existing ) {
        wp_send_json_error( array( 'error' => __( 'This email is already subscribed.', 'chronicle-magazine-core' ) ) );
    }

    $result = $wpdb->insert(
        $table_name,
        array(
            'email'  => $email,
            'name'   => $name,
            'status' => 'active',
        ),
        array( '%s', '%s', '%s' )
    );

    if ( false === $result ) {
        wp_send_json_error( array( 'error' => __( 'Database error. Please try again.', 'chronicle-magazine-core' ) ) );
    }

    wp_send_json_success( array( 'message' => __( 'Thank you for subscribing to our newsletter!', 'chronicle-magazine-core' ) ) );
}
add_action( 'wp_ajax_cmc_newsletter', 'cmc_handle_newsletter_submission' );
add_action( 'wp_ajax_nopriv_cmc_newsletter', 'cmc_handle_newsletter_submission' );

function cmc_newsletter_admin_menu() {
    add_submenu_page(
        'cmc-magazine-core',
        __( 'Newsletter Subscribers', 'chronicle-magazine-core' ),
        __( 'Newsletter Subscribers', 'chronicle-magazine-core' ),
        'manage_options',
        'cmc-newsletter',
        'cmc_render_newsletter_page'
    );
}
add_action( 'admin_menu', 'cmc_newsletter_admin_menu' );

function cmc_render_newsletter_page() {
    if ( isset( $_POST['action'] ) && 'delete' === $_POST['action'] && isset( $_POST['subscriber_ids'] ) ) {
        check_admin_referer( 'bulk-newsletter' );
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Unauthorized.', 'chronicle-magazine-core' ) );
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'cmc_newsletter_subscribers';
        $ids = array_map( 'intval', $_POST['subscriber_ids'] );
        if ( ! empty( $ids ) ) {
            $placeholders = implode( ',', array_fill( 0, count( $ids ), '%d' ) );
            $wpdb->query( $wpdb->prepare( "DELETE FROM $table_name WHERE id IN ($placeholders)", $ids ) );
            echo '<div class="notice notice-success"><p>' . esc_html__( 'Subscribers deleted.', 'chronicle-magazine-core' ) . '</p></div>';
        }
    }

    if ( ! class_exists( 'CMC_Newsletter_List_Table' ) ) {
        require_once CMC_PLUGIN_DIR . 'includes/cmc-newsletter-list-table.php';
    }

    $newsletter_table = new CMC_Newsletter_List_Table();
    $newsletter_table->prepare_items();
    ?>
<div class="wrap">
    <h1><?php esc_html_e( 'Newsletter Subscribers', 'chronicle-magazine-core' ); ?></h1>
    <form method="post">
        <?php $newsletter_table->display(); ?>
        <?php wp_nonce_field( 'bulk-newsletter' ); ?>
    </form>
</div>
<?php
}

/* ======================================================
   9. Advertisement Slots (Single Instance) + Meta Boxes
====================================================== */

function cmc_register_advertisement_cpt() {
    register_post_type( 'cmc_advertisement', array(
        'labels' => array(
            'name'          => __( 'Advertisement Slots', 'chronicle-magazine-core' ),
            'singular_name' => __( 'Advertisement Slot', 'chronicle-magazine-core' ),
            'add_new_item'  => __( 'Edit Advertisement Slot', 'chronicle-magazine-core' ),
            'edit_item'     => __( 'Edit Advertisement Slot', 'chronicle-magazine-core' ),
        ),
        'public'           => false,
        'show_ui'          => true,
        'show_in_menu'     => 'cmc-magazine-core',
        'menu_icon'        => 'dashicons-megaphone',
        'supports'         => array( 'title' ),
        'show_in_rest'     => true,
        'capability_type'  => 'post',
        'map_meta_cap'     => true,
    ) );
}
add_action( 'init', 'cmc_register_advertisement_cpt' );

function cmc_add_advertisement_meta_boxes() {
    add_meta_box(
        'cmc_advertisement_slots',
        __( 'Advertisement Slots', 'chronicle-magazine-core' ),
        'cmc_advertisement_slots_callback',
        'cmc_advertisement',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'cmc_add_advertisement_meta_boxes' );

function cmc_advertisement_slots_callback( $post ) {
    wp_nonce_field( 'cmc_advertisement_meta', 'cmc_advertisement_nonce' );
    
    $slots = get_post_meta( $post->ID, 'cmc_ad_slots', true );
    if ( ! is_array( $slots ) ) {
        $slots = array(
            array( 'name' => 'Header Banner', 'code' => '', 'active' => '1' ),
            array( 'name' => 'Sidebar', 'code' => '', 'active' => '1' ),
            array( 'name' => 'In-Article', 'code' => '', 'active' => '1' ),
            array( 'name' => 'Footer', 'code' => '', 'active' => '1' ),
        );
    }
    ?>
<div id="cmc-ad-slots-repeater" class="cmc-repeater">
    <input type="hidden" id="cmc-ad-slot-count" name="cmc_ad_slot_count" value="<?php echo esc_attr( count( $slots ) ); ?>">
    <?php foreach ( $slots as $index => $slot ) : ?>
    <div class="cmc-ad-slot-row cmc-repeater-row" data-index="<?php echo esc_attr( $index ); ?>">
        <p>
            <label><strong><?php esc_html_e( 'Slot Name', 'chronicle-magazine-core' ); ?></strong></label><br>
            <input type="text" name="cmc_ad_slots[<?php echo esc_attr( $index ); ?>][name]"
                value="<?php echo esc_attr( $slot['name'] ); ?>" class="widefat">
        </p>
        <p>
            <label><strong><?php esc_html_e( 'Ad Code / HTML', 'chronicle-magazine-core' ); ?></strong></label><br>
            <textarea name="cmc_ad_slots[<?php echo esc_attr( $index ); ?>][code]" rows="4"
                class="large-text"><?php echo esc_textarea( $slot['code'] ); ?></textarea>
        </p>
        <p>
            <label>
                <input type="checkbox" name="cmc_ad_slots[<?php echo esc_attr( $index ); ?>][active]" value="1" <?php checked( $slot['active'], '1' ); ?>>
                <?php esc_html_e( 'Active', 'chronicle-magazine-core' ); ?>
            </label>
        </p>
        <button type="button" class="button cmc-remove-row"><?php esc_html_e( 'Remove', 'chronicle-magazine-core' ); ?></button>
        <hr>
    </div>
    <?php endforeach; ?>
</div>
<button type="button" id="cmc-add-ad-slot"
    class="button"><?php esc_html_e( 'Add New Slot', 'chronicle-magazine-core' ); ?></button>
<?php
}

function cmc_enqueue_advertisement_assets( $hook ) {
    global $post_type;
    if ( 'cmc_advertisement' === $post_type && in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
        wp_enqueue_style( 'cmc-advertisement-css', CMC_PLUGIN_URL . 'assets/css/advertisement-admin.css', array(), CMC_VERSION );
        wp_enqueue_script( 'cmc-advertisement-js', CMC_PLUGIN_URL . 'assets/js/advertisement-admin.js', array( 'jquery' ), CMC_VERSION, true );
        wp_localize_script( 'cmc-advertisement-js', 'cmcAdL10n', array(
            'removeText' => __( 'Remove', 'chronicle-magazine-core' ),
        ) );
    }
}
add_action( 'admin_enqueue_scripts', 'cmc_enqueue_advertisement_assets' );

function cmc_save_advertisement_meta( $post_id ) {
    if ( ! isset( $_POST['cmc_advertisement_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cmc_advertisement_nonce'] ) ), 'cmc_advertisement_meta' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( 'cmc_advertisement' !== get_post_type( $post_id ) ) {
        return;
    }

    if ( isset( $_POST['cmc_ad_slots'] ) && is_array( $_POST['cmc_ad_slots'] ) ) {
        $slots = array();
        foreach ( $_POST['cmc_ad_slots'] as $slot ) {
            $name   = isset( $slot['name'] ) ? sanitize_text_field( wp_unslash( $slot['name'] ) ) : '';
            $code   = isset( $slot['code'] ) ? wp_kses_post( wp_unslash( $slot['code'] ) ) : '';
            $active = isset( $slot['active'] ) ? '1' : '0';
            if ( ! empty( $name ) ) {
                $slots[] = array( 'name' => $name, 'code' => $code, 'active' => $active );
            }
        }
        update_post_meta( $post_id, 'cmc_ad_slots', $slots );
    } else {
        update_post_meta( $post_id, 'cmc_ad_slots', array() );
    }
}
add_action( 'save_post_cmc_advertisement', 'cmc_save_advertisement_meta' );

/* ======================================================
   10. Footer Settings (Single Instance) + Meta Boxes
====================================================== */

function cmc_register_footer_settings() {
    register_post_type( 'cmc_footer', array(
        'labels' => array(
            'name'          => __( 'Footer Settings', 'chronicle-magazine-core' ),
            'singular_name' => __( 'Footer Settings', 'chronicle-magazine-core' ),
            'add_new_item'  => __( 'Edit Footer Settings', 'chronicle-magazine-core' ),
            'edit_item'     => __( 'Edit Footer Settings', 'chronicle-magazine-core' ),
        ),
        'public'           => false,
        'show_ui'          => true,
        'show_in_menu'     => 'cmc-magazine-core',
        'menu_icon'        => 'dashicons-layout',
        'supports'         => array( 'title' ),
        'show_in_rest'     => true,
        'capability_type'  => 'post',
        'map_meta_cap'     => true,
    ) );
}
add_action( 'init', 'cmc_register_footer_settings' );

function cmc_limit_footer_settings() {
    global $pagenow;
    if ( $pagenow === 'post-new.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] === 'cmc_footer' ) {
        $existing = get_posts( array(
            'post_type'      => 'cmc_footer',
            'posts_per_page' => 1,
            'post_status'    => 'publish',
            'fields'         => 'ids',
        ) );
        if ( ! empty( $existing ) ) {
            $post_id = $existing[0];
            if ( current_user_can( 'edit_post', $post_id ) ) {
                wp_redirect( admin_url( 'post.php?post=' . $post_id . '&action=edit' ) );
                exit;
            }
        }
    }
}
add_action( 'admin_init', 'cmc_limit_footer_settings' );

function cmc_add_footer_meta_boxes() {
    add_meta_box( 'cmc_footer_logo', __( 'Logo & Tagline', 'chronicle-magazine-core' ), 'cmc_footer_logo_callback', 'cmc_footer', 'normal', 'high' );
    add_meta_box( 'cmc_footer_social', __( 'Social Media URLs', 'chronicle-magazine-core' ), 'cmc_footer_social_callback', 'cmc_footer', 'normal', 'high' );
    add_meta_box( 'cmc_footer_quick_links', __( 'Quick Links (repeater)', 'chronicle-magazine-core' ), 'cmc_footer_links_callback', 'cmc_footer', 'normal', 'high' );
    add_meta_box( 'cmc_footer_contact', __( 'Contact Information', 'chronicle-magazine-core' ), 'cmc_footer_contact_callback', 'cmc_footer', 'normal', 'high' );
    add_meta_box( 'cmc_footer_copyright', __( 'Copyright Text', 'chronicle-magazine-core' ), 'cmc_footer_copyright_callback', 'cmc_footer', 'side', 'default' );
}
add_action( 'add_meta_boxes', 'cmc_add_footer_meta_boxes' );

function cmc_footer_logo_callback( $post ) {
    wp_nonce_field( 'cmc_footer_meta', 'cmc_footer_nonce' );
    $logo_text   = get_post_meta( $post->ID, 'cmc_footer_logo_text', true );
    $logo_accent = get_post_meta( $post->ID, 'cmc_footer_logo_accent', true );
    $tagline     = get_post_meta( $post->ID, 'cmc_footer_tagline', true );
    ?>
<p>
    <label
        for="cmc_footer_logo_text"><?php esc_html_e( 'Logo Base Text', 'chronicle-magazine-core' ); ?></label><br>
    <input type="text" name="cmc_footer_logo_text" id="cmc_footer_logo_text"
        value="<?php echo esc_attr( $logo_text ); ?>" class="widefat">
</p>
<p>
    <label
        for="cmc_footer_logo_accent"><?php esc_html_e( 'Logo Accent Text (highlighted)', 'chronicle-magazine-core' ); ?></label><br>
    <input type="text" name="cmc_footer_logo_accent" id="cmc_footer_logo_accent"
        value="<?php echo esc_attr( $logo_accent ); ?>" class="widefat">
</p>
<p>
    <label
        for="cmc_footer_tagline"><?php esc_html_e( 'Tagline / Description', 'chronicle-magazine-core' ); ?></label><br>
    <textarea name="cmc_footer_tagline" id="cmc_footer_tagline" rows="3"
        class="large-text"><?php echo esc_textarea( $tagline ); ?></textarea>
</p>
<?php
}

function cmc_footer_social_callback( $post ) {
    wp_nonce_field( 'cmc_footer_meta', 'cmc_footer_nonce' );
    $social = get_post_meta( $post->ID, 'cmc_footer_social', true );
    if ( ! is_array( $social ) ) $social = array();
    ?>
<p>
    <label
        for="cmc_footer_social_twitter"><?php esc_html_e( 'X (Twitter) URL', 'chronicle-magazine-core' ); ?></label><br>
    <input type="text" name="cmc_footer_social[twitter]" id="cmc_footer_social_twitter"
        value="<?php echo esc_attr( $social['twitter'] ?? '' ); ?>" class="widefat">
</p>
<p>
    <label
        for="cmc_footer_social_facebook"><?php esc_html_e( 'Facebook URL', 'chronicle-magazine-core' ); ?></label><br>
    <input type="text" name="cmc_footer_social[facebook]" id="cmc_footer_social_facebook"
        value="<?php echo esc_attr( $social['facebook'] ?? '' ); ?>" class="widefat">
</p>
<p>
    <label
        for="cmc_footer_social_instagram"><?php esc_html_e( 'Instagram URL', 'chronicle-magazine-core' ); ?></label><br>
    <input type="text" name="cmc_footer_social[instagram]" id="cmc_footer_social_instagram"
        value="<?php echo esc_attr( $social['instagram'] ?? '' ); ?>" class="widefat">
</p>
<p>
    <label
        for="cmc_footer_social_linkedin"><?php esc_html_e( 'LinkedIn URL', 'chronicle-magazine-core' ); ?></label><br>
    <input type="text" name="cmc_footer_social[linkedin]" id="cmc_footer_social_linkedin"
        value="<?php echo esc_attr( $social['linkedin'] ?? '' ); ?>" class="widefat">
</p>
<?php
}

function cmc_footer_links_callback( $post ) {
    wp_nonce_field( 'cmc_footer_meta', 'cmc_footer_nonce' );
    $links = get_post_meta( $post->ID, 'cmc_footer_links', true );
    if ( ! is_array( $links ) ) $links = array();
    $next_index = count( $links );
    ?>
<div id="cmc-footer-links-repeater" class="cmc-repeater">
    <input type="hidden" id="cmc-footer-link-count" name="cmc_footer_link_count"
        value="<?php echo esc_attr( $next_index ); ?>">
    <?php foreach ( $links as $index => $link ) : ?>
    <div class="cmc-footer-link-row cmc-repeater-row" data-index="<?php echo esc_attr( $index ); ?>">
        <input type="text" name="cmc_footer_links[<?php echo esc_attr( $index ); ?>][text]" class="cmc-link-text"
            value="<?php echo esc_attr( $link['text'] ); ?>"
            placeholder="<?php esc_attr_e( 'Link text', 'chronicle-magazine-core' ); ?>">
        <input type="text" name="cmc_footer_links[<?php echo esc_attr( $index ); ?>][url]" class="cmc-link-url"
            value="<?php echo esc_attr( $link['url'] ); ?>"
            placeholder="<?php esc_attr_e( 'URL', 'chronicle-magazine-core' ); ?>">
        <button type="button"
            class="button cmc-remove-row"><?php esc_html_e( 'Remove', 'chronicle-magazine-core' ); ?></button>
    </div>
    <?php endforeach; ?>
</div>
<button type="button" id="cmc-add-footer-link"
    class="button"><?php esc_html_e( 'Add Link', 'chronicle-magazine-core' ); ?></button>
<?php
}

function cmc_footer_contact_callback( $post ) {
    wp_nonce_field( 'cmc_footer_meta', 'cmc_footer_nonce' );
    $address = get_post_meta( $post->ID, 'cmc_footer_address', true );
    $phone   = get_post_meta( $post->ID, 'cmc_footer_phone', true );
    $email   = get_post_meta( $post->ID, 'cmc_footer_email', true );
    ?>
<p>
    <label for="cmc_footer_address"><?php esc_html_e( 'Address', 'chronicle-magazine-core' ); ?></label><br>
    <textarea name="cmc_footer_address" id="cmc_footer_address" rows="3"
        class="large-text"><?php echo esc_textarea( $address ); ?></textarea>
</p>
<p>
    <label for="cmc_footer_phone"><?php esc_html_e( 'Phone', 'chronicle-magazine-core' ); ?></label><br>
    <input type="tel" name="cmc_footer_phone" id="cmc_footer_phone" value="<?php echo esc_attr( $phone ); ?>"
        class="widefat">
</p>
<p>
    <label for="cmc_footer_email"><?php esc_html_e( 'Email', 'chronicle-magazine-core' ); ?></label><br>
    <input type="email" name="cmc_footer_email" id="cmc_footer_email" value="<?php echo esc_attr( $email ); ?>"
        class="widefat">
</p>
<?php
}

function cmc_footer_copyright_callback( $post ) {
    wp_nonce_field( 'cmc_footer_meta', 'cmc_footer_nonce' );
    $copyright = get_post_meta( $post->ID, 'cmc_footer_copyright', true );
    ?>
<p>
    <label
        for="cmc_footer_copyright"><?php esc_html_e( 'Copyright text', 'chronicle-magazine-core' ); ?></label><br>
    <input type="text" name="cmc_footer_copyright" id="cmc_footer_copyright"
        value="<?php echo esc_attr( $copyright ); ?>" class="widefat">
</p>
<?php
}

function cmc_enqueue_footer_assets( $hook ) {
    global $post_type;
    if ( 'cmc_footer' === $post_type && in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
        wp_enqueue_style( 'cmc-footer-css', CMC_PLUGIN_URL . 'assets/css/footer-admin.css', array(), CMC_VERSION );
        wp_enqueue_script( 'cmc-footer-js', CMC_PLUGIN_URL . 'assets/js/footer-admin.js', array( 'jquery' ), CMC_VERSION, true );
        wp_localize_script( 'cmc-footer-js', 'cmcFooterL10n', array(
            'linkTextPlaceholder' => __( 'Link text', 'chronicle-magazine-core' ),
            'urlPlaceholder'      => __( 'URL', 'chronicle-magazine-core' ),
            'removeText'          => __( 'Remove', 'chronicle-magazine-core' ),
        ) );
    }
}
add_action( 'admin_enqueue_scripts', 'cmc_enqueue_footer_assets' );

function cmc_save_footer_meta( $post_id ) {
    if ( ! isset( $_POST['cmc_footer_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cmc_footer_nonce'] ) ), 'cmc_footer_meta' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( get_post_type( $post_id ) !== 'cmc_footer' ) {
        return;
    }

    $logo_fields = array( 'cmc_footer_logo_text', 'cmc_footer_logo_accent', 'cmc_footer_tagline' );
    foreach ( $logo_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
        }
    }

    if ( isset( $_POST['cmc_footer_social'] ) && is_array( $_POST['cmc_footer_social'] ) ) {
        $social = array();
        foreach ( $_POST['cmc_footer_social'] as $key => $url ) {
            $social[ $key ] = sanitize_text_field( wp_unslash( $url ) );
        }
        update_post_meta( $post_id, 'cmc_footer_social', $social );
    }

    if ( isset( $_POST['cmc_footer_links'] ) && is_array( $_POST['cmc_footer_links'] ) ) {
        $links = array();
        foreach ( $_POST['cmc_footer_links'] as $link ) {
            $text = isset( $link['text'] ) ? sanitize_text_field( wp_unslash( $link['text'] ) ) : '';
            $url  = isset( $link['url'] )  ? sanitize_text_field( wp_unslash( $link['url'] ) ) : '';
            if ( ! empty( $text ) && ! empty( $url ) ) {
                $links[] = array( 'text' => $text, 'url' => $url );
            }
        }
        update_post_meta( $post_id, 'cmc_footer_links', $links );
    } else {
        update_post_meta( $post_id, 'cmc_footer_links', array() );
    }

    $contact_fields = array( 'cmc_footer_address', 'cmc_footer_phone', 'cmc_footer_email', 'cmc_footer_copyright' );
    foreach ( $contact_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
        }
    }
}
add_action( 'save_post_cmc_footer', 'cmc_save_footer_meta' );

/* ======================================================
   11. About Page (Single Instance) + Meta Boxes
====================================================== */

function cmc_register_about_page_cpt() {
    register_post_type( 'cmc_about_page', array(
        'labels' => array(
            'name'          => __( 'About Page', 'chronicle-magazine-core' ),
            'singular_name' => __( 'About Page', 'chronicle-magazine-core' ),
            'add_new_item'  => __( 'Edit About Page', 'chronicle-magazine-core' ),
            'edit_item'     => __( 'Edit About Page', 'chronicle-magazine-core' ),
        ),
        'public'           => false,
        'show_ui'          => true,
        'show_in_menu'     => 'cmc-magazine-core',
        'menu_icon'        => 'dashicons-info',
        'menu_position'    => 65,
        'supports'         => array( 'title' ),
        'show_in_rest'     => true,
    ) );
}
add_action( 'init', 'cmc_register_about_page_cpt' );

function cmc_limit_about_page() {
    global $pagenow;

    if ( $pagenow === 'post-new.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] === 'cmc_about_page' ) {

        $existing = get_posts( array(
            'post_type'      => 'cmc_about_page',
            'posts_per_page' => 1,
            'post_status'    => 'publish',
            'fields'         => 'ids',
        ) );

        if ( ! empty( $existing ) ) {
            wp_redirect( admin_url( 'post.php?post=' . $existing[0] . '&action=edit' ) );
            exit;
        }
    }
}
add_action( 'admin_init', 'cmc_limit_about_page' );

function cmc_add_about_page_meta_boxes() {

    add_meta_box(
        'cmc_about_header',
        __( 'Header', 'chronicle-magazine-core' ),
        'cmc_about_header_callback',
        'cmc_about_page'
    );

    add_meta_box(
        'cmc_about_text',
        __( 'Content', 'chronicle-magazine-core' ),
        'cmc_about_text_callback',
        'cmc_about_page'
    );

    add_meta_box(
        'cmc_about_slider',
        __( 'Slider (repeatable)', 'chronicle-magazine-core' ),
        'cmc_about_slider_callback',
        'cmc_about_page'
    );
}
add_action( 'add_meta_boxes', 'cmc_add_about_page_meta_boxes' );

function cmc_about_header_callback( $post ) {
    wp_nonce_field( 'cmc_about_page_meta', 'cmc_about_page_nonce' );

    $kicker = get_post_meta( $post->ID, 'cmc_about_kicker', true );
    $title  = get_post_meta( $post->ID, 'cmc_about_title', true );
    ?>
<p>
    <label for="cmc_about_kicker"><?php esc_html_e( 'Kicker', 'chronicle-magazine-core' ); ?></label><br>
    <input type="text" name="cmc_about_kicker" id="cmc_about_kicker" value="<?php echo esc_attr( $kicker ); ?>"
        class="widefat">
</p>
<p>
    <label for="cmc_about_title"><?php esc_html_e( 'Main Title', 'chronicle-magazine-core' ); ?></label><br>
    <input type="text" name="cmc_about_title" id="cmc_about_title" value="<?php echo esc_attr( $title ); ?>"
        class="widefat">
</p>
<?php
}

function cmc_about_text_callback( $post ) {

    $mission    = get_post_meta( $post->ID, 'cmc_about_mission', true );
    $team       = get_post_meta( $post->ID, 'cmc_about_team', true );
    $history    = get_post_meta( $post->ID, 'cmc_about_history', true );
    ?>
<p>
    <label for="cmc_about_mission"><?php esc_html_e( 'Mission Statement', 'chronicle-magazine-core' ); ?></label><br>
    <textarea name="cmc_about_mission" id="cmc_about_mission" rows="6"
        class="large-text"><?php echo esc_textarea( $mission ); ?></textarea>
</p>
<p>
    <label for="cmc_about_team"><?php esc_html_e( 'Our Team', 'chronicle-magazine-core' ); ?></label><br>
    <textarea name="cmc_about_team" id="cmc_about_team" rows="6"
        class="large-text"><?php echo esc_textarea( $team ); ?></textarea>
</p>
<p>
    <label for="cmc_about_history"><?php esc_html_e( 'History', 'chronicle-magazine-core' ); ?></label><br>
    <textarea name="cmc_about_history" id="cmc_about_history" rows="6"
        class="large-text"><?php echo esc_textarea( $history ); ?></textarea>
</p>
<?php
}

function cmc_about_slider_callback( $post ) {

    $slides = get_post_meta( $post->ID, 'cmc_about_slides', true );
    if ( ! is_array( $slides ) ) {
        $slides = array();
    }
    $next_index = count( $slides );
    ?>
<div id="cmc-about-slides-repeater" class="cmc-slides-repeater">
    <input type="hidden" id="cmc-about-slide-count" name="cmc_about_slide_count"
        value="<?php echo esc_attr( $next_index ); ?>">
    <?php foreach ( $slides as $index => $slide ) : ?>
    <div class="cmc-slide-row" data-index="<?php echo esc_attr( $index ); ?>">
        <p>
            <label><?php esc_html_e( 'Title', 'chronicle-magazine-core' ); ?></label><br>
            <input type="text" name="cmc_about_slides[<?php echo esc_attr( $index ); ?>][title]"
                value="<?php echo esc_attr( $slide['title'] ); ?>" class="widefat">
        </p>
        <p>
            <label><?php esc_html_e( 'Subtitle', 'chronicle-magazine-core' ); ?></label><br>
            <input type="text" name="cmc_about_slides[<?php echo esc_attr( $index ); ?>][subtitle]"
                value="<?php echo esc_attr( $slide['subtitle'] ); ?>" class="widefat">
        </p>
        <div class="slide-image-wrapper">
            <label><?php esc_html_e( 'Background Image', 'chronicle-magazine-core' ); ?></label><br>
            <input type="hidden" name="cmc_about_slides[<?php echo esc_attr( $index ); ?>][image]"
                class="slide-image-url" value="<?php echo esc_url( $slide['image'] ); ?>">
            <div class="image-preview">
                <?php if ( ! empty( $slide['image'] ) ) : ?>
                <img src="<?php echo esc_url( $slide['image'] ); ?>" class="preview-thumb">
                <?php endif; ?>
            </div>
            <button type="button"
                class="button select-slide-image"><?php esc_html_e( 'Select Image', 'chronicle-magazine-core' ); ?></button>
            <button type="button"
                class="button remove-slide-image <?php echo empty( $slide['image'] ) ? 'hidden' : ''; ?>"><?php esc_html_e( 'Remove Image', 'chronicle-magazine-core' ); ?></button>
        </div>
        <button type="button"
            class="button cmc-remove-slide-row mt-1"><?php esc_html_e( 'Remove Slide', 'chronicle-magazine-core' ); ?></button>
    </div>
    <?php endforeach; ?>
</div>
<button type="button" id="cmc-add-about-slide"
    class="button"><?php esc_html_e( 'Add Slide', 'chronicle-magazine-core' ); ?></button>
<?php
}

function cmc_enqueue_about_assets( $hook ) {
    global $post_type;
    if ( 'cmc_about_page' === $post_type && in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
        wp_enqueue_media();
        wp_enqueue_style( 'cmc-about-css', CMC_PLUGIN_URL . 'assets/css/about-admin.css', array(), CMC_VERSION );
        wp_enqueue_script( 'cmc-about-js', CMC_PLUGIN_URL . 'assets/js/about-admin.js', array( 'jquery' ), CMC_VERSION, true );
        wp_localize_script( 'cmc-about-js', 'cmcAboutL10n', array(
            'titlePlaceholder'    => __( 'Title', 'chronicle-magazine-core' ),
            'subtitlePlaceholder' => __( 'Subtitle', 'chronicle-magazine-core' ),
            'imagePlaceholder'    => __( 'Background Image', 'chronicle-magazine-core' ),
            'selectImage'         => __( 'Select Image', 'chronicle-magazine-core' ),
            'removeImage'         => __( 'Remove Image', 'chronicle-magazine-core' ),
            'removeText'          => __( 'Remove Slide', 'chronicle-magazine-core' ),
        ) );
    }
}
add_action( 'admin_enqueue_scripts', 'cmc_enqueue_about_assets' );

function cmc_save_about_page_meta( $post_id ) {

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    if ( ! isset( $_POST['cmc_about_page_nonce'] ) ) return;

    $nonce = sanitize_text_field( wp_unslash( $_POST['cmc_about_page_nonce'] ) );

    if ( ! wp_verify_nonce( $nonce, 'cmc_about_page_meta' ) ) return;

    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    if ( get_post_type( $post_id ) !== 'cmc_about_page' ) return;

    $text_fields = array( 'cmc_about_kicker', 'cmc_about_title' );
    foreach ( $text_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
        }
    }

    $textarea_fields = array( 'cmc_about_mission', 'cmc_about_team', 'cmc_about_history' );
    foreach ( $textarea_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, sanitize_textarea_field( wp_unslash( $_POST[ $field ] ) ) );
        }
    }

    if ( isset( $_POST['cmc_about_slides'] ) && is_array( $_POST['cmc_about_slides'] ) ) {

        $slides = array();

        foreach ( $_POST['cmc_about_slides'] as $slide ) {

            $title    = isset( $slide['title'] ) ? sanitize_text_field( wp_unslash( $slide['title'] ) ) : '';
            $subtitle = isset( $slide['subtitle'] ) ? sanitize_text_field( wp_unslash( $slide['subtitle'] ) ) : '';
            $image    = isset( $slide['image'] ) ? esc_url_raw( wp_unslash( $slide['image'] ) ) : '';

            if ( empty( $title ) && empty( $subtitle ) && empty( $image ) ) {
                continue;
            }

            $slides[] = array(
                'title'    => $title,
                'subtitle' => $subtitle,
                'image'    => $image,
            );
        }

        update_post_meta( $post_id, 'cmc_about_slides', $slides );

    } else {
        update_post_meta( $post_id, 'cmc_about_slides', array() );
    }
}
add_action( 'save_post_cmc_about_page', 'cmc_save_about_page_meta' );

/* ======================================================
   12. Contact Page (Single Instance) + Meta Boxes
====================================================== */

function cmc_register_contact_page_cpt() {
    register_post_type( 'cmc_contact_page', array(
        'labels' => array(
            'name'          => __( 'Contact Page', 'chronicle-magazine-core' ),
            'singular_name' => __( 'Contact Page', 'chronicle-magazine-core' ),
            'add_new_item'  => __( 'Edit Contact Page', 'chronicle-magazine-core' ),
            'edit_item'     => __( 'Edit Contact Page', 'chronicle-magazine-core' ),
        ),
        'public'           => false,
        'show_ui'          => true,
        'show_in_menu'     => 'cmc-magazine-core',
        'menu_icon'        => 'dashicons-email',
        'menu_position'    => 66,
        'supports'         => array( 'title' ),
        'show_in_rest'     => true,
        'capability_type'  => 'post',
        'map_meta_cap'     => true,
    ) );
}
add_action( 'init', 'cmc_register_contact_page_cpt' );

function cmc_limit_contact_page() {
    global $pagenow;
    if ( $pagenow === 'post-new.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] === 'cmc_contact_page' ) {
        $existing = get_posts( array(
            'post_type'      => 'cmc_contact_page',
            'posts_per_page' => 1,
            'post_status'    => 'publish',
            'fields'         => 'ids',
        ) );
        if ( ! empty( $existing ) ) {
            $post_id = $existing[0];
            if ( current_user_can( 'edit_post', $post_id ) ) {
                wp_redirect( admin_url( 'post.php?post=' . $post_id . '&action=edit' ) );
                exit;
            }
        }
    }
}
add_action( 'admin_init', 'cmc_limit_contact_page' );

function cmc_add_contact_page_meta_boxes() {
    add_meta_box(
        'cmc_contact_page_settings',
        __( 'Contact Page Content', 'chronicle-magazine-core' ),
        'cmc_contact_page_meta_callback',
        'cmc_contact_page',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'cmc_add_contact_page_meta_boxes' );

function cmc_contact_page_meta_callback( $post ) {
    wp_nonce_field( 'cmc_contact_page_meta', 'cmc_contact_page_nonce' );

    $address   = get_post_meta( $post->ID, 'cmc_contact_address', true );
    $phone     = get_post_meta( $post->ID, 'cmc_contact_phone', true );
    $email     = get_post_meta( $post->ID, 'cmc_contact_email', true );
    $map_embed = get_post_meta( $post->ID, 'cmc_contact_map_embed', true );
    $form_shortcode = get_post_meta( $post->ID, 'cmc_contact_form_shortcode', true );
    ?>
<p>
    <label for="cmc_contact_address"><?php esc_html_e( 'Address', 'chronicle-magazine-core' ); ?></label><br>
    <textarea name="cmc_contact_address" id="cmc_contact_address" rows="3"
        class="large-text"><?php echo esc_textarea( $address ); ?></textarea>
    <span class="description"><?php esc_html_e( 'Full office address.', 'chronicle-magazine-core' ); ?></span>
</p>
<p>
    <label for="cmc_contact_phone"><?php esc_html_e( 'Phone Number', 'chronicle-magazine-core' ); ?></label><br>
    <input type="tel" name="cmc_contact_phone" id="cmc_contact_phone" value="<?php echo esc_attr( $phone ); ?>"
        class="widefat">
</p>
<p>
    <label for="cmc_contact_email"><?php esc_html_e( 'Email Address', 'chronicle-magazine-core' ); ?></label><br>
    <input type="email" name="cmc_contact_email" id="cmc_contact_email" value="<?php echo esc_attr( $email ); ?>"
        class="widefat">
</p>
<p>
    <label
        for="cmc_contact_map_embed"><?php esc_html_e( 'Google Maps Embed URL', 'chronicle-magazine-core' ); ?></label><br>
    <input type="url" name="cmc_contact_map_embed" id="cmc_contact_map_embed"
        value="<?php echo esc_url( $map_embed ); ?>" class="widefat"
        placeholder="https://www.google.com/maps/embed?...">
    <span
        class="description"><?php esc_html_e( 'Paste the embed URL from Google Maps.', 'chronicle-magazine-core' ); ?></span>
</p>
<p>
    <label
        for="cmc_contact_form_shortcode"><?php esc_html_e( 'Contact Form Shortcode', 'chronicle-magazine-core' ); ?></label><br>
    <input type="text" name="cmc_contact_form_shortcode" id="cmc_contact_form_shortcode"
        value="<?php echo esc_attr( $form_shortcode ); ?>" class="widefat" placeholder="[contact-form-7 id=...]">
    <span
        class="description"><?php esc_html_e( 'If using a plugin like Contact Form 7, paste the shortcode here.', 'chronicle-magazine-core' ); ?></span>
</p>
<?php
}

function cmc_save_contact_page_meta( $post_id ) {
    if ( ! isset( $_POST['cmc_contact_page_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cmc_contact_page_nonce'] ) ), 'cmc_contact_page_meta' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( get_post_type( $post_id ) !== 'cmc_contact_page' ) {
        return;
    }

    if ( isset( $_POST['cmc_contact_address'] ) ) {
        update_post_meta( $post_id, 'cmc_contact_address', sanitize_textarea_field( wp_unslash( $_POST['cmc_contact_address'] ) ) );
    }

    if ( isset( $_POST['cmc_contact_phone'] ) ) {
        update_post_meta( $post_id, 'cmc_contact_phone', sanitize_text_field( wp_unslash( $_POST['cmc_contact_phone'] ) ) );
    }

    if ( isset( $_POST['cmc_contact_email'] ) ) {
        update_post_meta( $post_id, 'cmc_contact_email', sanitize_email( wp_unslash( $_POST['cmc_contact_email'] ) ) );
    }

    if ( isset( $_POST['cmc_contact_map_embed'] ) ) {
        update_post_meta( $post_id, 'cmc_contact_map_embed', esc_url_raw( wp_unslash( $_POST['cmc_contact_map_embed'] ) ) );
    }

    if ( isset( $_POST['cmc_contact_form_shortcode'] ) ) {
        update_post_meta( $post_id, 'cmc_contact_form_shortcode', sanitize_text_field( wp_unslash( $_POST['cmc_contact_form_shortcode'] ) ) );
    }
}
add_action( 'save_post_cmc_contact_page', 'cmc_save_contact_page_meta' );

/* ======================================================
   13. Contact Form 7 Support
====================================================== */

function cmc_get_first_cf7_shortcode() {
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
