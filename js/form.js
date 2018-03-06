function checkInput() {
  var eventname = document.forms["myform"]["eventname"].value;
  var location = document.forms["myform"]["location"].value;
  var regex = /^[a-zA-Z0-9]+[\sa-zA-Z0-9]*$/;
  if (!eventname.match(regex) || !location.match(regex)) {
    alert("Invalid input, input must be alphanumeric.");
      return false;
    }
  else {
      return true;
  }
}
