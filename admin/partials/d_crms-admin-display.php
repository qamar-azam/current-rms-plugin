<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.doddletech.com
 * @since      1.0.0
 *
 * @package    D_crms
 * @subpackage D_crms/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    <form action="options.php" method="post">
        <?php
            settings_fields( $this->plugin_name."_api_sub_page" );
            do_settings_sections( $this->plugin_name."_api_sub_page" );
            submit_button();
        ?>
    </form>
</div>
