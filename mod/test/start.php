<?php
function test_init(){
    expose_function("test.echo",
                    "my_echo",
                    array("string" => array('type' => 'string')),
                    'A testing method which echos back a string',
                    'GET',
                    false,
                    false
                   );
}
function my_echo($string){
    return $string;
}
elgg_register_event_handler('init', 'system', 'test_init');
?>
