<?php
include_once 'crud_user.php';
include_once 'crud_chatroom.php';

# chatroomusers crud ops
function lsroomusers($lf, $srv, $conn, $rname)
{
    $rid=getroomid($conn, $rname);
    if($rid == 0)
    {
        return [];
    }
    $sql="select uname from 
        su_chatroomusers inner join su_users 
        on su_chatroomusers.userid=su_users.uid 
        where roomid=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("i", $rid);
    $stmt->execute();
    $stmt->bind_result($uname);
    $unms=[];
    while($stmt->fetch())
    {
        $unms[]=$uname;
    }
    return $unms;
}

function getroomuserid($lf, $srv, $conn, $uid, $crid)
{
    $sql="select cruid from su_chatroomusers where roomid=? and userid=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("ii", $crid, $uid);
    $stmt->execute();
    $stmt->bind_result($cruid);
    $stmt->fetch();
    return $cruid;
}

function insertroomuser($lf, $srv, $conn, $crid, $uid)
{
    $sql="insert into su_chatroomusers (roomid, userid) values (?, ?)";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('ii', $crid, $uid);
    $stmt->execute();
    return "OK";
}

function deleteroomuser($lf, $srv, $conn, $crid, $uid)
{
    $sql="delete from su_chatroomusers where roomid=? and userid=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('ii', $crid, $uid);
    $stmt->execute();
    if($conn->affected_rows == 1)
    {
        return "OK";
    }
    else
    {
        return "KO";
    }
}

function addusr2room($lf, $srv, $conn, $uname, $rname)
{
    $uid=getuid($conn, $uname);
    if($uid == 0)
    {
        $rsp=mkusr($lf, $srv, $conn, $uname);
        if($rsp!=='OK')
        {
            return $rsp;
        }
        $uid=getuid($conn, $uname);
    }
    $crid=getroomid($conn, $rname);
    if($crid == 0)
    {
        $rsp=mkroom($lf, $srv, $conn, $rname, $rname);
        if($rsp!=='OK')
        {
            return $rsp;
        }
        $crid=getroomid($conn, $rname);
    }
    $cruid=getroomuserid($lf, $srv, $conn, $uid, $crid);
    if($cruid > 0)
    {
        return "user already present in room!";
    }
    return insertroomuser($lf, $srv, $conn, $crid, $uid);
}

function rmusrfromroom($lf, $srv, $conn, $uname, $rname)
{
    $uid=getuid($conn, $uname);
    if($uid == 0)
    {
        return "no such user!";
    }
    $crid=getroomid($conn, $rname);
    if($crid == 0)
    {
        return "no such room!";
    }
    return deleteroomuser($lf, $srv, $conn, $crid, $uid);
}
?>
