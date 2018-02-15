<template>
    <div class="col-sm col-lg-2 mb-2">
        <div class="card text-center border-info">
            <div class="card-body">
                <h1 class="display-4">{{ average }}</h1>
                <p>
                    <slot></slot>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['value', 'number'],

    computed: {
        average: function () {
            if (this.value != 0 && this.number != 0) {
                return this.round(this.value / this.number, 1);
            } else {
                return 0;
            }
        }
    },

    methods: {
        round(value, precision) {
            var multiplier = Math.pow(10, precision || 0);
            return Math.round(value * multiplier) / multiplier;
        }
    }
}
</script>
