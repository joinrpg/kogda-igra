var hostname=document.location.hostname;

function onSignIn(googleUser) {
  // The ID token you need to pass to your backend:
  var id_token = googleUser.getAuthResponse().id_token;
  console.log("ID Token: " + id_token);
  
  if (googleUser && !window.loggedIn) {
      if (XMLHttpRequest) {
        var req = new XMLHttpRequest();
        var csrf = document.getElementById('csrf_token').value;
        var uri = 'http://'+hostname+'/login/google/?csrf_token=' + csrf + '&token='+googleUser.getAuthResponse().id_token;
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
		

function logout_handler()
{
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
      console.log('User signed out.');
      	document.location.href = 'http://'+ hostname + "/logout/";
    });
}

function init()
{
	if(typeof String.prototype.trim !== 'function') {
    String.prototype.trim = function() {
      return this.replace(/^\s+|\s+$/g, ''); 
    }
  }
}

window.onload = init;
