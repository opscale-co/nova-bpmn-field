# Nova BPMN Field

Interactive BPMN viewer and editor field for Laravel Nova, powered by [`bpmn-js`](https://bpmn.io/toolkit/bpmn-js/).

## Requirements

- PHP ^8.3
- Laravel Nova ^5.0

## Installation

```bash
composer require opscale/nova-bpmn-field
```

## Usage

```php
use Opscale\NovaBpmnField\Bpmn;

public function fields(NovaRequest $request): array
{
    return [
        Bpmn::make('Process', 'bpmn_xml')
            ->height(700)
            ->minimap()
            ->propertiesPanel()
            ->zoomControls(),
    ];
}
```

### Available modifiers

| Method | Description |
| --- | --- |
| `height(int $pixels)` | Canvas height in pixels (default `600`). |
| `readonly(bool $value = true)` | Render the navigated viewer instead of the modeler. |
| `minimap(bool $enabled = true)` | Toggle the minimap. |
| `zoomControls(bool $enabled = true)` | Show in-canvas zoom buttons. |
| `palette(bool $enabled = true)` | Show the modeler palette. |
| `propertiesPanel(bool $enabled = true)` | Show the properties panel next to the canvas. |

## Development

```bash
npm install
npm run dev       # watch mode
npm run build     # production build to /dist
```

## License

MIT
