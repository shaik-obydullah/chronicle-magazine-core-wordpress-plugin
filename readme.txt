=== Obydullah Magazine Core ===
Contributors: obydullah
Tags: magazine, custom post types, newsletter, advertisement, slider
Text Domain: obydullah-magazine-core
Requires at least: 6.2
Tested up to: 7.1
Requires PHP: 8.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Core functionality plugin for the Chronicle Magazine WordPress theme.

== Description ==

Obydullah Magazine Core is a companion plugin that powers the backend of magazine-style WordPress sites. It registers 10 custom post types, a custom taxonomy, a newsletter subscription system backed by a custom database table, and a central admin dashboard.

Because all content lives in posts, post meta, and its own database table rather than in theme options, your data survives theme changes. Switch to a different theme and every post type, meta box, and subscriber record stays exactly where it is.

= Content Types =

* **Hero Slider** — Manage slides with subtitles and category labels
* **Featured Articles** — Single-instance featured article with excerpt, author, and publish date
* **Articles** — Full article management with a hierarchical category taxonomy, subtitle, author, and read time
* **Authors** — Author profiles with bio, position, email, and social links
* **Magazine Issues** — Track issues by number, month, and year, with PDF download support
* **News Ticker** — Breaking news items with custom links
* **Advertisement Slots** — Single-instance ad management with repeatable slots and HTML code support
* **Footer Settings** — Single-instance footer config: logo, tagline, social links, quick links, contact info, copyright
* **About Page** — Single-instance about page builder with mission, team, history, and image slides
* **Contact Page** — Single-instance contact page with address, map embed, and Contact Form 7 integration

= Newsletter Subscriptions =

Newsletter signups are stored in a dedicated `wp_obmc_newsletter_subscribers` table rather than as posts, which keeps the subscriber list fast and independent. Signups go through a nonce-verified AJAX endpoint that accepts both logged-in and logged-out visitors, and subscribers are managed from a dedicated admin list table with sortable columns and bulk delete.

= Admin Dashboard =

A single top-level **Obydullah Magazine Core** menu item links to every content type in the plugin, so you never have to hunt through the admin sidebar.

== Installation ==

1. Upload the `obydullah-magazine-core` folder to `/wp-content/plugins/`, or install the plugin through the Plugins screen in WordPress.
2. Activate the plugin through the Plugins screen.
3. A new **Obydullah Magazine Core** menu item appears in the admin sidebar.
4. Optionally install Contact Form 7 to enable form detection on the contact page.

The newsletter subscribers table is created automatically on activation and removed when the plugin is uninstalled.

== Frequently Asked Questions ==

= Is this plugin standalone, or does it require a theme? =

This plugin is a companion to the Chronicle Magazine theme. It manages the content and data; the theme's template files handle the front-end display.

= Does this plugin work with Contact Form 7? =

Yes. The contact page meta box includes a field for a CF7 shortcode, and when Contact Form 7 is active the plugin auto-detects your first published form so you can insert it with one click.

= Will I lose my content if I switch themes? =

No. Everything is stored in WordPress posts, post meta, and the plugin's own database table. Changing themes does not touch this data.

= What happens to newsletter subscribers when I uninstall the plugin? =

The subscribers table is dropped on uninstall. Deactivating the plugin keeps the table and all subscriber records intact.

= Does the plugin add anything to the front end? =

The plugin registers content types and admin tooling only. Newsletter signup markup and template output are handled by the theme, so nothing is printed on the front end unless the theme asks for it.

== Screenshots ==

1. The admin dashboard linking to all content types
2. Article editor with category, subtitle, author, and read time
3. Newsletter subscriber list table with sortable columns and bulk delete

== Changelog ==

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
Initial release.