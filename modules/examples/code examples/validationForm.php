<?php
/**
* @file
* Contains \Drupal\my_contact\Form\ContactForm.
*/

namespace Drupal\my_contact\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

// Traits
use Drupal\Core\StringTranslation\StringTranslationTrait;

class ContactForm extends FormBase {
    use StringTranslationTrait;

    /**
    * {@inheritdoc}.
    */
    public function getFormId() {
        return 'my_contact_form';
    }

    /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state, $params = NULL) {
        // This will generate an anchor scroll to the form when submitting
        $form['#action'] = '#my-contact-form';

        // Disable caching & HTML5 validation
        $form['#cache']['max-age'] = 0;
        $form['#attributes']['novalidate'] = 'novalidate';

        $form['personnal'] = array(
          '#type'  => 'fieldset',
          '#title' => $this->t('Your personnal data'),
        );

        $form['personnal']['firstname'] = array(
            '#title'       => $this->t('Your firstname *'),
            '#placeholder' => $this->t('Alain'),
            '#type'        => 'textfield',
            '#attributes'  => ['size' => 25],
            '#required'    => false,
            '#prefix'      => '<div class="form-group">',
            '#suffix'      => '</div>',
        );

        $form['personnal']['lastname'] = array(
            '#title'       => $this->t('Your lastname *'),
            '#placeholder' => $this->t('Rochat'),
            '#type'        => 'textfield',
            '#attributes'  => ['size' => 24],
            '#required'    => false,
            '#prefix'      => '<div class="form-group">',
            '#suffix'      => '</div>',
        );

        $form['personnal']['email'] = array(
            '#title'       => $this->t('Your email *'),
            '#placeholder' => $this->t('alain.rochat@domain.ltd'),
            '#type'        => 'textfield',
            '#required'    => false,
            '#prefix'      => '<div class="form-group">',
            '#suffix'      => '</div>',
        );

        $form['message'] = array(
          '#type'  => 'fieldset',
          '#title' => $this->t('Your message'),
        );

        $form['message']['subject'] = array(
            '#title'    => $this->t('Subject *'),
            '#type'     => 'textfield',
            '#required' => false,
            '#prefix'      => '<div class="form-group">',
            '#suffix'      => '</div>',
        );

        $form['message']['message'] = array(
            '#title'       => $this->t('Message *'),
            '#type'        => 'textarea',
            '#required'    => false,
            '#attributes'  => ['cols' => 59],
            '#prefix'      => '<div class="form-group">',
            '#suffix'      => '</div>',
        );

        $form['actions']['submit'] = array(
            '#type'        => 'submit',
            '#value'       => $this->t('Send'),
            '#attributes'  => ['class' => array('btn-lg btn-primary pull-right')],
            '#button_type' => 'primary',
            '#prefix'      => '<div class="form-group">',
            '#suffix'      => '</div>',
        );

    $form['user_email'] = array(
      '#type' => 'textfield',
      '#title' => 'User or Email',
      '#description' => 'Please enter in a user or email',
      '#prefix' => '<div id="user-email-result"></div>',
      '#ajax' => array(
        'callback' => '::checkUserEmailValidation',
        'effect' => 'fade',
        'event' => 'change',
        'progress' => array(
          'type' => 'throbber',
          'message' => NULL,
        ),
      ),
    );






        return $form;
    }

    /**
    * {@inheritdoc}
    */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        // Assert the firstname is valid
        if (!$form_state->getValue('firstname') || empty($form_state->getValue('firstname'))) {
            $form_state->setErrorByName('[personnal][firstname]', $this->t('Votre prénom est obligatoire.'));
        }

        // Assert the lastname is valid
        if (!$form_state->getValue('lastname') || empty($form_state->getValue('lastname'))) {
            $form_state->setErrorByName('[personnal][lastname]', $this->t('Votre nom est obligatoire.'));
        }

        // Assert the email is valid
        if (!$form_state->getValue('email') || !filter_var($form_state->getValue('email'), FILTER_VALIDATE_EMAIL)) {
            $form_state->setErrorByName('[personnal][email]', $this->t('Votre adresse e-mail semble invalide.'));
        }

        // Assert the subject is valid
        if (!$form_state->getValue('subject') || empty($form_state->getValue('subject'))) {
            $form_state->setErrorByName('[message][subject]', $this->t('Le sujet de votre demande est important.'));
        }

        // Assert the message is valid
        if (!$form_state->getValue('message') || empty($form_state->getValue('message'))) {
            $form_state->setErrorByName('[message][message]', $this->t('Le message de votre demande est important.'));
        }

        // If validation errors, add inline errors
        if ($errors = $form_state->getErrors()) {
            // Add error to fields using Symfony Accessor
            $accessor = PropertyAccess::createPropertyAccessor();
            foreach ($errors as $field => $error) {
                if ($accessor->getValue($form, $field)) {
                    $accessor->setValue($form, $field.'[#prefix]', '<div class="form-group error">');
                    $accessor->setValue($form, $field.'[#suffix]', '<div class="input-error-desc">' .$error. '</div></div>');
                }
            }
        }
    }

    /**
    * {@inheritdoc}
    */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $data = array(
            'firstname' => $form_state->getValue('firstname'),
            'lastname'  => $form_state->getValue('lastname'),
            'email'     => $form_state->getValue('email'),
            'subject'   => $form_state->getValue('subject'),
            'message'   => $form_state->getValue('message'),
        );

        drupal_set_message($this->t('Thank you very much @firstname @lastname for your message. You will receive a confirmation email shortly.', [
            '@firstname' => $form_state->getValue('firstname'),
            '@lastname'  => $form_state->getValue('lastname'),
        ]));
    }


  public function checkUserEmailValidation(array $form, FormStateInterface $form_state) {
     $ajax_response = new AjaxResponse();
   
    // Check if User or email exists or not
    if (user_load_by_name($form_state->getValue(user_email)) || user_load_by_mail($form_state->getValue(user_email))) {
      $text = 'User or Email is exists';
      $css = array('background-color' => 'yellow', 'color' => 'green');
    } else {
      $text = 'User or Email does not exists';
      $css = array('background-color' => 'yellow', 'color' => 'red');
    }
    $ajax_response->addCommand(new HtmlCommand('#user-email-result', $text));
    $ajax_response->addCommand(new CssCommand('#user-email-result', $css));     

    // Return the AjaxResponse Object.
    return $ajax_response;
  }




    
}