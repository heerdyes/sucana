<?php
include_once 'crud_mesg.php';

$cl=dbinit();
$conn=$cl[0];
$logfile=$cl[1];

# request handlers
function doget($lf, $srv, $conn)
{
    printf("%s", json_encode(array("हे"=>"nope.", "द"=>[])));
}

function dopost($lf, $srv, $conn)
{
    $json=file_get_contents('php://input');
    $o=json_decode($json);
    $stat=nqmsg($lf, $srv, $conn, $o->r, $o->u, $o->m);
    $res=array("हे"=>$stat, "द"=>[]);
    printf("%s", json_encode($res));
}

function doput($srv, $conn)
{
    printf("%s", json_encode(array("हे"=>"nope.", "द"=>[])));
}

function dodel($lf, $srv, $conn)
{
    printf("%s", json_encode(array("हे"=>"nope.", "द"=>[])));
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
