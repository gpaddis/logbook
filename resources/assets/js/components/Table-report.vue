<template>
    <table class="table">
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
        }
    }
}
</script>
