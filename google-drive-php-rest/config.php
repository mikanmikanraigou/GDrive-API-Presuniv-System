<?php
    define('OAUTH_CLIENT_ID', '');
    define('OAUTH_CLIENT_SECRET', '');
    define('API_KEY','');
    define('REDIRECT_URI', 'http://localhost/google-drive-php-rest/gdrives_sync.php');
    define('REDIRECT_URI2', 'http://localhost/google-drive-php-rest/gworkspace_sync.php');
    define('OAUTH_TOKEN_URI', 'https://oauth2.googleapis.com/token');
    
    //OAUTH SCOPEs
    define('GOOGLE_DRIVE_OAUTH_SCOPE', 'https://www.googleapis.com/auth/drive');
    define('GOOGLE_WORKSPACE_USER_LIST_OAUTH_SCOPE', 'https://www.googleapis.com/auth/admin.directory.user');
    

    if (!session_id()) session_start();



    //hit the addresses below to get authorization_code
    /**
     * Google Drive Oauth
     */
    $GDrive_oauth_URL = 'https://accounts.google.com/o/oauth2/v2/auth?scope='.urlencode(GOOGLE_DRIVE_OAUTH_SCOPE).'&access_type=offline&include_granted_scopes=true&response_type=code&redirect_uri='.REDIRECT_URI.'&client_id='.OAUTH_CLIENT_ID;
    
    /**
     * Google Workspace list user list 
     */
    $GWorkspace_list_user_oauth_URL = 'https://accounts.google.com/o/oauth2/v2/auth?redirect_uri='.urlencode(REDIRECT_URI2).'&prompt=consent&response_type=code&client_id='.OAUTH_CLIENT_ID.'&scope='.urlencode(GOOGLE_WORKSPACE_USER_LIST_OAUTH_SCOPE).'&access_type=offline';

?>