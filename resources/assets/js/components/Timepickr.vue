<template>
        <input type="text"
               :id="inputId"
               :class="inputClass"
               :name="inputName"
               :placeholder="inputPlaceholder"
               :required="inputRequired"
               :size="inputSize"
               v-model="mutableValue"
               v-on:blur="onFocusLost()"
        />
</template>

<script>
    export default {

        props: {
            value: {
                default: null,
                required: true,
                validator(value) {
                    return value === null || value instanceof Date || typeof value === 'string' || value instanceof String;
                }
            },
            inputId: {
                type: String
            },
            inputClass: {
                type: [String, Object],
                default: 'form-control'
            },
            inputName: {
                type: String,
                default: 'time'
            },
            inputPlaceholder: {
                type: String,
                default: '--:--'
            },
            inputRequired: {
                type: Boolean,
                default: false
            },
            inputSize: {
                type: Number,
                default: 5
            }
        },

        data() {
            return {
                mutableValue: this.value
            }
        },

        methods: {
            onFocusLost() {
                let value = this.mutableValue;
                let valueH = null;
                let valueM = null;

                if(!value || value.length == 0) return;


                let colonPosition = value.indexOf(":");

                if (colonPosition == -1) {
                    valueH = value.substr(0, 2);
                    valueM = value.substr(2, 2);
                } else {
                    valueH = value.substr(0, colonPosition);
                    valueM = value.substr(colonPosition+1, 2);
                }

                if (valueH > 23) valueH = 23;
                if (valueM > 59) valueM = 59;

                if (valueH == '') valueH = '00';
                if (valueM == '') valueM = '00';


                this.mutableValue = valueH + ':' + valueM;
            }
        }
    }
</script>