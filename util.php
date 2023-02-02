<?php
function dbinit()
{
    $cfg=parse_ini_file('../cfg.ini');

    $servername = $cfg['server'];
    $database = $cfg['database'];
    $username = $cfg['username'];
    $password = $cfg['password'];
    $logfile = $cfg['logfile'];

    $conn = new mysqli($servername, $username, $password, $database);
    if (!$conn)
    {
        die("Connection failed: " . mysqli_connect_error());
    }
    return array($conn, $logfile);
}

function d($lf, $srv, $msg)
{
    $method = $srv['REQUEST_METHOD'];
    $subpath = $srv['PATH_INFO'];
    $ipaddr = $srv['REMOTE_ADDR'];
    $logline = '['.$ipaddr.'] '.$method.' - <'.$subpath.'> '.$msg.PHP_EOL;
    file_put_contents($lf, $logline, FILE_APPEND);
}
?>
