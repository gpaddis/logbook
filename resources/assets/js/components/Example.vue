<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Example Component</div>

                    <div class="panel-body">
                        {{ rawDatasets }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState, mapMutations, mapGetters, mapActions} from 'vuex';

    export default {
        computed: {
            ...mapState(['rawDatasets']),
            ...mapGetters(['totalVisits'])
        },

        methods: {
            ...mapActions(['addDataset']),
            ...mapMutations(['clearDatasets'])
        } ,
        
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
