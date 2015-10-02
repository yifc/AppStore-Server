<?php

/**
 * @File Name:   restfulAPI
 * @Description: Expose the functions of engine/lib for calling from remote Android client
 * @Author:      Yezhuo Zhu <yzhu100@asu.edu>
 * @Version:     1.0 
 */

function restfulAPI_init(){

    expose_function("login",
                    "appstore_login",
                    array("username" => array('type' => 'string'),
                          "password" => array('type' => 'string'),
                          "persistent" => array('type' => 'bool')),
                    'Authenticate the user name and password and login',
                    'GET',
                    false,
                    false
                   );
    expose_function("logout",
                    "appstore_logout",
                    array(),
                    'Logout',
                    'GET',
                    false,
                    false
                   );

    expose_function("register",
                    "appstore_register",
                    array("username" => array('type' => 'string'),
                          "password" => array('type' => 'string'),
                          "name" => array('type' => 'string'),
                          "email" => array('type' => 'string'),
                          "allow_multiple_emails" => array('type' => 'bool'),
                          "friend_guid" => array('type' => 'int'),
                          "invitecode" => array('type' => 'string')),
                    'Regist a new user',
                    'GET',
                    false,
                    false
                   );

    expose_function("addfriendbyname",
                    "appstore_addfriendbyname",
                    array("user_username" => array('type' => 'string'),
                          "friend_username" => array('type' => 'string')),
                    'Add new friend using username',
                    'GET',
                    false,
                    false
                   );

    expose_function("addfriendbyemail",
                    "appstore_addfriendbyemail",
                    array("user_username" => array('type' => 'string'),
                          "friend_email" => array('type' => 'string')),
                    'Add new friend using email',
                    'GET',
                    false,
                    false
                   );

    expose_function("removefriendbyname",
                    "appstore_removefriendbyname",
                    array("user_username" => array('type' => 'string'),
                          "friend_username" => array('type' => 'string')),
                    'Remove friend using username',
                    'GET',
                    false,
                    false
                   );
    expose_function("removefriendbyemail",
                    "appstore_removefriendbyemail",
                    array("user_username" => array('type' => 'string'),
                          "friend_email" => array('type' => 'string')),
                    'Remove friend using email',
                    'GET',
                    false,
                    false
                   );

    expose_function("listfriend",
                    "appstore_listfriend",
                    array("user_username" => array('type' => 'string'),
                          "subtype" => array('type' => 'string'),
                          "limit" => array('type' => 'int'),
                          "full_view" => array('type' => 'bool'),
                          "listtypetoggle" => array('type' => 'bool'),
                          "pagination" => array('type' => 'bool'),
                          "timelower" => array('type' => 'int'),
                          "timeupper" => array('type' => 'int')),
                    'List all the friends of user',
                    'GET',
                    false,
                    false
                   );

    expose_function("downloadfile",
                    "appstore_downloadfile",
                    array("input_name" =>array('type' => 'string')),
                    'Download file',
                    'GET',
                    false,
                    false
                   );
}

function appstore_login($username, $password, $persistent = false){
    include(dirname(dirname(dirname(_FILE_)))."/engine/lib/sessions.php");
    include(dirname(dirname(dirname(_FILE_)))."/engine/lib/users.php");
    if(elgg_authenticate($username, $password) == "True"){
        $elgg_user = get_user_by_username($username);
        return login($elgg_user, $persistent);
    }
    return elgg_authenticate($username, $password);
}

function appstore_logout(){
    include(dirname(dirname(dirname(_FILE_)))."/engine/lib/sessions.php");
    return logout();
}

function appstore_register($username, $password, $name, $email, $allow_multiple_emails = false, $friend_guid = 0, $invitecode = ''){
    include(dirname(dirname(dirname(_FILE_)))."/engine/lib/users.php");
    return register_user($username, $password, $name, $email, $allow_multiple_emails, $friend_guid, $invitecode);
}

//Problem
function appstore_addfriendbyname($user_username, $friend_username){
    include(dirname(dirname(dirname(_FILE_)))."/engine/lib/users.php");
    $user_entity = get_user_by_username($user_username);
    $friend_entity = get_user_by_username($friend_username);
    return user_add_friend($user_entity->guid, $friend_entity->guid);
}

function appstore_addfriendbyemail($user_username, $friend_email){
    include(dirname(dirname(dirname(_FILE_)))."/engine/lib/users.php");
    $user_entity = get_user_by_username($user_username);
    $friend_entity = get_user_by_email($friend_email);
    return user_add_friend($user_entity->guid, $friend_entity->guid);
}

function appstore_removefriendbyname($user_username, $friend_username){
    include(dirname(dirname(dirname(_FILE_)))."/engine/lib/users.php");
    $user_entity = get_user_by_username($user_username);
    $friend_entity = get_user_by_username($friend_username);
    return user_remove_friend($user_entity->guid, $friend_entity->guid);
}

function appstore_removefriendbyemail($user_username, $friend_email){
    include(dirname(dirname(dirname(_FILE_)))."/engine/lib/users.php");
    $user_entity = get_user_by_username($user_username);
    $friend_entity = get_user_by_email($friend_email);
    return user_remove_friend($user_entity->guid, $friend_entity->guid);
}

function appstore_listfriend($user_username, $subtype = "", $limit = 10, $full_view = true, $listtypetoggle = true, $pagination = true, $timelower = 0, $timeupper = 0){
    include(dirname(dirname(dirname(_FILE_)))."/engine/lib/users.php");
    $user_entity = get_user_by_username($user_username);
    return list_user_friends_object($user_entity->guid, $subtype, $limit, $full_view, $listtypetoggle, $pagination, $timelower, $timeupper);
}

function appstore_downloadfile($input_name){
    include(dirname(dirname(dirname(_FILE_)))."/engine/lib/filestore.php");
    return get_uploaded_file($input_name);
}

elgg_register_event_handler('init', 'system', 'restfulAPI_init');
?>
