<template>
    <DefaultField
        :field="currentField"
        :errors="errors"
        :show-help-text="showHelpText"
        :full-width-content="true"
    >
        <template #field>
            <BpmnViewer
                v-if="currentField.readonly"
                :xml="value"
                :height="currentField.height"
                :zoom-controls="currentField.zoomControls"
            />
            <BpmnModeler
                v-else
                v-model="value"
                :height="currentField.height"
                :properties-panel="currentField.propertiesPanel"
                @import-error="onImportError"
            />
        </template>
    </DefaultField>
</template>

<script>
import { DependentFormField, HandlesValidationErrors } from 'laravel-nova'
import BpmnViewer from './BpmnViewer.vue'
import BpmnModeler from './BpmnModeler.vue'

export default {
    mixins: [DependentFormField, HandlesValidationErrors],
    components: { BpmnViewer, BpmnModeler },
    props: ['resourceName', 'resourceId', 'field'],

    methods: {
        fill(formData) {
            formData.append(this.currentField.attribute, this.value ?? '')
        },
        onImportError(error) {
            Nova.error(this.__('Invalid BPMN XML: ') + error.message)
        },
    },
}
</script>
