<?php

use App\Helpers\FirebaseService;
function headers()
{
    //   return array(
    //       "Authorization: key= AAAA1qWIWDQ:APA91bF3q804moQmnPOhCNNpCKxCFoL7JjNtXKkTbnReu5YzKGw5Cgz0yMLy-8qP4vtIAltkRws566jEIXfCKf6GUGHpeSXzAKQomzGrrDmtp5G2CisKn1rL3HcH9fNQbcStYy91njID",
    //       "Content-Type: application/json",
    //       "project_id: rida-d9ecf"
    //   );

    // );
    return array(
        "Authorization: key=" . config('firebase.FIREBASE_AUTH_KEY'),
        "Content-Type: application/json",
        "project_id: " . config('firebase.FIREBASE_PROJECT_ID')

    );
}

function prepare_notification($user, $title, $description, $booking_status, $ntype = 'service', $record_id = '', $record_type = '', $image_url = '')
{

   
    
    $notification_id = time();

    if (!empty($user->firebase_user_key)) {

        $dbNodeData = [
            "title" => $title,
            "description" => $description,
            "notificationType" => $ntype,
            'createdDate' => gmdate("d-m-Y H:i:s", $notification_id),
            "bookingId" => (string) $record_id,
            "record_type" => $record_type,
            "url" => "",
            "imageURL" => $image_url,
            "read" => "0",
            "seen" => "0",
            "booking_status" => $booking_status
        ];

        //$notification_data["Nottifications/" . $user->firebase_user_key . "/" . $notification_id] = $dbNodeData;

        $pathKey = "/Nottifications-Disraption/" . $user->firebase_user_key . "/" . $notification_id;

        // Firebase service init
        $firebaseService = new FirebaseService();

        // Add the notification to the database
        $firebaseService->updateData($pathKey, $dbNodeData);
    }
    

    if (!empty($user->fcm_token)) {
        $datasent = send_single_notification(
            $user->fcm_token,
            [
                "title" => $title,
                "body" => $description,
                "icon" => 'myicon',
                "sound" => 'default',
                "click_action" => "EcomNotification"
            ],
            [
                "type" => $ntype,
                "notificationID" => $notification_id,
                "serviceId" => (string) $record_id,
                "booking_status" => $booking_status,
                "imageURL" => $image_url,
                
            ]
        );
    }
}


function prepare_notificationBulk($firebase_user_keyIds, $fcm_tokens, $title, $description, $ntype = 'service', $record_id = '', $record_type = '', $image_url = '')
{


    $notification_id = time();

    

    if ($fcm_tokens) {

       send_multicast_notification($fcm_tokens, [
            "title" => $title,
            "body" => $description,
            "icon" => 'myicon',
            "sound" => 'default',
            "click_action" => "EcomNotification"
        ], [
            "type" => $ntype,
            "notificationID" => $notification_id,
            "carId" => (string) $record_id,
            "imageURL" => $image_url,
        ]);
    }


    if ($firebase_user_keyIds) {

        $dbNodeData = [
            "title" => $title,
            "description" => $description,
            "notificationType" => $ntype,
            'createdDate' => gmdate("d-m-Y H:i:s", $notification_id),
            "carId" => (string) $record_id,
            "record_type" => $record_type,
            "url" => "",
            "imageURL" => $image_url,
            "read" => "0",
            "seen" => "0",
        ];

        //$notification_data["Nottifications/" . $user->firebase_user_key . "/" . $notification_id] = $dbNodeData;

        //$pathKey = "/Nottifications-Disraption/" . $user->firebase_user_key . "/" . $notification_id;

        // Firebase service init
        $firebaseService = new FirebaseService();

        $dbBulkData = [];

        foreach ($firebase_user_keyIds as $firebase_user_keyId) {
            $dbBulkData[$firebase_user_keyId . "/" . $notification_id] = $dbNodeData;
        }
        
        return $firebaseService->updateData("/Nottifications-Disraption/", $dbBulkData);

        

        // $dbStore = [
        //     "updates" => $dbBulkData
        // ];

        // // Add the notification to the database
        // //$firebaseService->bulkUpdate($dbStore);

        // // loop through the firebase_user_keyIds and update the notification
        // foreach ($firebase_user_keyIds as $firebase_user_keyId) {
        //     $pathKey = "/Nottifications-Disraption/" . $firebase_user_keyId . "/" . $notification_id;
        //     $firebaseService->updateData($pathKey, $dbNodeData);
        // }
    }
}



