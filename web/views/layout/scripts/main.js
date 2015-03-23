function navigate(uri) {
  window.location.href = uri;
}

function getChar(e) {
  if (e.which == null) {
    if (e.keyCode < 32) return null;
    return String.fromCharCode(e.keyCode)
  }

  if (e.which != 0 && e.charCode != 0) {
    if (e.which < 32) return null;
    return String.fromCharCode(parseInt(e.which));
  }

  return null;
}

function focusElementByName(name) {
  var fields = document.getElementsByName(name);
  if (fields.length > 0) {
    fields[0].focus();
  }
}

function appendClass (element, className) {
  var currentClass = element.className;
  currentClass += currentClass ? ' ' : '';
  element.className = currentClass + className;
}

function removeClass(element, className) {
  var currentClass = element.className;
  var newClass = '';
  className = className.toLowerCase();
  if (currentClass) {
    var classes = currentClass.split(' ');
    for (var i = 0, count = classes.length; i < count; i++) {
      if (classes[i].toLowerCase() != className) {
        newClass += (i == 0 ? '' : ' ') + classes[i];
      }
    }
    element.className = newClass;
  }
}

function hasClass(element, className) {
  return ((" " + element.className + " ").replace(/[\n\t\r]/g, " ").indexOf(" " + className + " ") > -1);
}

function formatDateTime(elementID, timestamp, h24) {
  var now = new Date();
  var date = new Date(timestamp * 1000);
  var day = date.getDate();
  var month = date.getMonth();
  var year = date.getFullYear();
  var today = now.getDate() == day  && now.getMonth() == month  && now.getFullYear() == year;

  var element = document.getElementById(elementID);
  if (!today) {
    element.innerHTML = withZero(day) + '.' + withZero(month + 1) + '.' + year;
  }
  else if (h24) {
    element.innerHTML = date.getHours() + ':' + withZero(date.getMinutes());
  }
  else {
    var hours = date.getHours();
    var ampm = (hours >= 12) ? 'PM' : 'AM';
    hours %= 12;
    hours = hours ? hours : 12;
    element.innerHTML = hours + ':' + withZero(date.getMinutes()) + ' ' + ampm;
  }
}

function withZero(number) {
  return (number > 9) ? number : ('0' + number);
}

function formatTimeInterval(seconds) {
  var hs = seconds % 3600;
  var s = seconds % 60;
  var m = (hs - s) / 60;
  var h = (seconds - hs) / 3600;

  var ss = '0' + s;
  ss = ss.substr(ss.length - 2);
  if (h > 0) {
    var mm = '0' + m;
    return h + ':' + mm.substr(mm.length - 2) + ':' + ss;
  }
  return m + ':' + ss;
}

if(typeof String.prototype.trim !== 'function') {
  String.prototype.trim = function() {
    return this.replace(/^\s+|\s+$/g, '');
  }
}
