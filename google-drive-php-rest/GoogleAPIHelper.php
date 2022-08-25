<?php
    include_once 'config.php';
    /**
     * Helper to call API using oauth code or API token 
     */
    class GapiHelper
    {
        /**
         * Get Token for specified Google API 
         */
        function GetGapiToken($scopes_oauth_code){
            $cURLPOST = array(
                "client_id" => OAUTH_CLIENT_ID,
                "client_secret" => OAUTH_CLIENT_SECRET,
                "code" => $scopes_oauth_code,
                "grant_type" => "authorization_code",
                "redirect_uri" => REDIRECT_URI2,
            );

            $OauthToken = curl_init();
            curl_setopt($OauthToken, CURLOPT_URL, OAUTH_TOKEN_URI);
            curl_setopt($OauthToken, CURLOPT_POSTFIELDS, http_build_query($cURLPOST));
            curl_setopt($OauthToken, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/x-www-form-urlencoded"
            )); 
            curl_setopt($OauthToken, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($OauthToken, CURLOPT_POST, 1);
            $data = json_decode(curl_exec($OauthToken), true);

            $http_code = curl_getinfo($OauthToken, CURLINFO_HTTP_CODE);
            var_dump($data);
            if ($http_code != 200) {
                $error_msg = 'Failed to receive token';
                if(curl_error($OauthToken)) {
                    $error_msg = curl_error($OauthToken);
                }
                throw new Exception("Error ".$http_code.': '.$error_msg);
            }

            return $data;
        }

        /**
         * Refresh token for specified Google API only accept input from GetGapiToken()['refresh_token']
         */
        function GetGapiRefreshToken($refresh_token){
            $cURLPOST = array(
                "client_id" => OAUTH_CLIENT_ID,
                "client_secret" => OAUTH_CLIENT_SECRET,
                "refresh_token" => $refresh_token,
                "grant_type" => "refresh_token",
                "redirect_uri" => REDIRECT_URI,
            );

            $OauthRefreshToken = curl_init();
            curl_setopt($OauthRefreshToken, CURLOPT_URL, OAUTH_TOKEN_URI);
            curl_setopt($OauthRefreshToken, CURLOPT_POSTFIELDS, http_build_query($cURLPOST));
            curl_setopt($OauthRefreshToken, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/x-www-form-urlencoded"
            )); 
            curl_setopt($OauthRefreshToken, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($OauthRefreshToken, CURLOPT_POST, 1);
            $data = json_decode(curl_exec($OauthRefreshToken), true);

            $http_code = curl_getinfo($OauthRefreshToken, CURLINFO_HTTP_CODE);

            if ($http_code != 200) {
                $error_msg = 'Failed to receive token';
                if(curl_error($OauthRefreshToken)) {
                    $error_msg = curl_error($OauthRefreshToken);
                }
                throw new Exception("Error ".$http_code.': '.$error_msg);
            }

            return $data;
        }

        /**
         * Get User in GAdmin workspace
         */
        function GetGWorkspace_ListUser($google_data, $userId, $allow_refresh=true)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://admin.googleapis.com/admin/directory/v1/users/'.$userId.'?key='.API_KEY);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

            curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

            $headers = array();
            $headers[] = 'Authorization: Bearer '.$google_data['access_token'];
            $headers[] = 'Accept: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $data = json_decode(curl_exec($ch),true);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_err = curl_error($ch);
            
            if (isset($data['error'])) {
                if ($data['error']['code'] == 401 && $allow_refresh) {
                    //reload
                    $data_refresh = $this->GetGapiRefreshToken($google_data['refresh_token']);
                    var_dump($data_refresh);
                    $google_data['access_token'] = $data_refresh['access_token'];
                    return $this->GetGWorkspace_ListUser($google_data, $userId, false);
                }
            }

            // if ($http_code != 200) {
            //     $error_msg = 'Failed to receive token';
            //     if(curl_error($ch)) {
            //         $error_msg = curl_error($ch);
            //     }
            //     throw new Exception("Error ".$http_code.': '.$error_msg);
            // }

            return [
                'data' => $data,
                'http_code' => $http_code,
                'curl_err' => $curl_err,
            ];
        }
         /**
         * Create user in GAdmin workspace
         */
        function GetGWorkspace_CreateUser($google_data, $user_data)
        {
            $cURLPOST = array(
                'familyName' => $user_data['name']['famiy_name'],
                'givenName' => $user_data['name']['given_name'],
                'fullName' => $user_data['name']['full_Name'],
                'primaryEmail' => $user_data['primary_email'],
                'password' => $user_data['password'],
                "orgUnitPath" => $user_data['orgUnitPath']
             );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://admin.googleapis.com/admin/directory/v1/users/?key='.API_KEY);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_decode($cURLPOST, true) );
            // curl_setopt($ch, CURLOPT_POSTFIELDS,  "{\"name\":{\"familyName\":\"Pekerti\",\"givenName\":\"Budi\",\"fullName\":\"Budi Pekerti\"},\"password\":\"presuniv12345\",\"primaryEmail\":\"bayu.budi.pekerti@president.ac.id\",\"orgUnitPath\":\"/Student/ACC/2021\"}");
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

            $headers = array();
            $headers[] = 'Authorization: Bearer '.$google_data['access_token'];
            $headers[] = 'Accept: application/json';
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $data = json_decode(curl_exec($ch),true);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_err = curl_error($ch);
            
            // if (isset($data['error'])) {
            //     if ($data['error']['code'] == 401 && $allow_refresh) {
            //         //reload
            //         $data_refresh = $this->GetGapiRefreshToken($google_data['refresh_token']);
            //         var_dump($data_refresh);
            //         $google_data['access_token'] = $data_refresh['access_token'];
            //         return $this->GetGWorkspace_ListUser($google_data, $userId, false);
            //     }
            // }
            return $data;

        }
    }
?>