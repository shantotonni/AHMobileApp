<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Banner List']"/>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="datatable" v-if="!isLoading">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <input v-model="query" type="text" class="form-control" placeholder="Search">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-success btn-sm" @click="createBanner">
                                            <i class="fas fa-plus"></i>
                                            Add
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm" @click="reload">
                                            <i class="fas fa-sync"></i>
                                            <!--                                            Reload-->
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                        <tr>

                                            <th class="text-center">ID</th>
                                            <th class="text-center">Banner Name</th>
                                            <th class="text-center">Banner Image</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(banner, i) in banners" :key="banner.ID" v-if="banners.length">
                                            <th class="text-center" scope="row">{{ ++i }}</th>
                                            <td class="text-center">{{ banner.BannerName }}</td>
                                            <td class="text-center">
                                                <img v-if="banner.BannerImage" height="40" width="40" :src="tableImage(banner.BannerImage)" alt="">
                                            </td>
                                            <td class="text-center">
                                                <button @click="edit(banner)" class="btn btn-success btn-sm"><i class="far fa-edit"></i></button>
                                                <button hidden="hidden" @click="destroy(banner.ID)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="query === '' ? getAllBanner() : searchData()"
                                    ></pagination>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <skeleton-loader :row="14"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--  Modal content for the above example -->
        <div class="modal fade" id="bannerModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Banner</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">×</button>
                    </div>
                    <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Banner Name</label>
                                            <input type="text" name="Name" v-model="form.BannerName" class="form-control" :class="{ 'is-invalid': form.errors.has('BannerName') }">
                                            <div class="error" v-if="form.errors.has('BannerName')" v-html="form.errors.get('BannerName')" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Banner Image</label>
                                            <input @change="changeImage($event)" type="file" name="Image" class="form-control" :class="{ 'is-invalid': form.errors.has('BannerImage') }">
                                            <div class="error" v-if="form.errors.has('BannerImage')" v-html="form.errors.get('BannerImage')" />
                                            <img v-if="form.BannerImage" :src="showImage(form.BannerImage)" alt="" height="40px" width="40px">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Close</button>
                            <button :disabled="form.busy" type="submit" class="btn btn-primary">{{ editMode ? "Update" : "Create" }} User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "List",
    data() {
        return {
            banners: [],
            roles: [],
            pagination: {
                current_page: 1
            },
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                ID:'',
                BannerName:'',
                BannerImage: '',
            }),
        }
    },
    watch: {
        query: function(newQ, old) {
            if (newQ === "") {
                this.getAllBanner();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Banner List | Diesel Engine';
        this.getAllBanner();
    },
    methods: {
        getAllBanner(){
            this.isLoading = true;
            axios.get('/api/banner?page='+ this.pagination.current_page).then((response)=>{
                console.log(response.data.data)
                this.banners = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error)=>{

            })
        },
        searchData(){
            axios.get("/api/search/banner/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.banners = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload(){
            this.getAllBanner();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal(){
            $("#bannerModal").modal("hide");
        },
        createBanner(){
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#bannerModal").modal("show");
        },

        store(){
            this.form.busy = true;
            this.form.post("/api/banner").then(response => {
                $("#bannerModal").modal("hide");
                this.getAllBanner();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(banner) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(banner);
            $("#bannerModal").modal("show");
        },
        update(){
            this.form.busy = true;
            this.form.put("/api/banner/" + this.form.ID).then(response => {
                $("#bannerModal").modal("hide");
                this.getAllBanner();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        changeImage(event){
            let file = event.target.files[0];
            let reader = new FileReader();
            reader.onload = event => {
                this.form.BannerImage = event.target.result;
            };
            reader.readAsDataURL(file);
        },
        showImage(){
            let img = this.form.BannerImage;
            if (img.length > 100){
                return this.form.BannerImage;
            }else{
                return window.location.origin + "/AHMobileApp/images/banner/" + this.form.BannerImage;
            }
        },
        tableImage(image){
            return window.location.origin + "/AHMobileApp/images/banner/"+ image;
        },
        destroy(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete('api/banner/'+ id).then((response)=>{
                        this.getAllBanner();
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    })
                }
            })
        }
    },
}
</script>

<style scoped>

</style>
