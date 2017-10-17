<?php
/**
 * Custom content function. 
 * 
 * Set beginning and end dates, retrieve posts from database
 * saved in that time period.
 * 
 * @return 
 *   A result set of the targeted posts.
 */
function current_posts_contents(){
  //Get today's date.
  $today = getdate();
  //Calculate the date a week ago.
  $start_time = mktime(0, 0, 0,$today['mon'],($today['mday'] - 7), $today['year']);
  //Get all posts from one week ago to the present.
  $end_time = time();

  //Use Database API to retrieve current posts.
  $query = db_select('node', 'n')
    ->fields('n', array('nid', 'title', 'created'))
    ->condition('status', 1) //Published.
    ->condition('created', array($start_time, $end_time), 'BETWEEN')
    ->orderBy('created', 'DESC') //Most recent first.
    ->execute(); 
  return $query;  

//Get single value:

$query = \Drupal::database()->select('node_field_data', 'nfd');
$query->addField('nfd', 'nid');
$query->condition('nfd.title', 'Potato');
$query->range(0, 1);
$nid = $query->execute()->fetchField();

//Get single row:

$query = \Drupal::database()->select('node_field_data', 'nfd');
$query->fields('nfd', ['nid', 'title']);
$query->condition('nfd.type', 'vegetable');
$query->range(0, 1);
$vegetable = $query->execute()->fetchAssoc();

//Using db like:

$query = \Drupal::database()->select('node_field_data', 'nfd');
$query->fields('nfd', ['nid', 'title']);
$query->condition('nfd.type', 'vegetable');
$query->condition('nfd.title', $query->escapeLike('ca') . '%', 'LIKE');
$vegetable = $query->execute()->fetchAllKeyed();

//Get several rows with JOIN:

$query = \Drupal::database()->select('node_field_data', 'nfd');
$query->fields('nfd', ['nid', 'title']);
$query->addField('ufd', 'name');
$query->join('users_field_data', 'ufd', 'ufd.uid = nfd.uid');
$query->condition('nfd.type', 'vegetable');
$vegetable = $query->execute()->fetchAllAssoc('nid');

/*
Below I have also prepared examples of queries for insert, update, upsert and delete. They can be useful for your custom tables. But think twice before use these queries for tables which are implemented by Drupal core or contrib modules. Because in drupal the most operations to add, update or delete of data should be done through objects and their methods but not through direct sql queries.


Insert
*/

$query = \Drupal::database()->insert('flood');
$query->fields([
  'event',
  'identifier'
]);
$query->values([
  'My event',
  'My indentifier'
]);
$query->execute();

//You can call method values() several times to insert several rows at once.

//Update

$query = \Drupal::database()->update('flood');
$query->fields([
  'identifier' => 'My new identifier'
]);
$query->condition('event', 'My event');
$query->execute();

//Upsert

$query = \Drupal::database()->upsert('flood');
$query->fields([
  'fid',
  'identifier',
]);
$query->values([
  1,
  'My indentifier for upsert'
]);
$query->key('fid');
$query->execute();

/*
Method key() have to be used to specify the name of field, which will be used to identify the existing row. This field should have unique values, otherwise the upsert operation will always work as insert .

Delete

*/$query = \Drupal::database()->delete('flood');
$query->condition('event', 'My event');
$query->execute();
That's all. Maybe later I will add more examples here. Don't hesitate to ask for them.

}