<template>
    <div class="nova-bpmn-wrapper" :style="{ height: height + 'px' }">
        <div ref="canvas" class="nova-bpmn-canvas"></div>
        <div v-if="zoomControls" class="nova-bpmn-controls">
            <button type="button" @click.prevent="zoomIn" title="Zoom in">+</button>
            <button type="button" @click.prevent="zoomOut" title="Zoom out">−</button>
            <button type="button" @click.prevent="zoomReset" title="Reset">⟳</button>
        </div>
    </div>
</template>

<script>
import BpmnViewer from 'bpmn-js/lib/NavigatedViewer'
import 'bpmn-js/dist/assets/diagram-js.css'
import 'bpmn-js/dist/assets/bpmn-font/css/bpmn.css'

export default {
    props: {
        xml: { type: String, default: '' },
        height: { type: Number, default: 600 },
        zoomControls: { type: Boolean, default: true },
    },

    data: () => ({
        viewer: null,
        error: null,
    }),

    mounted() {
        this.viewer = new BpmnViewer({ container: this.$refs.canvas })
        this.render()
    },

    beforeUnmount() {
        this.viewer?.destroy()
        this.viewer = null
    },

    watch: {
        xml() {
            this.render()
        },
    },

    methods: {
        async render() {
            if (!this.viewer || !this.xml) return

            try {
                await this.viewer.importXML(this.xml)
                this.viewer.get('canvas').zoom('fit-viewport', 'auto')
                this.error = null
                this.$emit('imported')
            } catch (err) {
                this.error = err
                this.$emit('import-error', err)
            }
        },
        zoomIn() {
            this.viewer?.get('zoomScroll').stepZoom(1)
        },
        zoomOut() {
            this.viewer?.get('zoomScroll').stepZoom(-1)
        },
        zoomReset() {
            this.viewer?.get('canvas').zoom('fit-viewport', 'auto')
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
}

.nova-bpmn-canvas {
    width: 100%;
    height: 100%;
}

.nova-bpmn-controls {
    position: absolute;
    bottom: 0.75rem;
    right: 0.75rem;
    display: flex;
    gap: 0.25rem;
}

.nova-bpmn-controls button {
    width: 2rem;
    height: 2rem;
    border-radius: 0.375rem;
    background: #fff;
    border: 1px solid rgba(var(--colors-gray-300));
    cursor: pointer;
    font-weight: 600;
    line-height: 1;
}

.nova-bpmn-controls button:hover {
    background: rgba(var(--colors-gray-100));
}
</style>
