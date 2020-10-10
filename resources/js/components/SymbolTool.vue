<template>
    <div class="symbol-create">
        <transition name="fade">
        <loader v-if="loading"></loader>
        </transition>
        <transition name="fade">
            <average
                v-if="showAverage"
                :showAverage.sync="showAverage"
                :grid_size="grid_size"
                :loading.sync="loading"
            ></average>
        </transition>
        <transition name="fade">
        <symbols-preview 
            @setActiveItem="setActivesymbol"
            @closepreview="closepreview" 
            v-if="showPreview"
        ></symbols-preview>
        </transition>
        <toaster :show.sync="toaster.show" :message="toaster.message" :success="toaster.success"></toaster>
        <draw-settings 
            :drawMode.sync="drawMode" 
            :resolution="grid_size[0]"
            :totalPoints="Object.keys(points).length"
            :symbol_id="symbol_id"
            :loading="loading"
            :existingImage="existingImage"
            :symbol_categories="symbol_categories"
            :symbol_category_id.sync="symbol_category_id"
            @imageChanged="setBackgroundImage"
            @imagePositionChanged="setImagePosition"
            @resetPoints="resetPoints"
            @savePressed="saveSymbol"
            @newsymbol="newSymbol"
            @showpreview="openpreview"
            @showaverage="openaverage"
            @updateOpacity="updateOpacity"
            @updateGridOpacity="updateGridOpacity"
        ></draw-settings>
        <div class="grid-c">
            <div class="grid-c__container">
                <div class="grid-c__box" ref="gridBox">
                    <div class="grid-c__box__bgimage" v-if="bgImageUrl">
                        <img 
                            :src="bgImageUrl"
                            :style="{ 
                                transform: `translate( ${imgPosition.x}px, ${imgPosition.y}px ) scale(${imgPosition.scale}) rotate(${imgPosition.rotate}deg)`,
                                opacity: imgOpacity 
                            }"
                        >
                    </div>
                    <div class="grid-c__box__row" 
                        v-for="(rows, rows_key) in grid_size[0]" 
                        v-bind:key="'row_'+rows_key"
                        :style="{ opacity: gridOpacity }"
                        >
                        <div 
                            class="grid-c__box__item"
                            v-for="(cols, cols_key) in grid_size[1]"
                            v-bind:key="'col_'+cols_key"
                            :class="{ 'active' : isChecked([rows_key + 1, cols_key + 1]), center : itemIsCenter(rows_key+1,cols_key+1) }"
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
    let html2canvas = require('html2canvas');
    export default {
        props: ['symbol_categories'],
        data() {
            return {
                grid_size: [82,82],
                points: {},
                drawMode: false,
                bgImage: null,
                symbol_id: null,
                symbol_category_id: null,
                showAverage: false,
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
                },
                existingImage: false,
                imgOpacity: 1,
                gridOpacity: 1.0,
                previewImage: null,
                loading: false,
                showPreview: false,
            }
        },
        mounted() {
            this.initDrawMode();
        },
        computed: {
            bgImageUrl: function() {
                if(this.bgImage && typeof this.bgImage === 'object') {
                    return URL.createObjectURL(this.bgImage);
                }
                return this.bgImage;
            },
        },
        methods: {

            async saveSymbol() {
                this.loading = true;
                const data = new FormData();
                if(this.bgImage && this.bgImage instanceof File) {
                    data.append('image', this.bgImage);
                }
                // await this.setPreviewImage();
                let _points = [];
                Object.keys(this.points).forEach((point) => {
                    _points.push(this.points[point]);
                });
                const json = JSON.stringify({
                    symbol_id: this.symbol_id,
                    symbol_category_id: this.symbol_category_id,
                    points: _points,
                    img_position: this.imgPosition,
                    preview_image: this.previewImage
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
                    this.loading = false;
                })
                .catch( (error) => {
                    console.log(error);
                    this.toaster.message = error.response.data.errors;
                    this.toaster.show = true;
                    this.toaster.success = false;
                    this.loading = false;
                });
            },
            
            checkUncheck(point) {
                if(!this.isChecked(point)) {
                    // this.points.push(point);
                    this.$set(this.points, point, point)
                }
                else {
                    if(!this.drawMode) {
                        this.$delete(this.points, point)
                        // this.points.splice(this.getPointsArrayIndex(point), 1);
                    }
                }
            },

            getPointsArrayIndex(point) {
                return this.points.findIndex(x => x.every((value, index) => point[index] == value));
            },

            isChecked(point) {
                return this.points.hasOwnProperty(point);
                // return this.points.some(
                //     r => r.length == point.length &&
                //         r.every((value, index) => point[index] == value)
                // );
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
                this.existingImage = false;
            },

            setBackgroundImage(image) {
                this.bgImage = image;
            },
            setImagePosition(position) {
                this.imgPosition = position;
            },
            itemIsCenter(x,y) {
                if( x == this.grid_size[0]/2 && y == this.grid_size[1]/2) {
                    return true;
                }
                return false;
            },
            closepreview() {
                this.showPreview = false;
            },
            openpreview() {
                this.showPreview = true;
            },
            openaverage() {
                this.showAverage = true;
            },
            setActivesymbol(id) {
                this.loading = true;
                axios.get(`/api/symbols/${id}`)
                .then((response) => {
                    if(response.data.success) {
                        if(response.data.symbol.points.length) {
                            this.points = {};
                            response.data.symbol.points.forEach((point, index) => {
                                this.$set(this.points, [point.x, point.y], [point.x, point.y])
                            });
                        }
                        this.bgImage = '/storage/'+response.data.symbol.image;
                        this.symbol_id = response.data.symbol.id;
                        this.imgPosition.x = response.data.symbol.position_x;
                        this.imgPosition.y = response.data.symbol.position_y;
                        this.imgPosition.scale = response.data.symbol.scale;
                        this.imgPosition.rotate = response.data.symbol.rotate;
                        this.showPreview = false;
                        this.existingImage = true;
                        console.log(this.imgPosition)
                    }
                    this.loading = false;
                })
                .catch( (error) => {
                    console.log(error);
                    this.loading = false;
                });
            },
            updateOpacity(value) {
                this.imgOpacity = value;
            },
            updateGridOpacity(value) {
                this.gridOpacity = value;
            },

            async setPreviewImage() {
                const gridbox = this.$refs.gridBox;
                await html2canvas(gridbox).then(canvas => {
                    this.previewImage = canvas.toDataURL("image/png");
                }).catch((error) => {
                    console.log("Error rendering canvas to image", error)
                });
            }
            
        }
    }
</script>