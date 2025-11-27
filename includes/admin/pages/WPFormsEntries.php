<?php
namespace RobertWP\WPFormsEntriesPro\Admin\Pages;

use RobertWP\WPFormsEntriesPro\Admin\ListTables\EntriesListTable;
use RobertWP\WPFormsEntriesPro\Traits\Singleton;
use RobertWP\WPFormsEntriesPro\Utils\TemplateLoader;

class WPFormsEntries{

    use Singleton;

    public static function render_wpforms_entries_page(): void
    {
        $list_table = new EntriesListTable();
        $list_table->prepare_items();

        TemplateLoader::load('modules/entries-page', ['list_table' => $list_table]);
    }

    // Capture WPForms Lite submissions
    public static function save_wpforms_entry($fields, $entry, $form_data, $entry_id): void
    {
        global $wpdb;
        $table = $wpdb->prefix . 'rwwep_wpforms_entries';

        // 提取字段数据
        $field_values = array_map(function ($value) {
            return $value;
        }, $entry['fields']);

        // JSON 保存
        $data = [
            'form_id'      => intval( $form_data['id'] ),
            'field_data'   => wp_json_encode( $field_values ),
            'submitted_at' => current_time( 'mysql' ),
        ];

        $wpdb->insert( $table, $data );

    }



}
