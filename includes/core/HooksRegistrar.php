<?php
namespace RobertWP\WPFormsEntriesPro\Core;

use RobertWP\WPFormsEntriesPro\Admin\Menu;
use RobertWP\WPFormsEntriesPro\Admin\Pages\WPFormsEntries;
use RobertWP\WPFormsEntriesPro\Admin\UI\AdminNotice;
use RobertWP\WPFormsEntriesPro\Assets\AdminAssets;

class HooksRegistrar {

    public static function register(): void
    {
        self::register_core_hooks();    // 核心功能，如版本检查、激活等
        self::register_admin_hooks();    // 管理后台钩子
        self::register_frontend_hooks();    // 前台钩子
    }

    private static function register_core_hooks(): void
    {
        add_action('admin_init', self::cb([AdminNotice::class,'maybe_add_notice']));
    }

    private static function register_admin_hooks(): void
    {
        if (!is_admin()) return;

        add_action('admin_enqueue_scripts', [AdminAssets::class, 'enqueue']);

        $menu = Menu::get_instance();
        add_action('admin_menu', [$menu, 'add_entries_menu']);

    }

    private static function register_frontend_hooks(): void
    {
        add_action('wpforms_process_complete', self::cb([WPFormsEntries::class, 'save_wpforms_entry']), 10, 4);
    }

    private static function cb($callback): callable
    {
        return CallbackWrapper::plugin_context_only($callback);
    }

}
