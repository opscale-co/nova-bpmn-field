import DetailField from './components/DetailField.vue'
import FormField from './components/FormField.vue'
import IndexField from './components/IndexField.vue'

Nova.booting((app) => {
    app.component('index-nova-bpmn-field', IndexField)
    app.component('form-nova-bpmn-field', FormField)
    app.component('detail-nova-bpmn-field', DetailField)
})
