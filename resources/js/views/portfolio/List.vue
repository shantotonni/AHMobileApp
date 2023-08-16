<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Portfolio List']"/>
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
                                        <button type="button" class="btn btn-success btn-sm" @click="createPortfolio">
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
                                            <th class="text-left">Id</th>
                                            <th class="text-left">Name</th>
                                            <th class="text-left">Image</th>
                                            <th class="text-left">Status</th>
                                            <th class="text-left">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(portfolio, i) in portfolios" :key="portfolio.ID" v-if="portfolios.length">
                                            <th class="text-center" scope="row">{{ ++i }}</th>
                                            <td class="text-left">{{ portfolio.PortfolioName }}</td>
                                            <td class="text-center">
                                                <img v-if="portfolio.PortfolioImage" height="40" width="40" :src="tableImage(portfolio.PortfolioImage)" alt="">
                                            </td>
                                            <td class="text-left">
                                                <span class="badge badge-success" v-if="portfolio.Active == 'Y'">Active</span>
                                                <span class="badge badge-success" v-else>InActive</span>
                                            </td>
                                            <td class="text-center">
                                                <button @click="edit(portfolio)" class="btn btn-success btn-sm"><i class="far fa-edit"></i></button>
                                                <button  @click="destroy(portfolio.ID)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="query === '' ? getAllPortfolio() : searchData()"
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
        <div class="modal fade" id="portfolioModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Portfolio</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">×</button>
                    </div>
                    <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Portfolio Name</label>
                                            <input type="text" name="Name" v-model="form.PortfolioName" class="form-control" :class="{ 'is-invalid': form.errors.has('PortfolioName') }">
                                            <div class="error" v-if="form.errors.has('PortfolioName')" v-html="form.errors.get('PortfolioName')" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Image</label>
                                            <input @change="changeImage($event)" type="file" name="PortfolioImage" class="form-control" :class="{ 'is-invalid': form.errors.has('PortfolioImage') }">
                                            <div class="error" v-if="form.errors.has('PortfolioImage')" v-html="form.errors.get('PortfolioImage')" />
                                            <img v-if="form.PortfolioImage" :src="showImage(form.PortfolioImage)" alt="" height="40px" width="40px">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Close</button>
                            <button :disabled="form.busy" type="submit" class="btn btn-primary">{{ editMode ? "Update" : "Create" }} Portfolio</button>
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
            portfolios: [],
            pagination: {
                current_page: 1
            },
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                ID:'',
                PortfolioName:'',
                PortfolioImage: '',
            }),
        }
    },
    watch: {
        query: function(newQ, old) {
            if (newQ === "") {
                this.getAllPortfolio();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Portfolio List | AHMobileApp';
        this.getAllPortfolio();
    },
    methods: {
        getAllPortfolio(){
            this.isLoading = true;
            axios.get('/api/portfolio?page='+ this.pagination.current_page).then((response)=>{
                this.portfolios = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error)=>{

            })
        },
        searchData(){
            axios.get("/api/search/portfolio/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.portfolios = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload(){
            this.getAllPortfolio();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal(){
            $("#portfolioModal").modal("hide");
        },
        createPortfolio(){
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#portfolioModal").modal("show");
        },
        store(){
            this.form.busy = true;
            this.form.post("/api/portfolio").then(response => {
                $("#portfolioModal").modal("hide");
                this.getAllPortfolio();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(portfolio) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(portfolio);
            $("#portfolioModal").modal("show");
        },
        update(){
            this.form.busy = true;
            this.form.put("/api/portfolio/" + this.form.ID).then(response => {
                $("#portfolioModal").modal("hide");
                this.getAllPortfolio();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        changeImage(event){
            let file = event.target.files[0];
            let reader = new FileReader();
            reader.onload = event => {
                this.form.PortfolioImage = event.target.result;
            };
            reader.readAsDataURL(file);
        },
        showImage(){
            let img = this.form.PortfolioImage;
            if (img.length > 100){
                return this.form.PortfolioImage;
            }else{
                return window.location.origin + "/AHMobileApp/images/portfolio/" + this.form.PortfolioImage;
            }
        },
        tableImage(image){
            return window.location.origin + "/AHMobileApp/images/portfolio/"+ image;
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
                    axios.delete('api/portfolio/'+ id).then((response)=>{
                        this.getAllPortfolio();
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
