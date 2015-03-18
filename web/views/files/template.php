<?php
/** @var FilesView $this */
$bag = $this->Bag;
$strings = $this->FilesStrings;
$layoutStrings = $this->LayoutStrings;
$files = $this->Bag->Files;
$filesCount = count($files);
$uploadAllowed = $filesCount < Settings::MAX_FILES_PER_USER;
$totalText = $filesCount ? $strings::LABEL_TOTAL . ': ' . $filesCount . ' (' . $this->FormatSize($bag->TotalSize, 1, false) . ')' : '';
$uploadHint = $uploadAllowed ? $strings::HINT_UPLOAD_NEW : $strings::HINT_LIMIT_REACHED;
$rules = $uploadAllowed ?
  sprintf($strings::HINT_RULES, Settings::MAX_FILES_PER_USER, $this->FormatSize(Settings::MAX_FILE_SIZE, 1, false)):
  $strings::HINT_LIMIT_REACHED;
?>
<div id="files_frame" class="frame">
  <div class="frame-header">
    <span id="header_title"><?=$strings::FORM_TITLE?></span>
    <span id="header_total"><?=$totalText?></span>
  </div>
  <div id="toolbar">
    <div id="tb_upload_file" class="tb-button <?php $uploadAllowed && print("tb-button-enabled")?> file-input-wrapper" title="<?=$uploadHint?>">
<?php if ($uploadAllowed): ?>
      <form id="upload_form" action="<?=$bag->UploadUri?>" method="post" enctype="multipart/form-data">
        <input id="upload_button" class="file-input" type="file" title="<?=$uploadHint?>" name="<?=FilesView::FIELD_NAME_UPLOADED_FILE?>">
      </form>
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
    InitializeFileView(
      '<?=FilesView::FIELD_NAME_SELECTED_FILE?>[]',
      '<?=$strings::ALERT_DELETE_FILES?>',
      '<?=$strings::ALERT_DELETE_FILE?>',
      '<?=$strings::HINT_DELETE_SELECTED?>',
      '<?=$strings::HINT_NOTHING_DELETE?>'
    );
  </script>
</div>
