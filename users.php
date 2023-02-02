<?php
include_once 'crud_user.php';

$cl=dbinit();
$conn=$cl[0];
$logfile=$cl[1];

# request handlers
function doget($srv, $conn)
{
    $cx=lsusr($conn);
    $res=array("status" => "success", "data" => $cx);
    printf("%s", json_encode($res));
}

function dopost($lf, $srv, $conn)
{
    $json=file_get_contents('php://input');
    $obj=json_decode($json);
    $stat=mkusr($lf, $srv, $conn, $obj->username);
    $res=array("msg" => $stat);
    printf("%s", json_encode($res));
}

function doput($srv, $conn)
{
    $res=array("status" => "in progress");
    printf("%s", json_encode($res));
}

function dodel($lf, $srv, $conn)
{
    $uname=explode('/', $srv['PATH_INFO'])[1];
    $stat=rmusr($lf, $srv, $conn, $uname);
    $res=array("msg" => $stat);
    printf("%s", json_encode($res));
}

# program flow
$method = $_SERVER['REQUEST_METHOD'];
header('Content-type: application/json; charset=utf-8');
if($method === 'GET')
{
    doget($_SERVER, $conn);
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