function prepare_notificationBulkTesting($firebase_user_keyIds, $fcm_tokens, $title, $description, $ntype = 'service', $record_id = '', $record_type = '', $image_url = '')
{


    $notification_id = time();

    

    // if ($fcm_tokens) {

    //     return send_multicast_notification($fcm_tokens, [
    //         "title" => $title,
    //         "body" => $description,
    //         "icon" => 'myicon',
    //         "sound" => 'default',
    //         "click_action" => "EcomNotification"
    //     ], [
    //         "type" => $ntype,
    //         "notificationID" => $notification_id,
    //         "carId" => (string) $record_id,
    //         "imageURL" => $image_url,
    //     ]);
    // }


    if ($firebase_user_keyIds) {

        $dbNodeData = [
            "title" => $title,
            "description" => $description,
            "notificationType" => $ntype,
            'createdDate' => gmdate("d-m-Y H:i:s", $notification_id),
            "carId" => (string) $record_id,
            "record_type" => $record_type,
            "url" => "",
            "imageURL" => $image_url,
            "read" => "0",
            "seen" => "0",
        ];

        //$notification_data["Nottifications/" . $user->firebase_user_key . "/" . $notification_id] = $dbNodeData;

        //$pathKey = "/Nottifications-Disraption/" . $user->firebase_user_key . "/" . $notification_id;

        // Firebase service init
        $firebaseService = new FirebaseService();

        $dbBulkData = [];

        foreach ($firebase_user_keyIds as $firebase_user_keyId) {
            $dbBulkData[$firebase_user_keyId . "/" . $notification_id] = $dbNodeData;
        }
        
        return $firebaseService->updateData("/Nottifications-Disraption/", $dbBulkData);

        $dbStore = [
            "updates" => $dbBulkData
        ];

        // Add the notification to the database
        // @todo fix the bulk update
        //$firebaseService->bulkUpdate($dbStore);

        // loop through the firebase_user_keyIds and update the notification
        foreach ($firebase_user_keyIds as $firebase_user_keyId) {
            $pathKey = "/Nottifications-Disraption/" . $firebase_user_keyId . "/" . $notification_id;
            $firebaseService->updateData($pathKey, $dbNodeData);
        }
    }
}

// function send_single_notification($fcm_token, $notification, $data, $priority = 'high')
// {
//     $project_id = config('firebase.FIREBASE_PROJECT_ID');
//     $fields = array(
//         'notification' => $notification,
//         'data' => $data,
//         'content_available' => true,
//         'priority' =>  $priority,
//         'to' => $fcm_token
//     );

//     if ($curl_response =  send(json_encode($fields), "https://fcm.googleapis.com/v1/projects/$project_id/messages:send")) {
//         dd(json_decode($curl_response));
//     } else
//         return false;
// }


 function getAccessToken()
{

    //$jsonKey = json_decode(file_get_contents(config('firebase.FIREBASE_CREDENTIALS')), true);
    try {
        // Load the service account credentials JSON file
        $jsonKey = json_decode(file_get_contents(base_path(config('firebase.FIREBASE_CREDENTIALS'))),true);

        $now = time();
$token = [
    'iss' => $jsonKey['client_email'], // issuer
    'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
    'aud' => 'https://oauth2.googleapis.com/token',
    'exp' => $now + 3600, // Token expiration time, set to 1 hour
    'iat' => $now // Token issued at time
];

// Encode the JWT
$jwtHeader = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
$jwtHeader = base64_encode($jwtHeader);

$jwtPayload = json_encode($token);
$jwtPayload = base64_encode($jwtPayload);

// Sign the JWT using the private key
openssl_sign($jwtHeader . '.' . $jwtPayload, $signature, $jsonKey['private_key'], 'sha256');
$jwtSignature = base64_encode($signature);

// Concatenate the three parts to create the final JWT
$assertion = $jwtHeader . '.' . $jwtPayload . '.' . $jwtSignature;
        
        // Prepare the cURL request
        // Now make the request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
    'assertion' => $assertion, // Use the generated JWT as the assertion
]));

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded',
]);

$response = curl_exec($ch);


        if (curl_errno($ch)) {
            // Handle cURL error
            curl_close($ch);
            return null;
        }

        curl_close($ch);

        $authToken = json_decode($response, true);

        return $authToken['access_token'];
    } catch (Exception $e) {
        // Handle exceptions, e.g., log errors or throw a custom exception
        return null; // Or handle differently based on your application's needs
    }
}
function send_single_notification_working($fcm_token, $notification, $data, $priority = 'high')
{
    // Set your project ID and access token
    $project_id = config('firebase.FIREBASE_PROJECT_ID');
    $access_token =getAccessToken(); // You'll need to generate this as described below
    // Set the v1 endpoint
    $url = "https://fcm.googleapis.com/v1/projects/$project_id/messages:send";
    //dd($notification['title']);
    // Create the message payload
    $message = [
        'message' => [
            'token' => $fcm_token,
            'notification' => $notification,
            'data' => $data,
            'android' => [
                'priority' => $priority
            ],
            'apns' => [
                'headers' => [
                    'apns-priority' => $priority == 'high' ? '10' : '5',
                ]
            ],
        ]
    ];

    // Set the headers for the request
    $headers = [
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/json'
    ];

    // Make the request
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    //curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(['message' => $message]));
    
    $payload = json_encode([
            'message' => [
                'token' => $fcm_token,
                'notification' => [
                                "title" => $notification['title'],
                                "body" => $notification['body']
                            ],
                'data' =>convert_all_elements_to_string_fcm($data),
            ],
        ]);
        
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
    $curl_response = curl_exec($curl);
    
    curl_close($curl);

    if ($curl_response) {
        return json_decode($curl_response);
    } else {
        return false;
    }
}

