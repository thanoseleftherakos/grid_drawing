<template>
    <div class="symbol-create">
        <draw-settings 
            :drawMode.sync="drawMode" 
            :resolution="grid_size[0]"
            :totalPoints="points.length"
            @imageChanged="setBackgroundImage"
            @imagePositionChanged="setImagePosition"
            @resetPoints="resetPoints"
        ></draw-settings>
        <div class="grid-c">
            <div class="grid-c__container">
                <div class="grid-c__box">
                    <div class="grid-c__box__bgimage">
                        <img 
                            :src="bgImageUrl"
                            :style="{ transform: `translate( ${imgPosition.x}px, ${imgPosition.y}px ) scale(${imgPosition.scale})` }"
                        >
                    </div>
                    <div class="grid-c__box__row" v-for="(rows, rows_key) in grid_size[0]" v-bind:key="'row_'+rows_key">
                        <div 
                            class="grid-c__box__item" 
                            v-for="(cols, cols_key) in grid_size[1]"
                            v-bind:key="'col_'+cols_key"
                            :class="{ 'active' : isChecked([rows_key + 1, cols_key + 1]) }"
                            @mouseover = "drawMode ? checkUncheck([rows_key + 1, cols_key + 1]) : ''"
                            @click = "checkUncheck([rows_key + 1, cols_key + 1])"
                        ></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                grid_size: [64,64],
                points: [],
                drawMode: false,
                bgImageUrl: null,
                imgPosition: {
                    x: 0,
                    y: 0,
                    scale: 1
                }
            }
        },
        mounted() {
            this.initDrawMode();
        },
        methods: {
            
            checkUncheck(point) {
                if(!this.isChecked(point)) {
                    this.points.push(point);
                }
                else {
                    if(!this.drawMode) {
                        this.points.splice(this.getPointsArrayIndex(point), 1);
                    }
                }
            },

            getPointsArrayIndex(point) {
                return this.points.findIndex(x => x.every((value, index) => point[index] == value));
            },

            isChecked(point) {
                return this.points.some(
                    r => r.length == point.length &&
                        r.every((value, index) => point[index] == value)
                );
            },

            initDrawMode() {
                window.addEventListener('keydown', (e) => {
                    if (e.key == 'd') {
                        this.drawMode = !this.drawMode;
                    }
                });
            },

            resetPoints() {
                this.points = [];
            },

            setBackgroundImage(url) {
                console.log(url);
                this.bgImageUrl = url;
            },
            setImagePosition(position) {
                this.imgPosition = position;
                console.log(this.imgPosition.x)
            }
        }
    }
</script>