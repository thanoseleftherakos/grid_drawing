<template>
    <div class="draw-settings">
        <div class="draw-settings__inner">
            <h3>Settings</h3>
            <div class="draw-settings__row">
                <h5>Drawing mode: <span :class="{ active : drawMode, inactive : !drawMode }">{{ drawMode ? 'ON' : 'OFF' }}</span></h5>
                <h5>Resolution: <span>{{ resolution }} dpi</span></h5>
                <h5>Total points: <span>{{ totalPoints }}</span></h5>
            </div>
            <div class="draw-settings__row">
                <h4>Image</h4>
                <h5>Upload Image: <input @change="onFileChange" type="file" name="image" id="" accept="image/png, image/jpeg"></h5>
                <h5 v-if="image">Position: 
                    <button class="btn--small" @click.prevent="imgPosition.y -= 2"><i class="fas fa-arrow-up"></i></button> 
                    <button class="btn--small" @click.prevent="imgPosition.y +=2"><i class="fas fa-arrow-down"></i></button> 
                    <button class="btn--small" @click.prevent="imgPosition.x -= 2"><i class="fas fa-arrow-left"></i></button> 
                    <button class="btn--small" @click.prevent="imgPosition.x +=2"><i class="fas fa-arrow-right"></i></button> 
                </h5>
                <h5 v-if="image">Scale: 
                    <button class="btn--small" @click.prevent="imgPosition.scale += 0.05"><i class="fas fa-plus"></i></button> 
                    <button class="btn--small" @click.prevent="imgPosition.scale -= 0.05"><i class="fas fa-minus"></i></button>
                </h5> 
                <h5 v-if="image">Rotate: 
                    <button class="btn--small" @click.prevent="imgPosition.rotate += 1"><i style="transform: scaleX(-1);" class="fas fa-undo"></i></button>
                    <button class="btn--small" @click.prevent="imgPosition.rotate -= 1"><i class="fas fa-undo"></i></button> 
                </h5> 
            </div>
        </div>
        <div class="draw-settings__actions">
            <div class="draw-settings__actions__inner">
                <button class="btn btn--green" @click.prevent="saveSymbol" :disabled="loading">
                    {{ symbol_id ? 'UPDATE' : 'SAVE' }}
                    <div class="spinner" v-if="loading"></div>
                </button>
                <button class="btn btn--blue" @click.prevent="$emit('newsymbol')" v-if="symbol_id">NΕW</button>
                <button class="btn btn--red" @click="confirmReset">CLEAR</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['drawMode', 'totalPoints', 'resolution', 'symbol_id', 'loading'],
        data() {
            return {
                image: null,
                imgPosition: {
                    x: 0,
                    y: 0,
                    scale: 1,
                    rotate: 0
                }
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
            }
        },
        methods: {
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
        overflow-y: scroll;
        overflow-x: hidden;
        background: #0b1015;
        color: #fff;
        &__inner {
            position: relative;
            padding: 20px 30px 30px
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
            h4 {
                font-size: 1.2em;
                font-weight: 800;
                margin: 0;
                margin-bottom: 10px;
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
            &__inner {
                padding: 30px;
                position: relative;
                display: flex;
                justify-content: space-between;
            }
        }
    }
</style>