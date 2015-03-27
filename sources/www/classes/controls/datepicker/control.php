<?php
require_once __DIR__ . '/strings.php';

class DatePicker {
  const MONTH_NAME_TYPE_REGULAR = 0;
  const MONTH_NAME_TYPE_OF      = 1;
  const MONTH_NAME_TYPE_SHORT   = 2;
  const WEEKDAY_TYPE_SHORT2     = 0;

  protected $inputID;
  protected $minYear;
  protected $minMonth;
  protected $minDay;
  protected $maxYear;
  protected $maxMonth;
  protected $maxDay;
  protected $inputPattern;
  protected $sundayFirst;
  protected $monthsNames;
  protected $shortMonthsNames;
  protected $weekDaysNames;

  public function __construct($inputID, $language = '', DateTime $minDate = null, DateTime $maxDate = null) {
    /** @var DatePickerStrings $strings */
    $strings = DatePickerStrings::GetInstance($language);

    $this->inputID = $inputID;

    if ($minDate) {
      $this->minYear = $minDate->format('Y');
      $this->minMonth = $minDate->format('m') - 1;
      $this->minDay = $minDate->format('d');
    }
    else {
      $this->minYear = -9999;
      $this->minMonth = 0;
      $this->minDay = 1;
    }

    if ($maxDate) {
      $this->maxYear = $maxDate->format('Y');
      $this->maxMonth = $maxDate->format('m') - 1;
      $this->maxDay = $maxDate->format('d');
    }
    else {
      $this->maxYear = 9999;
      $this->maxMonth = 11;
      $this->maxDay = 31;
    }

    $this->inputPattern = $strings::INPUT_PATTERN;
    $this->sundayFirst = $strings::WEEK_SUNDAY_FIRST;
    $this->monthsNames = json_encode(self::GetMonthsArray($strings, self::MONTH_NAME_TYPE_REGULAR), JSON_UNESCAPED_UNICODE);
    $this->shortMonthsNames = json_encode(self::GetMonthsArray($strings, self::MONTH_NAME_TYPE_SHORT), JSON_UNESCAPED_UNICODE);
    $this->weekDaysNames = json_encode(self::GetWeekdaysArray($strings, self::WEEKDAY_TYPE_SHORT2), JSON_UNESCAPED_UNICODE);
  }

  public function Render() {
    require __DIR__ . '/template.php';
  }

  static public function FormatDate($language, DateTime $date) {
    /** @var DatePickerStrings $strings */
    $strings = DatePickerStrings::GetInstance($language);
    $months = self::GetMonthsArray($strings, self::MONTH_NAME_TYPE_OF);
    $month = $months[$date->format('n') - 1];
    return $date->format('j') . " $month " . $date->format('Y');
  }

  static protected function GetMonthsArray(DatePickerStrings $strings, $type) {
    switch ($type) {
      case self::MONTH_NAME_TYPE_REGULAR:
        return array(
          $strings::MONTH_NAME_JANUARY,
          $strings::MONTH_NAME_FEBRUARY,
          $strings::MONTH_NAME_MARCH,
          $strings::MONTH_NAME_APRIL,
          $strings::MONTH_NAME_MAY,
          $strings::MONTH_NAME_JUNE,
          $strings::MONTH_NAME_JULY,
          $strings::MONTH_NAME_AUGUST,
          $strings::MONTH_NAME_SEPTEMBER,
          $strings::MONTH_NAME_OCTOBER,
          $strings::MONTH_NAME_NOVEMBER,
          $strings::MONTH_NAME_DECEMBER
        );
      case self::MONTH_NAME_TYPE_OF:
        return array(
          $strings::MONTH_NAME_OF_JANUARY,
          $strings::MONTH_NAME_OF_FEBRUARY,
          $strings::MONTH_NAME_OF_MARCH,
          $strings::MONTH_NAME_OF_APRIL,
          $strings::MONTH_NAME_OF_MAY,
          $strings::MONTH_NAME_OF_JUNE,
          $strings::MONTH_NAME_OF_JULY,
          $strings::MONTH_NAME_OF_AUGUST,
          $strings::MONTH_NAME_OF_SEPTEMBER,
          $strings::MONTH_NAME_OF_OCTOBER,
          $strings::MONTH_NAME_OF_NOVEMBER,
          $strings::MONTH_NAME_OF_DECEMBER
        );
      case self::MONTH_NAME_TYPE_SHORT:
        return array(
          $strings::MONTH_SHORT_JANUARY,
          $strings::MONTH_SHORT_FEBRUARY,
          $strings::MONTH_SHORT_MARCH,
          $strings::MONTH_SHORT_APRIL,
          $strings::MONTH_SHORT_MAY,
          $strings::MONTH_SHORT_JUNE,
          $strings::MONTH_SHORT_JULY,
          $strings::MONTH_SHORT_AUGUST,
          $strings::MONTH_SHORT_SEPTEMBER,
          $strings::MONTH_SHORT_OCTOBER,
          $strings::MONTH_SHORT_NOVEMBER,
          $strings::MONTH_SHORT_DECEMBER
        );
      default:
        throw new LogicException('Unexpected month type.');
    }
  }

  static protected function GetWeekdaysArray(DatePickerStrings $strings, $type) {
    switch ($type) {
      case self::WEEKDAY_TYPE_SHORT2:
        return array(
          $strings::DAY_SHORT2_MON,
          $strings::DAY_SHORT2_TUE,
          $strings::DAY_SHORT2_WED,
          $strings::DAY_SHORT2_THU,
          $strings::DAY_SHORT2_FRI,
          $strings::DAY_SHORT2_SAT,
          $strings::DAY_SHORT2_SUN
        );
      default:
        throw new LogicException('Unexpected week day type.');
    }
  }
}
