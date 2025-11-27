<?php
if (!defined('ABSPATH')) exit;

/** @var WP_List_Table $list_table */
?>

<div class="wrap">
    <h1 class="wp-heading-inline">WPForms Lite Entries</h1>

    <form method="get">
        <input type="hidden" name="page" value="rwwep-wpforms-entries">

        <?php
        $list_table->search_box('Search', 'rwwep-search');
        $list_table->display();
        ?>
    </form>
</div>
