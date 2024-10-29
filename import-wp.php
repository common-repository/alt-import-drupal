<?php

/*
  Plugin Name: AlT Import drupal
  Plugin URI:  : http://wordpress.lived.fr/plugins/alt-import-drupal/
  Description: Importer drupal à partir de son flux xml
  Version: 1.0.1
  Author: AlTi5
  Author URI: http://wordpress.lived.fr/alti5/
 */


if (!class_exists('import_wp')) {

    class import_wp {

        public static function hooks() {
            if (is_admin()) {
                add_action('admin_menu', array(__CLASS__, 'add_settings_panels'));
                add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_enqueue_scripts')); //argument de la priorité en avant dernier /10
            }
        }

        public static function add_settings_panels() {
            global $tblbords_option;
            add_submenu_page
                    ('tools.php', __('Importer drupal'), __('Importer drupal'), 'manage_options', 'importer_wp', array(__CLASS__,
                'importer_wp')
            );
        }

        public static function admin_enqueue_scripts() {
            global $pagenow, $typenow, $plugin_page;
        }

        public static function importer_wp() {
            global $tblbords_option;
            require_once 'import-page.php';
        }

    }

    import_wp::hooks();
}