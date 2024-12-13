<?php

$config['server_mode']                  = 'live'; //live

$config['site_name']                                    = env("APP_NAME",'LIveMarket');
$config['date_timezone']								= 'Asia/Dubai';
$config['datetime_format']								= 'M d, Y h:i A';
$config['date_format']									= 'M d, Y';
$config['date_format_excel']							= 'd/m/Y';
$config['default_currency_code']						= 'SAR';

$config['upload_bucket']						= 's3';//'s3';//s3 // @localStorage: comment this line if needed local storage
//$config['upload_bucket']						= 'public' // @localStorage: uncomment this line if needed local storage
$config['upload_path']              					= 'storage/';
$config['user_image_upload_dir']    					= 'users/';
$config['company']    					                = 'company/';
$config['category_image_upload_dir']    				= 'category/';
$config['food_category_image_upload_dir']    				= 'food_category/';
$config['deligates_upload_dir']                         = 'deligates/';
$config['facilities_upload_dir']                        = 'facilities/';
$config['product_image_upload_dir']    				    = 'products/';
$config['reservation_product_upload_dir']               = 'reservation_products/';
$config['post_image_upload_dir']    				    = 'posts/';
$config['banner_image_upload_dir']                      = 'banner_images/';
$config['service_image_upload_dir']                      = 'service_requests/';


// Tax
$config['tax_percentage']                               = 5;

//activity type
$config['activity_image_upload_dir']    				    = 'activity_type';

//order status
$config['order_status_pending']                                 = 0;
$config['order_status_accepted']                                = 1;
$config['order_payment_completed']                              = 2;
$config['order_status_ready_for_delivery']                      = 3;
$config['order_status_driver_accepted']                         = 4;
$config['order_status_dispatched']                              = 5;
$config['order_status_delivered']                               = 6;
$config['order_status_cancelled']                               = 10;
$config['order_status_returned']                               = 11;
$config['order_status_rejected']                               = 12;

//service status
$config['service_status_pending']                               = 0;
$config['service_status_rejected']                              = 11;
$config['service_quote_added']                                  = 1;
$config['service_quote_accepted']                               = 2;
$config['service_quote_rejected']                               = 10;
$config['service_location_added']                               = 3;
$config['service_on_the_way']                                   = 4;
$config['service_work_started']                                 = 5;
$config['service_work_completed']                               = 6;
$config['service_payment_completed']                            = 7;
$config['service_service_completed']                            = 8;

//wholesale status
$config['wholesale_status_pending']                               = 0;
$config['wholesale_status_rejected']                              = 11;
$config['wholesale_quote_added']                                  = 1;
$config['wholesale_quote_accepted']                               = 2;
$config['wholesale_quote_rejected']                               = 10;
$config['wholesale_on_the_way']                                   = 4;
$config['wholesale_completed']                                    = 5;
$config['wholesale_payment_completed']                            = 3;

//gym status
$config['gym_status_pending']                               = 0;
$config['gym_status_cancelled']                             = 10;
$config['gym_status_rejected']                              = 11;
$config['gym_status_completed']                             = 1;


//Chalet Status
$config['booking_status_waiting_for_confirmation']             = 0;
$config['booking_status_booking_confirmed']                    = 1;
$config['booking_status_wait_for_schedule']                    = 2;
$config['booking_status_reserved']                             = 3;
$config['booking_status_completed']                            = 4;
$config['booking_status_rejected']                             = 5;
$config['reservation_status_cancelled']                        = 6;

//deligate service status
$config['deligate_status_waiting_for_confirmation']             = 0;
$config['deligate_status_booking_confirmed']                    = 1;
$config['deligate_status_waiting_for_payment']                    = 2;
$config['deligate_status_payment_completed']                             = 3;
$config['deligate_status_on_the_way']                             = 4;
$config['deligate_status_delivered']                             = 5;
$config['deligate_status_cancelled']                             = 6;
$config['deligate_status_rejected']                             = 7;
$config['deligate_status_auto_rejected']                             = 11;
//table booking status
$config['table_booking_status_pending']                               = 0;
$config['table_booking_status_accepted']                              = 1;
$config['table_booking_status_rejected']                              = 11;
$config['table_booking_status_completed']                             = 4;

$config['driver_alloted_order_status_pending']             = 0;
$config['driver_alloted_order_status_accepted']            = 1;
$config['driver_alloted_order_status_rejected']            = 2;
$config['driver_alloted_order_status_no_response']         = 3;
$config['driver_alloted_order_status_completed']           = 4;

