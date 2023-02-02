let roomctr=null;

function appinit() {
  roomctr=document.getElementById('roomsel');
  apiget("chatrooms.php", function(res) {
    console.log(res['द']);
  });
}

window.addEventListener('load', (e) => {
  appinit();
});

