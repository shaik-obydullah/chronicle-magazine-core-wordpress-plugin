# Chronicle Magazine Core

**Contributors:** obydullah  
**Tags:** magazine, custom post types, newsletter, advertisement, slider  
**Requires at least:** 6.0  
**Tested up to:** 7.0  
**Requires PHP:** 8.0  
**Stable tag:** 1.0.0  
**License:** GPL v2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html

Core functionality plugin for the Chronicle Magazine WordPress theme. Decouples custom post types, meta boxes, and database tables from the theme so data persists through theme switches.

## Description

Chronicle Magazine Core is a companion plugin that powers the backend of magazine-style WordPress sites. It registers 9 custom post types, a custom taxonomy, a newsletter subscription system with a custom database table, and an admin dashboard — all designed to work with the Chronicle Magazine theme but built to survive theme changes.

## Features

- **Hero Slider** — Manage slides with subtitles and category labels
- **Featured Articles** — Single-instance featured article with excerpt, author, and publish date
- **Articles** — Full article management with a hierarchical category taxonomy, subtitle, author, and read time
- **Authors** — Author profiles with bio, position, email, and social links
- **Magazine Issues** — Track issues by number, month, and year, with PDF download support
- **News Ticker** — Breaking news items with custom links
- **Newsletter Subscriptions** — Custom database table, AJAX signup, and admin list table with bulk actions
- **Advertisement Slots** — Single-instance ad management with repeatable slots and HTML code support
- **Footer Settings** — Single-instance footer config: logo, tagline, social links, quick links, contact info, copyright
- **About Page** — Single-instance about page builder with mission, team, history, and image slides
- **Contact Page** — Single-instance contact page with address, map embed, and Contact Form 7 integration
- **Admin Dashboard** — Central hub linking all content types

## Installation

1. Upload the `chronicle-magazine-core` folder to `/wp-content/plugins/`
2. Activate the plugin through the Plugins screen in WordPress
3. A new **Magazine Theme Core** menu item appears in the admin sidebar
4. (Optional) Install Contact Form 7 for contact page form support

## Frequently Asked Questions

### Is this plugin standalone or does it require a theme?

This plugin is designed as a companion to the Chronicle Magazine theme. The custom post types and meta boxes it registers are intended to be displayed by that theme's template files.

### Does this plugin work with Contact Form 7?

Yes. The contact page meta box includes a field for a CF7 shortcode, and the plugin provides a helper function to auto-detect the first available CF7 form.

### Will I lose my data if I switch themes?

No. The plugin stores all content in WordPress posts, post meta, and its own custom database table. Changing themes will not affect this data.

## Changelog

### 1.0.0

- Initial release

## Upgrade Notice

### 1.0.0

Initial release.
