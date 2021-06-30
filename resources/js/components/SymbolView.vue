<template>
    <div class="average">
        <div class="average__inner">
            <div class="grid-c" v-if="points">
                <div class="grid-c__container" style="display:flex;justify-content: center;">
                    <div class="grid-c__box" ref="gridBox">
                        <div class="grid-c__box__row" 
                            v-for="(rows, rows_key) in grid_size[0]" 
                            stagger="50"
                            v-bind:key="'row_'+rows_key"
                            >
                            <div 
                                class="grid-c__box__item"
                                v-for="(cols, cols_key) in grid_size[1]"
                                v-bind:key="'col_'+cols_key"
                                :class="{ 'active' : isChecked(rows_key + 1, cols_key + 1)}"
                                :style="{ transitionDelay: Math.floor(Math.random() * 15000) + 'ms' }"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['symbol'],
    data() {
        return {
            loading: true,
            points: [],
            grid_size: [82, 82]
        }
    },
    mounted() {
        // this.fetchPoints();
        setTimeout(() => {
            this.points = this.symbol.points;
        },10);
    },
    methods: {
        fetchPoints() {
            this.$emit('update:loading', true);
            axios.get('/api/symbols-average')
                .then((response) => {
                    this.points = response.data.points;
                    this.$emit('update:loading', false);
                })
                .catch( (error) => {
                    this.$emit('update:loading', false);
                });
        },

        isChecked(x,y) {
            if (this.points.filter(points => points.point === `${x} ${y}`).length ) {
                return true;
            }
        },
        closeModal() {
            this.$emit('update:showAverage', false)
        }
    },
    watch: {
        showAverage: function() {
            console.log(this.showAverage);
        }
    }
}
</script>
<style lang="scss">
.average {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 99999;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    .grid-c__box__item {
        outline: 1px solid rgba(0, 0, 0, 0);
        &.active {
            background: #000;
        }
    }
}
</style>