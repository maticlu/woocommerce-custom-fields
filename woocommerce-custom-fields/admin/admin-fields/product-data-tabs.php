<?php

add_filter("woocommerce_product_data_tabs", "wccf_product_data_tabs", 10, 1);

function wccf_product_data_tabs($woocommerce_product_data_tabs)
{
    $woocommerce_product_data_tabs["test"] = array(
        "label" => "Juhuuuu",
        "target" => "okay_kaj_je_to",
        "class" => array(),
        "priority" => 10
    );
    return $woocommerce_product_data_tabs;
}

add_action("woocommerce_product_data_panels", function () {

    echo '<div id="okay_kaj_je_to" class="panel">';

    echo '</div>';
});