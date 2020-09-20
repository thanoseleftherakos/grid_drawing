<template>
    <div class="symbol-create">
        <toaster :show.sync="toaster.show" :message="toaster.message" :success="toaster.success"></toaster>
        <draw-settings 
            :drawMode.sync="drawMode" 
            :resolution="grid_size[0]"
            :totalPoints="points.length"
            :symbol_id="symbol_id"
            @imageChanged="setBackgroundImage"
            @imagePositionChanged="setImagePosition"
            @resetPoints="resetPoints"
            @savePressed="saveSymbol"
            @newsymbol="newSymbol"
        ></draw-settings>
        <div class="grid-c">
            <div class="grid-c__container">
                <div class="grid-c__box">
                    <div class="grid-c__box__bgimage" v-if="bgImageUrl">
                        <img 
                            :src="bgImageUrl"
                            :style="{ transform: `translate( ${imgPosition.x}px, ${imgPosition.y}px ) scale(${imgPosition.scale}) rotate(${imgPosition.rotate}deg)` }"
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
                bgImage: null,
                symbol_id: null,
                toaster: {
                    show: false,
                    message: '',
                    success: false
                },
                imgPosition: {
                    x: 0,
                    y: 0,
                    scale: 1,
                    rotate: 0
                }
            }
        },
        mounted() {
            this.initDrawMode();
        },
        computed: {
            bgImageUrl: function() {
                if(this.bgImage) {
                    return URL.createObjectURL(this.bgImage);
                }
                return '';
            }
        },
        methods: {

            saveSymbol() {
                const data = new FormData();
                if(this.bgImage) {
                    data.append('image', this.bgImage);
                }
                const json = JSON.stringify({
                    symbol_id: this.symbol_id,
                    points: this.points,
                    img_position: this.imgPosition
                });
                data.append('data', json);

                axios.post('/api/symbols', data)
                .then((response) => {
                    if(response.data.success) {
                        this.symbol_id = response.data.symbol_id;
                        this.toaster.message = response.data.message;
                        this.toaster.show = true;
                        this.toaster.success = true;
                    }
                })
                .catch( (error) => {
                    console.log(error);
                    this.toaster.message = error;
                    this.toaster.show = true;
                    this.toaster.success = false;
                });
            },
            
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
            newSymbol() {
                this.resetPoints();
                this.imgPosition = { x: 0, y: 0, scale: 1, rotate: 0 };
                this.symbol_id = null;
                this.bgImage = null;
            },

            setBackgroundImage(image) {
                this.bgImage = image;
            },
            setImagePosition(position) {
                this.imgPosition = position;
            }
        }
    }
</script>