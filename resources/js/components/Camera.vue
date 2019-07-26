<template>
    <div id="app">
        <v-app id="inspire">
        <form @submit.prevent="onSubmit" @keydown="form.errors.clear()" enctype="multipart/form-data">
        <v-container grid-list-md text-xs-center class="unsetWidth">
            <v-layout row wrap>
                <v-flex xs12 sm6 md6>
                    <v-layout row wrap>
                        <v-flex xs12 sm6 md12>
                            <video ref="video" id="video" width="400" height="400"autoplay></video>   
                            <canvas ref="canvas" id="canvas" width="400" height="400"></canvas>
                        </v-flex>
                        <v-flex xs12 sm6 md12>
                            <div class="text-xs-center">
                                <v-btn flat primary id="snap" v-on:click="capture()">Snap Photo</v-btn>
                            </div>
                        </v-flex>
                    </v-layout>
                </v-flex>
                <v-flex xs12 sm6 md6>
                    <v-layout row wrap>
                        <v-flex xs12 sm6 md12>
                            <img v-if="form.image" v-model="form.image" v-bind:src="form.image" width="400" height="400" />
                        </v-flex>
                        <v-flex xs12 sm6 md12>
                            <div class="text-xs-center">
                                <v-btn v-if="form.image" color="primary" type="submit">Save</v-btn>
                            </div>
                        </v-flex>
                    </v-layout>
                </v-flex>
            </v-layout>
        </v-container>      
        </form>          
        </v-app>
        <!-- ul>
            <li v-for="c in captures">
                <img v-bind:src="c" height="50" />
            </li>
        </ul> -->
    </div>
</template>
<script>
    export default {
        name: 'app',
        props: ['logtype'],
        data() {
            return {
                form: new Form({
                    image: '',
                }),
                video: {},
                canvas: {},
                captures: '',
            }
        },
        mounted() {
            this.video = this.$refs.video;
            if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: true }).then(stream => {
                    // HTMLMediaElement.srcObject = stream
                    video.srcObject = stream;
                    video.play();
                });
            }
        },
        methods: {
            capture() {
                this.canvas = this.$refs.canvas;
                var context = this.canvas.getContext("2d").drawImage(this.video, 0, 0, 640, 480);
                this.form.image = canvas.toDataURL("image/png");
            },
            onSubmit() {
                let formData = new FormData();
                formData.append('image', this.form.image);
  
                axios.post('/user/capture/'+this.logtype,
                    formData,{headers: {'Content-Type': 'multipart/form-data'}})
                .then((data) => {
                    this.video = {};
                    this.canvas = {};
                    this.form.image={};
                    this.$emit('imageuploaded',data);
                    // this.form = [];
                })
                .catch((error) => {
                    // this.wbserrors = error.response.data.message;
                    // this.dialog = true;
                });
               
            },   
        }
    }
</script>

<style>
   
    
    #video {
        background-color: #000000;
    }
    #canvas {
        display: none;
    }
    
</style>