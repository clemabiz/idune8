<?php
/**
 * @file
 * Contains \Drupal\first_module\Controller\FirstController.
 */

namespace Drupal\first_module\Controller;

use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\Core\Controller\ControllerBase;

class FirstController extends ControllerBase {


/*	
  public function content() {
    return array(
      '#type' => 'markup',
      '#markup' => t('Hello World 2017'),

    );
  }

*/

/**
 * Custom content function. 
 * 
 * Set beginning and end dates, retrieve posts from database
 * saved in that time period.
 * 
 * @return 
 *   A result set of the targeted posts.
 */

  public function content() {
/*    return array(
		'#type' => 'markup',
		'#markup' => t('Hello World 2017'),
    );
*/	

	//Get today's date.
	$today = getdate();
	//Calculate the date a week ago.
	$start_time = mktime(0, 0, 0,$today['mon'],($today['mday'] - 30), $today['year']);
	//Get all posts from one week ago to the present.
	$end_time = time();




// you can write your own query to fetch the data I have given my example.

    $result = \Drupal::database()->select('users_field_data', 'n')
		->fields('n', array('uid', 'name', 'mail'))

//		->condition('status', 1) //Published.
		->execute()->fetchAllAssoc('uid');
// Create the row element.
    $rows = array();
    foreach ($result as $row => $content) {

	    $rates = $this->getRate();
		dpm($rate->am)	;

/*	    if ($content->shift_type = 'E') {
	      $rate = getRate->am;
  		} elseif ($content->shift_type = 'L') {
	      $rate = getRate;
   		} elseif ($content->shift_type = 'N') {
	      $rate = getRate;
  		}
*/
  		$pay = $rates->am;

		$rows[] = array(
		'data' => array($content->uid, $content->name, $content->mail, $pay));
    }
// Create the header.
    $header = array('uid', 'name', 'mail', 'Rate');
    $output = array(
      '#theme' => 'table',    // Here you can write #type also instead of #theme.
      '#header' => $header,
      '#rows' => $rows,

    );
    return $output;
  }

  	public function getRate() {
    // db_query()
  
	//Use Database API to retrieve current posts.

	    $results = \Drupal::database()->select('rates', 'r')
			->fields('r', array('rid', 'profession', 'profession', 'level', 'client_type','am', 'pm', 'nd', 'sat', 'sun', 'descr'))
			->condition('profession', 1)
			->condition('profession', 1)
			->condition('level', 1)
			->condition('client_type', 1)
			->execute()->fetchAllAssoc('rid');

	    $rows = array();
	    foreach ($results as $result => $rate) {
	      	$rates[] = array(
	        	'data' => array($rate->am, $rate->pm, $rate->nd, $rate->nd, $rate->sat, $rate->sun));

		}

	return $rates;

	}  

}