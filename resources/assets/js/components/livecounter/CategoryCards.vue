<template>
    <div class="row justify-content-center">
        <category-card
            v-for="category in patronCategories"
            :key="category.id"
            :category="category"
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
    }
</script>
