function FilesView() {
  this.uploadRequestSent = false;
  this.currentUri = location.href;
  this.timerID = null;
  this.elapsed = 0;
}

FilesView.prototype.initialize = function(filesInputName, deleteStrings) {
  var self = this;
  this.uploadButton = document.getElementById('tb_upload_file');
  this.deleteButton = document.getElementById('tb_delete_file');
  this.headerProgress = document.getElementById('header_progress');
  this.headerTotal = document.getElementById('header_total');
  this.uploadTimer = document.getElementById('upload_timer');
  this.uploadFrame = document.getElementById('upload_frame');
  this.files = document.getElementsByName(filesInputName);
  this.filesCount = this.files.length;
  this.filesSelected = 0;
  this.selectAll = document.getElementById('select_all');
  this.alertDeleteFiles = deleteStrings[0];
  this.alertDeleteFile = deleteStrings[1];
  this.hintDelete = deleteStrings[2];
  this.hintNoDelete = deleteStrings[3];
  this.checkResponseTimeout = 200; // ms

  if (this.uploadFrame) {
    appendClass(this.uploadButton, 'tb-button-enabled');

    // IE 'frame:hover' fix
    this.uploadFrame.onmouseover = function() {
      if (!hasClass(self.uploadButton, 'hover')) {
        appendClass(self.uploadButton, 'hover');
      }
    };

    this.uploadFrame.onmouseout = function() {
      if (hasClass(self.uploadButton, 'hover')) {
        removeClass(self.uploadButton, 'hover');
      }
    };
  }

  this.deleteButton.title = this.hintNoDelete;
  this.deleteButton.onclick = function() {
    self.onDeleteButtonClick();
  };

  if (this.filesCount > 0) {
    this.selectAll.onclick = function() {
      self.onSelectAll();
    };

    for (var i = 0; i < this.filesCount; i++) {
      var file = this.files[i];
      this.files[i].onclick = function(file) {
        return function() {
          self.onFileSelected(file);
        }
      }(file);
    }
  }
  else {
    this.selectAll.style.display = 'none';
  }
};

FilesView.prototype.onSelectAll = function() {
  var checked = this.selectAll.checked;
  for (var i = 0; i < this.filesCount; i++) {
    this.files[i].checked = checked;
  }
  this.filesSelected = checked ? this.filesCount : 0;
  this.enableDelete(this.filesSelected > 0);
};

FilesView.prototype.onFileSelected = function(checkbox) {
  if (this.filesSelected == 0) {
    this.enableDelete(true);
  }
  this.filesSelected += checkbox.checked ? 1 : -1;
  this.selectAll.checked = (this.filesSelected == this.filesCount);
  if (this.filesSelected == 0) {
    this.enableDelete(false);
  }
};

FilesView.prototype.enableDelete = function(enable) {
  if (enable) {
    this.deleteButton.title = this.hintDelete;
    appendClass(this.deleteButton, 'tb-button-enabled');
  }
  else {
    this.deleteButton.title = this.hintNoDelete;
    removeClass(this.deleteButton, 'tb-button-enabled');
  }
};

FilesView.prototype.onDeleteButtonClick = function() {
  if (hasClass(this.deleteButton, 'tb-button-enabled')) {
    var self = this;
    var alertText = this.alertDeleteFiles;
    if (this.filesSelected == 1) {
      for (var i = 0; i < this.filesCount; i++) {
        if (this.files[i].checked) {
          var link = document.getElementById('link' + this.files[i].value);
          alertText = this.alertDeleteFile.replace('%s', ' \'' + link.innerHTML + '\'');
          break;
        }
      }
    }
    messageBox.show(alertText,
      MessageBox.TypeInfo,
      MessageBox.ButtonYes | MessageBox.ButtonNo,
      function(code) {
        if (code == MessageBox.ButtonYes) {
          var deleteForm = document.getElementById('delete_form');
          self.submitForm(deleteForm);
        }
      }
    );
  }
};

FilesView.prototype.onUploadFrameReady = function(frame) {
  if (!this.uploadRequestSent) {
    var frameDocument = frame.contentDocument || frame.contentWindow.document;
    var uploadInput = frameDocument.getElementById('upload_input');

    var self = this;
    uploadInput.onchange = function() {
      if (uploadInput.value) {
        frame.blur(); // FF BUG#554039 fix
        self.uploadRequestSent = true;
        self.startTimer();
        self.submitForm(uploadInput.parentNode);
        self.checkResponse();
      }
    };
  }
};

FilesView.prototype.submitForm = function(form) {
  form.submit();
  this.disableControls();
};

FilesView.prototype.disableControls = function() {
  removeClass(this.uploadButton, 'tb-button-enabled');
  removeClass(this.deleteButton, 'tb-button-enabled');

  this.selectAll.disabled = true;
  for (var i = 0; i < this.filesCount; i++) {
    this.files[i].disabled = true;
  }

  if (this.uploadFrame) {
    this.uploadFrame.style.display = 'none';
  }
};

FilesView.prototype.checkResponse = function() {
  var self = this;
  var finished = false;
  try {
    var frameDocument = this.uploadFrame.contentDocument || this.uploadFrame.contentWindow.document;
    finished = frameDocument.getElementById('upload_input') ? false : true;
  }
  catch(e) {
    finished = true;
  }
  if (finished) {
    this.stopTimer();
    navigate(this.currentUri);
  }
  else {
    if (this.checkResponseTimeout < 1000) {
      this.checkResponseTimeout += 10;
    }
    setTimeout(function() {
      self.checkResponse();
    }, this.checkResponseTimeout);
  }
};

FilesView.prototype.startTimer = function() {
  this.headerProgress.style.display = 'block';
  this.headerTotal.style.display = 'none';

  var self = this;
  this.elapsed = 0;
  this.timerID = setInterval(function() {
    self.elapsed++;
    self.uploadTimer.innerHTML = '(' + formatTimeInterval(self.elapsed) + ')';
  }, 1000);
};

FilesView.prototype.stopTimer = function() {
  this.headerProgress.style.display = 'none';
  this.headerTotal.style.display = 'block';

  if (this.timerID != null) {
    clearInterval(this.timerID);
    this.timerID = null;
  }
};

var filesView = new FilesView();