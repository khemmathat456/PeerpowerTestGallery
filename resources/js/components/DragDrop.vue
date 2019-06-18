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
           <div class="col-4" v-for="img in lst_img">
               <input type="button" value="Delete" class="delete btn btn-danger delete-user" @click="delete_image(img)">
               <input type="button" value="Show" class="update btn btn-primary" @click="show_image(img)" data-toggle="modal" data-target=".image-modal-lg">

               <div v-if="img.modal">
                   <div class="modal image-modal-lg" id="image-modal-lg" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                       <div class="container-fluid" role="document">
                           <div class="modal-content">
                               <img :src="img.image.name_unique" alt="">
                           </div>


                           <div class="modal-footer">
                               <button type="button" @click="img.modal=false" class="close" data-dismiss="modal" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                               </button>
                           </div>
                       </div>
                   </div>
               </div>
               <div v-if="index==1"><h1>11111111111111</h1></div>
               <div v-else-if="index==2"><h1>222222222222222</h1></div>
               <div v-else-if="index==3"><h1>333333333333333</h1></div>
               <img v-if="img" :src="img.image.name_unique" alt="" width="200px" height="200px">
               {{ img.uploadPercentage }}
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
               index:0,
               files:[],
           };
        },
        methods: {
            checkFileType() {
                this.files = this.$refs.myFiles.files

                for(let file of this.files){
                    this.images[this.lst_img.length] = {
                        image:{},
                        uploadPercentage:0,
                        modal:false,
                        err:''
                    };
                    // this.lst_img.push(this.images[this.lst_img.length])
                    this.uploadfile(file);
                }
            },

            uploadfile(file) {
                const Uploadprogress = (index) => (progress) => {
                    // this.$set(this.images, index, {uploadPercentage: Math.floor((progress.loaded * 100) / progress.total)});
                    Object.assign(this.images[index].uploadPercentage = Math.floor((progress.loaded * 100) / progress.total))
                    console.log(this.images[index].uploadPercentage)
                };
                let formData = new FormData();

                formData.append('fileUpload', file);
                var config = {
                    onUploadProgress: Uploadprogress(this.lst_img.length)
                };
                axios.post('/images', formData, config).then((response) =>  {
                    // Object.assign(this.images[this.lst_img.length].image = response.data)
                    // this.$set(this.images, this.lst_img.length, {image:response.data});
                    this.images[this.lst_img.length] = {
                        image:response.data,
                        uploadPercentage:0,
                        modal:false,
                        err:''
                    }
                    this.lst_img.push(this.images[this.lst_img.length])
                    console.log(this.images)
                })
                .catch(error => {
                    if (error.response){
                        Object.assign(this.images[this.lst_img.length].err = error.response.data.errors.fileUpload[1]+file.name)
                    }
                });
            },


            delete_image(img){
                axios.delete('/images/'+img.image.name_unique);
                this.lst_img.splice(this.lst_img.indexOf(img), 1)
            },

            show_image(img){
                img.modal = true
            }
        },
        created(){
            axios.get('/images').then((response) => {
                for (let index in response.data) {
                    this.images[index] = {
                        image:response.data[index],
                        uploadPercentage:0,
                        modal:false,
                        err:''
                    };

                    this.lst_img.push(this.images[index])
                }
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