$config['order_prefix']                                 = 'LM-';
$config['product_image_width']              			= '1024';
$config['product_image_height']              			= '1024';

$config['wowza_key']                              = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5NTU3NzEwZS0yYzhlLTQ1MDgtOTEwOS1hZWMxNTEwODAxY2UiLCJqdGkiOiI1N2M3ZDk5MDRhMDYwY2ZlNGQ0NjBjYmI3ZTI2NGE3NTAwYzU1Y2FjMzdkNWI0MDI0YTg0Njk5NzQxYzAyMjZjYjY1ODlmZDc4YmJhZTczYyIsImlhdCI6MTY2MzA0MDA3MywibmJmIjoxNjYzMDQwMDczLCJleHAiOjIyOTQxOTIwNzMsInN1YiI6Ik9VLTRmMjQzYTM2LThjZTItNDcyYS04MDhlLWE3Njg2NDE4MzViOCJ9.s4TXFbAO1J-MqfxxT7Bw3x8Ohjm6tmPvcZemcs6whQIP1LHPb4BPcDVlqt8HnsGnpWgI0DMARmxpOHR1d43nOYAxgBekIgPZn59BHB8gb-ovKvdOkqXYu7u1olvxPfs0tpJ1w_ey-3oxaeVdLIbYtSiyvB8KALN90Xpy1ueSyhcAdtulfRlcwUj5cXZkaeMJleCujpU7X_NSvAHG1xjAKk0yd3Tt9bt4a71VpP7B8wpkaSsf1vQ_PQphfFgEG0xqPOeTxPPIUUIHLfC46vVDySh8Kgo0Hxm1ZXRB0futXf8h6bCvB3HPIOzmdmUUtrmK_XRfkARPYRF5yserjX7vJ8674fqMyusroIBRfErlw5aDHnh4VKlLuZAIlizYlnoTWdF1cFCntTnsTo_tso0LjAFP-eAShitrSAzsAnJvymsXjslIBQdPixtNY32f8srowxnFqXY52UHEfae1jmZk-6F5TjxU7n6dCjaIukVJ_uOmpIq9crhE2wB5jQVkgQHJWEQpSsQ2q1Mob4OWhTPHT6xCsce3R0vS4dnHfreLMF5jRFnugH9vUurwNul3miDMFjzSVhU788xudLAmCcIFnfbozms2KjeijstpiH77BCD8-NNZzXAlcJLAfpYZxyacQaEAseEPnCCxiZPTrB7ccxStVh6DXLMo8ewnXjEWWp8';
$config['wowza_token_name']                       ='api_token_v1';

$config['message_privacy']                        = [
    '1'     =>  '24 Hours',
    '7'     =>  '7 Days',
    '90'    =>  '90 Days',
    '999999' =>  'Off'
];

$config['report_user_problems']                        = [
    '1'    =>  'Nudity or sexual activity',
    '2'    =>  'Hate speach or symbols',
    '3'    =>  'Scam or fraud',
    '4'    =>  'Violence or dangerous organisations',
    '5'    =>  'Sale of illegal or regulated goods',
    '6'    =>  'Bullying or harassment',
    '7'    =>  'Pretending to be someone else',
];

//define('OWN_DELIGATE_ID', 2);
// define('COMERCIAL_CENTER_USER_TYPE_ID', 1);
// define('SERIVICE_PROVIDER_USER_TYPE_ID', 4);
// define('RESERVATION_USER_TYPE_ID', 2);
// define('INDIVIDUAL_USER_TYPE_ID', 3);
// define('WHOLESALER_USER_TYPE_ID', 5);
// define('DRIVER_USER_TYPE_ID', 6);
// define('GYM_ACTIVITY_TYPE_ID', 16);


// $config['limit_distance'] = 60; //km
// $config['limit_distance_crossby'] = 20; //km

// $config['paytab_profile_id'] = 104462;
// $config['paytab_auth_key'] = 'SZJN6NDLGW-JHBZHHKWKG-BMRJZHTTT2';

// define('PAYMENT_TYPE_CARD', 1);
// define('PAYMENT_TYPE_CASH', 2);
// define('PAYMENT_TYPE_WALLET', 3);
// define('PAYMENT_TYPE_APPLE_PAY', 4);

// $config['order_reject_count'] = 5;
// $config['order_reject_time_second'] = 300;

return $config;
?>
