<template>
    <div>
        <constraints-filter :schedules="schedules"
                            :availability-constraints="availabilityConstraints"
                            :fixed-constraints="fixedConstraints"
        ></constraints-filter>
        <constraint-fixed :constraints-props="fixedConstraints"
                          :constraint-types="constraintTypes"
        ></constraint-fixed>
        <hr>
        <constraint-availability :constraints-props="availabilityConstraints"
                                 :constraint-types="constraintTypes"
        ></constraint-availability>
    </div>
</template>

<script>
    import ConstraintsFilter from './Constraints-Filter.vue'
    import ConstraintFixed from './Constraints-Fixed.vue'
    import ConstraintAvailability from './Constraints-Availability.vue'

    export default {

        components: {
            ConstraintsFilter,
            ConstraintFixed,
            ConstraintAvailability,
        },

        props: {
            schedules: { required: true },
            constraintTypes: { required:true },
            availabilityConstraints: { required: true },
            fixedConstraints: { required: true }
        },

        data() {
            return {
            }
        }
    }
</script>