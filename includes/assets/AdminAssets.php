<?php
namespace RobertWP\WPFormsEntriesPro\Assets;

class AdminAssets {

    public static function enqueue(): void
    {
        self::enqueue_styles();
    }

    public static function enqueue_styles(): void
    {
        wp_register_style('rwwep-admin-style-min', RWWEP_ASSETS_URL. 'css/rwwep-admin-style.min.css', [], RWWEP_PLUGIN_VERSION );
        wp_enqueue_style('rwwep-admin-style-min');
    }

}
