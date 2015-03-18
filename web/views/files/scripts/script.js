function InitializeFileView(filesInputName, alertDeleteFiles, alertDeleteFile, hintDelete, hintNoDelete) {
  var files = document.getElementsByName(filesInputName);
  var filesCount = files.length;
  var selectAll = document.getElementById('select_all');
  var uploadButton = document.getElementById('upload_button');
  var deleteButton = document.getElementById('tb_delete_file');
  var selectedFiles = 0;

  function initialize() {
    deleteButton.onclick = onDeleteButtonClick;
    deleteButton.title = hintNoDelete;

    if (filesCount > 0) {
      selectAll.onclick = onSelectAll;
    }
    else {
      selectAll.style.display = 'none';
    }

    if (uploadButton) {
      uploadButton.onchange = function() {
        submitForm('upload_form');
      }
    }

    for (var i = 0; i < filesCount; i++) {
      var file = files[i];
      files[i].onclick = function(file) {
        return function() {
          onFileSelected(file);
        }
      }(file);
    }
  }

  function onDeleteButtonClick() {
    if (selectedFiles > 0) {
      var alertText = alertDeleteFiles;
      if (selectedFiles == 1) {
        for (var i = 0; i < filesCount; i++) {
          if (files[i].checked) {
            var link = document.getElementById('link' + files[i].value);
            alertText = alertDeleteFile.replace('%s', ' \'' + link.innerHTML + '\'');
            break;
          }
        }
      }
      messageBox.show(alertText,
        MessageBox.TypeInfo,
        MessageBox.ButtonYes | MessageBox.ButtonNo,
        function(code) {
          if (code == MessageBox.ButtonYes) {
            submitForm('delete_form');
          }
        }
      );
    }
  }

  function onFileSelected(checkbox) {
    if (selectedFiles == 0) {
      enableDelete(true);
    }
    selectedFiles += checkbox.checked ? 1 : -1;
    selectAll.checked = (selectedFiles == filesCount);
    if (selectedFiles == 0) {
      enableDelete(false);
    }
  }

  function onSelectAll() {
    var checked = selectAll.checked;
    for (var i = 0; i < filesCount; i++) {
      files[i].checked = checked;
    }
    selectedFiles = checked ? filesCount : 0;
    enableDelete(selectedFiles > 0);
  }

  function enableDelete(enable) {
    if (enable) {
      deleteButton.title = hintDelete;
      appendClass(deleteButton, 'tb-button-enabled');
    }
    else {
      deleteButton.title = hintNoDelete;
      removeClass(deleteButton, 'tb-button-enabled');
    }
  }

  function submitForm(formID) {
    var form = document.getElementById(formID);
    form.submit();
  }

  initialize();
}