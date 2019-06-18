<template>
    <div class="container-fluid">

        <div class="row" style="padding-bottom: 2rem">
            <div class="col-12">
            <form enctype="multipart/form-data">
                <div class="dropbox">
                    <input class="input-file" ref="myFiles" type="file" multiple @change="checkFileType">
                    <p>
                        Drop files here or click to choose files...
                    </p>
                </div>
            </form>
            </div>
        </div>

<!--        <div class="row">-->
<!--                <div v-if="uploadPercentage">-->
<!--                    <div v-for="file in files">-->
<!--                        {{ file.name }}-->
<!--                        <progress max="100" :value.prop="lst[file.name]"></progress>-->
<!--                        {{ lst[file.name] }}%-->
<!--                    </div>-->
<!--                </div>-->

<!--            <div class="col-4" v-if="path_lst" v-for="path in path_lst" style="padding-bottom: 2rem">-->
<!--                <div class="show-image" v-if="path">-->

<!--                    <input type="button" value="Delete" class="delete btn btn-danger delete-user" @click="delete_image(path)">-->
<!--                    <input type="button" value="Show" class="update btn btn-primary" @click="show_image(path)" data-toggle="modal" data-target=".image-modal-lg">-->

<!--                    <div v-if="modal">-->
<!--                        <div class="modal image-modal-lg" id="image-modal-lg" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true">-->
<!--                            <div class="container-fluid" role="document">-->
<!--                                <div class="modal-content">-->
<!--                                    <img :id="img" :src="img" alt="">-->
<!--                                </div>-->

<!--                                <div class="modal-footer">-->
<!--                                    <button type="button" @click="modal=false" class="close" data-dismiss="modal" aria-label="Close">-->
<!--                                        <span aria-hidden="true">&times;</span>-->
<!--                                    </button>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->

<!--                    <img :id="path" @mouseover="" :src="path" alt="" width="200px" height="200px">-->
<!--                </div>-->
<!--            </div>-->

<!--            <div class="col-4" v-if="err_lst" v-for="err in err_lst" style="padding-bottom: 2rem">-->
<!--                <div class="show-image" v-if="err">-->
<!--                    <input type="button" value="Delete" class="delete btn btn-danger delete-user" @click="delete_err(err)">-->
<!--                    <span style="color: red">{{ err }}</span>-->
<!--                </div>-->
<!--            </div>-->

<!--        </div>-->


        <div class="row">
            <div class="col-4" v-if="images" v-for="image in images">
                <div v-if="image.info && !image.err">
                    <img v-if="image.info && !image.err" :src="image.info.name_unique" alt="" width="200px" height="200px">
                    <div v-else-if="image.uploadPercentage">{{ image.uploadPercentage }}%</div>
                </div>
                <div v-if="image.err">
                    <input type="button" value="Delete" class="delete btn btn-danger delete-user" @click="delete_err(err)">
                    <span style="color: red">{{ image.err }}</span>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        data() {
           return {
               images:{},
               lst_img: [],
               files:[],
               index:0,
               uploadPercentage: 0,
               lst: {}, // TODO: pack lst
               path_lst: [],
               img: '',
               modal: false,
               err_lst: []
           };
        },
        methods: {
            checkFileType() {
                this.files = this.$refs.myFiles.files

                for(let file of this.files){
                    this.images[this.index]= {
                        uploadPercentage: 0,
                        info: {},
                        modal: false,
                        err: ''
                    };

                    this.uploadfile(file);
                    this.index++
                }

            },

            uploadfile(file) {
                const myUploadProgress = (myFileId) => (progress) => {
                    this.uploadPercentage = Math.floor((progress.loaded * 100) / progress.total);
                    this.lst[myFileId] = this.uploadPercentage;
                };

                const Uploadprogress = (index) => (progress) => {
                    // this.$set(this.images, index, {uploadPercentage: Math.floor((progress.loaded * 100) / progress.total)});
                    Object.assign(this.images[index],{uploadPercentage: Math.floor((progress.loaded * 100) / progress.total)})
                    console.log(this.images[index])
                };
                let formData = new FormData();

                formData.append('fileUpload', file);
                var config = {
                    // onUploadProgress: myUploadProgress(file.name)
                    onUploadProgress: Uploadprogress(this.index)
                };

                axios.post('/images', formData, config).then((response) =>  {
                    this.$set(this.images, this.index, {uploadPercentage: 0, info: response.data, err: '', modal: false});

                    // this.uploadPercentage =0;
                    // this.path_lst.push(response.data)
                })
                .catch((error) => {
                    console.log(error)
                    this.$set(this.images, this.index, {uploadPercentage: 0, info: {}, err: error.response.data.errors.fileUpload[1]+file.name,  modal: false});
                    // this.err_lst.push(error.response.data.errors.fileUpload[1]+file.name)
                });
            },


            delete_image(id){
                this.path_lst.splice(this.path_lst.indexOf(id), 1)
                axios.delete('/images/'+id);
            },

            delete_err(err){
                this.err_lst.splice(this.err_lst.indexOf(err), 1)
            },

            show_image(id){
                this.modal = true;
                this.img = id;
            }
        },
        created(){
                axios.get('/images').then((response) => {
                    for (let index in response.data) {
                        this.$set(this.images,this.index++,{
                                uploadPercentage: 0,
                                info: response.data[index],
                                modal: false,
                                err: ''
                            })
                        //
                        // this.lst_img.push(this.index= {
                        //         uploadPercentage: 0,
                        //         info: response.data[index],
                        //         modal: false,
                        //         err: ''
                        //     }
                        // );

                        // this.path_lst.push(response.data[index].name_unique)
                    }
                    console.log(this.images);
                });
        },
    }
</script>

<style lang="scss">
    .dropbox {
        outline: 2px dashed grey; /* the dash box */
        padding: 10px 10px;
        position: relative;
        cursor: pointer;
    }

    .input-file {
        opacity: 0; /* invisible but it's there! */
        width: 100%;
        height: 100%;
        position: absolute;
        cursor: pointer;
    }

    .dropbox:hover {
        background: lightblue; /* when mouse over to the drop zone, change color */
    }

    .dropbox p {
        font-size: 1.2em;
        text-align: center;
        padding: 50px 0;
    }


    div.show-image {
    position: relative;
    float: left;
    margin: 5px;
    }

    div.show-image:hover img {
        opacity: 1;
    }

    div.show-image:hover input {
        display: block;
    }

    div.show-image input {
        position: absolute;
        display: none;
    }

    div.show-image input.update {
        top: 50%;
        left: 12.5%;
    }

    div.show-image input.delete {
        top: 50%;
        left: 50%;
    }

</style>
