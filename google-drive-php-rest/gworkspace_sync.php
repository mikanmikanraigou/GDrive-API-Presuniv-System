<?php
include_once 'GoogleAPIHelper.php';

if (isset($_GET['code'])) {
    $oauth2_code = $_GET['code']; 
    $GHelper = new GapiHelper();
    // unset($_SESSION['google_data']);
    // unset($_SESSION['list_users']);

    //get session code
    // if (empty($_SESSION['google_data'])) {
         $data = null;
         if (isset($_SESSION['google_data'])) {
            $data = $_SESSION['google_data'];
         } else {
            $data = $GHelper->GetGapiToken($oauth2_code);
            $_SESSION['google_data'] = $data;
         }  

        if (!empty($data)) {

            $result = $GHelper->GetGWorkspace_ListUser($data, 'heri.kurniawan%40student.president.ac.id');
            var_dump($result);
            echo '<hr>';
            $result = $GHelper->GetGWorkspace_ListUser($data, 'galang.izatul%president.ac.id');
            var_dump($result);
            echo '<hr>';

            $user_data = array(
                'name' => [
                    'famiy_name' => 'Pekerti',
                    'given_name' => 'Budi',
                    'full_Name' => 'Budi Pekerti'
                ],
                'password' => 'presuniv1234',
                'primary_email' => 'bayu.budi.pekerti@student.president.ac.id',
                'orgUnitPath' => '/Student/ACC/2021',
            );
            $result = $GHelper->GetGWorkspace_CreateUser($data, $user_data);
            var_dump($result);
            $_SESSION['list_users'] = $data;
            $result = $GHelper->GetGWorkspace_ListUser($data, 'heri.kurniawan%40student.president.ac.id');
            var_dump($result);
            echo '<hr>';
            
            // var_dump($_SESSION['list_users']);
        }
    // }
}

?>