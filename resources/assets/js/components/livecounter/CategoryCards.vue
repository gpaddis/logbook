<template>
    <div>
        <div class="row justify-content-center">
            <category-card
            @add="add"
            @subtract="subtract"
            v-for="category in patronCategories"
            :key="category.id"
            :category-id="category.id"
            :name="category.name"
            :visits="visits[category.id]"
            v-show="category.is_primary == true || showSecondary == true"
            ></category-card>
        </div>

        <div class="row justify-content-center" v-show="containsSecondary">
            <a
            href="#"
            @click="toggleSecondary"
            data-toggle="collapse"
            data-target=".multi-collapse"
            aria-expanded="false"
            >Toggle secondary categories</a>
        </div>
    </div>
</template>

<script>
import CategoryCard from './CategoryCard.vue'

export default {
    components: {
        CategoryCard
    },

    props: ['patronCategories', 'initialCount'],

    data() {
        return {
            visits: this.initialCount,
            showSecondary: false
        }
    },

    methods: {
        refreshCount() {
            axios.get('/logbook/livecounter/show').then(response => this.visits = response.data);
        },

        add(id) {
            axios.post('/logbook/livecounter/add', { patron_category_id: id})
            .then(response => this.visits = response.data);
        },

        subtract(id) {
            axios.post('/logbook/livecounter/subtract', { patron_category_id: id})
            .then(response => this.visits = response.data);
        },

        toggleSecondary() {
            this.showSecondary = ! this.showSecondary;
        }
    },

    computed: {
        containsSecondary() {
            let result = false;
            for (let cat of this.patronCategories) {
                if (cat.is_primary === false) {
                    result = true;
                }
            }
            return result;
        }
    }
}
</script>
