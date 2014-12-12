function get_select_value(obj)
{
  return obj.options[obj.selectedIndex].value;
}

var weekday=new Array(7);
weekday[0]="Воскресенье";
weekday[1]="Понедельник";
weekday[2]="Вторник";
weekday[3]="Среда";
weekday[4]="Четверг";
weekday[5]="Пятница";
weekday[6]="Суббота";

var hostname=document.location.hostname;

function install_search_plugin() {
 if (window.external && ("AddSearchProvider" in window.external)) {
   // Firefox 2 and IE 7, OpenSearch
   window.external.AddSearchProvider("http://"+hostname+"/opensearch.xml");
 } else {
   // No search engine support (IE 6, Opera, etc).
   alert("Поисковые плагины не поддерживаются вашим браузером.");
 }
}

function show_cancelled_games()
{
  show = !!document.getElementById('show_cancel').checked;
  var calendar_table = document.getElementById('calendar');
  if (calendar_table)
  {
    var cancel_games = calendar_table.getElementsByClassName('cancel_game');
    for (var i =0; i < cancel_games.length; i++)
    {
      cancel_games[i].style.display = show ? 'table-row' : 'none';
    }
  }
}

		function got_assertion(assertion)
		{
			if (assertion) {
						if (XMLHttpRequest) {
							var req = new XMLHttpRequest();
							var csrf = document.getElementById('csrf_token').value;
							var uri = 'http://'+hostname+'/login/browserid/?csrf_token=' + csrf + '&assert='+assertion;
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
		}
		
		function try_login()
		{
			navigator.id.get(got_assertion, {allowPersistent: true});
		}
		

function logout_handler(event)
{
	event.preventDefault();
	
	var req = new XMLHttpRequest();
	window.navigator.id.logout();
	var csrf = document.getElementById('csrf_token').value;
	var uri = 'http://'+hostname+'/logout/?csrf_token=' + csrf;
	req.open ('GET', uri, true);
	req.onreadystatechange = function (aEvt) {
		if (req.readyState == 4)
		{
			window.navigator.id.logout(logged_out);
		}
	}
	req.send(null);
}

function logged_out()
{
	document.location.reload();
}


function init()
{
	if (!window.loggedIn)
	{
		window.navigator.id.get(got_assertion, {silent: true});
	}
	else
	{
		
	  var logout_button = document.getElementById('logout_button');
		logout_button.addEventListener('click',logout_handler);
  }
	
	if(typeof String.prototype.trim !== 'function') {
  String.prototype.trim = function() {
    return this.replace(/^\s+|\s+$/g, ''); 
  }
  

  
  
  var search_link = document.getElementById("add_search_form");
  if (search_link && window.external && ("AddSearchProvider" in window.external))
  {
    if (("IsSearchProviderInstalled" in window.external) && window.external.IsSearchProviderInstalled("http://"+hostname+"/"))
    {

      return;
    }
     search_link.style.visibility = "visible";
  }
  
  
  
}

}


function start_allrpg_info_sync (game_id, before, sync_correct, sync_failed)
{
  if (!XMLHttpRequest)
  {
    return;
  }
   var req = new XMLHttpRequest();
  var dropdown = document.getElementById('allrpg_games');
  var uri = 'http://allrpg.info/kogdaigra2.php?from=' + game_id +'&to=' + game_id+ '&automated=1';
  req.open ('GET', uri, true);
  req.onreadystatechange = function (aEvt) {
    if (req.readyState != 4)
    {
      return;
    }
    if (req.status != 200)
    {
      return;
    }
    var str = req.responseText;
    var result = JSON.parse(str);
    if (result.length == 0)
    {
      sync_failed();
      return;
    }
    else 
    {
      result = result[0];
      
      if (!result.allrpg_id)
      {
        sync_failed();
        return;
      }
      sync_correct(result);
      if (before != result.allrpg_id)
      {
        req = new XMLHttpRequest();
        uri = '/api/allrpg-info/update-allrpg.php';
        var params = 'id=' + game_id + "&allrpg=" + result.allrpg_id;
        req.open ('POST', uri, true);
        req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        req.setRequestHeader("Content-length", params.length);
        req.send(params);
      }
    }
  }
  req.send();
}


window.onload = init;
