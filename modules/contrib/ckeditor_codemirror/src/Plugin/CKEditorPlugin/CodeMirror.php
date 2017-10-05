<?php

namespace Drupal\ckeditor_codemirror\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginBase;
use Drupal\ckeditor\CKEditorPluginConfigurableInterface;
use Drupal\ckeditor\CKEditorPluginContextualInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\editor\Entity\Editor;

/**
 * Defines the "CodeMirror" plugin.
 *
 * @CKEditorPlugin(
 *   id = "codemirror",
 *   label = @Translation("CodeMirror"),
 *   module = "ckeditor_codemirror"
 * )
 */
class CodeMirror extends CKEditorPluginBase implements CKEditorPluginConfigurableInterface, CKEditorPluginContextualInterface {

  /**
   * {@inheritdoc}
   */
  public function getFile() {
    if ($library_path = libraries_get_path('ckeditor.codemirror')) {
      return $library_path . '/plugin.js';
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getDependencies(Editor $editor) {
    return array();
  }

  /**
   * {@inheritdoc}
   */
  public function getLibraries(Editor $editor) {
    return array();
  }

  /**
   * {@inheritdoc}
   */
  public function isInternal() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getButtons() {
    return array();
  }

  /**
   * {@inheritdoc}
   */
  public function isEnabled(Editor $editor) {
    $settings = $editor->getSettings();

    if (isset($settings['plugins']['codemirror'])) {
      return $editor->getSettings()['plugins']['codemirror']['enable'];
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getConfig(Editor $editor) {
    $settings = $editor->getSettings()['plugins']['codemirror'];

    return array(
      'codemirror' => array(
        'enable' => isset($settings['enable']) ? $settings['enable'] : FALSE,
        'mode' => isset($settings['mode']) ? $settings['mode'] : 'htmlmixed',
        'theme' => isset($settings['theme']) ? $settings['theme'] : 'default',
        'lineNumbers' => isset($settings['lineNumbers']) ? $settings['lineNumbers'] : TRUE,
        'lineWrapping' => isset($settings['lineWrapping']) ? $settings['lineWrapping'] : TRUE,
        'matchBrackets' => isset($settings['matchBrackets']) ? $settings['matchBrackets'] : TRUE,
        'autoCloseTags' => isset($settings['autoCloseTags']) ? $settings['autoCloseTags'] : TRUE,
        'autoCloseBrackets' => isset($settings['autoCloseBrackets']) ? $settings['autoCloseBrackets'] : TRUE,
        'enableSearchTools' => isset($settings['enableSearchTools']) ? $settings['enableSearchTools'] : TRUE,
        'enableCodeFolding' => isset($settings['enableCodeFolding']) ? $settings['enableCodeFolding'] : TRUE,
        'enableCodeFormatting' => isset($settings['enableCodeFormatting']) ? $settings['enableCodeFormatting'] : TRUE,
        'autoFormatOnStart' => isset($settings['autoFormatOnStart']) ? $settings['autoFormatOnStart'] : TRUE,
        'autoFormatOnModeChange' => isset($settings['autoFormatOnModeChange']) ? $settings['autoFormatOnModeChange'] : TRUE,
        'autoFormatOnUncomment' => isset($settings['autoFormatOnUncomment']) ? $settings['autoFormatOnUncomment'] : TRUE,
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state, Editor $editor) {
    $editor_settings = $editor->getSettings();
    if (isset($editor_settings['plugins']['codemirror'])) {
      $settings = $editor_settings['plugins']['codemirror'];
    }

    $form['#attached']['library'][] = 'ckeditor_codemirror/ckeditor_codemirror.admin';

    $form['enable'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Enable CodeMirror source view syntax highlighting.'),
      '#default_value' => isset($settings['enable']) ? $settings['enable'] : FALSE,
    );

    $form['mode'] = array(
      '#type' => 'select',
      '#title' => $this->t('Mode'),
      '#options' => array(
        'htmlmixed' => $this->t('HTML (including css, xml and javascript)'),
        'application/x-httpd-php' => $this->t('PHP (including HTML)'),
        'text/javascript' => $this->t('Javascript only'),
      ),
      '#default_value' => isset($settings['mode']) ? $settings['mode'] : 'htmlmixed',
    );

    $theme_options = array('default' => 'default');
    $themes_directory = libraries_get_path('ckeditor.codemirror') . '/theme';
    if (is_dir($themes_directory)) {
      $theme_css_files = file_scan_directory($themes_directory, '/\.css/i');
      foreach ($theme_css_files as $file) {
        $theme_options[$file->name] = $file->name;
      }
    }

    $form['theme'] = array(
      '#type' => 'select',
      '#title' => $this->t('Theme'),
      '#options' => $theme_options,
      '#default_value' => isset($settings['theme']) ? $settings['theme'] : 'default',
    );

    $checkboxes = array(
      'lineNumbers' => $this->t('Show line numbers.'),
      'lineWrapping' => $this->t('Enable line wrapping.'),
      'matchBrackets' => $this->t('Highlight matching brackets.'),
      'autoCloseTags' => $this->t('Close tags automatically.'),
      'autoCloseBrackets' => $this->t('Close brackets automatically.'),
      'enableSearchTools' => $this->t('Enable search tools.'),
      'enableCodeFolding' => $this->t('Enable code folding.'),
      'enableCodeFormatting' => $this->t('Enable code formatting.'),
      'autoFormatOnStart' => $this->t('Format code on start.'),
      'autoFormatOnModeChange' => $this->t('Format code each time source is opened.'),
      'autoFormatOnUncomment' => $this->t('Format code when a line is uncommented.'),
    );

    foreach ($checkboxes as $setting => $description) {
      $form[$setting] = array(
        '#type' => 'checkbox',
        '#title' => $description,
        '#default_value' => isset($settings[$setting]) ? $settings[$setting] : TRUE,
      );
    }

    return $form;
  }

}
