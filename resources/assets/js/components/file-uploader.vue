<template>
    <div id="dropzone">
        <div 
            class="dropzone mbot-10 text-center container-fluid" 
            :class="{'active': dragover, 'has-files': tmpFile.length > 0}"
            @dragover="ondragover"
            @drop="ondrop"
            @dragleave="ondragleave"
            @dragenter="ondragenter"
        >
            <span class="placeholder" v-show="tmpFile != null">
            Drag and drop your images here...(jpg, jpeg, png, bmp)</span>
            
            <div class="row" v-if="tmpFiles.length > 0">
                <div class="col-sm-3"
                >
                    <div class="file">
                        <img
                            :src="fileToImg(tmpFile)" 
                            class="img-responsive center-block"
                        >
                        <button 
                            class="close"
                            @click="deleteFile(tmpFile)"
                        >
                            <i class="fa fa-close"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right m-top-10">
            <button 
                class="btn btn-primary" 
                :disabled="tmpFile == null"
                @click="upload"
            >
                Upload
            </button>
        </div>
    </div>    
</template>
<script type="text/javascript">
    module.exports = {
        el: function () {
            return '#dropzone';
        },
        props: {
            url: {
                type: String,
                required: true,
            },
            file: {
                type: Object,
                default: function () {
                    return null;
                }
            },
            validTypes: {
                type: Array,
                default: function () {
                    return ['image/jpeg', 'image/jpg', 'image/png', 'image/bmp'];
                }
            },
        },
        data: function () {
            return {
                tmpFile: null,
                dragover: false,
            };
        },
        computed: {
            errors: function () {
                var errors = {};
                if (this.validTypes.indexOf(this.tmpFile.type) === -1) {
                    errors[this.tmpFile.name] = 'File is not a valid type';
                }
            
                return errors;
            },
            isValid: function () {
                return Object.keys(this.errors).length === 0;
            }
        },
        methods: {
            ondragover: function (e) {
                this.dragover = true;
                e.preventDefault();
            },
            ondragenter: function (e) {
                e.preventDefault();
            },
            ondragleave: function (e) {
                this.dragover = false;
            },
            ondrop: function (e) {
                e.preventDefault();
                // this.tmpFile = [];
                var file = e.dataTransfer.file;
                this.dragover = false;
                this.tmpFile = file;
            },
            deleteFile: function () {
                this.tmpFile = null;
            },
            upload: function() {
                if (!this.isValid) {
                    return;
                }
                this.file = this.$data.tmpFile;

                var data = new FormData();
                var self = this;
                data.append('file', this.file);
                this.$http.post(this.url, data, function (response) {
                    self.file = null;
                    self.tmpFile = null;
                }, {
                    emulateJSON: true
                });
            },
            fileToImg: function (file) {
                return URL.createObjectURL(file);
            }
        }
    };
</script>