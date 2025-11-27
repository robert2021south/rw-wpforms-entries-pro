<?php
namespace RobertWP\WPFormsEntriesPro\Admin\UI;

use RobertWP\WPFormsEntriesPro\Utils\TemplateLoader;

class AdminNotice {

    private static bool $general_notice_registered = false;

    public static function maybe_add_notice(): void
    {
        self::maybe_show_general_notice();
    }

    public static function maybe_show_general_notice(): void
    {
        if (self::$general_notice_registered) return;

        $key = sanitize_text_field( wp_unslash( $_GET['notice'] ?? '' ) );
        $context = sanitize_key( wp_unslash( $_GET['context'] ?? 'common' ) );

        if (empty($key)) return;

        $notices = self::get_notice_definitions();

        // 查找对应消息
        $notice_key = "{$context}:{$key}"; // 例：settings:success
        $default_key = "common:{$key}";

        $notice_data = $notices[$notice_key] ?? $notices[$default_key] ?? null;
        if (!$notice_data) return;

        $custom_message = isset($_GET['msg']) ? sanitize_text_field(wp_unslash($_GET['msg'])) : null;

        $message = $custom_message ?: $notice_data['message'];
        $type = $notice_data['type'] ?? 'warning';

        add_action('admin_notices', function() use ($message, $type) {
            TemplateLoader::load('partials/admin-notice-generic', [
                'message' => $message,
                'notice_type' => $type
            ]);
        });

        self::$general_notice_registered = true;
    }

    private static function get_notice_definitions(): array
    {
        return [

            // context: settings
            'settings:success' => [
                'type' => 'success',
                'message' => __('Settings saved successfully.', 'rw-wpforms-entries-pro')
            ],

            // common context
            'common:success' => [
                'type' => 'success',
                'message' => __('Operation completed successfully.', 'rw-wpforms-entries-pro')
            ],
            'common:failure' => [
                'type' => 'error',
                'message' => __('Operation failed. Please try again.', 'rw-wpforms-entries-pro')
            ],

            'common:ins_perm' => [
                'message' => __('You do not have sufficient permissions', 'rw-wpforms-entries-pro'),
                'type' => 'error'
            ],
            'common:inv_req' => [
                'message' => __('Invalid request', 'rw-wpforms-entries-pro'),
                'type' => 'error'
            ],
            'common:inv_nonce' => [
                'message' => __('Invalid Nonce', 'rw-wpforms-entries-pro'),
                'type' => 'error'
            ],
            'common:sec_chk_fail' => [
                'message' => __('Security check failed.', 'rw-wpforms-entries-pro'),
                'type' => 'error'
            ],
            'common:unc_exce' => [
                'message' => __('Uncaught exception.', 'rw-wpforms-entries-pro'),
                'type' => 'error'
            ],

        ];
    }
}
