function readAll() {
  let xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if(this.readyState == 4 && this.status == 200) {
      location.reload(true);
    }
  };
  xhttp.open("GET", "php/ajax_response/notification_reader.php");
  xhttp.send();
}