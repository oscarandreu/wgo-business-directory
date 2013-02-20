<?php

function thankyou_page_function($atts) {
    global $wpdb;

    if ($_GET['subid'] != "") {
        if ($_GET['sc'] == 1) {
            //MARK SUBMISSION ACTIVE AND DISPLAY THANK YOU MESSAGE
            $id = $_GET['subid'];
            $wpdb->update($wpdb->prefix . 'wgo_listings', array('active' => 1), array('id' => $id));
            $return.="<p>" . get_option('wgo_thank_you_message') . "</p>";
        } else {
            //LEAVE SUBMISSION INACTIVE AND DISPLAY BAIL MESSAGE
            $return.="<p>" . get_option('wgo_bail_message') . "</p>";
        }
    }

    return $return;
}