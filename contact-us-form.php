<?php

/**
 *Plugin Name: Contact Form Plugin
 *Description: This plugin enables user to use this form to contact the IFAS team for any queries
 **/

register_activation_hook(__FILE__, 'on_activate');

function on_activate()
{
    global $wpdb;
    $create_table_query = "
        CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}queries` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `case_number` BIGINT DEFAULT NULL,
            `email` varchar(255) NOT NULL,
            `phone_number` text NOT NULL,
            `query` text NOT NULL,
            `author_IP` text,
            `time` datetime,
            primary key (id)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($create_table_query);
}

function contact_form()
{
    $content = '';

    /* Update this link to redirect to the URL after the form is submitted */
    $content .= '<form method="post" action="http://blogs.ifas.ufl.edu/contact/">';

    $content .= '<input type="text" required name="full_name" placeholder="Full Name" />';
    $content .= '<br />';

    $content .= '<input type="text" pattern="^\d{8}" required name="uf_id" placeholder="UF ID" />';
    $content .= '<br />';

    $content .= '<input type="email" required name="email_address" placeholder="Email Address" />';
    $content .= '<br />';

    $content .= '<input type="tel" pattern="^\+?\d{10,13}" required name="phone_number" placeholder="Phone Number" />';
    $content .= '<br />';

    $content .= '<textarea name="query" required placeholder="Please enter your query"></textarea>';
    $content .= '<br />';

    $content .= '<input type="submit" name="submit_form" value="SUBMIT" />';

    $content .= '</form>';

    return $content;
}
add_shortcode('contact_form', 'contact_form');


function set_html_content_type()
{
    return 'text/html';
}

function form_capture()
{
    global $wpdb;
    $tablename = $wpdb->prefix .'queries';
    $time = current_time('mysql');
    $case_number = wp_rand(1,999999);
    if (array_key_exists('submit_form', $_POST)) {
        $to = "webteam@ifas.ufl.edu";
        $subject = "Case Number: ".$case_number ." Inquiry form recieved";
        $body = '';
        $body .= 'Name: ' . $_POST['full_name'] . ' <br /> ';
        $body .= 'UFID: ' . $_POST['uf_id'] . ' <br /> ';
        $body .= 'Email: ' . $_POST['email_address'] . ' <br /> ';
        $body .= 'Phone: ' . $_POST['phone_number'] . ' <br /> ';
        $body .= 'Query: ' . $_POST['query'] . ' <br /> ';

        $headers = array('MIME-Version: 1.0','Content-Type: text/html; charset=UTF-8', 'Cc:' . $_POST['email_address']);
        
        add_filter('wp_mail_content_type', 'set_html_content_type');

        wp_mail($to, $subject, $body, $headers);

        remove_filter('wp_mail_content_type', 'set_html_content_type');

        $data = array(
            'name' => $_POST['full_name'],
            'case_number' => $case_number,
            'email' => $_POST['email_address'],
            'phone_number' => $_POST['phone_number'],
            'query' => $_POST['query'],
            'author_IP' => $_SERVER['REMOTE_ADDR'],
            'time' => $time
        );

        $wpdb->insert($tablename, $data);
    }
}
add_action('wp_head', 'form_capture');
