<template>
<div class="card">
    <div class="card-body">
        <h4>Comparison</h4>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Year</th>
                    <th v-for="field in this.labels">{{ field }}</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="dataset in this.totalVisits">
                    <td><strong>{{ dataset.label }}</strong></td>
                    <td v-for="value in dataset.data">{{ typeof(value) !== 'undefined' ? value : '-' }}</td>
                </tr>

                <tr v-if="ready">
                    <td><i class="fa fa-line-chart" aria-hidden="true" title="Increase / decrease percentage"></i></td>
                    <td v-for="percValue in this.percentage">{{ percValue !== '-' ? percValue + '%' : '-' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</template>

<script>
import {mapState, mapGetters} from 'vuex';


export default {
    computed: {
        ...mapState(['updated']),
        ...mapGetters(['totalVisits', 'labels']),

        /** 
         * Evaluate to true if both datasets are loaded.
         */
        ready() {
            return this.totalVisits.hasOwnProperty(0) && this.totalVisits.hasOwnProperty(1);
        },

        /** 
         * Return a serie containing the percentage variation across the datasets compared.
         */
        percentage() {
            let serie1 = this.totalVisits[0].data;
            let serie2 = this.totalVisits[1].data;

            let collection = [];

            if (serie2) {
                for (let index in this.labels) {
                    collection.push(this.calculateVariation(serie1[index], serie2[index]));
                }
            }

            return collection;
        }
    },

    methods: {
        /** 
         * Calculate the percentage variation between the values provided.
         */
        calculateVariation(first, second) {
            if (first && second) {
                let variation = second - first;

                return (Math.round((variation / first) * 100));
            } else {
                return '-';
            }
        }
    }
}
</script>

