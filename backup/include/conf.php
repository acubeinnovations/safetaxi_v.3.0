<?php
// prevent execution of this page by direct call by browser
if ( !defined('CHECK_INCLUDED') ){
    exit();
}
date_default_timezone_set('Asia/Kolkata');
// Mysql Configuration Constants

define('MYSQL_USERNAME', 'safe789_demo');
define('MYSQL_PASSWORD', 'acubedemo');
define('MYSQL_HOST', 'localhost');
define('MYSQL_DB_NAME', 'safe789_demo​');

define('LOG_LOCATION',0);
define('LOG_LOCATION_AND_TRIP_DETAILS', 1);
define('NO_NEW_TRIP', 2);
define('NEW_INSTANT_TRIP', 3);
define('NEW_FUTURE_TRIP', 5);
define('CANCEL_TRIP', 7);
define('UPDATE_FUTURE_TRIP', 11);

define('TRIP_NOTIFICATION_REJECTED', 0);
define('TRIP_NOTIFICATION_ACCEPTED', 1);
define('TRIP_NOTIFICATION_TIME_OUT', 2);

define('TRIP_ERROR',0);
define('TRIP_AWARDED', 1);
define('TRIP_REGRET', 2);
define('TRIP_REJECTED', 3);
define('TRIP_TIME_OUT', 4);

define('NO_ERROR',0);
define('ERROR',1);

define('TRIP_STATUS_BOOKED', 1);
define('TRIP_STATUS_ACCEPTED', 2);
define('TRIP_STATUS_ON_TRIP', 3);
define('TRIP_STATUS_COMPLETED', 4);
define('TRIP_STATUS_CANCELLED', 5);
define('TRIP_STATUS_DRIVER_CANCELLED', 6);
define('TRIP_STATUS_CUSTOMER_CANCELLED', 7);

define('INSTANT_TRIP', 1);
define('FUTURE_TRIP', 2);

define('DRIVER_STATUS_ACTIVE', 1);
define('DRIVER_STATUS_ENGAGED', 2);
define('DRIVER_STATUS_DISMISSED', 3);
define('DRIVER_STATUS_SUSPENDED', 4);

define('NOTIFICATION_TYPE_NEW_TRIP', 1);
define('NOTIFICATION_TYPE_TRIP_CANCELLED', 2);
define('NOTIFICATION_TYPE_TRIP_UPDATE', 3);
define('NOTIFICATION_TYPE_PAYMENT', 4);

define('NOTIFICATION_STATUS_NOTIFIED', 1);
define('NOTIFICATION_STATUS_RESPONDED', 2);
define('NOTIFICATION_STATUS_EXPIRED', 3);

define('NOTIFICATION_VIEWED_STATUS', 1);
define('NOTIFICATION_NOT_VIEWED_STATUS', 2);

define('gINVALID', -1);
?>
