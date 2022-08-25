<?php
include_once 'GoogleAPIHelper.php';
$oauth2_code = $_GET['code']; 

if (isset($oauth2_code)) {
    $GHelper = new GapiHelper();
    unset($_SESSION['google_data']);
    unset($_SESSION['list_users']);

    //get session code
    if (empty($_SESSION['google_data'])) {
        $data = $GHelper->GetGapiToken($oauth2_code);
        $_SESSION['google_data'] = $data;

        if (!empty($_SESSION['google_data'])) {
            $data = $GHelper->GetGWorkspace_ListUser($data['access_token']);
            $_SESSION['list_users'] = $data;
            
            var_dump($_SESSION['list_users']);
        }
    }

    // //get session code
    // if (empty($_SESSION['google_data'])) {
    //     $data = $GHelper->GetGapiToken($oauth2_code);
    //     // $access_token = $data['access_token'];
    //     // $expires = $data['access_token'];
    //     $_SESSION['google_data'] = $data;
    //     // $_SESSION['google_expires_token'] = $expires;
    // }

    // if (!empty($_SESSION['google_data'])) {
    //     var_dump($_SESSION['google_data']);

    //     // var_dump($_SESSION['google_expires_token']);
    // }
}

?>