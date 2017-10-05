$form_state is now a classed object

Primary tabs
View(active tab)
View history
Posted by tim.plunkett on 31 July 2014
Change record status: 
Published (View all published change records)
Project: 
Drupal core
Introduced in branch: 
8.x
Issues: 
#2225353: Convert $form_state to an object and provide methods like setError()
#2308821: Replace FormErrorInterface with $form_state->setErrorByName() and $form_state->setError()
#2313479: Add FormState::setResponse()
#2313823: Use FormStateInterface for all typehints
#2316533: Add getValue/setValue/hasValue and isValueEmpty to FormState
#2318087: Replace $form_state['input'] with FormState::getUserInput()
#2332389: Finish adding methods to FormStateInterface
#2335659: Remove FormState ArrayAccess usage from core
Description: 
Since its introduction in #138706: FormAPI 3: Ready to rock, the form state has been an array. The form state tracks the "current state" of the form as it is built, processed, validated, and submitted.

In Drupal 8, the newly added FormStateInterface is used instead of an array for $form_state.

The following code snippets reference the FormBuilder. See drupal_*_form() methods are replaced by a form builder service for more information.

Before:

$form_state['rebuild'] = TRUE;
$form_builder->buildForm('Drupal\my_module\Form\MyForm', $form_state);
After:

use Drupal\Core\Form\FormState;

$form_state = new FormState();
$form_state->setRebuild();
$form_builder->buildForm('Drupal\my_module\Form\MyForm', $form_state);
In order to do things like setting errors, you no longer need the whole form builder:

Before:

form_set_error($element, $form_state, $message);
form_set_error('element_name', $form_state, $message);
After:

$form_state->setError($element, $message);
$form_state->setErrorByName($element, $message);
In addition to the functions that were replaced by methods, all array-based usage of $form_state is now replaced by methods:
Before:

$complete_form = &$form_state['complete_form'];

$form_state['complete_form'] = &$complete_form;
After:

$complete_form = &$form_state->getCompleteForm();

$form_state->setCompleteForm($complete_form);
Before:

$response = $form_state['response'];
$form_state['response'] = $response;
After:

$response = $form_state->getResponse();
$form_state->setResponse($response);
Before:

$form_state['redirect'] = new Url('route_name', array('route' => 'parameters'));
$form_state['redirect'] = array('route_name', array('route' => 'parameters'));
$redirect = $form_state['redirect'];
$form_state['no_redirect'] = TRUE;
if (!empty($form_state['no_redirect'])) {
}
After:

$form_state->setRedirectUrl(new Url('route_name', ['route' => 'parameters']));
$form_state->setRedirect('route_name', ['route' => 'parameters']);
$redirect = $form_state->getRedirect();
$form_state->disableRedirect();
if ($form_state->isRedirectDisabled()) {
}
Before:

$storage = &$form_state['storage'];
$form_state['storage'] = &$storage;
After:

$storage = &$form_state->getStorage();
$form_state->setStorage($storage);
Before:

$temporary = $form_state['temporary'];
$form_state['temporary'] = $temporary;
After:

$temporary = $form_state->getTemporary();
$form_state->setTemporary($temporary);
Before:

$arbitrary_value = $form_state['arbitrary_key'];
$form_state['arbitrary_key'] = $arbitrary_value;

$nested_arbitrary_value = $form_state['nested']['arbitrary_key'];
$form_state['nested']['arbitrary_key'] = $nested_arbitrary_value;
After:

$arbitrary_value = $form_state->get('arbitrary_key');
$form_state->set('arbitrary_key', $arbitrary_value);

$nested_arbitrary_value = $form_state->get(['nested', 'arbitrary_key']);
$form_state->set(['nested', 'arbitrary_key'], $nested_arbitrary_value);
Before:

$build_info = $form_state['build_info'];
$form_state['build_info'] = $build_info;
$form_state['build_info']['args'] = $args;
$args = $form_state['build_info']['args'];
After:

$build_info = $form_state->getBuildInfo();
$form_state->setBuildInfo($build_info);
$form_state->addBuildInfo('args', $args);
$args = $form_state->getBuildInfo()['args'];
Before:

$input = &$form_state['input'];
$form_state['input'] = $input;
After:

$input = &$form_state->getUserInput();
$form_state->setUserInput($input);
Before:

$foo = $form_state['values']['foo']; // This will return an array. print_r($foo)

$foobar = $form_state['values']['foo']['bar'];

$values = $form_state['values'];

$form_state['values']['foo'] = 'bar';

$form_state['values']['bar']['baz'] = 'foo';

if (isset($form_state['values']['foo'])) {
}

if (isset($form_state['values']['foo']['bar'])) {
}

if (empty($form_state['values']['foo'])) {
}

if (empty($form_state['values']['foo']['bar'])) {
}
After:

$foo = $form_state->getValue('foo');

$foobar = $form_state->getValue(['foo', 'bar']);

