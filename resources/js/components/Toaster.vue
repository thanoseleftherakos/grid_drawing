<template>
    <div class="toaster" :class="{ active : visible, success : success }">
        <div class="toaster__inner">
            {{message}}
        </div>
    </div>
</template>
<script>
export default {
    props: ['show', 'message', 'success'],
    data() {
        return {
            visible: false
        }
    },
    watch: {
        show: function() {
            this.visible = this.show;
            setTimeout(() => {
                this.visible = false;
                this.$emit('update:show', false)
            }, 5000);
        }
    }
}
</script>
<style lang="scss">
    .toaster {
        position: fixed;
        bottom: 20px;
        left: 30px;
        z-index: 99999;
        transform: translateY(200%);
        transition: transform 0.4s cubic-bezier(.59,-0.55,.52,1.49);
        &.active {
            transform: translateY(0);
        }
        &.success {
            .toaster__inner {
                background: #00C6B5!important;    
            }
        }
        &__inner {
            padding: 10px 40px;
            background: rgb(240, 54, 54);
            border-radius: 2px;
            color: #fff;
            text-align: center;
            min-width: 150px;
            letter-spacing: 0.1em;
            font-weight: 700;
        }
    }
</style>