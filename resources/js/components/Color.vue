<template>
    <div class="colorpicker">
        <label for="">{{name}}</label>
        <input type="text" class="colorpicker__color" v-model="mcolor.color" @input="changeColor">
        <input type="number" class="colorpicker__percent" v-model="mcolor.percent" @input="changeColor">
        <span :style="{backgroundColor : mcolor.color}"></span>
    </div>
</template>

<script>
export default {
    props: ['value', 'name'],
    data() {
        return {
            mcolor: {
                color: this.value.color,
                percent: this.value.percent,
            }
        }
    },
    methods: {
        changeColor() {
            this.$emit('input', this.mcolor);
        }
    }
}
</script>

<style lang="scss">
    .colorpicker {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        margin-bottom: 20px;
        &__color {
            width: 60px;
            margin-left: 15px;
        }
        &__percent {
            width: 45px;
            margin-left: 15px;
        }
        span {
            margin-left: 15px;
            width: 21px;
            height: 21px;
            border-radius: 50%;
            border: 2px solid #fff;
        }
    }
</style>