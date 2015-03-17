<?php/** @var LayoutView $this */$strings = $this->LayoutStrings;$alert = $this->alert;$page_title = $strings::APPLICATION_NAME . ': ' . $this->headerTitle;?><!DOCTYPE html><html><head>  <meta http-equiv="X-UA-Compatible" content="IE=edge" charset="utf-8"/>  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">  <title><?=$page_title?></title><?php foreach ($this->stylesheets as $stylesheetUri): ?>  <link rel="stylesheet" type="text/css" href="<?="$stylesheetUri?".Settings::VERSION?>" /><?php endforeach ?><?php foreach ($this->scripts as $scriptUri): ?>  <script type="text/javascript" src="<?="$scriptUri?".Settings::VERSION?>" ></script><?php endforeach ?></head><body><?php if ($this->messageBoxRequired): ?><script type="text/javascript">  var messageBox = new MessageBox(<?=MessageBox::GetButtonTitles($strings->GetLanguage())?>);<?php if ($alert): ?><?php if ($this->focusedElement): ?>  // TODO: File name should be html'ed before  messageBox.show('<?=$alert->Text?>', <?=$alert->Type?>, <?=$alert->Buttons?>, function() {    focusElementByName('<?=$this->focusedElement?>');  });<?php else: ?>  messageBox.show('<?=$alert->Text?>', <?=$alert->Type?>, <?=$alert->Buttons?>);<?php endif ?><?php endif ?></script><?php endif ?><div class="header">  <div class="header-item header-item-left"><?=$this->headerTitle?></div><?php foreach ($this->headerItems as $link): ?>  <div class="header-item"><a href="<?=$link->uri?>"><?=$link->text?></a>|</div><?php endforeach ?><?php if ($this->languageItems): ?>  <div class="header-item">    <ul id="language_menu" class="drop-menu">      <li onclick="">        <img class="flag-image" src="/views/layout/img/flags/<?=$this->languageItems[0]->code?>.png" /><span><?=$this->languageItems[0]->name?></span>        <ul><?php for ($i = 1, $count = count($this->languageItems); $i < $count; $i++): ?>            <li>              <a href="<?=$this->languageItems[$i]->uri?>">                <img class="flag-image" src="/views/layout/img/flags/<?=$this->languageItems[$i]->code?>.png" /><span><?=$this->languageItems[$i]->name?></span>              </a>            </li><?php endfor ?>        </ul>      </li>    </ul>  </div><?php endif ?></div><?php $this->RenderBody() ?><?php if (!$alert && $this->focusedElement): ?><script type="text/javascript">focusElementByName('<?=$this->focusedElement?>');</script><?php endif ?></body></html>