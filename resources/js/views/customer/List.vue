<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Customer List']"/>
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
                                        <button type="button" class="btn btn-success btn-sm" @click="createCustomer">
                                            <i class="fas fa-plus"></i>
                                            Add Customer
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
                                                <th class="text-left">Customer Name</th>
                                                <th class="text-left">Customer Address</th>
                                                <th class="text-left">Generator Purchase Date</th>
                                                <th class="text-left">Image</th>
                                                <th class="text-left">Status</th>
                                                <th class="text-left">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(customer, i) in customers" :key="customer.CustomerID" v-if="customers.length">
                                                <th class="text-left" scope="row">{{ ++i }}</th>
                                                <td class="text-left">{{ customer.name }}</td>
                                                <td class="text-left">{{ customer.mobile }}</td>
                                                <td class="text-left">{{ customer.date_of_purchase }}</td>
                                                <td class="text-left">{{ customer.image }}</td>
                                                <td class="text-left">
                                                    <span class="badge badge-success" v-if="customer.status =='Y'">Active</span>
                                                    <span class="badge badge-danger" v-else></span>
                                                </td>
                                                <td class="text-left">
                                                    <button @click="edit(customer)" class="btn btn-success btn-sm"><i class="far fa-edit"></i></button>
<!--                                                    <button @click="show(company)" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></button>-->
<!--                                                    <button @click="destroy(customer)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>-->
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="query === '' ? getAllCustomer() : searchData()"
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
        <div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click.prevent="closeModal">×</button>
                    </div>
                    <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Customer Name</label>
                                            <input v-model="form.name" type="text" name="name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                            <div class="error" v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Customer Mobile</label>
                                            <input v-model="form.mobile" type="number" name="mobile" class="form-control" :class="{ 'is-invalid': form.errors.has('mobile') }">
                                            <div class="error" v-if="form.errors.has('mobile')" v-html="form.errors.get('mobile')" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6" v-if="!editMode">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" name="password" v-model="form.password" class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                            <div class="error" v-if="form.errors.has('password')" v-html="form.errors.get('password')" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Image</label>
                                            <input @change="changeImage($event)" type="file" name="Image" class="form-control" :class="{ 'is-invalid': form.errors.has('Image') }">
                                            <div class="error" v-if="form.errors.has('Image')" v-html="form.errors.get('Image')" />
                                            <img v-if="form.Image" :src="showImage(form.Image)" alt="" height="40px" width="40px">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click.prevent="closeModal">Close</button>
                            <button :disabled="form.busy" type="submit" class="btn btn-primary">{{ editMode ? "Update" : "Create" }} Customer</button>
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
document.title = 'Customer List | Diesel Engine';
export default {
    name: "List",
    data() {
        return {
            customers: [],
            companies: [],
            pagination: {
                current_page: 1
            },
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                id:'',
                name: '',
                mobile: '',
                password: '',
                Image: '',
            }),
        }
    },
    watch: {
        query: function(newQ, old) {
            if (newQ === "") {
                this.getAllCustomer();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        this.getAllCustomer();
    },
    methods: {
        getAllCustomer(){
            this.isLoading = true;
            axios.get('/api/customer?page='+ this.pagination.current_page).then((response)=>{
                this.customers = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error)=>{

            })
        },
        searchData(){
            axios.get("/api/search/customer/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                    this.customers = response.data.data;
                    this.pagination = response.data.meta;
                }).catch(e => {
                    this.isLoading = false;
                });
        },
        reload(){
            this.getAllCustomer();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal(){
            $("#customerModal").modal("hide");
        },
        createCustomer(){
            this.getAllCompany();
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#customerModal").modal("show");
        },
        store(){
            this.form.busy = true;
            this.form.post("/api/customer").then(response => {
                    $("#customerModal").modal("hide");
                    this.getAllCustomer();
                }).catch(e => {
                    this.isLoading = false;
                });
        },
        edit(customer) {
            this.getAllCompany();
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(customer);
            $("#customerModal").modal("show");
        },
        update(){
            this.form.busy = true;
            this.form.put("/api/customer/" + this.form.id).then(response => {
                    $("#customerModal").modal("hide");
                    this.getAllCustomer();
                }).catch(e => {
                    this.isLoading = false;
                });
        },
        changeImage(event){
            let file = event.target.files[0];
            let reader = new FileReader();
            reader.onload = event => {
                this.form.Image = event.target.result;
            };
            reader.readAsDataURL(file);
        },
        showImage(){
            let img = this.form.Image;
            if (img.length > 100){
                return this.form.Image;
            }else{
                return window.location.origin + "/DEGS/images/customer/" + this.form.Image;
            }
        },
        getAllCompany(){
            axios.get('/api/get-company').then((response)=>{
                this.companies = response.data.data;
            }).catch((error)=>{

            })
        }
    },
}
</script>

<style scoped>

</style>
