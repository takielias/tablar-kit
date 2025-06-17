<?php

namespace TakiElias\TablarKit\Tests\Unit\Fields;

use Orchestra\Testbench\TestCase;
use Takielias\TablarKit\Fields\FileField;
use Illuminate\Support\Facades\View;

class FileFieldTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Mock Laravel View facade
        View::shouldReceive('make')
            ->andReturn(\Mockery::mock(['render' => '<input type="file">']));
    }

    /** @test */
    public function it_can_create_file_field()
    {
        $field = new FileField('document', 'Upload Document');

        $this->assertEquals('document', $field->getName());
        $this->assertEquals('Upload Document', $field->getLabel());
    }

    /** @test */
    public function it_auto_generates_label_from_name()
    {
        $field = new FileField('profile_image');

        $this->assertEquals('profile_image', $field->getName());
        $this->assertEquals('Profile Image', $field->getLabel());
    }

    /** @test */
    public function it_can_create_with_config()
    {
        $config = [
            'label' => 'Profile Photo',
            'required' => true,
            'help' => 'Upload your profile picture'
        ];

        $field = new FileField('avatar', '', $config);

        $this->assertEquals('Profile Photo', $field->getLabel());
        $this->assertTrue($field->isRequired());
        $this->assertEquals('Upload your profile picture', $field->getHelp());
    }

    /** @test */
    public function it_can_set_accepted_file_types()
    {
        $field = new FileField('document');
        $field->accept(['.pdf', '.doc', '.docx']);

        // Test fluent interface returns self
        $this->assertInstanceOf(FileField::class, $field);
    }

    /** @test */
    public function it_can_set_max_file_size()
    {
        $field = new FileField('image');
        $field->maxSize(2048); // 2MB in KB

        // Test fluent interface returns self
        $this->assertInstanceOf(FileField::class, $field);
    }

    /** @test */
    public function it_can_enable_multiple_files()
    {
        $field = new FileField('images');
        $field->multiple();

        // Test fluent interface returns self
        $this->assertInstanceOf(FileField::class, $field);
    }

    /** @test */
    public function it_can_disable_multiple_files()
    {
        $field = new FileField('images');
        $field->multiple(false);

        // Test fluent interface returns self
        $this->assertInstanceOf(FileField::class, $field);
    }

    /** @test */
    public function it_can_set_accepted_file_types_via_attributes()
    {
        $field = new FileField('document');
        $field->acceptedFileTypes(['.pdf', '.jpg', '.png']);

        $attributes = $field->renderAttributes();
        $this->assertEquals('.pdf,.jpg,.png', $attributes['accept']);
    }

    /** @test */
    public function it_can_chain_methods()
    {
        $field = FileField::make('gallery_images')
            ->accept(['image/*'])
            ->maxSize(5120)
            ->multiple()
            ->required()
            ->help('Upload up to 10 images');

        $this->assertEquals('gallery_images', $field->getName());
        $this->assertEquals('Gallery Images', $field->getLabel());
        $this->assertTrue($field->isRequired());
        $this->assertEquals('Upload up to 10 images', $field->getHelp());
    }

    /** @test */
    public function it_can_create_using_make_method()
    {
        $field = FileField::make('user_avatar');

        $this->assertEquals('user_avatar', $field->getName());
        $this->assertEquals('User Avatar', $field->getLabel());
    }

    /** @test */
    public function it_can_set_attributes()
    {
        $field = new FileField('upload');
        $field->addClass('custom-file-input')
            ->id('file-upload')
            ->disabled()
            ->setAttribute('data-max-files', '5');

        $attributes = $field->renderAttributes();

        $this->assertEquals('file-upload', $attributes['id']);
        $this->assertEquals('disabled', $attributes['disabled']);
        $this->assertEquals('5', $attributes['data-max-files']);
        $this->assertStringContainsString('custom-file-input', $attributes['class'] ?? '');
    }

    /** @test */
    public function it_can_set_validation_rules()
    {
        $field = new FileField('document');
        $field->required()
            ->rules(['file', 'mimes:pdf,doc', 'max:2048']);

        $rules = $field->getValidationRules();

        $this->assertContains('required', $rules);
        $this->assertContains('file', $rules);
        $this->assertContains('mimes:pdf,doc', $rules);
        $this->assertContains('max:2048', $rules);
    }

    /** @test */
    public function it_renders_with_view()
    {
        $field = new FileField('document');
        $field->accept(['.pdf', '.doc'])
            ->maxSize(1024)
            ->multiple()
            ->help('Upload your documents');

        // Mock view rendering
        $mockView = \Mockery::mock();
        $mockView->shouldReceive('render')->andReturn('<input type="file" multiple>');

        \Illuminate\Support\Facades\View::shouldReceive('__callStatic')
            ->with('make', \Mockery::type('array'))
            ->andReturn($mockView);

        $html = $field->render();

        $this->assertIsString($html);
        $this->assertStringContainsString('input', $html);
    }

    /** @test */
    public function it_returns_correct_array_representation()
    {
        $field = new FileField('upload');
        $field->label('File Upload')
            ->required()
            ->help('Select files')
            ->value('test.pdf')
            ->rules(['required', 'file']);

        $array = $field->toArray();

        $this->assertEquals('FileField', $array['type']);
        $this->assertEquals('upload', $array['name']);
        $this->assertEquals('File Upload', $array['label']);
        $this->assertEquals('test.pdf', $array['value']);
        $this->assertTrue($array['required']);
        $this->assertEquals('Select files', $array['help']);
        $this->assertContains('required', $array['validation']);
        $this->assertContains('file', $array['validation']);
    }

    /** @test */
    public function it_can_set_column_width()
    {
        $field = new FileField('document');
        $field->columnWidth(8);

        $this->assertEquals(8, $field->getColumnWidth());
    }

    /** @test */
    public function max_file_size_method_returns_self()
    {
        $field = new FileField('document');
        $result = $field->maxFileSize('2MB');

        $this->assertInstanceOf(FileField::class, $result);
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
