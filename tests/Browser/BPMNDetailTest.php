<?php

declare(strict_types=1);

namespace Opscale\Fields\Tests\Browser;

use Laravel\Dusk\Browser;
use Opscale\Fields\Tests\DuskTestCase;
use PHPUnit\Framework\Attributes\Test;

final class BPMNDetailTest extends DuskTestCase
{
    #[Test]
    public function detail_view_renders_the_interactive_bpmn_viewer(): void
    {
        $this->browse(function (Browser $browser): void {
            $this->loginToNova($browser)
                ->visit('/nova/resources/diagrams/1')
                ->waitFor('[data-testid="bpmn-detail"]', 15)
                ->waitFor('[data-testid="bpmn-viewer"]', 15)
                ->waitFor('[data-testid="bpmn-canvas"] .djs-container', 15)
                ->assertPresent('[data-testid="bpmn-controls"]')
                ->assertPresent('[data-testid="bpmn-zoom-in"]')
                ->assertPresent('[data-testid="bpmn-zoom-out"]')
                ->assertPresent('[data-testid="bpmn-zoom-reset"]');
        });
    }

    #[Test]
    public function the_canvas_displays_task_elements_from_the_bpmn_file(): void
    {
        $this->browse(function (Browser $browser): void {
            $this->loginToNova($browser)
                ->visit('/nova/resources/diagrams/1')
                ->waitFor('[data-testid="bpmn-canvas"] .djs-container', 15)
                ->waitFor('[data-testid="bpmn-canvas"] [data-element-id="Task_1"]', 15)
                ->assertPresent('[data-element-id="StartEvent_1"]')
                ->assertPresent('[data-element-id="Task_1"]')
                ->assertPresent('[data-element-id="EndEvent_1"]');
        });
    }
}