$values = $form_state->getValues();

$form_state->setValue('foo', array('bar'));

$form_state->setValue(['bar', 'baz'], 'foo');

if ($form_state->hasValue('foo')) {
}

if ($form_state->hasValue(['foo', 'bar'])) {
}

if ($form_state->isValueEmpty('foo')) {
}

if ($form_state->isValueEmpty(['foo', 'bar'])) {
}
Before:

$form_state['rebuild'] = TRUE;
$form_state['rebuild'] = FALSE;
if (!empty($form_state['rebuild'])) {
}
After:

$form_state->setRebuild();
$form_state->setRebuild(FALSE);
if ($form_state->isRebuilding()) {
}
Before:

$form_state['build_info']['callback_object'] = $callback;
$callback = $form_state['build_info']['callback_object'];
After:

$form_state->setFormObject($callback);
$callback = $form_state->getFormObject();
Before:

$form_state['always_process'] = TRUE;
$form_state['always_process'] = FALSE;
if (!empty($form_state['always_process'])) {
}
After:

$form_state->setAlwaysProcess();
$form_state->setAlwaysProcess(FALSE);
if ($form_state->getAlwaysProcess()) {
}
Before:

$buttons = $form_state['buttons'];
$form_state['buttons'] = $buttons;
After:

$buttons = $form_state->getButtons();
$form_state->setButtons($buttons);
Before:

$form_state['cache'] = TRUE;
$form_state['cache'] = FALSE;
$form_state['no_cache'] = TRUE;
if (!empty($form_state['cache']) && empty($form_state['no_cache'])) {
}
After:

$form_state->setCached();
$form_state->setCached(FALSE);
$form_state->disableCache();
if ($form_state->isCached()) {
}
Before:

$form_state['executed'] = TRUE;
if (!empty($form_state['executed'])) {
}
After:

$form_state->setExecuted();
if ($form_state->isExecuted()) {
}
Before:

$groups = &$form_state['groups'];
$form_state['groups'] = $groups;
After:

$groups = &$form_state->getGroups();
$form_state->setGroups($groups);
Before:

$form_state['has_file_element'] = TRUE;
$form_state['has_file_element'] = FALSE;
if (!empty($form_state['has_file_element'])) {
}
After:

$form_state->setHasFileElement();
$form_state->setHasFileElement(FALSE);
if ($form_state->hasFileElement()) {
}
Before:

$limit_validation_errors = $form_state['limit_validation_errors'];
$form_state['limit_validation_errors'] = $limit_validation_errors;
After:

$limit_validation_errors = $form_state->getLimitValidationErrors();
$form_state->setLimitValidationErrors($limit_validation_errors);
Before:

$form_state['method'] = 'get';
if ($form_state['method'] == 'get') {
}
After:

$form_state->setMethod('get');
if ($form_state->isMethodType('get')) {
}
Before:

$form_state['must_validate'] = TRUE;
$form_state['must_validate'] = FALSE;
if (!empty($form_state['must_validate'])) {
}
After:

$form_state->setValidationEnforced();
$form_state->setValidationEnforced(FALSE);
if ($form_state->isValidationEnforced()) {
}
Before:

$form_state['process_input'] = TRUE;
$form_state['process_input'] = FALSE;
if (!empty($form_state['process_input'])) {
}
After:

$form_state->setProcessInput();
$form_state->setProcessInput(FALSE);
if ($form_state->isProcessingInput()) {
}
Before:

$form_state['programmed'] = TRUE;
$form_state['programmed'] = FALSE;
if (!empty($form_state['programmed'])) {
}
After:

$form_state->setProgrammed();
$form_state->setProgrammed(FALSE);
if ($form_state->isProgrammed()) {
}
Before:

$form_state['programmed_bypass_access_check'] = TRUE;
$form_state['programmed_bypass_access_check'] = FALSE;
if (!empty($form_state['programmed_bypass_access_check'])) {
}
After:

$form_state->setProgrammedBypassAccessCheck();
$form_state->setProgrammedBypassAccessCheck(FALSE);
if ($form_state->isBypassingProgrammedAccessChecks()) {
}
Before:

$form_state['submitted'] = TRUE;
if (!empty($form_state['submitted'])) {
}
After:

$form_state->setSubmitted();
if ($form_state->isSubmitted()) {
}
Before:

$form_state['validation_complete'] = TRUE;
$form_state['validation_complete'] = FALSE;
if (!empty($form_state['validation_complete'])) {
}
After:

$form_state->setValidationComplete();
$form_state->setValidationComplete(FALSE);
if ($form_state->isValidationComplete()) {
}
Before:

$build_info = $form_state['rebuild_info'];
$form_state['rebuild_info'] = $build_info;
$form_state['rebuild_info']['copy'] = $info;
$info = $form_state['rebuild_info']['copy'];
After:

