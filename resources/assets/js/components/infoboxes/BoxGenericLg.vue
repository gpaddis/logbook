<template>
    <div class="col-lg-4 col-md-6 col-sm-6 mb-2">
        <div class="card border-secondary mb-3">
            <div class="card-body">
                <h4 class="card-title">
                    <slot name="title"></slot>
                </h4>
                <h2 class="display-2 text-center">
                    {{ value }}
                </h2>
                <h5 class="card-subtitle mb-2 text-muted text-center">
                    <slot name="subtitle"></slot>
                </h5>
            </div>
            <div class="card-footer text-center">
                <slot name="footer"></slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['value']
}
</script>
