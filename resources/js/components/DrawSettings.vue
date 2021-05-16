<template>
    <div class="draw-settings">
        <div class="draw-settings__inner">
            <h3>Settings</h3>
            <div class="draw-settings__row">
                <h4>Grid</h4>
                <h5>Drawing mode: <span :class="{ active : drawMode, inactive : !drawMode }">{{ drawMode ? 'ON' : 'OFF' }}</span></h5>
                <h5>Resolution: <span>{{ resolution }} dpi</span></h5>
                <h5>Total points: <span>{{ totalPoints }}</span></h5>
                <h5>Opacity: 
                <vue-slider v-model="gridOpacity" :min="0" :max="1" :interval="0.01" @change="updateGridOpacity"/>
                </h5>
                <h5 v-if="symbol_categories.length">Category:
                    <v-select 
                    label="name"
                    v-model="symbol_category_id"
                    :options="symbol_categories"
                    :reduce="name => name.id"
                    >
                    </v-select>
                </h5>
            </div>
            <div class="draw-settings__row">
                <h4>Image</h4>
                <h5>Upload Image: <input @change="onFileChange" type="file" name="image" id="" accept="image/png, image/jpeg"></h5>
                <h5 v-if="image || existingImage">Position: 
                    <button class="btn--small" @click.prevent="imgPosition.y -= 2"><i class="fas fa-arrow-up"></i></button> 
                    <button class="btn--small" @click.prevent="imgPosition.y +=2"><i class="fas fa-arrow-down"></i></button> 
                    <button class="btn--small" @click.prevent="imgPosition.x -= 2"><i class="fas fa-arrow-left"></i></button> 
                    <button class="btn--small" @click.prevent="imgPosition.x +=2"><i class="fas fa-arrow-right"></i></button> 
                </h5>
                <h5 v-if="image || existingImage">Scale: 
                    <button class="btn--small" @click.prevent="imgPosition.scale += 0.05"><i class="fas fa-plus"></i></button> 
                    <button class="btn--small" @click.prevent="imgPosition.scale -= 0.05"><i class="fas fa-minus"></i></button>
                </h5> 
                <h5 v-if="image || existingImage">Rotate: 
                    <button class="btn--small" @click.prevent="imgPosition.rotate += 1"><i style="transform: scaleX(-1);" class="fas fa-undo"></i></button>
                    <button class="btn--small" @click.prevent="imgPosition.rotate -= 1"><i class="fas fa-undo"></i></button> 
                </h5> 
                <h5 v-if="image || existingImage">Opacity: 
                <vue-slider v-model="imageOpacity" :min="0" :max="1" :interval="0.01" @change="updateOpacity"/>
                </h5> 
            </div>
            <div class="draw-settings__row">
                <h4>Symbols</h4>
                <h5><a href="#" @click.prevent="$emit('showpreview')">View all created symbols <i class="fas fa-arrow-right"></i></a></h5>
                <h5><a href="#" @click.prevent="$emit('showaverage')">Show Average <i class="fas fa-arrow-right"></i></a></h5>
            </div>
            <div class="draw-settings__row">
                <h4>Auto Color</h4>
                <color v-model="colors[0]" name='Color1'/>
                <color v-model="colors[1]" name='Color2'/>
                <color v-model="colors[2]" name='Color3'/>
                <color v-model="colors[3]" name='Color4'/>
                <button class="btn btn--blue" @click.prevent="runAutocolor">APPLY</button>
            </div>
        </div>
        <div class="draw-settings__actions">
            <div class="draw-settings__actions__inner">
                <button class="btn btn--green" @click.prevent="saveSymbol" :disabled="loading">
                    {{ symbol_id ? 'UPDATE' : 'SAVE' }}
                    <div class="spinner" v-if="loading"></div>
                </button>
                <button class="btn btn--blue" @click.prevent="newSymbol" v-if="symbol_id">NΕW</button>
                <button class="btn btn--red" @click="confirmReset">CLEAR</button>
            </div>
        </div>
    </div>
</template>

