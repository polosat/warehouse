<?php
/** @var $this DatePicker */
?>
<script type="text/javascript">
  var birthdayPicker = new DatePicker(
    new DatePicker.Config(
      '<?=$this->inputID?>',
      '<?=$this->inputPattern?>',
      <?=$this->weekDaysNames?>,
      <?=$this->shortMonthsNames?>,
      <?=$this->monthsNames?>,
      <?=$this->sundayFirst?>,
      <?=$this->minYear?>,
      <?=$this->minMonth?>,
      <?=$this->minDay?>,
      <?=$this->maxYear?>,
      <?=$this->maxMonth?>,
      <?=$this->maxDay?>
    )
  );
</script>
