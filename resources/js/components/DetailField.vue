<template>
    <PanelItem :index="index" :field="field">
        <template #value>
            <BpmnViewer
                v-if="field.value"
                :xml="field.value"
                :height="field.height"
                :zoom-controls="field.zoomControls"
            />
            <p v-else class="text-sm text-gray-500">{{ __('No BPMN diagram provided.') }}</p>
        </template>
    </PanelItem>
</template>

<script>
import BpmnViewer from './BpmnViewer.vue'

export default {
    components: { BpmnViewer },
    props: ['index', 'resource', 'resourceName', 'resourceId', 'field'],
}
</script>
