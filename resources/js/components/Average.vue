<template>
    <div class="average">
        <a href="#" @click.prevent="closeModal" class="average__close"><i class="fas fa-times-circle" aria-hidden="true"></i></a>
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
    props: ['grid_size', 'showAverage', 'loading'],
    data() {
        return {
            points: []
        }
    },
    mounted() {
        this.fetchPoints();
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

    &__close {
        position: absolute;
        z-index: 100;
        right: 15px;
        top: 15px;
        font-size: 2em;
        color: #000;
        transition: color 0.2s ease;
        &:hover {
            color: #00C6B5;
        }
    }
}
</style>