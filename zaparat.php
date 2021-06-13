<?php

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Invalid request.' );
}

// [aparat id="iybdS"]
// [aparat id="iybdS" width="600" height="450" style="margin: 15px; padding: 7px"]

function zaparat($atts)
{
    $param = array(
        'id' => '',
        'width' => '100%',
        'height' => 450,
        'style' => 'margin: 15px 1px 10px 1px;'
    );
    $servertype = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http');
    extract( shortcode_atts( $param , $atts ) );

    return "<center style='{$style}'><iframe src='".$servertype."://www.aparat.com/video/video/embed/videohash/{$id}/vt/frame' width='{$width}' height='{$height}' allowfullscreen='true' style='border:none!important'></iframe></center>";
}


function aparat_editor_btn($buttons)
{
    array_push($buttons, "separator", "aparat_shortcode");
    return $buttons;
}


function aparat_shortcode_register($plugin_array)
{
    $plugin_array['aparat_shortcode'] = plugins_url('tinyMCE/editor_plugin.js', __FILE__);
    return $plugin_array;
}

add_filter('mce_buttons', 'aparat_editor_btn', 0);
add_filter('mce_external_plugins', "aparat_shortcode_register");
add_shortcode( 'zaparat', 'zaparat' );

?>