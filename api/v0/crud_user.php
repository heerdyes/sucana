<?php
include_once 'util.php';

function usroccurs($conn, $uname)
{
    $sql="select count(*) from su_users where uname=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("s", $uname);
    $stmt->execute();
    $stmt->bind_result($nrows);
    $stmt->fetch();
    return $nrows;
}

function getuid($conn, $uname)
{
    $sql="select uid from su_users where uname=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("s", $uname);
    $stmt->execute();
    $stmt->bind_result($uid);
    $stmt->fetch();
    return $uid;
}

function mkusr($lf, $srv, $conn, $uname)
{
    if(strpos($uname, ' ') !== FALSE)
    {
        $errmsg="username $uname contains space!";
        d($lf, $srv, $errmsg."\n");
        return $errmsg;
    }
    $occurrences = usroccurs($conn, $uname);
    if($occurrences == 0)
    {
        $sql="insert into su_users (uname) values (?)";
        $stmt=$conn->prepare($sql);
        $stmt->bind_param("s", $uname);
        $stmt->execute();
        return 'OK';
    }
    $errmsg="username $uname unavailable!";
    d($lf, $srv, $errmsg."\n");
    return $errmsg;
}

function rmusr($lf, $srv, $conn, $uname)
{
    $sql="delete from su_users where uname=?";
    d($lf, $srv, "deleting user: $uname");
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("s", $uname);
    $stmt->execute();
    if($conn->affected_rows > 0)
    {
        return "OK";
    }
    else
    {
        return "unable to delete $uname";
    }
}

function lsusr($conn)
{
    $sql="select * from su_users";
    $result=$conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    $unames=[];
    foreach ($result as $row)
    {
        $unames[]=$row['uname'];
    }
    return $unames;
}
?>
