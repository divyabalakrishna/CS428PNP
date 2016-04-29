/**
 * http open url 
 * @param string url
 * @param string callback function
 */
function downloadUrl(url, callback) {
  var request = window.ActiveXObject ?
      new ActiveXObject('Microsoft.XMLHTTP') :
      new XMLHttpRequest;

  request.onreadystatechange = function() {
    if (request.readyState == 4) {
      request.onreadystatechange = doNothing;
      callback(request, request.status);
    }
  };

  request.open('GET', url, true);
  request.send(null);
}

/**
 * helper function 
 */
function doNothing() {}

/**
 * update notification read flag when user click
 * @param integer notifID
 * @param string redirectUrl
 */
function updateNotifFlag (notifID,redirectUrl) {

    url = '/index.php/notifs/updateFlag/' + notifID; 
    downloadUrl(url, function(data) {
        window.location = 'http://' + window.location.hostname + '/index.php' + redirectUrl;
    });
}
