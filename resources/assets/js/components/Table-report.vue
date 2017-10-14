<template>
    <table class="table table-sm">
      <thead>
        <tr>
          <th>{{ name }}</th>
          <th v-for="field in fields">{{ field }}</th>
      </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">{{ dataset1Name }}</th>
      <td v-for="n in fieldKeys">{{ dataset1[n] }}</td>
  </tr>

  <tr>
      <th scope="row">{{ dataset2Name }}</th>
      <td v-for="n in fieldKeys">{{ dataset2[n] }}</td>
  </tr>

  <tr>
    <th scope="row">
      <i class="fa fa-line-chart" aria-hidden="true" title="Increase / decrease percentage"></i>
    </th>
    <td v-for="n in fieldKeys">
        <p v-bind:class="[dataset1[n] > dataset2[n] ? 'text-success' : '', dataset1[n] < dataset2[n] ? 'text-danger' : '']">
            {{ variation(dataset1[n], dataset2[n])}}
        </p>
    </td>
</tr>
</tbody>
</table>
</template>

<script>
export default {
    props: {
        name: {
            required: true
        },
        fields: {
            required: true
        },
        dataset1: {
            default: function () {
                return ['-'];
            }
        },
        dataset1Name: {},
        dataset2: {
            default: function () {
                return ['-'];
            }
        },
        dataset2Name: {}
    },

    computed: {
        fieldKeys: function () {
            return this.fields.length;
        },
    },

    methods: {
        variation(first, second) {
            if (first && second) {
                let variation = second - first;

                // This is where we can set a prop to color the result based on the variation being positive or negative
                return ((variation / first) * 100) + '%';
            }

            return '-';
        }
    }
}
</script>
