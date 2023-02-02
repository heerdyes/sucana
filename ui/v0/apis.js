function apipost(data,path,callback) {
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

function apiget(path,callback) {
  var myHeaders = new Headers();
  myHeaders.append("Content-Type", "application/json");

  var requestOptions = {
    method: 'GET',
    headers: myHeaders,
    redirect: 'follow'
  };

  fetch(location.protocol+"//"+location.host+"/"+path, requestOptions)
    .then(response => response.json())
    .then(result => callback(result))
    .catch(error => console.log('error', error));
}