<script>
    import VueSlider from 'vue-slider-component'
    import 'vue-slider-component/theme/antd.css'
    import vSelect from 'vue-select';
    import 'vue-select/dist/vue-select.css';
    import Color from './Color'
    export default {
        props: ['drawMode', 'totalPoints', 'resolution', 'symbol_id', 'loading', 'existingImage', 'symbol_categories','symbol_category_id'],
        components: {
            VueSlider, vSelect,Color
        },
        data() {
            return {
                image: null,

                imgPosition: {
                    x: 0,
                    y: 0,
                    scale: 1,
                    rotate: 0,
                },
                imageOpacity: 1.0,
                gridOpacity: 1.0,
                colors: [
                    { percent: 25, color: '#000000'},
                    { percent: 25, color: '#53f144'},
                    { percent: 25, color: '#f03636'},
                    { percent: 25, color: '#3399ec'}
                ]
            }
        },
        mounted() {
            this.initKeyboardActions();
        },
        watch: {
            imgPosition: {
                handler(val){
                    this.$emit('imagePositionChanged', val)
                },
                deep: true
            },
            symbol_category_id: {
                handler(val){
                    if(val == 2) {
                        this.$emit('update:showHelpers', true)
                    } else {
                        this.$emit('update:showHelpers', false)
                    }
                    this.$emit('update:symbol_category_id', val)
                }
            }
        },
        methods: {
            runAutocolor() {
                this.$emit('colorsChanged', this.colors)
            },

            onFileChange(e) {
                const file = e.target.files[0];
                this.image = file;
                // this.imageUrl = URL.createObjectURL(file);
                this.$emit('imageChanged', this.image)
            },
            
            confirmReset() {
                if (confirm("Are you sure!? 😳")) {
                    this.$emit('resetPoints')
                }
            },

            initKeyboardActions() {
                window.addEventListener('keydown', (e) => {
                    if (e.key == 'ArrowUp') this.imgPosition.y--;
                    if (e.key == 'ArrowDown') this.imgPosition.y++;
                    if (e.key == 'ArrowLeft') this.imgPosition.x--;
                    if (e.key == 'ArrowRight') this.imgPosition.x++;
                    if (e.key == '+') this.imgPosition.scale += 0.05;
                    if (e.key == '-') this.imgPosition.scale -= 0.05;
                });
            },

            saveSymbol() {
                this.$emit('savePressed')
            },
            newSymbol(){
                if (confirm("Are you sure!? Any unsaved changes will be lost 😳")) {
                    this.$emit('newsymbol')
                }
            },
            updateOpacity(value) {
                this.$emit('updateOpacity', value)
            },
            updateGridOpacity(value) {
                this.$emit('updateGridOpacity', value)
            }
        }
    }
</script>

<style lang="scss">
    .draw-settings {
        position: fixed;
        right: 0;
        width: 30vw;
        top: 0;
        height: 100vh;
        background: #0b1015;
        color: #fff;
        &__inner {
            position: relative;
            padding: 20px 30px 30px;
            overflow-y: scroll;
            height: 100%;
            overflow-x: hidden;
            padding-bottom: 100px;
        }
        h3 {
            font-size: 2em;
            font-weight: 800;
            margin-top: 0;
            padding-bottom: 20px;
            margin-bottom: 20px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }
        &__row {
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
            &:last-child {
                border :none;
            }
            h4 {
                font-size: 1.2em;
                font-weight: 800;
                margin: 0;
                margin-bottom: 10px;
            }
            a {
                color: #00C6B5;
                text-decoration: none;
            }
            h5 {
                margin-bottom: 10px;
                span {
                    padding-left: 5px;
                    font-weight: 800;
                    color: #00C6B5;
                    &.active {
                        color: rgb(83, 241, 68);
                    }
                    &.inactive {
                        color: rgb(240, 54, 54);
                    }
                }
            }
        }
        &__actions {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #192430;
            &__inner {
                // border-top: 2px solid rgba(255, 255, 255, 0.2);
                padding: 20px 30px;
                position: relative;
                display: flex;
                justify-content: space-between;
            }
        }
    }
    .v-select {
        margin-top: 10px;
    }
    .vs__dropdown-toggle {
        border-color: #fff;
    }
    .vs__open-indicator, .vs__clear  {
        fill: #fff;
    }
</style>