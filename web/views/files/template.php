<?php
/** @var FilesView $this */
$strings = $this->FilesStrings;
$layoutStrings = $this->LayoutStrings;
$files = $this->Bag->Files;
$filesCount = count($files);
?>
<script type="text/javascript">
  var h24 = <?=$layoutStrings::TIME_FORMAT_H24?>;

  // TODO: Leave variables here but move logic into a dedicated js file
  function onDeleteButtonClick() {
    if (selectedFiles > 0) {
      var alertText = '<?=$strings::ALERT_DELETE_FILES?>';
      if (selectedFiles == 1) {
        for (var i = 0; i < filesCount; i++) {
          if (files[i].checked) {
            var link = document.getElementById('link' + files[i].value);
            alertText = '<?=$strings::ALERT_DELETE_FILE?>\'' + link.innerHTML + '\'?';
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
      deleteButton.title = "<?=$strings::HINT_DELETE_SELECTED?>";
      appendClass(deleteButton, 'tb-button-enabled');
    }
    else {
      deleteButton.title = "<?=$strings::HINT_NOTHING_DELETE?>";
      removeClass(deleteButton, 'tb-button-enabled');
    }
  }

  function submitForm(formID) {
    var form = document.getElementById(formID);
    form.submit();
  }
</script>
<div id="files_frame" class="frame">
  <h1><?=$strings::FORM_TITLE?></h1>

  <div id="toolbar">
    <div id="tb_upload_file" class="tb-button <?php $filesCount >= 20 || print("tb-button-enabled")?> file-input-wrapper" title="You've reached the storage capacity limit">
      <form id="upload_form" action="" method="post" enctype="multipart/form-data">
        <input class="file-input" type="file" name="<?=FilesView::FIELD_NAME_USER_FILE?>" onchange="submitForm('upload_form')">
      </form>
    </div><div id="tb_delete_file" class="tb-button" title="<?=$strings::HINT_NOTHING_DELETE?>" onclick="onDeleteButtonClick()">
    </div><div id="tb_hint">
      <!--  TODO: Get sizes from the config-->
      <?=$strings::HINT_RULES?>
    </div>
  </div>

  <div id="files_table">
    <div class="files-row files-header">
      <div class="column column-first">
        <label for="select_all">
          <input id="select_all" type="checkbox" onclick="onSelectAll()">
        </label>
      </div><div class="column column-name">
        <?=$strings::COLUMN_NAME?>
      </div><div class="column column-size">
        <?=$strings::COLUMN_SIZE?>
      </div><div class="column column-uploaded">
        <?=$strings::COLUMN_UPLOADED?>
      </div>
    </div>

<!--    TODO: use php provided controller name and language -->
    <form id="files_form" action="/files/delete" method="post">
<?php for ($i = 0, $count = max($filesCount, FilesView::MINIMUM_FILE_ROWS); $i < $count; $i++): ?>
      <div class="files-row">
<?php if ($i < $filesCount): ?>
        <div class="column column-first">
          <label for="file<?=$files[$i]->ID?>">
            <input id="file<?=$files[$i]->ID?>" value="<?=$files[$i]->ID?>" name="select_file" type="checkbox" onclick="onFileSelected(this)">
          </label>
        </div><div class="column column-name">
          <a id="link<?=$files[$i]->ID?>" href="<?=html($files[$i]->Uri)?>"><?=html($files[$i]->Name)?></a>
        </div><div class="column column-size">
          <span><?=$this->FormatSize($files[$i]->Size)?></span>
        </div><div id="uploaded_<?=$files[$i]->ID?>" class="column column-uploaded">
        </div>
        <script type="text/javascript">formatDateTime('uploaded_<?=$files[$i]->ID?>', <?=$files[$i]->UploadedOn?>, h24);</script>
<?php else: ?>
        <div class="column column-first">&nbsp;</div>
<?php endif ?>
      </div>
<?php endfor ?>
    </form>
  </div>
  <script type="text/javascript">
    var selectAll = document.getElementById('select_all');
    var files = document.getElementsByName('select_file');
    var deleteButton = document.getElementById('tb_delete_file');
    var filesCount = files.length;
    var selectedFiles = 0;
    selectAll.style.display = (filesCount == 0) ? 'none' : 'inline';
  </script>
</div>
