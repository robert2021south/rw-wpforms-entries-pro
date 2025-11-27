<?php
namespace RobertWP\WPFormsEntriesPro\Admin;

use RobertWP\WPFormsEntriesPro\Admin\Pages\WPFormsEntries;
use RobertWP\WPFormsEntriesPro\Traits\Singleton;

class Menu {
    use Singleton;

    public function add_entries_menu(): void
    {
        add_menu_page(
            __('WPForms Lite Entries', 'rw-wpforms-entries-pro'),          // page title
            __('Form Entries', 'rw-wpforms-entries-pro'),              // menu title
            'manage_options',          // Permission requirement
            'rwwep-wpforms-entries', // menu slug
            [WPFormsEntries::class, 'render_wpforms_entries_page'], // Callback function
            'dashicons-list-view'
        );

    }

}
