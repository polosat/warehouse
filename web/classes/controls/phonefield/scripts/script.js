function PhoneField(inputID) {
  var inputPadding = 14;

  var input = document.getElementById(inputID);
  var inputWidth = input.clientWidth;
  input.style.paddingLeft = inputPadding + 'px';
  input.style.width = inputWidth - inputPadding + 'px';

  var wrapper = document.createElement('div');
  wrapper.className = 'phone-field-wrapper';
  wrapper.style.width = input.offsetWidth + 'px';

  input.parentNode.insertBefore(wrapper, input.nextSibling);
  wrapper.appendChild(input);

  var value = input.value.trim();
  var prefix = document.createElement('div');
  prefix.className = 'phone-field-prefix';
  prefix.style.lineHeight = wrapper.clientHeight + 'px';
  prefix.style.visibility = value ? 'visible' : 'hidden';
  prefix.innerHTML = '+';
  wrapper.insertBefore(prefix, input);

  input.onfocus = function() {
    prefix.style.visibility = 'visible';
  };

  input.onblur = function() {
    var value = input.value.replace(/[^\d\-\s]/g,'');
    value = value.replace(/\s+/g, ' ');
    value = value.replace(/\s\-+/g, '-');
    value = value.replace(/\-+\s/g, '-');
    value = value.replace(/^[\s\-]+/g, '');
    value = value.replace(/[\s\-]+$/g, '');
    input.value = value;

    prefix.style.visibility = value ? 'visible' : 'hidden';
  };

  input.onkeypress = function(e) {
    e = e || event;
    if (e.ctrlKey || e.altKey || e.metaKey) return true;

    var chr = getChar(e);
    if (chr === null) return true;
    return (chr >= '0' && chr <= '9' || chr == ' ' || chr == '-');
  };
}