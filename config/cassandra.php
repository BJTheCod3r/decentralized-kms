<?php

return [
    'nodes' => [
        [                // advanced way, using Connection\Stream, persistent connection
            'host' => 'cassandra',
            'port' => 9042,
            'username' => '',
            'password' => '',
            'class' => 'Cassandra\Connection\Stream',//use stream instead of socket, default socket. Stream may not work in some environment
            'connectTimeout' => 10, // connection timeout, default 5,  stream transport only
            'timeout' => 30, // write/recv timeout, default 30, stream transport only
            'persistent' => true, // use persistent PHP connection, default false,  stream transport only
        ]
    ],
    'key_space' => env('DB_DATABASE', 'decentralized')
];
