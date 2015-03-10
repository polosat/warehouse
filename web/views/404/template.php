<?php
/** @var NotFoundViewStrings $strings */
/** @var string $language */
$homeLink = '<a href="/' . $language .'">' . $strings::TEXT_START . '</a>';
$pageText = sprintf($strings::PAGE_TEXT, $homeLink);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" http-equiv="X-UA-Compatible" content="IE=edge" />
  <title><?=$strings::PAGE_TITLE?></title>
  <style>
    .banner {
      width: 500px;
      margin: 100px auto;
      padding: 20px 30px 40px;
      border: 1px solid #808080;
      font: normal 14px Arial, Verdana, sans-serif;
    }
    .banner-header {
      color: #FF0000;
      font-size: 24px;
    }
  </style>
</head>
<body>
  <div class="banner">
    <h1 class="banner-header"><?=$strings::PAGE_TITLE?></h1>
    <?=$pageText?>
  </div>
</body>
</html>