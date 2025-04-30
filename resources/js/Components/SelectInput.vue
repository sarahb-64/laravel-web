<template>
    <select v-bind="$attrs" class="form-select">
        <slot></slot>
    </select>
</template>

<script>
export default {
    inheritAttrs: false,
}
</script>