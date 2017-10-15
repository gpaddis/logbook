<template>
    <div class="row justify-content-center">
        <category-card
        @updated="refreshCount"
        v-for="category in patronCategories"
        :key="category.id"
        :category-id="category.id"
        :name="category.name"
        :visits="visits[category.id]"
        ></category-card>
    </div>
</template>

<script>
import CategoryCard from './CategoryCard.vue'

export default {
    components: {
        CategoryCard
    },

    props: ['patronCategories'],

    data() {
        return {
            visits: []
        }
    },

    methods: {
        refreshCount() {
            axios.get('/logbook/livecounter/show').then(response => this.visits = response.data);
        },
    },

    mounted() {
        this.refreshCount();
    }
}
</script>
