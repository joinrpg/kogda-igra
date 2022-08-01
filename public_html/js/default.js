function onSignIn(googleUser) {
  // The ID token you need to pass to your backend:
  var id_token = googleUser.credential;
  console.log("Credential Token: " + id_token);
  
  if (googleUser && !window.loggedIn) {
      if (XMLHttpRequest) {
        var req = new XMLHttpRequest();
        var csrf = document.getElementById('csrf_token').value;
        var uri = '/login/google/?csrf_token=' + csrf + '&token='+id_token;
        req.open ('GET', uri, true);
        req.onreadystatechange = function (aEvt) {
          if (req.readyState == 4)
          {
            if (req.status == 200)
            {
              document.location.reload();
            }
          }
        }
        req.send(null);
      }
    } else {
  }
};
