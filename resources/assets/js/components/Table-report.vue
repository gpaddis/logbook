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
            required: true,
            default: '#'
        },
        fields: {
            required: true,
        },
        dataset1: {
            default: function () {
                return ['-'];
            }
        },
        dataset1Name: {
          default: 'dataset 1'
        },
        dataset2: {
            default: function () {
                return ['-'];
            }
        },
        dataset2Name: {
          default: 'dataset 2'
        }
    },

    computed: {
        fieldKeys: function () {
          if (typeof this.fields != 'undefined') {
            return this.fields.length;
          }

          return 0;
        },
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
