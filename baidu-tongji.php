<?php
/*
 * Plugin Name: Baidu Tongji generator
 * Version: 1.0.2
 * Plugin URI: http://gochannel.org/blog
 * Description: This pulgin generates Baidu Tongji script for WordPress Blog.
 * Author: Haoqisir
 * Author URI: http://gochannel.org/blog
 */
 


 /*
 * Based on: Baidu tracker Generator 1.0.0(webbeast)
 * Copyright: 2011,2016  BSD
 */

function baidu_tongji_set_option($option_name, $option_value) {
    $baidu_tongji_options = get_option('baidu_tongji_options');
    $baidu_tongji_options [$option_name] = $option_value;
    update_option('baidu_tongji_options', $baidu_tongji_options);
}

function baidu_tongji_get_option($option_name) {

    $baidu_tongji_options = get_option('baidu_tongji_options');

    if (!$baidu_tongji_options || !array_key_exists($option_name, $baidu_tongji_options)) {

        $baidu_tongji_default_options = array();

        $baidu_tongji_default_options ["site_code"] = "";

        $baidu_tongji_default_options ['enable_tracker'] = true;

        $baidu_tongji_default_options ['track_adm_pages'] = true;

        add_option('baidu_tongji_options', $baidu_tongji_default_options, 'Settings for baidu tracker plugin');

        $result = $baidu_tongji_default_options [$option_name];
    } else {

        $result = $baidu_tongji_options [$option_name];
    }

    return $result;
}

function baidu_tongji_footer() {

    if (baidu_tongji_get_option('enable_tracker')) {
        $html = stripslashes(baidu_tongji_get_option('site_code'));
        echo $html;
    }
}

function baidu_tongji_Tip($msg) {

    return '<div class="updated"><p><strong>' . $msg . '</strong></p></div>';
}

function baidu_tongji_error($msg) {

    return '<div class="error settings-error"><p><strong>' . $msg . '</strong></p></div>';
}

function baidu_tongji_admin_html($options) {

    $enable_tracker = $options['enable_tracker'] ? ' checked="true"' : '';

    $track_adm_pages = $options['track_adm_pages'] ? ' checked="true"' : '';

    echo '<div class=wrap>';

    echo '<form method="post">';

    echo '<h2>设置</h2>';

    echo '<fieldset class="options" name="general"><legend>请复制统计代码粘贴到下面输入框中：</legend>';

    echo '<p></p>';

    echo '<textarea rows="5" class="large-text code" id="site_code"	name="site_code">' . stripslashes($options['site_code']) . '</textarea>';

    echo '<input type="checkbox" value="true" id="enable_tracker" name="enable_tracker"' . $enable_tracker . '>&nbsp;开启统计';

    echo '<p><input type="checkbox" value="true" id="track_adm_pages" name="track_adm_pages"' . $track_adm_pages . '>';

    echo '&nbsp;跟踪管理页面</p>';

    echo '<p class="submit"><input type="submit" value="保存设置" class="button-primary" name="Submit"></p>';

    echo '</fieldset>';

    echo '</form>';

    echo '</div>';
}

function baidu_tongji_options() {

    $code = trim($_POST ['site_code']);

    $submit = trim($_POST ['Submit']);
    if ($submit) {
        if ($code && preg_match("@hm.baidu.com@i", $code)) {
            baidu_tongji_set_option('site_code', $code);
            echo baidu_tongji_Tip("设置成功！");
        } else {
            echo baidu_tongji_error("统计代码格式不正确，请重新输入!");
        }

        if (isset($_POST ['track_adm_pages'])) {
            baidu_tongji_set_option('track_adm_pages', true);
        } else {
            baidu_tongji_set_option('track_adm_pages', false);
        }
        if (isset($_POST ['enable_tracker'])) {
            baidu_tongji_set_option('enable_tracker', true);
        } else {
            baidu_tongji_set_option('enable_tracker', false);
        }
    }

    baidu_tongji_admin_html(get_option('baidu_tongji_options'));

    if (baidu_tongji_get_option('track_adm_pages')) {
        add_action('admin_footer', 'baidu_tongji_footer');
    } else {
        remove_action('admin_footer', 'baidu_tongji_footer');
    }
}

 
function baidu_tongji_admin() {
    if (function_exists('add_options_page')) {
        add_options_page('百度统计代码安装卸载', '百度统计功能', 8, 'baidu_tongji', 'baidu_tongji_options');
    }
}

add_action('admin_menu', 'baidu_tongji_admin');

if (baidu_tongji_get_option('enable_tracker')) {
    add_action('wp_footer', 'baidu_tongji_footer');
}
