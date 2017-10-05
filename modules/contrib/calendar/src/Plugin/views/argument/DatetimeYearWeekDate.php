<?php

namespace Drupal\calendar\Plugin\views\argument;

use Drupal\datetime\Plugin\views\Argument\Date;

/**
 * Argument handler for a day.
 *
 * @ViewsArgument("datetime_year_week")
 */
class DatetimeYearWeekDate extends Date {

  /**
   * {@inheritdoc}
   */
  protected $argFormat = 'YW';

}
