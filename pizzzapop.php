<?php
/*
Plugin Name: Pizza Popup
Description: Show Popup to subscribe then show coupon
Version: 0.1
Author: Abderrahim OUAKKI
Author URI: https://www.freelancer.com/u/abderrahimouakki.html
*/

if (!defined('RC_TC_BASE_FILE'))
    define('RC_TC_BASE_FILE', __FILE__);
if (!defined('RC_TC_BASE_DIR'))
    define('RC_TC_BASE_DIR', dirname(RC_TC_BASE_FILE));
if (!defined('RC_TC_PLUGIN_URL'))
    define('RC_TC_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * popup template using jqueryUI modal
 */
function insert_popup()
{
    $options = get_option('wppp_settings');


    echo '';
    echo '';
    echo '<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">';
    echo "<link rel='stylesheet' href='" . RC_TC_PLUGIN_URL . "/assets/style.css'" . " > ";
    echo '<link rel="stylesheet" href="https://jqueryui.com/resources/demos/style.css">';
    echo '';
    echo '';
    echo '';
    echo '';
    echo '<div id="dialog-message" title="Special Offer for You !" style="display: none;">';
    echo '';
    echo '<div id="subscribemsg">';
    echo '<p style="text-align: center">Click and get 20% discount</p>';
    echo '<p style="text-align: center ;color : rgb(119, 119, 119); font-size:12px;">Follow us to get your coupon';
    echo 'code </p>';
    echo '';
    echo '<div class="social" style="text-align: center">';
    echo '<span class="twitter">';
    if ($options['wppp_text_field_3']) {
        echo "<a href='" . $options['wppp_text_field_3'] . "'";
    } else {

        echo "<a href='https://twitter.com/mypizza_shop'";
    }
    echo 'class="twitter-follow-button" data-show-count="false"></a>';
    echo '</span>';
    echo '<span class="Facebook">';
    if ($options['wppp_text_field_3']) {
        echo "<div class='fb-like' data-href='".$options['wppp_text_field_2']."' data-layout='button_count'";
    } else {

        echo '<div class="fb-like" data-href="https://web.facebook.com/mypizzashopus/" data-layout="button_count"';
    }
    echo 'data-action="like" data-size="small" data-show-faces="true" data-share="false"></div>';
    echo '';
    echo '<p style="font-size : 16px; text-align:center; margin:5px;">OR</p>';
    echo '<p style="text-align: center ;color : rgb(119, 119, 119); font-size:12px;">Subscribe to get your coupon';
    echo 'code </p>';
    echo '';
    echo '<center>';
    echo '<form action="" method="post" id="sendyform">';
    echo '<input id="emailsendy" class="style-1" name="email" type="email"';
    echo 'placeholder="insert your email please " required>';
    echo "<input id='sendyurl' name='sendyurl' type='hidden' value='" . RC_TC_PLUGIN_URL . "subscribe.php'>";
    echo '<div class="sendyerror" style="display: none">';
    echo '<strong>Error :</strong>';
    echo '<span class="sendyerrormsg"></span>';
    echo '</div>';
    echo '<br>';
    echo '<input id="subscribesendy" type="submit" class="ui-button ui-corner-all ui-widget" value="Subscribe">';
    echo '</form>';
    echo '</center>';
    echo '</div>';
    echo '';
    echo '</div>';
    echo '<div id="couponmsg" class="coupon"  style="display: none;">';
    echo '<p style="text-align: center ;color : rgb(119, 119, 119); font-size:12px;">Your discount coupon of -20% </p>';
    echo "<strong>" . $options['wppp_text_field_1'] . "</strong>";
    echo '</div>';
    echo '';
    echo '';
    echo '';
    echo '';
    echo '<div id="fb-root"></div>';
    echo '';
    echo '</div>';
    echo '<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>';
    echo '<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>';
    echo "<script type='text/javascript' src=" . RC_TC_PLUGIN_URL . "assets/jquerycookies.js></script>";
    echo '';
    echo '<script type="text/javascript" src="https://apis.google.com/js/platform.js" defer async>';
    echo '{';
    echo "lang: 'en'";
    echo '}';
    echo '</script>';
    echo '<script type="text/javascript" src="//platform.twitter.com/widgets.js" charset="utf-8" id="twitterjs" async></script>';
    echo '';
    echo '';

    wp_enqueue_script('app', RC_TC_PLUGIN_URL . "/assets/app.js", array('jquery'), '1.0', true);

    wp_localize_script('app', 'ajaxurl', admin_url('admin-ajax.php'));


}

add_action('get_footer', 'insert_popup');


/**
 * Ajax call
 */

add_action('wp_ajax_subscribe_to_sendy', 'subscribe_to_sendy');
add_action('wp_ajax_nopriv_mon_action', 'subscribe_to_sendy');

function subscribe_to_sendy()
{

    $options = get_option('wppp_settings');

//------------------- Edit here --------------------//
    $sendy_url = $options['wppp_text_field_0'];
    $list = $options['wppp_text_field_4'];
//------------------ /Edit here --------------------//

//--------------------------------------------------//
//POST variables
    $email = $_POST['email'];

//subscribe
    $postdata = http_build_query(
        array(
            'email' => $email,
            'list' => $list,
            'boolean' => 'true'
        )
    );
    $opts = array('http' => array('method' => 'POST', 'header' => 'Content-type: application/x-www-form-urlencoded', 'content' => $postdata));
    $context = stream_context_create($opts);
    $result = file_get_contents($sendy_url . '/subscribe.php', false, $context);
//--------------------------------------------------//

    echo $result;
    die();
}


/**
 * Admin section
 *
 */


add_action('admin_menu', 'wppp_add_admin_menu');
add_action('admin_init', 'wppp_settings_init');


function wppp_add_admin_menu()
{

    add_menu_page('pizzapop', 'Pizza PopUp', 'manage_options', 'pizzapop', 'wppp_options_page');

}


function wppp_settings_init()
{

    register_setting('pluginPage', 'wppp_settings');

    add_settings_section(
        'wppp_pluginPage_section',
        __(' ', 'wppp'),
        'wppp_settings_section_callback',
        'pluginPage'
    );


    add_settings_field(
        'wppp_text_field_0',
        __('Sendy installation URL : ', 'wppp'),
        'wppp_text_field_0_render',
        'pluginPage',
        'wppp_pluginPage_section'
    );

    add_settings_field(
        'wppp_text_field_4',
        __('Sendy Mailist ID : ', 'wppp'),
        'wppp_text_field_4_render',
        'pluginPage',
        'wppp_pluginPage_section'
    );


    add_settings_field(
        'wppp_text_field_1',
        __('Coupon code : ', 'wppp'),
        'wppp_text_field_1_render',
        'pluginPage',
        'wppp_pluginPage_section'
    );

    add_settings_field(
        'wppp_text_field_2',
        __('Facebook page URL :', 'wppp'),
        'wppp_text_field_2_render',
        'pluginPage',
        'wppp_pluginPage_section'
    );

    add_settings_field(
        'wppp_text_field_3',
        __('Twitter account handle :', 'wppp'),
        'wppp_text_field_3_render',
        'pluginPage',
        'wppp_pluginPage_section'
    );


}


function wppp_text_field_0_render()
{

    $options = get_option('wppp_settings');
    ?>
    <input type='text' placeholder="URL to your sendy installation" class="regular-text code"
           name='wppp_settings[wppp_text_field_0]' value='<?php echo $options['wppp_text_field_0']; ?>'>
    <?php

}


function wppp_text_field_4_render()
{

    $options = get_option('wppp_settings');
    ?>
    <input type='text' placeholder="id of your Sendy MailList " class="regular-text code"
           name='wppp_settings[wppp_text_field_4]' value='<?php echo $options['wppp_text_field_4']; ?>'>
    <?php

}


function wppp_text_field_1_render()
{

    $options = get_option('wppp_settings');
    ?>
    <input type='text' placeholder="coupon code to display to the user" class="regular-text code"
           class="regular-text code" name='wppp_settings[wppp_text_field_1]'
           value='<?php echo $options['wppp_text_field_1']; ?>'>
    <?php

}


function wppp_text_field_2_render()
{

    $options = get_option('wppp_settings');
    ?>
    <input type='text' placeholder="https://www.facebook.com/your-page" class="regular-text code"
           name='wppp_settings[wppp_text_field_2]' value='<?php echo $options['wppp_text_field_2']; ?>'>
    <?php

}


function wppp_text_field_3_render()
{

    $options = get_option('wppp_settings');
    ?>
    <input type='text' placeholder="https://www.twitter.com/your-handle" class="regular-text code"
           name='wppp_settings[wppp_text_field_3]' value='<?php echo $options['wppp_text_field_3']; ?>'>
    <?php

}


function wppp_settings_section_callback()
{

    echo __('Setting page for popupPizza', 'wppp');

}


function wppp_options_page()
{

    ?>
    <form action='options.php' method='post'>

        <h2>Pizza PopUp Settings</h2>

        <?php
        settings_fields('pluginPage');
        do_settings_sections('pluginPage');
        submit_button();
        ?>

    </form>
    <?php

}


