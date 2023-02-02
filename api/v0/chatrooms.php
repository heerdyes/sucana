<?php
include_once 'crud_chatroom.php';

$cl=dbinit();
$conn=$cl[0];
$logfile=$cl[1];

# request handlers
function doget($lf, $srv, $conn)
{
    $cx=lsrooms($lf, $srv, $conn);
    $res=array("हे" => "OK", "द" => $cx);
    printf("%s", json_encode($res));
}

function dopost($lf, $srv, $conn)
{
    $json=file_get_contents('php://input');
    $obj=json_decode($json);
    $stat=mkroom($lf, $srv, $conn, $obj->name, $obj->desc);
    $res=array("हे"=>$stat, "द"=>[]);
    printf("%s", json_encode($res));
}

function doput($srv, $conn)
{
    $res=array("हे" => "in progress");
    printf("%s", json_encode($res));
}

function dodel($lf, $srv, $conn)
{
    $rname=explode('/', $srv['PATH_INFO'])[1];
    $stat=rmroom($lf, $srv, $conn, $rname);
    $res=array("हे" => $stat, "द"=>[]);
    printf("%s", json_encode($res));
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

