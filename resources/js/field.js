import IndexField from './components/IndexField.vue'
import DetailField from './components/DetailField.vue'
import FormField from './components/FormField.vue'

Nova.booting((app) => {
    app.component('index-nova-bpmn-field', IndexField)
    app.component('detail-nova-bpmn-field', DetailField)
    app.component('form-nova-bpmn-field', FormField)
})
