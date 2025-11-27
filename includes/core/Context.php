<?php
namespace RobertWP\WPFormsEntriesPro\Core;

class Context
{
    public static function is_plugin_context(): bool
    {
        global $pagenow;

        if (is_admin()) {
            $page = wp_unslash($_GET['page'] ?? '');

            // 1. 检查是否在后台文章/页面/自定义类型列表页
            if ($pagenow === 'edit.php') {
                return true;
            }
            // 2. 检查插件专属页面（如 ?page=rwlmp...）
            if (str_starts_with($page, 'rwwep')) {
                return true;
            }
        }

        // 3. 检查插件专属 AJAX/REST 操作
        $action = wp_unslash( $_REQUEST['action'] ?? '' );
        if (str_starts_with($action, 'rwwep_')) {
            return true;
        }

        $uri = wp_unslash( $_SERVER['REQUEST_URI'] ?? '' );
        $uri = is_string($uri) ? $uri : '';
        if (defined('REST_REQUEST') && REST_REQUEST && str_contains($uri ?? '', '/wp-json/rwwep/')) {
            return true;
        }

        // ⚡ 关键：允许前台 WPForms Lite 提交
        if (!is_admin() && isset($_POST['wpforms'])) {
            return true;
        }

        return false;
    }
}

