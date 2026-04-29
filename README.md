## Support us

At Opscale, we’re passionate about contributing to the open-source community by providing solutions that help businesses scale efficiently. If you’ve found our tools helpful, here are a few ways you can show your support:

⭐ **Star this repository** to help others discover our work and be part of our growing community. Every star makes a difference!

💬 **Share your experience** by leaving a review on [Trustpilot](https://www.trustpilot.com/review/opscale.co) or sharing your thoughts on social media. Your feedback helps us improve and grow!

📧 **Send us feedback** on what we can improve at [feedback@opscale.co](mailto:feedback@opscale.co). We value your input to make our tools even better for everyone.

🙏 **Get involved** by actively contributing to our open-source repositories. Your participation benefits the entire community and helps push the boundaries of what’s possible.

💼 **Hire us** if you need custom dashboards, admin panels, internal tools or MVPs tailored to your business. With our expertise, we can help you systematize operations or enhance your existing product. Contact us at hire@opscale.co to discuss your project needs.

Thanks for helping Opscale continue to scale! 🚀



## Description

BPMN diagrams usually live in designer tools (bpmn.io, Camunda Modeler, Signavio) and rarely make it into the apps that execute them. This package closes that gap: upload a `.bpmn` file from any Nova resource and the interactive process diagram shows up right on the record, with pan, zoom, and minimap — powered by `bpmn-js`. Compatible with Nova 5.

![Demo](https://raw.githubusercontent.com/opscale-co/nova-bpmn-field/refs/heads/main/screenshots/nova-bpmn-field.png)

## Installation

[![Latest Version on Packagist](https://img.shields.io/packagist/v/opscale-co/nova-bpmn-field.svg?style=flat-square)](https://packagist.org/packages/opscale-co/nova-bpmn-field)

You can install the package in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require opscale-co/nova-bpmn-field
```

The package will auto-register its service provider.

Back your field with a `string` column on the owning model — it stores the disk path of the uploaded `.bpmn`. The file itself lives on the Laravel disk of your choice (typically `public`).

```php
Schema::create('diagrams', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('bpmn_path')->nullable();
    $table->timestamps();
});
```

Make sure the disk is publicly readable (for the default `public` disk: `php artisan storage:link`).

## Usage

Add the `BPMN` field to any Nova Resource that owns a BPMN attribute:

```php
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;
use Opscale\Fields\BPMN;

class Diagram extends Resource
{
    public static $model = \App\Models\Diagram::class;

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Name')->rules('required', 'max:255'),

            BPMN::make('Diagram', 'bpmn_path', 'public')
                ->prunable()
                ->deletable()
                ->height(600)
                ->minimap()
                ->zoomControls(),
        ];
    }
}
```

On **Create / Update** the field renders Nova’s native File dropzone — pick a `.bpmn` or `.xml` file, it is uploaded to the configured disk and the path is persisted. On **Detail / Lens** the field fetches the file from the disk and renders the full interactive viewer. It is intentionally **hidden from Index**.

### Field behavior

| View | Behavior |
|---|---|
| Create / Update | Nova’s native File field dropzone (`.bpmn`, `.xml`). Inherited storage, deletion, and `->prunable()` semantics. |
| Detail / Lens | Interactive `bpmn-js` `NavigatedViewer` with pan, zoom, fit-to-viewport, and optional minimap. |
| Index | Hidden — a full process diagram inside a table cell adds no decision-making value. |

### Modifiers

| Method | Purpose |
|---|---|
| `->height(int $pixels)` | Canvas height in pixels (default `600`). |
| `->minimap(bool $enabled = true)` | Enable the minimap overlay. |
| `->zoomControls(bool $enabled = true)` | Show the in-canvas zoom in / out / reset buttons. |
| `->prunable()` / `->deletable()` | Inherited from `Laravel\Nova\Fields\File` — remove the stored file when the record is deleted or the field is cleared. |

### Under the hood

| Layer | Dependency |
|---|---|
| Upload / storage | `Laravel\Nova\Fields\File` — disk-backed upload, deletion, prunable. |
| Renderer | [`bpmn-js`](https://bpmn.io/toolkit/bpmn-js/) `NavigatedViewer`. |

The PHP field class is a thin subclass of Nova’s `File`; the Vue `form` and `index` slots alias `form-file-field` and `index-file-field` verbatim, so there is no custom upload UI to maintain. Only the `detail` slot is custom — it reads `field.previewUrl`, fetches the XML, and mounts the viewer.

## Testing

```bash

npm run test

```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/opscale-co/.github/blob/main/CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email development@opscale.co instead of using the issue tracker.

## Credits

- [Opscale](https://github.com/opscale-co)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
