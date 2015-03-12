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

if(typeof String.prototype.trim !== 'function') {
  String.prototype.trim = function() {
    return this.replace(/^\s+|\s+$/g, '');
  }
}

function focusElementByName(name) {
  var fields = document.getElementsByName(name);
  if (fields.length > 0) {
    fields[0].focus();
  }
}
