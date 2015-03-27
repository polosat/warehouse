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
    body {
      background-image: url("/views/404/bg404.gif");
      padding: 0;
      margin: 0;
    }
    .banner {
      width: 100%;
      margin: 130px 0;
      padding: 20px 0 40px;
      border: 1px solid #808080;
      background-color: #FFFFFF;
      text-align: center;
      font: normal 14px Arial, Verdana, sans-serif;
    }
    .banner h1 {
      color: #606060;
      font-size: 24px;
    }
  </style>
</head>
<body>
  <div class="banner">
    <h1><?=$strings::PAGE_TITLE?></h1>
    <?=$pageText?>
  </div>
</body>
</html>