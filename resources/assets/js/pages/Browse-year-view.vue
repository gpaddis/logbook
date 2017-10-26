<script>
export default {
    props: ['data', 'categories'],

    data() {
        return {
            entries: this.data,
        }
    },

    computed: {
        test() {
            for (let key in this.entries) {
                console.log(key, this.entries[key]);
            }
        }
    }
}
</script>
