<?php
namespace RobertWP\WPFormsEntriesPro\Admin\ListTables;

use WP_List_Table;
class EntriesListTable extends WP_List_Table
{
    private \WP_Error|\wpdb $db;
    private string $table;

    public function __construct()
    {
        parent::__construct([
            'singular' => 'entrie',
            'plural'   => 'entries',
            'ajax'     => false,
        ]);

        global $wpdb;
        $this->db = $wpdb;
        $this->table = $this->db->prefix . 'rwwep_wpforms_entries';
    }

    public function get_columns(): array
    {
        return [
            'cb'          => '<input type="checkbox" />',
            'id'                    => 'ID',
            'form_id'              => 'Form ID',
            'field_data'           => 'Field Data',
            'submitted_at'         => 'Submitted At',
        ];
    }

    protected function get_sortable_columns(): array
    {
        return [
            'id'         => ['id', true],
            'submitted_at' => ['submitted_at', false]
        ];
    }

    public function column_default($item, $column_name)
    {
        return $item->$column_name ?? '';
    }

    private function get_total_items(): int
    {
        $search_query = '';
        if (!empty($_REQUEST['s'])) {
            $search = esc_sql($_REQUEST['s']);
            $search_query = "WHERE field_data LIKE '%$search%'";
        }

        return (int) $this->db->get_var("SELECT COUNT(*) FROM {$this->table} $search_query");
    }

    private function get_rows($per_page, $page_number)
    {
        $orderby = sanitize_sql_orderby($_REQUEST['orderby'] ?? 'id');
        $order   = ($_REQUEST['order'] ?? '') === 'asc' ? 'ASC' : 'DESC';

        $search_query = '';
        if (!empty($_REQUEST['s'])) {
            $search = esc_sql($_REQUEST['s']);
            $search_query = "WHERE field_data LIKE '%$search%'";
        }

        $offset = ($page_number - 1) * $per_page;

        $sql = "SELECT * FROM {$this->table} $search_query ORDER BY $orderby $order LIMIT $per_page OFFSET $offset";

        return $this->db->get_results($sql);
    }

    public function prepare_items(): void
    {
        $columns  = $this->get_columns();
        $hidden   = [];
        $sortable = $this->get_sortable_columns();

        // ⭐⭐⭐ 关键：必须设置 column headers，否则表头不会显示
        $this->_column_headers = [$columns, $hidden, $sortable];

        $per_page     = 20;
        $current_page = $this->get_pagenum();
        $total_items  = $this->get_total_items();

        $this->items = $this->get_rows($per_page, $current_page);

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items / $per_page),
        ]);
    }

    public function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="entry_id[]" value="%s" />',
            $item->id
        );
    }
}
