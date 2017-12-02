<template>
        <div class="col">
            <div class="card">
                <p>Example Component</p>

                <div>
                    {{ rawDatasets }}
                </div>
            </div>
        </div>
</template>

<script>
    import {mapState, mapMutations, mapGetters, mapActions} from 'vuex';

    export default {
        computed: {
            ...mapState(['rawDatasets']),
            ...mapGetters(['groupedBy']),
            ...mapGetters({
                datasets: 'totalVisits', 
            })
        },

        mounted() {
            let currentYear = new Date().getFullYear();
            this.fetchDatasets(currentYear);
        },

        methods: {
            ...mapActions(['addDataset']),
            ...mapMutations(['clearDatasets']),

            fetchDatasets(year) {
                this.clearDatasets();

                this.addDataset(year);
                this.addDataset(year - 1);
            }
        } 
    }
</script>