function send_single_notification($fcm_token, $notification, $data, $priority = 'high')
{
    // Set your project ID and access token
    $project_id = config('firebase.FIREBASE_PROJECT_ID');
    $access_token = getAccessToken(); // You'll need to generate this as described below

    // Set the v1 endpoint
    $url = "https://fcm.googleapis.com/v1/projects/$project_id/messages:send";

    // Create the message payload
    $message = [
        'message' => [
            'token' => $fcm_token,
            'notification' => [
                'title' => $notification['title'],
                'body' => $notification['body'],
            ],
            'data' => convert_all_elements_to_string_fcm($data),
            'android' => [
                'priority' => $priority,
            ],
            'apns' => [
                'headers' => [
                    'apns-priority' => $priority == 'high' ? '10' : '5',
                ],
                'payload' => [
                    'aps' => [
                        'content-available' => 1,
                    ],
                ],
            ],
        ],
    ];

    // Set the headers for the request
    $headers = [
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/json',
    ];

    // Make the request
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $payload = json_encode($message);

    curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
    $curl_response = curl_exec($curl);

    curl_close($curl);

    if ($curl_response) {
        return json_decode($curl_response);
    } else {
        return false;
    }
}


function convert_all_elements_to_string_fcm($data = null, $emptyArrayShouldBeObject = false)
{
    if ($data != null) {
        array_walk_recursive($data, function (&$value, $key) use ($emptyArrayShouldBeObject) {
            if (!is_object($value)) {
                if ($value) {
                    $value = (string) $value;
                } else {
                    $value = (string) $value;
                }
            } else {
                $json = json_encode($value);
                $array = json_decode($json, true);

                array_walk_recursive($array, function (&$obj_val, $obj_key) use ($emptyArrayShouldBeObject) {
                    $obj_val = (string) $obj_val;
                });

                if (!empty($array)) {
                    $json = json_encode($array);
                    $value = json_decode($json);
                } else {
                    if ($emptyArrayShouldBeObject) {
                        $value = (object)[];
                    } else {
                        $value = [];
                    }
                }
            }
        });
    }
    return $data;
}
function send_multicast_notification($fcm_tokens, $notification, $data, $priority = 'high')
{
    $fields = array(
        'notification' => $notification,
        'data' => $data,
        'content_available' => true,
        'priority' =>  $priority,
        'registration_ids' => $fcm_tokens
    );

    if ($curl_response = send(json_encode($fields), "https://fcm.googleapis.com/fcm/send")) {
        return json_decode($curl_response);
    } else
        return false;
}

function send_notification($notification_key, $notification, $data, $priority = 'high')
{
    $fields = array(
        'notification' => $notification,
        'data' => $data,
        'content_available' => true,
        'priority' =>  $priority,
        'to' => $notification_key
    );

    if ($curl_response = send(json_encode($fields), "https://fcm.googleapis.com/fcm/send")) {
        return json_decode($curl_response);
    } else
        return false;
}

function send($fields,  $url = "https://fcm.googleapis.com/fcm/send", $headers = array())
{



    $headers = array_merge(headers(), $headers);

    $ch = curl_init();

    if (!$ch) {
        $curl_error = "Couldn't initialize a cURL handle";
        return false;
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

    $curl_response = curl_exec($ch);

    if (curl_errno($ch))
        $curl_error = curl_error($ch);

    if ($curl_response == FALSE) {
        return false;
    } else {
        $curl_info = curl_getinfo($ch);
        //printr($curl_info);
        curl_close($ch);
        return $curl_response;
    }
}


if (!function_exists('getUserId')) {
    function getUserId($access_token)
    {
        $user_id = 0;
        $user = \App\Models\User::where(['user_access_token' => $access_token])->where('user_access_token', '!=', '')->get();
        if ($user->count() > 0) {
            $user_id = $user->first()->id;
        }

        return $user_id;
    }
}
