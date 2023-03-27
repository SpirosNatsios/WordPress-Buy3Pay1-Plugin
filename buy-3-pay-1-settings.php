<?php
add_action('admin_menu', 'b3p1_add_options_page');
add_action('admin_init', 'b3p1_register_settings');

function b3p1_add_options_page()
{
    add_submenu_page(
        'woocommerce',
        'Buy 3 Pay 1 Options',
        'Buy 3 Pay 1',
        'manage_options',
        'buy-3-pay-1',
        'b3p1_options_page'
    );
}

function b3p1_register_settings()
{
    register_setting(
        'b3p1_options_group',
        'b3p1_excluded_categories'
    );
}

function b3p1_options_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $excluded_categories = get_option('b3p1_excluded_categories', array());
    $categories = get_terms(
        array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        )
    );
    ?>
    <div class="wrap">
        <h1>
            <?php echo esc_html(get_admin_page_title()); ?>
        </h1>
        <form action="options.php" method="post">
            <?php settings_fields('b3p1_options_group'); ?>
            <?php do_settings_sections('b3p1_options_group'); ?>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row">
                            <?php _e('Excluded Categories', 'b3p1'); ?>
                        </th>
                        <td>
                            <?php foreach ($categories as $category): ?>
                                <label>
                                    <input type="checkbox" name="b3p1_excluded_categories[]"
                                        value="<?php echo esc_attr($category->term_id); ?>" <?php if (is_array($excluded_categories) && in_array($category->term_id, $excluded_categories))
                                               echo 'checked="checked"'; ?>>
                                    <?php echo esc_html($category->name); ?>
                                </label><br>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php submit_button(); ?>
        </form>

    </div>
    <?php
}
?>