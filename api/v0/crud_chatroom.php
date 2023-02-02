<?php
include_once 'util.php';

function lsrooms($lf, $srv, $conn)
{
    $sql="select * from su_chatrooms";
    $result=$conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    $rnames=[];
    foreach ($result as $row)
    {
        $rnames[]=array("name"=>$row['name'], "desc"=>$row['description']);
    }
    return $rnames;
}

function getroomid($conn, $rname)
{
    $sql="select crid from su_chatrooms where name=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("s", $rname);
    $stmt->execute();
    $stmt->bind_result($crid);
    $stmt->fetch();
    return $crid;
}

function roomoccurs($conn, $rname)
{
    $sql="select count(*) from su_chatrooms where name=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("s", $rname);
    $stmt->execute();
    $stmt->bind_result($nrows);
    $stmt->fetch();
    return $nrows;
}

function mkroom($lf, $srv, $conn, $rname, $rdesc)
{
    if(strpos($rname, ' ') !== FALSE)
    {
        $errmsg="username contains space!";
        d($lf, $srv, $errmsg."\n");
        return $errmsg;
    }
    $occurrences = roomoccurs($conn, $rname);
    if($occurrences == 0)
    {
        $sql="insert into su_chatrooms (name, description) values (?, ?)";
        $stmt=$conn->prepare($sql);
        $stmt->bind_param("ss", $rname, $rdesc);
        $stmt->execute();
        return 'OK';
    }
    $errmsg="roomname $rname unavailable!";
    d($lf, $srv, $errmsg."\n");
    return $errmsg;
}

function rmroom($lf, $srv, $conn, $rname)
{
    $sql="delete from su_chatrooms where name=?";
    d($lf, $srv, "deleting chatroom: $rname");
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("s", $rname);
    $stmt->execute();
    if($conn->affected_rows > 0)
    {
        return "OK";
    }
    else
    {
        return "unable to delete $rname";
    }
}
?>
