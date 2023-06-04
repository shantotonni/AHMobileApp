<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Brand List']"/>
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
                                        <button type="button" class="btn btn-success btn-sm" @click="createRole">
                                            <i class="fas fa-plus"></i>
                                            Add Role
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm" @click="reload">
                                            <i class="fas fa-sync"></i>
                                            Reload
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                            <tr>
                                                <th class="text-left">SN</th>
                                                <th class="text-left">Brand Name</th>
                                                <th class="text-left">Created At</th>
                                                <th class="text-left">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(brand, i) in brands" :key="brand.id" v-if="brands.length">
                                                <th class="text-left" scope="row">{{ ++i }}</th>
                                                <td class="text-left">{{ brand.name }}</td>
                                                <td class="text-left">{{ brand.created_at }}</td>
                                                <td class="text-left">
                                                    <button @click="edit(brand)" class="btn btn-success btn-sm"><i class="far fa-edit"></i></button>
                                                    <button @click="destroy(brand.id)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="query === '' ? getAllBrand() : searchData()"
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
        <div class="modal fade" id="brandModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Brand</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">×</button>
                    </div>
                    <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Brand Name</label>
                                            <input type="text" name="name" v-model="form.name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                            <div class="error" v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Close</button>
                            <button :disabled="form.busy" type="submit" class="btn btn-primary">{{ editMode ? "Update" : "Create" }} Brand</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </div>
</template>

<script>

export default {
    name: "List",
    data() {
        return {
            brands: [],
            pagination: {
                current_page: 1
            },
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                id:'',
                name:'',
            }),
        }
    },
    watch: {
        query: function(newQ, old) {
            if (newQ === "") {
                this.getAllBrand();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Brand List | Diesel Engine';
        this.getAllBrand();
    },
    methods: {
        getAllBrand(){
            this.isLoading = true;
            axios.get('/api/brand?page='+ this.pagination.current_page).then((response)=>{
                console.log(response)
                this.brands = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error)=>{

            })
        },
        searchData(){
            axios.get("/api/search/brand/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                    this.brands = response.data.data;
                    this.pagination = response.data.meta;
                }).catch(e => {
                    this.isLoading = false;
                });
        },
        reload(){
            this.getAllBrand();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal(){
            $("#brandModal").modal("hide");
        },
        createRole(){
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#brandModal").modal("show");
        },
        store(){
            this.form.busy = true;
            this.form.post("/api/brand").then(response => {
                    $("#brandModal").modal("hide");
                    this.getAllBrand();
                }).catch(e => {
                    this.isLoading = false;
                });
        },
        edit(role) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(role);
            $("#brandModal").modal("show");
        },
        update(){
            this.form.busy = true;
            this.form.put("/api/brand/" + this.form.id).then(response => {
                    $("#brandModal").modal("hide");
                    this.getAllBrand();
                }).catch(e => {
                    this.isLoading = false;
                });
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
                    axios.delete('api/brand/'+ id).then((response)=>{
                        this.getAllBrand();
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
