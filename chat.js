let ibx=null;
let msglog=null;
let chatroom=null;
let username=null;
let setupbtn=null;
let unbox=null;
let crbox=null;
let polldly=2000;
let pdbox=null;
let allowpolling=false;

function onkeydown(e) {
  let kc=e.keyCode;
  if(kc==13){
    let msg=ibx.value;
    ibx.value='';
    msglog.value+='[me] '+msg+'\n';
    sendmesg(msg);
  }
}

function apicall(data,path,callback) {
  var myHeaders = new Headers();
  myHeaders.append("Content-Type", "application/json");

  var raw = JSON.stringify(data);

  var requestOptions = {
    method: 'POST',
    headers: myHeaders,
    body: raw,
    redirect: 'follow'
  };

  fetch(location.protocol+"//"+location.host+"/"+path, requestOptions)
    .then(response => response.json())
    .then(result => callback(result))
    .catch(error => console.log('error', error));
}

function sendmesg(m) {
  let payload={
    "r": chatroom,
    "u": username,
    "m": m
  };
  apicall(payload, "sendmesg.php", function(res) {
    console.log(`response: ${res['msg']}`);
  });
}

function msgpoll() {
  if(chatroom!=='' && username!=='' && allowpolling) {
    let payload={
      'r': chatroom,
      'u': username
    };
    apicall(payload, "recvmesg.php", function(res) {
      let msgs=res['msg'];
      msgs.forEach((item) => {
        msglog.value+='['+item.u+'] '+item.m+'\n';
      });
    });
  }
  setTimeout(msgpoll, polldly);
}

function setuproomuser(un,cr) {
  let payload={
    "username": un,
    "roomname": cr
  };
  apicall(payload, "roomusers.php", function(res) {
    console.log(res);
    allowpolling=true;
  });
}

function onenter(e) {
  username=unbox.value;
  chatroom=crbox.value;
  if(username==='' || chatroom===''){
    return;
  }
  setuproomuser(username, chatroom);
  ibx.disabled=false;
  ibx.value='greetings!';
}

function handleurlparams() {
  let params=new URLSearchParams(location.search);
  if(params.has('chatroom')) {
    chatroom=params.get('chatroom');
    crbox.value=chatroom;
  }
  if(params.has('username')) {
    username=params.get('username');
    unbox.value=username;
  }
}

function appinit() {
  ibx=document.getElementById('inputbox');
  ibx.addEventListener("keydown", onkeydown);
  msglog=document.getElementById('msglog');
  setupbtn=document.getElementById('setupbtn');
  setupbtn.addEventListener('pointerdown', onenter);
  unbox=document.getElementById('username');
  crbox=document.getElementById('chatroom');
  pdbox=document.getElementById('polldly');
  handleurlparams();
  msgpoll();
}

window.addEventListener('load', (e) => {
  appinit();
});

