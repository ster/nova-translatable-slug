<template>
    <DefaultField
        :field="field"
        :errors="errors"
        :show-help-text="showHelpText"
        :full-width-content="fullWidthContent"
    >
        <template #field>
            <div class="flex items-center">
                <input
                    :id="field.attribute"
                    ref="theInput"
                    type="text"
                    class="w-full form-control form-input form-input-bordered"
                    :class="errorClasses"
                    :placeholder="field.name"
                    :dusk="field.attribute"
                    v-model="value"
                    :disabled="isReadonly"
                    v-bind="extraAttributes"
                />

                <button
                    class="
                    btn btn-link
                    rounded
                    px-1
                    py-1
                    inline-flex
                    text-sm text-primary
                    ml-1
                    mt-2
                "
                    v-if="field.showCustomizeButton"
                    type="button"
                    @click="toggleCustomizeClick"
                >
                    {{ __('Customize') }}
                </button>
            </div>
        </template>
    </DefaultField>
</template>

<script>
import {FormField, HandlesValidationErrors} from 'laravel-nova'
import slugify from '@/util/slugify'

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    mounted() {
        this.attribute_lang = this.field.attribute.split('.')[1];

        if (this.shouldRegisterInitialListener) {
            this.registerChangeListener()
        }
    },

    methods: {
        changeListener(value) {
            return value => {
                this.value = slugify(value, this.field.separator)
            }
        },

        registerChangeListener() {
            Nova.$on(this.eventName, value => {
                this.value = slugify(value, this.field.separator)
            })
        },

        toggleCustomizeClick() {
            if (this.field.readonly) {
                Nova.$off(this.eventName)
                this.field.readonly = false
                this.field.extraAttributes.readonly = false
                this.field.showCustomizeButton = false
                this.$refs.theInput.focus()
                return
            }

            this.registerChangeListener()
            this.field.readonly = true
            this.field.extraAttributes.readonly = true
        },

        /*
         * Set the initial, internal value for the field.
         */
        // setInitialValue() {
        //     this.value = this.field.value || ''
        // },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        // fill(formData) {
        //     formData.append(this.field.attribute, this.value || '')
        // },
    },

    computed: {
        eventName() {
            return `${this.field.from}.${this.attribute_lang}-change`
        },

        shouldRegisterInitialListener() {
            return !this.field.updating
        },

        extraAttributes() {
            return this.field.extraAttributes || {}
        },
    },
}
</script>
