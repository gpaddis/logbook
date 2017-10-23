<template>
    <div class="col-sm col-lg-2 mb-2">
        <div class="card text-center border-info">
            <div class="card-body">
                <h1 class="display-4">{{ this.value }}</h1>
                <p>
                    <slot></slot>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        value: {
            defalut: 0
        }
    }
}
</script>
