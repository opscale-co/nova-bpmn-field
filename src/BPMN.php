<?php

declare(strict_types=1);

namespace Opscale\Fields;

use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Fields\File;
use Override;

class BPMN extends File
{
    public $component = 'nova-bpmn-field';

    public $showOnIndex = false;

    public $acceptedTypes = '.bpmn,.xml';

    /**
     * @param  \Stringable|string  $name
     */
    final public function __construct($name, mixed $attribute = null, ?string $disk = null, ?callable $storageCallback = null)
    {
        parent::__construct($name, $attribute, $disk, $storageCallback);

        $this->preview(fn (?string $value, ?string $diskName): ?string => $value !== null && $value !== ''
            ? Storage::disk($diskName ?? $this->getStorageDisk())->url($value)
            : null,
        );
    }

    final public function height(int $pixels): self
    {
        return $this->withMeta(['height' => $pixels]);
    }

    final public function minimap(bool $enabled = true): self
    {
        return $this->withMeta(['minimap' => $enabled]);
    }

    final public function zoomControls(bool $enabled = true): self
    {
        return $this->withMeta(['zoomControls' => $enabled]);
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function meta()
    {
        return array_merge(parent::meta(), [
            'height' => 600,
            'minimap' => true,
            'zoomControls' => true,
        ], $this->meta);
    }
}
