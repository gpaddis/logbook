<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Example Component</div>

                    <div class="panel-body">
                        {{ datasets }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState, mapMutations, mapActions} from 'vuex';

    export default {
        computed: mapState(['datasets', 'labels']),

        methods: {
            ...mapActions(['addDataset']),
            ...mapMutations(['clearDatasets'])
        } ,
        
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
