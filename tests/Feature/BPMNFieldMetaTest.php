<?php

declare(strict_types=1);

use Laravel\Nova\Fields\File;
use Opscale\Fields\BPMN;

it('uses the nova-bpmn-field Vue component', function (): void {
    $field = BPMN::make('Diagram', 'bpmn_path');

    expect($field->component)->toBe('nova-bpmn-field');
});

it('is hidden from the index view', function (): void {
    $field = BPMN::make('Diagram', 'bpmn_path');

    expect($field->showOnIndex)->toBeFalse();
});

it('inherits Laravel Nova File behavior', function (): void {
    $field = BPMN::make('Diagram', 'bpmn_path');

    expect($field)->toBeInstanceOf(File::class);
});

it('advertises .bpmn and .xml as accepted upload types', function (): void {
    $field = BPMN::make('Diagram', 'bpmn_path');

    expect($field->acceptedTypes)->toBe('.bpmn,.xml');
});

it('exposes viewer defaults via meta()', function (): void {
    $meta = BPMN::make('Diagram', 'bpmn_path')->meta();

    expect($meta)
        ->toHaveKey('height', 600)
        ->toHaveKey('minimap', true)
        ->toHaveKey('zoomControls', true);
});

it('overrides viewer defaults through chainable modifiers', function (): void {
    $meta = BPMN::make('Diagram', 'bpmn_path')
        ->height(900)
        ->minimap(false)
        ->zoomControls(false)
        ->meta();

    expect($meta)
        ->toHaveKey('height', 900)
        ->toHaveKey('minimap', false)
        ->toHaveKey('zoomControls', false);
});

it('serializes the File-level meta keys expected by the form-file-field component', function (): void {
    $field = BPMN::make('Diagram', 'bpmn_path');

    $json = $field->jsonSerialize();

    expect($json)
        ->toHaveKey('component', 'nova-bpmn-field')
        ->toHaveKey('attribute', 'bpmn_path')
        ->toHaveKey('acceptedTypes', '.bpmn,.xml')
        ->toHaveKeys(['previewUrl', 'thumbnailUrl', 'downloadable', 'deletable']);
});
