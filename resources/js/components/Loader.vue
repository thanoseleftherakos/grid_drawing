<template>
    <div class="loader">
        <div class="loader__inner">
            <div class="spinner"></div>
        </div>
    </div>
</template>
<script>
export default {
    
}
</script>
<style lang="scss">
    .loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 198, 181, 0.5);
        z-index: 1000;
        &__inner {
            position: relative;
            width: 100%;
            height: 100%;
            .spinner  {
                right: 50%;
                transform: translate(-50%,-50%);
                width: 80px;
                height: 80px;
                &:after {
                    border-width: 8px;
                    width: 80px;
                    height: 80px;
                }
            }
        }
    }
</style>