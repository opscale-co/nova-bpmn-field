<template>
    <span class="whitespace-nowrap">
        {{ hasValue ? __('BPMN diagram') : '—' }}
    </span>
</template>

<script>
import { FieldValue } from 'laravel-nova'

export default {
    mixins: [FieldValue],
    props: ['resourceName', 'field'],

    computed: {
        hasValue() {
            return typeof this.field.value === 'string' && this.field.value.trim().length > 0
        },
    },
}
</script>
