<template>
    <div class="symbols_preview">
        <a href="#" class="symbols_preview__close" @click.prevent="$emit('closepreview')"><i class="fas fa-times-circle"></i></a>
        <div class="symbols_preview__inner">
            <div href="#" class="symbols_preview__item" v-for="( symbol, key ) in symbols" v-bind:key="'symbols_preview'+key">
                <img :src="'/storage/'+symbol.preview" alt="">
                <div class="symbols_preview__item__icons">
                    <a :href="'/storage/'+symbol.preview" :download="'/storage/'+symbol.preview" class="download"><i class="fas fa-download"></i></a>
                    <a href="#" @click.prevent="setActiveItem(symbol.id)" class="edit"><i class="fas fa-edit"></i></a>
                </div>
            </div>
            <div class="symbols_preview__item symbols_preview__item--empty"></div>
            <div class="symbols_preview__item symbols_preview__item--empty"></div>
            <div class="symbols_preview__item symbols_preview__item--empty"></div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            visible: false,
            symbols: null,
        }
    },
    mounted() {
        this.fetchSymbols();
    },
    methods: {
        fetchSymbols() {
            axios.get('/api/symbols')
            .then((response) => {
                this.symbols = response.data.symbols;
            })
            .catch( (error) => {
                console.log(error);
            });
        },
        setActiveItem(id){
            this.$emit('setActiveItem', id)
        }
    }
}
</script>
<style lang="scss">
    .symbols_preview {
        position: fixed;
        z-index: 10000;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow-y: scroll;
        background: #0b1015;
        padding: 30px;
        &__inner {
            position: relative;
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        &__item {
            position: relative;
            flex: 0 1 calc(25% - 15px);
            margin-bottom: 20px;
            transition: opacity 0.2s ease;
            &--empty {
                pointer-events: none;
                height: 0;
                visibility: hidden;
            }
            &:hover {
                .symbols_preview__item__icons {
                    opacity: 1;
                }
            }
            img {
                display: block;
                // max-width: 300px;
            }
            &__icons {
                position: absolute;
                top: 50%;
                width: 100%;
                left: 0;
                text-align: center;
                transition: opacity 0.4s ease;
                transform: translateY(-50%);
                opacity: 0;
                a {
                    display: inline-block;
                    width: 40px;
                    height: 40px;
                    line-height: 40px;
                    text-align: center;
                    border-radius: 40px;
                    margin: 10px;
                    color: #fff;
                    &.download {
                        background: #3399ec;
                    }
                    &.edit {
                        background: #00C6B5;
                    }
                }
            }
        }
        &__close {
            position: absolute;
            z-index: 100;
            right: 15px;
            top: 15px;
            font-size: 2em;
            color: #f1f1f1;
            transition: color 0.2s ease;
            &:hover {
                color: #00C6B5;
            }
        }

    }
</style>