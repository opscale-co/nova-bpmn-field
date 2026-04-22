<template>
    <div class="nova-bpmn-wrapper" :style="{ height: height + 'px' }">
        <div ref="canvas" class="nova-bpmn-canvas"></div>
        <div v-if="propertiesPanel" ref="properties" class="nova-bpmn-properties"></div>
    </div>
</template>

<script>
import BpmnModeler from 'bpmn-js/lib/Modeler'
import 'bpmn-js/dist/assets/diagram-js.css'
import 'bpmn-js/dist/assets/bpmn-font/css/bpmn.css'
import 'bpmn-js/dist/assets/bpmn-js.css'

const EMPTY_DIAGRAM = `<?xml version="1.0" encoding="UTF-8"?>
<bpmn:definitions xmlns:bpmn="http://www.omg.org/spec/BPMN/20100524/MODEL"
                  xmlns:bpmndi="http://www.omg.org/spec/BPMN/20100524/DI"
                  xmlns:dc="http://www.omg.org/spec/DD/20100524/DC"
                  id="Definitions_1" targetNamespace="http://bpmn.io/schema/bpmn">
  <bpmn:process id="Process_1" isExecutable="false">
    <bpmn:startEvent id="StartEvent_1"/>
  </bpmn:process>
  <bpmndi:BPMNDiagram id="BPMNDiagram_1">
    <bpmndi:BPMNPlane id="BPMNPlane_1" bpmnElement="Process_1">
      <bpmndi:BPMNShape id="_BPMNShape_StartEvent_2" bpmnElement="StartEvent_1">
        <dc:Bounds x="173" y="102" width="36" height="36"/>
      </bpmndi:BPMNShape>
    </bpmndi:BPMNPlane>
  </bpmndi:BPMNDiagram>
</bpmn:definitions>`

export default {
    props: {
        modelValue: { type: String, default: '' },
        height: { type: Number, default: 600 },
        propertiesPanel: { type: Boolean, default: false },
    },

    emits: ['update:modelValue', 'imported', 'import-error'],

    data: () => ({
        modeler: null,
    }),

    mounted() {
        this.modeler = new BpmnModeler({ container: this.$refs.canvas })
        this.modeler.on('commandStack.changed', this.sync)
        this.render()
    },

    beforeUnmount() {
        this.modeler?.destroy()
        this.modeler = null
    },

    watch: {
        modelValue(value, previous) {
            if (value !== previous && value !== this.latestXml) this.render()
        },
    },

    methods: {
        async render() {
            const xml = this.modelValue || EMPTY_DIAGRAM

            try {
                await this.modeler.importXML(xml)
                this.modeler.get('canvas').zoom('fit-viewport', 'auto')
                this.$emit('imported')
            } catch (err) {
                this.$emit('import-error', err)
            }
        },
        async sync() {
            const { xml } = await this.modeler.saveXML({ format: true })
            this.latestXml = xml
            this.$emit('update:modelValue', xml)
        },
    },
}
</script>

<style scoped>
.nova-bpmn-wrapper {
    position: relative;
    width: 100%;
    border: 1px solid rgba(var(--colors-gray-300));
    border-radius: 0.5rem;
    background: #fff;
    overflow: hidden;
    display: flex;
}

.nova-bpmn-canvas {
    flex: 1;
    height: 100%;
}

.nova-bpmn-properties {
    width: 260px;
    border-left: 1px solid rgba(var(--colors-gray-300));
    overflow: auto;
}
</style>
