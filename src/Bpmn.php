<?php

declare(strict_types=1);

namespace Opscale\NovaBpmnField;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\SupportsDependentFields;

class Bpmn extends Field
{
    use SupportsDependentFields;

    public $component = 'nova-bpmn-field';

    public $showOnIndex = false;

    public function height(int $pixels): self
    {
        return $this->withMeta(['height' => $pixels]);
    }

    public function readonly($callback = true): self
    {
        return $this->withMeta(['readonly' => $callback]);
    }

    public function minimap(bool $enabled = true): self
    {
        return $this->withMeta(['minimap' => $enabled]);
    }

    public function zoomControls(bool $enabled = true): self
    {
        return $this->withMeta(['zoomControls' => $enabled]);
    }

    public function palette(bool $enabled = true): self
    {
        return $this->withMeta(['palette' => $enabled]);
    }

    public function propertiesPanel(bool $enabled = true): self
    {
        return $this->withMeta(['propertiesPanel' => $enabled]);
    }

    public function meta()
    {
        return array_merge([
            'height' => 600,
            'readonly' => false,
            'minimap' => true,
            'zoomControls' => true,
            'palette' => false,
            'propertiesPanel' => false,
        ], $this->meta);
    }
}
