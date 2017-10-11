<template>
    <div>
        <constraints-filter :schedules="schedules"
                            :availability-constraints="availabilityConstraints"
                            :fixed-constraints="fixedConstraints"
        ></constraints-filter>
        <h1>Test {{ message }}</h1>
        <p>{{ constraintTypes }}</p>
    </div>
</template>

<script>
    import ConstraintsFilter from './Constraints-Filter.vue'

    export default {

        components: {
            ConstraintsFilter
        },

        props: {
            schedules: { required: true},
            availabilityConstraints: { required: true},
            fixedConstraints: { required: true}
        },

        mounted() {
            this.constraintTypes = this.getConstraintTypes();
        },

        data() {
            return {
                constraintTypes : [],
            }
        },

        methods: {
            getConstraintTypes() {
                axios.get('/api/constraintTypes')
                    .then(res => {
                        this.constraintTypes = res.data;
                    });
            }

        }
    }
</script>