<?php
include_once 'crud_mesg.php';

$cl=dbinit();
$conn=$cl[0];
$logfile=$cl[1];

# request handlers
function doget($lf, $srv, $conn)
{
    printf("%s", json_encode(array("msg"=>"nope.")));
}

function dopost($lf, $srv, $conn)
{
    $json=file_get_contents('php://input');
    $o=json_decode($json);
    $stat=dqmsg($lf, $srv, $conn, $o->r, $o->u);
    $res=array("msg" => $stat);
    printf("%s", json_encode($res));
}

function doput($srv, $conn)
{
    printf("%s", json_encode(array("msg"=>"nope.")));
}

function dodel($lf, $srv, $conn)
{
    printf("%s", json_encode(array("msg"=>"nope.")));
}

# program flow
$method = $_SERVER['REQUEST_METHOD'];
header('Content-type: application/json; charset=utf-8');
if($method === 'GET')
{
    doget($logfile, $_SERVER, $conn);
}
elseif($method === 'POST')
{
    dopost($logfile, $_SERVER, $conn);
}
elseif($method === 'PUT')
{
    doput($_SERVER, $conn);
}
elseif($method === 'DELETE')
{
    dodel($logfile, $_SERVER, $conn);
}

mysqli_close($conn);
?>
