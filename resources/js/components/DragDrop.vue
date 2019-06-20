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

        <div class="row">
           <div class="col-4 " style="padding-bottom: 2rem" v-for="(img, index) in lst_img">
               <div class="card d-flex border-light text-center show-image" style="border: 0px; width: 100%; height: 200px ">


               <div class="d-flex justify-content-center card-body" v-if="img.uploadPercentage">
                   <p class="align-self-center text-primary card-text">{{ img.uploadPercentage+'%' }}</p>
               </div>

                   <button type="button" v-if="img.image.name_unique" value="Delete" class="delete btn btn-danger delete-user" @click="delete_image(img)">Delete</button>
                   <button type="button" v-if="img.image.name_unique" value="Show" class="update btn btn-primary" @click="show_image(img)" data-toggle="modal" data-target=".image-modal-lg">show</button>
               <img v-if="img.image.name_unique" class="card-img" :id="index" :src="img.image.name_unique" alt="Card image" height="100%">

               <div v-if="img.modal">
                   <div class="modal image-modal-lg" role="dialog" id="image-modal-lg" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                       <div class="modal-dialog" style="max-width: 60%">
                           <div class="modal-content">
                                <img class="img-fluid"  :src="img.image.name_unique" alt="">

                           <div class="modal-footer">
                               <button type="button" @click="img.modal=false" class="close" data-dismiss="modal" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                               </button>
                           </div>
                           </div>
                       </div>
                   </div>
               </div>

               <div class="d-flex justify-content-center card-body" v-if="img.err">
                   <button v-if="img.err" type="button" value="Delete" class=" delete_error btn btn-danger delete-user" @click="delete_image(img)">Delete</button>
                   <p v-if="img.err" class="align-self-center text-danger card-text">{{ img.err }}</p>
               </div>

               </div>
           </div>
        </div>

    </div>
</template>

<script>
    export default {
        data() {
           return {
               lst_img: [],
               index:0,
               files:[],
           };
        },
        methods: {
            checkFileType() {
                this.files = this.$refs.myFiles.files;
                for(let file of this.$refs.myFiles.files){
                    this.uploadfile(file);
                }
                this.$refs.myFiles.value = null
            },

            uploadfile(file) {

                let images = {
                    image:{},
                    uploadPercentage:0,
                    modal:false,
                    err:''
                };
                this.lst_img.push(images)

                const Uploadprogress = (index) => (progress) => {
                    Object.assign(images.uploadPercentage = Math.floor((progress.loaded * 100) / progress.total))
                };
                let formData = new FormData();

                formData.append('fileUpload', file);
                var config = {
                    onUploadProgress: Uploadprogress(file.name)
                };

                axios.post('/images', formData, config).then((response) =>  {
                    Object.assign(images.uploadPercentage = 0)
                    Object.assign(images.image = response.data)


                    this.index++

                })
                .catch(error => {
                    if (error.response){
                        Object.assign(images.uploadPercentage = 0)
                        Object.assign(images.err = error.response.data.errors.fileUpload[1]+file.name)
                        this.index++
                    }
                });
            },


            delete_image(img){
                axios.delete('/images/'+img.image.name_unique);
                this.lst_img.splice(this.lst_img.indexOf(img), 1)

                console.log(img)
                console.log(this.images)
                console.log(this.lst_img)
            },

            show_image(img){
                img.modal = true
            }
        },
        created(){
            axios.get('/images').then((response) => {
                for (let index in response.data) {
                    let images = {
                        image:response.data[index],
                        uploadPercentage:0,
                        modal:false,
                        err:''
                    };

                    this.lst_img.push(images)
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
        /*margin: 5px;*/
        display: block;
    }

    div.show-image:hover img.card-img {
        opacity: 0;
        display: none;
    }
    div.show-image:hover div.card-body p {
        display: none;
        opacity: 0;
    }
    div.show-image:hover button {
        display: block;
        opacity: 1;
    }
    div.show-image button {
        position: absolute;
        opacity: 0;
    }
    div.show-image button.update {
        top: 40%;
        left: 12.5%;
    }
    div.show-image button.delete {
        top: 40%;
        left: 50%;
    }
    div.show-image button.delete_error {
        top: 40%;
        left: 30%;
    }


</style>
