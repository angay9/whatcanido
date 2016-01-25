<template>
    <div id="dropzone">
        <div 
            class="dropzone mbot-10 text-center container-fluid" 
            :class="{'active': dragover, 'has-files': tmpFile != null}"
            @dragover="ondragover"
            @drop="ondrop"
            @dragleave="ondragleave"
            @dragenter="ondragenter"
        >
            <span class="placeholder" v-show="tmpFile == null">
            Drag and drop your images here...(jpg, jpeg, png, bmp)</span>
            
            <div class="row" v-if="tmpFile != null">
                <div class="col-xs-3"
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
            multiple: {
                type: Boolean,
                default: function () {
                    return false;
                }
            }
        },
        data: function () {
            return {
                tmpFile: null,
                dragover: false,
            };
        },
        ready: function () {
            if (this.file) {
                this.tmpFile = this.file;
            }
        },
        methods: {
            validateFile: function (file) {
                var errors = {};
                if (this.validTypes.indexOf(file.type) === -1) {
                    errors[this.tmpFile.name] = 'File is not a valid type';
                }
                
                return Object.keys(errors).length > 0 ? errors : null;
            },
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
                var file = e.dataTransfer.files[0];
                var errors = this.validateFile(file)
                if (errors) {
                    App.alert(errors, 'danger');
                    return false;
                }
                
                if (this.tmpFile != null && this.multiple == false) {
                    App.alert('You can only add one file.', 'danger');
                    return false;
                }
                this.tmpFile = file;
                this.dragover = false;

            },
            deleteFile: function () {
                this.tmpFile = null;
            },
            upload: function() {
                this.file = this.$data.tmpFile;

                var data = new FormData();
                var self = this;
                data.append('file', this.file);
                this.$http.post(this.url, data).then(function (response) {
                    self.file = null;
                    self.tmpFile = null;
                    App.alert('Avatar has been uploaded');
                }, function (errorResp) {
                    App.alert(errorResp.data, 'danger');
                }, {
                    emulateJSON: true
                });
            },
            fileToImg: function (file) {
                if (file.src) {
                    return file.src;
                }
                return URL.createObjectURL(file);
            }
        }
    };
</script>