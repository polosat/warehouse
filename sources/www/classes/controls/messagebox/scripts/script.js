function MessageBox(buttons) {
  this.shadow = null;
  this.messageBox = null;
  this.text = null;
  this.buttons = [];
  this.onClose = null;
  this.bodyElement = document.getElementsByTagName('body')[0];
  this.htmlElement = document.getElementsByTagName('html')[0];

  var self = this;

  var initialize = function() {
    self.shadow = document.createElement('div');
    self.shadow.className = 'message-box-shadow';
    self.messageBox = document.createElement('div');
    self.messageBox.className = 'message-box';
    self.messageBox.tabIndex = -1;
    self.text = document.createElement('div');
    self.text.className = 'message-box-text';
    self.messageBox.appendChild(self.text);

    var code = MessageBox.ButtonOK;
    for (var i = 0, count = buttons.length; i < count; i++) {
      var button = createButton(buttons[i], code);
      self.messageBox.appendChild(button);
      self.buttons[i] = button;
      code <<= 1;
    }

    self.shadow.appendChild(self.messageBox);
    self.bodyElement.insertBefore(self.shadow, self.bodyElement.firstChild);
  };

  var createButton = function(name, code) {
    var button = document.createElement('input');
    button.type = 'button';
    button.className = 'button';
    button.value = name;
    button.style.display = 'none';
    button.onclick = function() {
      removeClass(self.bodyElement, 'show-message-box');
      removeClass(self.htmlElement, 'show-message-box');
      self.text.innerHTML = '';
      if (self.onClose) {
        self.onClose(code);
        self.onClose = null;
      }
    };
    return button;
  };

  initialize();
}


MessageBox.TypeInfo     = 0;
MessageBox.TypeError    = 1;
MessageBox.ButtonOK     = 1;
MessageBox.ButtonCancel = 2;
MessageBox.ButtonYes    = 4;
MessageBox.ButtonNo     = 8;


MessageBox.prototype.show = function(text, type, buttons, onClose) {
  this.text.innerHTML = text;

  switch (type) {
    case MessageBox.TypeInfo:
      this.messageBox.className = 'message-box info-border';
      break;
    case MessageBox.TypeError:
      this.messageBox.className = 'message-box error-border';
      break;
    default:
      this.messageBox.className = 'message-box ';
  }

  var code = MessageBox.ButtonOK;
  for (var i = 0, count = this.buttons.length; i < count; i++) {
    var button = this.buttons[i];
    button.style.display = (buttons & code) ? 'inline' : 'none';
    code <<= 1;
  }

  this.onClose = onClose ? onClose : null;

  appendClass(this.bodyElement, 'show-message-box');
  appendClass(this.htmlElement, 'show-message-box');

  var focused = (buttons == MessageBox.ButtonOK) ? this.buttons[0] : this.messageBox;
  setTimeout(function() {
    focused.focus();
  }, 0);
};

