<?php
/** @var FilesView $this */
$bag = $this->Bag;
$strings = $this->FilesStrings;
$layoutStrings = $this->LayoutStrings;

$files = $bag->Files;
$filesCount = count($files);
$uploadAllowed = $filesCount < Settings::MAX_FILES_PER_USER;

$totalText = $filesCount ? $strings::LABEL_TOTAL . ': ' . $filesCount . ' (' . $this->FormatSize($bag->TotalSize, 1, false) . ')' : '';
$hintUpload = $uploadAllowed ? $strings::HINT_UPLOAD_NEW : $strings::HINT_LIMIT_REACHED;
$rules = $uploadAllowed ?
  sprintf($strings::HINT_RULES, Settings::MAX_FILES_PER_USER, $this->FormatSize(Settings::MAX_FILE_SIZE, 1, false)):
  $strings::HINT_LIMIT_REACHED;
?>
<div id="files_frame" class="frame">
  <div class="frame-header">
    <div id="header_title"><?=$strings::FORM_TITLE?></div>
    <div id="header_total"><?=$totalText?></div>
    <div id="header_progress">
      <img id="image_spin" src="/views/files/index/img/spin.gif">
      <span><?=$strings::LABEL_UPLOADING?></span>
      <span id="upload_timer">(0:00)</span>
    </div>
  </div>
  <div id="toolbar">
    <div id="tb_upload_file" class="tb-button file-input-wrapper" title="<?=$hintUpload?>">
<?php if ($uploadAllowed): ?>
      <iframe id="upload_frame" src="<?=$bag->UploadUri?>" frameBorder="0" scrolling="no" onload="filesView.onUploadFrameReady(this)"></iframe>
<?php endif ?>
    </div><div id="tb_delete_file" class="tb-button">
    </div><div id="tb_hint"<?php $uploadAllowed || print(' class="text-red"')?>>
      <?=$rules?>
    </div><div class="tb-right-stub">
    </div>
  </div>
  <div id="files_table">
    <div class="files-row files-header">
      <div class="column column-first">
        <label for="select_all">
          <input id="select_all" type="checkbox">
        </label>
      </div><div class="column column-name">
        <span><?=$strings::COLUMN_NAME?></span>
      </div><div class="column column-size">
        <span><?=$strings::COLUMN_SIZE?></span>
      </div><div class="column column-uploaded">
        <span><?=$strings::COLUMN_UPLOADED?></span>
      </div>
    </div>
    <form id="delete_form" action="<?=$bag->DeleteUri?>" method="post">
<?php for ($i = 0, $count = max($filesCount, FilesView::MINIMUM_FILE_ROWS); $i < $count; $i++): ?>
      <div class="files-row">
<?php if ($i < $filesCount): ?>
        <div class="column column-first">
          <label for="file<?=$files[$i]->ID?>">
            <input id="file<?=$files[$i]->ID?>" value="<?=$files[$i]->ID?>" name="<?=FilesView::FIELD_NAME_SELECTED_FILE?>[]" type="checkbox">
          </label>
        </div><div class="column column-name">
          <a id="link<?=$files[$i]->ID?>" href="<?=html($files[$i]->Uri)?>"><?=html($files[$i]->Name)?></a>
        </div><div class="column column-size">
          <span><?=$this->FormatSize($files[$i]->Size)?></span>
        </div><div id="uploaded_<?=$files[$i]->ID?>" class="column column-uploaded">
        </div>
        <script type="text/javascript">formatDateTime('uploaded_<?=$files[$i]->ID?>', <?=$files[$i]->UploadedOn?>, <?=$layoutStrings::TIME_FORMAT_H24?>);</script>
<?php else: ?>
        <div class="column column-first">&nbsp;</div>
<?php endif ?>
      </div>
<?php endfor ?>
    </form>
  </div>
  <script type="text/javascript">
    filesView.initialize(
      '<?=FilesView::FIELD_NAME_SELECTED_FILE?>[]',
      '<?=$bag->StatusUri?>', [
        '<?=$strings::ALERT_DELETE_FILES?>',
        '<?=$strings::ALERT_DELETE_FILE?>',
        '<?=$strings::HINT_DELETE_SELECTED?>',
        '<?=$strings::HINT_NOTHING_DELETE?>',
        '<?=$strings::ERROR_UPLOAD_ERROR?>'
      ]
    );
  </script>
</div>
