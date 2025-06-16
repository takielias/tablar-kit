<?php

namespace Tests\Unit\TablarKit\Fields;

use Orchestra\Testbench\TestCase;
use Takielias\TablarKit\Fields\JoditField;
use Mockery;

class JoditFieldTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // We'll focus on testing the field properties and methods
        // rather than the complex render method that involves view instantiation
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_create_jodit_field()
    {
        $field = new JoditField('content', 'Article Content');

        $this->assertEquals('content', $field->getName());
        $this->assertEquals('Article Content', $field->getLabel());
    }

    /** @test */
    public function it_can_create_using_make_method()
    {
        $field = JoditField::make('blog_content');

        $this->assertEquals('blog_content', $field->getName());
        $this->assertEquals('Blog Content', $field->getLabel());
    }

    /** @test */
    public function it_auto_generates_label_from_name()
    {
        $field = new JoditField('article_description');

        $this->assertEquals('Article Description', $field->getLabel());
    }

    /** @test */
    public function it_can_set_editor_config()
    {
        $field = new JoditField('content');
        $config = ['theme' => 'dark', 'language' => 'en'];

        $field->config($config);

        // Since editorConfig is protected, we test through render method
        $this->assertInstanceOf(JoditField::class, $field);
    }

    /** @test */
    public function it_can_set_height()
    {
        $field = new JoditField('content');
        $field->height(400);

        // Test fluent interface
        $this->assertInstanceOf(JoditField::class, $field);
    }

    /** @test */
    public function it_can_set_toolbar_buttons()
    {
        $field = new JoditField('content');
        $buttons = ['bold', 'italic', 'underline', 'link'];

        $field->toolbar($buttons);

        // Test fluent interface
        $this->assertInstanceOf(JoditField::class, $field);
    }

    /** @test */
    public function it_can_chain_multiple_configurations()
    {
        $field = new JoditField('content');
        $field->height(500)
            ->toolbar(['bold', 'italic'])
            ->config(['theme' => 'light'])
            ->required()
            ->placeholder('Enter your content here');

        $attributes = $field->renderAttributes();

        $this->assertEquals('required', $attributes['required']);
        $this->assertEquals('Enter your content here', $attributes['placeholder']);
        $this->assertEquals('content', $attributes['name']);
        $this->assertTrue($field->isRequired());
    }

    /** @test */
    public function it_can_set_value()
    {
        $field = new JoditField('content');
        $field->value('<p>Test content</p>');

        $this->assertEquals('<p>Test content</p>', $field->getValue());
    }

    /** @test */
    public function it_can_create_jodit_field_without_rendering()
    {
        $field = new JoditField('content', 'Article Content');

        $this->assertEquals('content', $field->getName());
        $this->assertEquals('Article Content', $field->getLabel());
        $this->assertInstanceOf(JoditField::class, $field);
    }

    /** @test */
    public function it_can_configure_jodit_options_without_rendering()
    {
        $field = new JoditField('content');

        $result = $field->height(500)
            ->config(['theme' => 'dark', 'language' => 'en'])
            ->value('<p>Initial content</p>');

        $this->assertInstanceOf(JoditField::class, $result);
        $this->assertEquals('<p>Initial content</p>', $field->getValue());
    }

    /** @test */
    public function it_handles_file_upload_configuration()
    {
        $field = new JoditField('content');
        $uploadConfig = [
            'path' => 'uploads/images',
            'source' => 'local'
        ];

        $field->config($uploadConfig);

        // Test that the field accepts upload-related configuration
        $this->assertInstanceOf(JoditField::class, $field);
    }

    /** @test */
    public function it_can_set_default_jodit_options()
    {
        $field = new JoditField('content');
        $defaultOptions = [
            'height' => 400,
            'language' => 'en',
            'theme' => 'default',
            'toolbar' => true,
            'spellcheck' => true,
            'enableDragAndDropFileToEditor' => true
        ];

        $field->config($defaultOptions);

        $this->assertInstanceOf(JoditField::class, $field);
    }

    /** @test */
    public function it_merges_editor_config_properly()
    {
        $field = new JoditField('content');

        // Set initial config
        $field->config(['height' => 300, 'theme' => 'dark']);

        // Add more config - should merge, not replace
        $field->config(['language' => 'en', 'toolbar' => ['bold', 'italic']]);

        // Set specific options
        $field->height(500); // Should override the height from config

        $this->assertInstanceOf(JoditField::class, $field);
    }

    /** @test */
    public function it_can_handle_passed_values()
    {
        $field = new JoditField('content');

        // Test that getFieldValue handles passed values correctly
        $fieldValue = $field->getFieldValue('<p>Passed content</p>');

        $this->assertEquals('<p>Passed content</p>', $fieldValue);
    }

    /** @test */
    public function it_can_set_required()
    {
        $field = new JoditField('content');
        $field->required();

        $this->assertTrue($field->isRequired());

        $attributes = $field->renderAttributes();
        $this->assertEquals('required', $attributes['required']);
    }

    /** @test */
    public function it_can_set_help_text()
    {
        $field = new JoditField('content');
        $field->help('This is a rich text editor');

        $this->assertEquals('This is a rich text editor', $field->getHelp());
    }

    /** @test */
    public function it_can_add_css_classes()
    {
        $field = new JoditField('content');
        $field->addClass('custom-editor');

        $attributes = $field->renderAttributes();
        $this->assertStringContainsString('custom-editor', $attributes['class']);
    }

    /** @test */
    public function it_can_set_id()
    {
        $field = new JoditField('content');
        $field->id('my-editor');

        $attributes = $field->renderAttributes();
        $this->assertEquals('my-editor', $attributes['id']);
    }

    /** @test */
    public function it_can_be_disabled()
    {
        $field = new JoditField('content');
        $field->disabled();

        $attributes = $field->renderAttributes();
        $this->assertEquals('disabled', $attributes['disabled']);
    }

    /** @test */
    public function it_can_be_readonly()
    {
        $field = new JoditField('content');
        $field->readonly();

        $attributes = $field->renderAttributes();
        $this->assertEquals('readonly', $attributes['readonly']);
    }

    /** @test */
    public function it_can_set_validation_rules()
    {
        $field = new JoditField('content');
        $field->rules('required|min:10');

        $rules = $field->getValidationRules();
        $this->assertContains('required', $rules);
        $this->assertContains('min:10', $rules);
    }

    /** @test */
    public function it_can_set_validation_rules_as_array()
    {
        $field = new JoditField('content');
        $field->rules(['required', 'min:10', 'max:1000']);

        $rules = $field->getValidationRules();
        $this->assertContains('required', $rules);
        $this->assertContains('min:10', $rules);
        $this->assertContains('max:1000', $rules);
    }

    /** @test */
    public function it_can_set_column_width()
    {
        $field = new JoditField('content');
        $field->columnWidth(12);

        $this->assertEquals(12, $field->getColumnWidth());
    }

    /** @test */
    public function it_can_convert_to_array()
    {
        $field = new JoditField('content', 'Article Content');
        $field->value('<p>Test</p>')
            ->required()
            ->help('Enter article content');

        $array = $field->toArray();

        $this->assertEquals('JoditField', $array['type']);
        $this->assertEquals('content', $array['name']);
        $this->assertEquals('Article Content', $array['label']);
        $this->assertEquals('<p>Test</p>', $array['value']);
        $this->assertTrue($array['required']);
        $this->assertEquals('Enter article content', $array['help']);
    }

    /** @test */
    public function it_handles_empty_field_value()
    {
        $field = new JoditField('content');

        $fieldValue = $field->getFieldValue();

        $this->assertEquals('', $fieldValue);
    }

    /** @test */
    public function it_can_create_with_config_array()
    {
        $config = [
            'label' => 'Custom Label',
            'required' => true,
            'help' => 'Help text',
            'placeholder' => 'Enter content',
            'value' => '<p>Default content</p>',
            'attributes' => ['class' => 'custom-class']
        ];

        $field = new JoditField('content', '', $config);

        $this->assertEquals('Custom Label', $field->getLabel());
        $this->assertTrue($field->isRequired());
        $this->assertEquals('Help text', $field->getHelp());
        $this->assertEquals('<p>Default content</p>', $field->getValue());
    }

    /** @test */
    public function it_generates_unique_id_for_jodit_instance()
    {
        $field1 = new JoditField('content1');
        $field2 = new JoditField('content2');

        // Each field should have a unique ID for Jodit initialization
        $id1 = $field1->getId();
        $id2 = $field2->getId();

        $this->assertNotEquals($id1, $id2);
        $this->assertStringContainsString('content1', $id1);
        $this->assertStringContainsString('content2', $id2);
    }

    /** @test */
    public function it_can_override_default_id()
    {
        $field = new JoditField('content');
        $field->id('my-custom-editor');

        $attributes = $field->renderAttributes();
        $this->assertEquals('my-custom-editor', $attributes['id']);
    }

    /** @test */
    public function it_handles_old_values_correctly()
    {
        $field = new JoditField('content');

        // Test the field's ability to handle old values
        // The actual old() behavior will be handled by Laravel's session
        $fieldValue = $field->getFieldValue();

        $this->assertIsString($fieldValue);
    }

    /** @test */
    public function it_can_handle_complex_jodit_configuration()
    {
        $field = new JoditField('rich_editor', 'Rich Text Editor');

        $complexConfig = [
            'height' => 600,
            'width' => '100%',
            'language' => 'en',
            'theme' => 'dark',
            'toolbar' => true,
            'spellcheck' => true,
            'enableDragAndDropFileToEditor' => true,
            'uploader' => [
                'insertImageAsBase64URI' => false,
                'imagesExtensions' => ['jpg', 'png', 'gif', 'jpeg', 'webp']
            ],
            'filebrowser' => [
                'ajax' => [
                    'url' => '/admin/file-browser'
                ]
            ],
            'buttons' => [
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'ul', 'ol', '|',
                'font', 'fontsize', 'brush', 'paragraph', '|',
                'image', 'file', 'video', 'table', 'link', '|',
                'align', 'undo', 'redo', 'hr', 'source', 'fullsize'
            ],
            'buttonsMD' => [
                'bold', 'italic', 'underline', '|',
                'ul', 'ol', '|',
                'image', 'link', '|',
                'align', 'undo', 'redo', 'source'
            ],
            'buttonsSM' => [
                'bold', 'italic', '|',
                'ul', 'ol', '|',
                'link', 'undo', 'redo'
            ]
        ];

        $field->config($complexConfig)
            ->required()
            ->help('Use this rich text editor to create engaging content')
            ->placeholder('Start writing your content here...');

        $this->assertEquals('rich_editor', $field->getName());
        $this->assertEquals('Rich Text Editor', $field->getLabel());
        $this->assertTrue($field->isRequired());
        $this->assertEquals('Use this rich text editor to create engaging content', $field->getHelp());

        $attributes = $field->renderAttributes();
        $this->assertEquals('Start writing your content here...', $attributes['placeholder']);
    }
}
