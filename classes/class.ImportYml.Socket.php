<?php
echo "Start";
$clients = array();
$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
socket_bind($socket,'127.0.0.1',5000);
socket_listen($socket);
socket_set_nonblock($socket);
echo "Start";
while(true)
{
    if(($newc = socket_accept($socket)) !== false)
    {
        echo "Client $newc has connected\n";
        $clients[] = $newc;
    }
}

?>
