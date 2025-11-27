<?php
namespace RobertWP\WPFormsEntriesPro\Core;

use RobertWP\WPFormsEntriesPro\Utils\RWLogger;
use RobertWP\WPFormsEntriesPro\Utils\TemplateLoader;

class Bootstrap {
    private static bool $initialized = false;

    public static function run(): void
    {
        if (self::$initialized) return;

        // 2. init template
        TemplateLoader::init(plugin_dir_path(RWWEP_PLUGIN_FILE));

        // 3. init log
        RWLogger::init(function () {return false;},'rw-wpforms-entries-pro','RWWEP');

        // 4. register all hooks
        HooksRegistrar::register();

        self::$initialized = true;
    }

    public static function activate(bool $network_wide = false): void
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rwwep_wpforms_entries';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        form_id mediumint(9) NOT NULL,
        field_data text NOT NULL,
        submitted_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public static function deactivate(): void
    {

    }

    public static function uninstall(): void
    {

    }

}