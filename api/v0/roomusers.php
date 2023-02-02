<?php
include_once 'crud_roomuser.php';

$cl=dbinit();
$conn=$cl[0];
$logfile=$cl[1];

# request handlers
function doget($lf, $srv, $conn)
{
    if(array_key_exists('PATH_INFO', $srv))
    {
        $rname=explode('/', $srv['PATH_INFO'])[1];
        $cx=lsroomusers($lf, $srv, $conn, $rname);
        $res=array("हे" => "OK", "द" => $cx);
        printf("%s", json_encode($res));
    }
    else
    {
        printf("%s", json_encode(array("हे"=>"roomname is required", "द"=>[])));
    }
}

function dopost($lf, $srv, $conn)
{
    $json=file_get_contents('php://input');
    $obj=json_decode($json);
    $stat=addusr2room($lf, $srv, $conn, $obj->username, $obj->roomname);
    $res=array("हे" => $stat, "द"=>[]);
    printf("%s", json_encode($res));
}

function doput($srv, $conn)
{
    $res=array("हे" => "in progress", "द"=>[]);
    printf("%s", json_encode($res));
}

function dodel($lf, $srv, $conn)
{
    $px=explode('/', $srv['PATH_INFO']);
    if(count($px) != 3)
    {
        $res=array("हे" => "wrong number of parameters", "द"=>[]);
        printf("%s", json_encode($res));
        return;
    }
    $rname=$px[1];
    $uname=$px[2];
    $stat=rmusrfromroom($lf, $srv, $conn, $uname, $rname);
    $res=array("हे"=>$stat, "द"=>[]);
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

