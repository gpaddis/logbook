<template>
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Year</th>
                <th v-for="field in this.fields">{{ field }}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="values, year in this.values ">
                <th scope="row">{{ year }}</th>
                <td v-for="value in values">{{ value }}</td>
            </tr>

            <tr>
                <th scope="row">
                    <i class="fa fa-line-chart" aria-hidden="true" title="Increase / decrease percentage"></i>
                </th>
                <td v-for="element in this.percentage"
                :class="element >= 0 ? 'text-success' : 'text-danger'">
                    <p v-if="element">
                        {{ element }}%
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: {
        values: {
            required: true
        }
    },

    data() {
        return {
            fields: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        }
    },

    computed: {
        series() {
            return this.convertToArray(this.values);
        },

        percentage() {
            let serie1 = this.series[0];
            let serie2 = this.series[1];

            let collection = [];

            for (let index in serie1) {
                collection.push(this.calculateVariation(serie1[index], serie2[index]));
            }

            return collection;
        }
    },

    methods: {
        calculateVariation(first, second) {
            if (first && second) {
                let variation = second - first;

                return (Math.round((variation / first) * 100));
            }
        },

        convertToArray(object) {
            let collection = [];

            for (let prop in object) {
                collection.push(object[prop]);
            }

            return collection;
        }
    }
}
</script>