$build_info = $form_state->getRebuildInfo();
$form_state->setRebuildInfo($build_info);
$form_state->addRebuildInfo('copy', $info);
$info = $form_state->getRebuildInfo()['copy'];
Before:

$submit_handlers = $form_state['submit_handlers'];
$form_state['submit_handlers'] = $submit_handlers;
After:

$submit_handlers = $form_state->getSubmitHandlers();
$form_state->setSubmitHandlers($submit_handlers);
Before:

$validate_handlers = $form_state['validate_handlers'];
$form_state['validate_handlers'] = $validate_handlers;
After:

$validate_handlers = $form_state->getValidateHandlers();
$form_state->setValidateHandlers($validate_handlers);
Before:

$triggering_element = &$form_state['triggering_element'];
$form_state['triggering_element'] = $triggering_element;
After:

$triggering_element = &$form_state->getTriggeringElement();
$form_state->setTriggeringElement($triggering_element);
Impacts: 
Module developers
Updates Done (doc team, etc.)
Online documentation: 
Not done
Theming guide: 
Not done
Module developer documentation: 
Not done
Examples project: 
Not done
Coder Review: 
Not done
Coder Upgrade: 
Not done
Other: 
Other updates done
Add new comment
Comments
 jbrown’s picture
This is not correct:
jbrown commented 3 years ago
Note this is not correct:

$storage = &$form_state->getStorage();
$form_state->setStorage($storage);
Either get $storage as a & reference, or set it once you are finished. It does not make sense to do both.
--
Jonathan Brown
http://jonathanpatrick.me/
reply
 tim.plunkett’s picture
These are not functional
tim.plunkett commented 3 years ago
These are not functional examples. Just showing what methods are available, and where appropriate, that some getters return by reference.
reply
 radamiel’s picture
How to check which button is clicked...
radamiel commented 2 years ago
public function submitForm(array &$form, FormStateInterface $form_state) {            
    $clicked_button = &$form_state->getTriggeringElement()['#parents'][0];          
    if ($clicked_button == 'save') {
         // ...
    } elseif ($clicked_button == 'delete') {
    	// ...
    } 
}
reply
 SKAUGHT’s picture
#validate and #submit still
SKAUGHT commented about a year ago
#validate and #submit still exist.. rather then odd logic loops in one the submit.

....
class myForm extends FormBase {
   public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {
      $form['actions'] = ['#type' => 'actions'];
      $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => t('Save Changes'),
        '#validate' => array('::duplicate_check', '::empty_check'),
      ];

        $form['actions']['alpha-sort'] = array(
          '#type' => 'submit',
          '#value' => t('Sort Alpabetically') . '°',  
          '#submit' => array('::alpha_sort'),
          '#validate' => array('::duplicate_check', '::empty_check'),
        );
  }

  public function duplicate_check(array &$form, FormStateInterface $form_state) {
   // do stuff..
  }
}
now that this is within it's own class the:: is used to calls the follow up function. OOP tricks.

(: also, as your sample is a delete situation, check into Removed confirm_form() in favor of \Drupal\Core\Form\ConfirmFormBase to verify delete operations. better to be safe than sorry.
reply
 nithinkolekar’s picture
no follow button :(.
nithinkolekar commented about a year ago
no follow button :(.

hence commenting..
reply
 Rob230’s picture
If only it was this simple
Rob230 commented 6 months ago
Before: $foo = $form_state['values']['foo']

After: $foo = $form_state->getValue('foo');
Actually, in Drupal 7 $form_state['values']['foo'] would have returned the submitted value. In Drupal 8 it appears to be an array. You have to access it like: $foo = $form_state->getValue('foo')[0]['value']. For a multivalue field as well as the values themselves it will have one blank item, and an "Add another item" object, which means stepping through the array to look at the values is ugly.
reply
 yash_khandelwal’s picture
Hello,
yash_khandelwal commented 4 months ago
Hello,

I am having a problem with port below code to Drupal 8.

$form_state['field_deltas'][] = count($form_state['field_deltas']) > 0 ?max($form_state['field_deltas'])+1: 0;

Please suggest the solution for this.
reply
 pankajxenix’s picture
setValue Not working properly .
pankajxenix commented 4 months ago
Hi,
When I tried to alter a field value it replace first latter of string to 0.

$form_state->setValue('foo', array('bar'));

That means result of this code is 0ar.Also get error
Warning: Illegal string offset '_original_delta' in Drupal\Core\Field\WidgetBase->extractFormValues() (line 361 of core/lib/Drupal/Core/Field/WidgetBase.php).

I am using this in custom validation of form.
reply
 cocorodeo’s picture
Same issue with setValue()
cocorodeo commented 3 months ago
Hi pankajdrync,

I'm having the same issue on custom entity submitForm,
$from_state->setValue('foo', ['value'])
is generate warning messages "Warning: Illegal string offset '_original_delta' ..."

The value is correctly saved but I get warnings...

Did you find a solution to avoid these warnings ?