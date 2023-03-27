<?php

add_action('woocommerce_cart_calculate_fees', 'discount_for_every_3_items');
    
function discount_for_every_3_items($cart)
{
    // Define the excluded categories by their term IDs
    $excluded_categories = $excluded_categories = get_option('b3p1_excluded_categories', array());

    // Calculate the number of items in the cart
    $cart_items_count = $cart->get_cart_contents_count();

    // If there are less than 3 items in the cart, no discount is applied
    if ($cart_items_count < 3) {
        return;
    }

    // Get the price of the items in the cart, excluding any items that belong to the excluded categories
    $cart_items = $cart->get_cart();
    $total_price = 0;
    foreach ($cart_items as $cart_item) {
        if (!empty($excluded_categories) && has_term($excluded_categories, 'product_cat', $cart_item['product_id'])) {
            continue;
        }
        $total_price += $cart_item['data']->get_price_excluding_tax() * $cart_item['quantity'];
    }

    // Calculate the discount amount as the price of two items for every set of three items
    $number_of_sets_of_three = floor($cart_items_count / 3);
    $discount_amount = $number_of_sets_of_three * 2 * ($total_price / $cart_items_count);

    //Apply the discount to the cart
    $cart->add_fee(__('Discount', 'woocommerce'), -$discount_amount);
}
?>