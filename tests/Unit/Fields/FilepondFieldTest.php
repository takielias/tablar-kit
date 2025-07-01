<?php

namespace TakiElias\TablarKit\Tests\Unit\Fields;

use Orchestra\Testbench\TestCase;
use TakiElias\TablarKit\Fields\FilepondField;
use Illuminate\Support\Facades\View;

class FilepondFieldTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        View::shouldReceive('make')
            ->andReturn(\Mockery::mock(['render' => '<input>']));
    }

    /** @test */
    public function it_can_create_filepond_field()
    {
        $field = new FilepondField('avatar', 'Profile Picture');

        $this->assertEquals('avatar', $field->getName());
        $this->assertEquals('Profile Picture', $field->getLabel());
    }

    /** @test */
    public function it_auto_generates_label_from_name()
    {
        $field = new FilepondField('profile_image');

        $this->assertEquals('Profile Image', $field->getLabel());
    }

    /** @test */
    public function it_can_set_filepond_config()
    {
        $config = ['allowMultiple' => true, 'maxFiles' => 5];
        $field = new FilepondField('upload');
        $field->config($config);

        $this->assertInstanceOf(FilepondField::class, $field);
    }

    /** @test */
    public function it_can_merge_config_values()
    {
        $field = new FilepondField('upload');
        $field->config(['allowMultiple' => true])
            ->config(['maxFiles' => 5]);

        $this->assertInstanceOf(FilepondField::class, $field);
    }

    /** @test */
    public function it_can_allow_multiple_files()
    {
        $field = new FilepondField('gallery');
        $field->allowMultiple();

        $this->assertInstanceOf(FilepondField::class, $field);
    }

    /** @test */
    public function it_can_disable_multiple_files()
    {
        $field = new FilepondField('avatar');
        $field->allowMultiple(false);

        $this->assertInstanceOf(FilepondField::class, $field);
    }

    /** @test */
    public function it_can_set_accepted_file_types()
    {
        $types = ['image/jpeg', 'image/png', 'image/gif'];
        $field = new FilepondField('upload');
        $field->acceptedFileTypes($types);

        $this->assertInstanceOf(FilepondField::class, $field);
    }

    /** @test */
    public function it_can_set_max_file_size()
    {
        $field = new FilepondField('upload');
        $field->maxFileSize('2MB');

        $this->assertInstanceOf(FilepondField::class, $field);
    }

    /** @test */
    public function it_can_enable_image_editor()
    {
        $field = new FilepondField('photo');
        $field->imageEditor();

        $this->assertInstanceOf(FilepondField::class, $field);
    }

    /** @test */
    public function it_can_disable_image_editor()
    {
        $field = new FilepondField('document');
        $field->imageEditor(false);

        $this->assertInstanceOf(FilepondField::class, $field);
    }

    /** @test */
    public function it_can_chain_methods()
    {
        $field = FilepondField::make('gallery')
            ->allowMultiple()
            ->acceptedFileTypes(['image/jpeg', 'image/png'])
            ->maxFileSize('5MB')
            ->imageEditor()
            ->required()
            ->help('Upload your photos');

        $this->assertEquals('gallery', $field->getName());
        $this->assertEquals('Gallery', $field->getLabel());
        $this->assertTrue($field->isRequired());
        $this->assertEquals('Upload your photos', $field->getHelp());
    }

    /** @test */
    public function it_can_create_using_make_method()
    {
        $field = FilepondField::make('user_avatar');

        $this->assertEquals('user_avatar', $field->getName());
        $this->assertEquals('User Avatar', $field->getLabel());
    }

    /** @test */
    public function it_can_set_attributes()
    {
        $field = new FilepondField('upload');
        $field->addClass('filepond-custom')
            ->id('pond-upload')
            ->disabled()
            ->setAttribute('data-upload-url', '/api/upload');

        $attributes = $field->renderAttributes();

        $this->assertEquals('pond-upload', $attributes['id']);
        $this->assertEquals('disabled', $attributes['disabled']);
        $this->assertEquals('/api/upload', $attributes['data-upload-url']);
        $this->assertStringContainsString('filepond-custom', $attributes['class'] ?? '');
    }

    /** @test */
    public function it_can_set_validation_rules()
    {
        $field = new FilepondField('upload');
        $field->required()
            ->rules(['file', 'image', 'max:2048']);

        $rules = $field->getValidationRules();

        $this->assertContains('required', $rules);
        $this->assertContains('file', $rules);
        $this->assertContains('image', $rules);
        $this->assertContains('max:2048', $rules);
    }

    /** @test */
    public function it_has_correct_attributes_for_rendering()
    {
        $field = new FilepondField('upload');
        $field->allowMultiple()
            ->acceptedFileTypes(['image/jpeg'])
            ->maxFileSize('1MB')
            ->imageEditor()
            ->required()
            ->addClass('custom-pond');

        $attributes = $field->renderAttributes();

        $this->assertEquals('upload', $attributes['name']);
        $this->assertEquals('required', $attributes['required']);
        $this->assertStringContainsString('custom-pond', $attributes['class'] ?? '');
    }

    /** @test */
    public function it_returns_correct_array_representation()
    {
        $field = new FilepondField('gallery');
        $field->label('Photo Gallery')
            ->allowMultiple()
            ->acceptedFileTypes(['image/jpeg', 'image/png'])
            ->maxFileSize('5MB')
            ->imageEditor()
            ->required()
            ->help('Upload your gallery photos')
            ->rules(['required', 'file']);

        $array = $field->toArray();

        $this->assertEquals('FilepondField', $array['type']);
        $this->assertEquals('gallery', $array['name']);
        $this->assertEquals('Photo Gallery', $array['label']);
        $this->assertTrue($array['required']);
        $this->assertEquals('Upload your gallery photos', $array['help']);
        $this->assertContains('required', $array['validation']);
        $this->assertContains('file', $array['validation']);
    }

    /** @test */
    public function it_can_set_column_width()
    {
        $field = new FilepondField('upload');
        $field->columnWidth(12);

        $this->assertEquals(12, $field->getColumnWidth());
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
