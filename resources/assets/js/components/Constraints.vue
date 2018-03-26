<template>
    <div>
        <div class="row">
            <div class="col-md-3">
                <constraints-filter :schedules="schedules"
                                    :availability-constraints="availabilityConstraints"
                                    :fixed-constraints="fixedConstraints"
                ></constraints-filter>
            </div>
            <div class="col-md-3 col-md-offset-6">
                <a href="#"><i class="fa fa-eye"></i> Voir anciennes contraintes</a>
            </div>

            <div class="col-md-12">
            <constraint-fixed :constraints-props="fixedConstraints"
                              :constraint-types="constraintTypes"
            ></constraint-fixed>
            <hr>
            <constraint-availability :constraints-props="availabilityConstraints"
                                     :constraint-types="constraintTypes"
            ></constraint-availability>
                </div>
        </div>
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