<template>
    <div class="card">
        <div class="card-body">
            <h3>User statistics: 2017</h3>

            <p class="card-text">
                Use the dropdown menu to load the statistics for a different year.
            </p>

            <div class="form-row align-items-center">
                <div class="col-auto">
                    <select v-if="yearsAvailable" name="years" class="custom-select">
                        <option v-for="year in yearsAvailable" value="year" :key="year">{{ year }}</option>
                    </select>

                    <select v-else class="custom-select">
                        <option class="disabled">No data available</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    props: ['yearsAvailable'],

    data() {
        return {
            currentYear: null,
        }
    }
}
</script>
