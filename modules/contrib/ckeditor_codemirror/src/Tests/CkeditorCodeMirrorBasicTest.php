<?php

namespace Drupal\ckeditor_codemirror\Tests;

use Drupal\editor\Entity\Editor;
use Drupal\simpletest\WebTestBase;
use Drupal\filter\Entity\FilterFormat;

/**
 * @coversDefaultClass \Drupal\ckeditor_codemirror\Plugin\CKEditorPlugin.
 *
 * @group ckeditor_codemirror
 */
class CkeditorCodeMirrorBasicTest extends WebTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array(
    'filter',
    'editor',
    'ckeditor',
    'node',
    'ckeditor_codemirror',
  );

  /**
   * User with permissions.
   * @var
   */
  protected $privilegedUser;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    // Create text format, associate CKEditor.
    $full_html_format = FilterFormat::create(array(
      'format' => 'full_html',
      'name' => 'Full HTML',
      'weight' => 0,
      'filters' => array(),
    ));
    $full_html_format->save();
    $editor = Editor::create([
      'format' => 'full_html',
      'editor' => 'ckeditor',
    ]);
    $editor->save();

    // Create node type.
    $this->drupalCreateContentType(array(
      'type' => 'article',
      'name' => 'Article',
    ));

    $this->privilegedUser = $this->drupalCreateUser(array(
      'administer site configuration',
      'administer filters',
      'create article content',
      'edit any article content',
      'use text format full_html',
    ));
    $this->drupalLogin($this->privilegedUser);
  }

  /**
   * Check the library status on "Status report" page.
   */
  public function testCheckStatusReportPage() {
    $this->drupalLogin($this->privilegedUser);
    $this->drupalGet('admin/reports/status');

    if (($library = libraries_detect('ckeditor.codemirror')) && !empty($library['library path'])) {
      $this->assertRaw(t('CKEditor CodeMirror plugin %version installed at %path.',
          array(
            '%path' => $library['library path'],
            '%version' => $library['version'],
          ))
      );
    }
    else {
      $this->assertText(t('CKEditor CodeMirror plugin was not found.'));
    }
  }

  /**
   * Enable CKEditor CodeMirror plugin.
   */
  public function testEnableCkeditorCodeMirrorPlguin() {
    $this->drupalLogin($this->privilegedUser);
    $this->drupalGet('admin/config/content/formats/manage/full_html');
    $this->assertText(t('Enable CodeMirror source view syntax highlighting.'));
    $this->assertNoFieldChecked('edit-editor-settings-plugins-codemirror-enable');

    // Enable the plugin.
    $edit = array(
      'editor[settings][plugins][codemirror][enable]' => '1',
    );
    $this->drupalPostForm(NULL, $edit, t('Save configuration'));
    $this->assertText(t('The text format Full HTML has been updated.'));

    // Check for the plugin on node add page.
    $this->drupalGet('node/add/article');
    $editor_settings = $this->getDrupalSettings()['editor']['formats']['full_html']['editorSettings'];

    if (($library = libraries_detect('ckeditor.codemirror')) && !empty($library['library path'])) {
      $ckeditor_enabled = $editor_settings['codemirror']['enable'];
      $this->assertTrue($ckeditor_enabled);
    }
  }
}
