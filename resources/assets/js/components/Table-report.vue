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
                <td v-for="n in this.fields">
                    <p>-
                        <!-- {{ variation(dataset1[n], dataset2[n]) }} -->
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
            return new Map(Object.entries(this.values));
        }
    },

    methods: {
        variation(first, second) {
            if (first && second) {
                let variation = second - first;

                return ((variation / first) * 100) + '%';
            }

            return '-';
        }
    }
}
</script>
