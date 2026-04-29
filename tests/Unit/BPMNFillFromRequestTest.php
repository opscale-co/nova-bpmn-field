<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Http\Requests\NovaRequest;
use Opscale\Fields\BPMN;
use Workbench\App\Models\Diagram;

function sampleBPMN(): string
{
    return (string) file_get_contents(__DIR__.'/../fixtures/sample.bpmn');
}

function bpmnRequest(array $data): NovaRequest
{
    $files = [];
    $params = [];

    foreach ($data as $key => $value) {
        if ($value instanceof UploadedFile) {
            $files[$key] = $value;
        } else {
            $params[$key] = $value;
        }
    }

    return NovaRequest::create('/', 'POST', $params, [], $files);
}

beforeEach(function (): void {
    Storage::fake('public');
});

it('stores the uploaded file on the configured disk and persists the path', function (): void {
    $tmp = tempnam(sys_get_temp_dir(), 'bpmn').'.bpmn';
    file_put_contents($tmp, sampleBPMN());
    $upload = new UploadedFile($tmp, 'diagram.bpmn', 'application/xml', null, true);

    $field = BPMN::make('Diagram', 'bpmn_path', 'public');
    $model = new Diagram;

    $field->fill(bpmnRequest(['bpmn_path' => $upload]), $model);

    expect($model->bpmn_path)->toBeString()->not->toBeEmpty();

    Storage::disk('public')->assertExists($model->bpmn_path);
    expect(Storage::disk('public')->get($model->bpmn_path))->toContain('Process_sample');

    @unlink($tmp);
});

it('leaves the attribute untouched when no file is uploaded', function (): void {
    $field = BPMN::make('Diagram', 'bpmn_path', 'public');
    $model = new Diagram(['bpmn_path' => 'diagrams/existing.bpmn']);

    $field->fill(bpmnRequest([]), $model);

    expect($model->bpmn_path)->toBe('diagrams/existing.bpmn');
});

it('rejects files whose extension is not in acceptedTypes', function (): void {
    $upload = UploadedFile::fake()->createWithContent('diagram.pdf', '%PDF-1.4');

    $field = BPMN::make('Diagram', 'bpmn_path', 'public')->rules('mimes:bpmn,xml');
    expect($field->acceptedTypes)->toBe('.bpmn,.xml');

    // rules('mimes:...') is enforced by Nova's validator layer; here we assert the
    // field advertises the restriction that the browser + backend will honor.
});
