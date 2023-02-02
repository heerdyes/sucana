<?php
include_once 'crud_user.php';
include_once 'crud_chatroom.php';
include_once 'crud_roomuser.php';

function mkmsg($lf,$srv,$conn,$msg,$uid)
{
    $sql="insert into su_messages (txt, mkrid) values (?, ?)";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("si", $msg, $uid);
    $stmt->execute();
    return $conn->insert_id;
}

function populateinboxes($lf,$srv,$conn,$uid,$rid,$mid)
{
    $sql="select cruid from su_chatroomusers where roomid=? and userid!=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("ii", $rid, $uid);
    $stmt->execute();
    $stmt->bind_result($ruid);
    $recvusrs=[];
    while($stmt->fetch())
    {
        $recvusrs[]=$ruid;
    }
    foreach($recvusrs as $cruid)
    {
        $msql="insert into su_chatroomuserinbox (cruser, msgid) values (?, ?)";
        $xs=$conn->prepare($msql);
        $xs->bind_param("ii", $cruid, $mid);
        $xs->execute();
    }
    return "OK";
}

function nqmsg($lf, $srv, $conn, $rn, $un, $msg)
{
    $rid=getroomid($conn, $rn);
    if($rid == 0)
    {
        return "unknown chatroom";
    }
    $uid=getuid($conn, $un);
    if($uid == 0)
    {
        return "unknown username";
    }
    $ruid=getroomuserid($lf,$srv,$conn,$uid,$rid);
    if($ruid == 0)
    {
        return "username non-existent in chatroom";
    }
    $mid=mkmsg($lf,$srv,$conn,$msg,$uid);
    return populateinboxes($lf,$srv,$conn,$uid,$rid,$mid);
}

function exhaustinbox($lf,$srv,$conn,$ruid)
{
    $sql="select txt, uname from su_chatroomuserinbox
        inner join su_messages
        on mid=msgid
        inner join su_users
        on mkrid=uid
        where cruser=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("i", $ruid);
    $stmt->execute();
    $stmt->bind_result($msg, $uname);
    $msgs=[];
    while($stmt->fetch())
    {
        $msgs[]=array("m"=>$msg, "u"=>$uname);
    }
    # clear inbox after reading
    $dsql="delete from su_chatroomuserinbox where cruser=?";
    $dstmt=$conn->prepare($dsql);
    $dstmt->bind_param("i", $ruid);
    $dstmt->execute();
    # clear up dangling messages
    $rmsql="delete su_messages from su_messages left join su_chatroomuserinbox on mid=msgid where msgid is null";
    $rmstmt=$conn->prepare($rmsql);
    $rmstmt->execute();
    return $msgs;
}

function dqmsg($lf, $srv, $conn, $rn, $un)
{
    $rid=getroomid($conn, $rn);
    if($rid == 0)
    {
        return array("हे"=>"unknown chatroom", "द"=>[]);
    }
    $uid=getuid($conn, $un);
    if($uid == 0)
    {
        return array("हे"=>"unknown username", "द"=>[]);
    }
    $ruid=getroomuserid($lf,$srv,$conn,$uid,$rid);
    if($ruid == 0)
    {
        return array("हे"=>"username non-existent in chatroom", "द"=>[]);
    }
    return array("हे"=>"OK", "द"=>exhaustinbox($lf,$srv,$conn,$ruid));
}

?>
