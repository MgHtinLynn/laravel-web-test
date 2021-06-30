<template>
    <div class="map-view">
        <gmap-map :center="center" :zoom="2" ref="mmm" style="width: 100%; height: 500px">
            <gmap-info-window :options="infoOptions" :position="infoWindowPos" :opened="infoWinOpen"
                              @closeclick="infoWinOpen=false">
            </gmap-info-window>
            <gmap-marker :key="index" v-for="(m, index) in markers" :position="m.position" :clickable="true"
                         @click="toggleInfoWindow(m,index)"></gmap-marker>
        </gmap-map>
    </div>
</template>

<script>
export default {
    props: ['data'],
    name: "AppMap",
    data() {
        return {
            center: {
                //center lat an
                lat: 30.00,
                lng: 31.00
            },
            infoWindowPos: null,
            infoWinOpen: false,
            currentMidx: null,

            infoOptions: {
                content: '',
                //optional: offset info-window so it visually sits nicely on top of our marker
                pixelOffset: {
                    width: 0,
                    height: -35
                }
            },
            markers: this.data // makers from backend api data
        }
    },
    methods: {
        toggleInfoWindow(marker, idx) {
            this.infoWindowPos = marker.position;
            this.infoOptions.content = marker.location.title + '<br>' + marker.location.date_time;

            //check if its the same marker that was selected if yes toggle
            if (this.currentMidx === idx) {
                this.infoWinOpen = !this.infoWinOpen;
            }
            //if different marker set info-window to open and reset current marker index
            else {
                this.infoWinOpen = true;
                this.currentMidx = idx;

            }
        }
    }
}
</script>

<style scoped>
.map-view {
    width: 100%;
    height: 100%;
}
</style>
