<?php
namespace RobertWP\WPFormsEntriesPro\Utils;

use RobertWP\WPFormsEntriesPro\Admin\Settings\SettingsRegistrar;

/**
 * Class RWLogger
 * RobertWP 通用日志系统
 */
class RWLogger {

    /**
     * 默认保留日志天数
     */
    const RETENTION_DAYS = 30;

    /**
     * 子目录名称（每个插件可用不同目录）
     */
    private static string $subdir;
    private static string $log_pre;

    private static $debug_check_callback = null;

    public static function init($debug_check_callback = null, $plugin_name='rw-plugin-pro', $short_plugin_name='RW'): void
    {
        self::$debug_check_callback = $debug_check_callback;
        self::$log_pre = $short_plugin_name;
        self::$subdir = $plugin_name;
    }

    /**
     * 设置插件专用子目录
     *
     * @param string $subdir
     */
    public static function set_subdir(string $subdir): void
    {
        self::$subdir = $subdir;
    }

    /**
     * 记录日志
     *
     * @param string $message
     * @param string $level info|warning|error
     */
    public static function log(string $message, string $level = 'info'): void
    {
        if (!self::is_enabled()) {
            return;
        }

        //$timestamp = date('Y-m-d H:i:s');
        $level = strtolower($level);
        //$formatted = sprintf("[%s] [%s] %s", $timestamp, strtoupper($level), $message);
        $formatted = sprintf("[%s] %s", strtoupper($level), $message);

        // 写入 PHP 错误日志
        error_log('['.self::$log_pre.'] ' . $formatted);

        // 写入自定义文件
        self::write_to_file($formatted);

        // 清理过期日志（每日首次记录时执行）
        self::cleanup_old_logs();
    }

    /**
     * 判断日志是否开启
     *
     * @return bool
     */
    private static function is_enabled(): bool
    {
        // 1. 优先检查常量
        if (defined('RW_DEBUG') && RW_DEBUG) {
            return true;
        }

        // 2. 插件自定义检测逻辑
        if (is_callable(self::$debug_check_callback)) {
            return (bool) call_user_func(self::$debug_check_callback);
        }

        // 3. 默认逻辑（适用于简单插件）
        $settings = get_option(SettingsRegistrar::RWPSP_SITE_SETTINGS_OPTION);
        return (bool) $settings['debug_enabled'] ?? false;
    }

    /**
     * 获取日志目录路径（uploads/{subdir}/logs）
     *
     * @return string
     */
    private static function get_log_dir(): string
    {
        $upload_dir = wp_upload_dir();
        return trailingslashit($upload_dir['basedir']) . self::$subdir . '/logs';
    }

    /**
     * 写入文件日志
     *
     * @param string $text
     */
    private static function write_to_file(string $text): void
    {
        $log_dir = self::get_log_dir();

        // 确保路径存在
        if (!wp_mkdir_p($log_dir)) {
            error_log('[RWLogger] Failed to create log directory: ' . $log_dir);
            return;
        }

        // 确保目录可写
        if (!is_writable($log_dir)) {
            error_log('[RWLogger] Log directory is not writable: ' . $log_dir);
            return;
        }

        $log_file = $log_dir . '/rwlog-' . date('Y-m-d') . '.log';
        //error_log('$log_file=' . $log_file);

        // 尝试写入日志
        $result = @file_put_contents($log_file, $text . PHP_EOL, FILE_APPEND | LOCK_EX);
        if ($result === false) {
            error_log('[RWLogger] Failed to write log file: ' . $log_file);
            return;
        }

        // 设置文件权限
        @chmod($log_file, 0664);
    }

    /**
     * 清理过期日志
     */
    private static function cleanup_old_logs(): void
    {
        static $cleaned_today = false;
        if ($cleaned_today) return;

        $cleaned_today = true;

        $log_dir = self::get_log_dir();
        if (!file_exists($log_dir)) {
            return;
        }

        $files = glob($log_dir . '/rwlog-*.log');
        $threshold = strtotime('-' . self::RETENTION_DAYS . ' days');

        foreach ($files as $file) {
            if (filemtime($file) < $threshold) {
                @unlink($file);
            }
        }
    }

    /**
     * 获取日志文件路径（用于后台查看或下载）
     *
     * @param string|null $date 格式：YYYY-MM-DD
     * @return string|null
     */
    public static function get_log_file(string $date = null): ?string
    {
        $date = $date ?: date('Y-m-d');
        $file = self::get_log_dir() . '/rwlog-' . $date . '.log';
        return file_exists($file) ? $file : null;
    }
}