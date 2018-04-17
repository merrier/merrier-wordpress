<?php
// Require theme functions
require get_stylesheet_directory() . '/functions-theme.php';
// Customize your functions

// 解决找回密码链接无效问题
function reset_password_message( $message, $key ) {
    if ( strpos($_POST['user_login'], '@') ) {
        $user_data = get_user_by('email', trim($_POST['user_login']));
    } else {
        $login = trim($_POST['user_login']);
        $user_data = get_user_by('login', $login);
    }
    $user_login = $user_data->user_login;
    $msg = __('有人要求重设如下帐号的密码：'). "\r\n\r\n";
    $msg .= network_site_url() . "\r\n\r\n";
    $msg .= sprintf(__('用户名：%s'), $user_login) . "\r\n\r\n";
    $msg .= __('若这不是您本人要求的，请忽略本邮件，一切如常。') . "\r\n\r\n";
    $msg .= __('要重置您的密码，请打开下面的链接：'). "\r\n\r\n";
    $msg .= network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') ;
    return $msg;
}
add_filter('retrieve_password_message', reset_password_message, null, 2);

if ( function_exists('add_theme_support') )add_theme_support('post-thumbnails');

add_filter('script_loader_src', 'agnostic_script_loader_src', 20,2);
function agnostic_script_loader_src($src, $handle) {
    return preg_replace('/^(http|https):/', '', $src);
}

add_filter('style_loader_src', 'agnostic_style_loader_src', 20,2);
function agnostic_style_loader_src($src, $handle) {
    return preg_replace('/^(http|https):/', '', $src);
}
