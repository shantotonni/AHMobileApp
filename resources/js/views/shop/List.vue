<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Shop List']"/>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="datatable" v-if="!isLoading">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <input v-model="query" type="text" class="form-control"
                                                       placeholder="Search">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-tools">
                                            <button type="button" class="btn btn-success btn-sm" @click="createShop">
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
                                    <table
                                        class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Id</th>
                                            <th class="text-center">Shop Name</th>
                                            <th class="text-center">ShopOwner Name</th>
                                            <th class="text-center">ShopOwner Mobile No</th>
                                            <th class="text-center">Shop Address</th>
                                            <th class="text-center">District</th>
                                            <th class="text-center">Thana</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(shop, i) in shops" :key="shop.ID" v-if="shops.length">
                                            <td class="text-center" scope="row">{{ ++i }}</td>
                                            <td class="text-center">{{ shop.ShopName }}</td>
                                            <td class="text-center">{{ shop.ShopOwnerName }}</td>
                                            <td class="text-center">{{ shop.ShopOwnerMobile }}</td>
                                            <td class="text-center">{{ shop.ShopAddress}}</td>
                                            <td class="text-center">{{ shop.District }}</td>
                                            <td class="text-center">{{ shop.Thana }}</td>
                                            <td class="text-center">
                                                <button @click="edit(shop)" class="btn btn-success btn-sm"><i
                                                    class="far fa-edit"></i></button>
                                                <button hidden="hidden" @click="destroy(shop.ID)" class="btn btn-danger btn-sm"><i
                                                    class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="query === '' ? getAllShop() : searchData()"
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
        <div class="modal fade" id="shopModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Shop</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">
                            ×
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)"
                          enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Shop Name</label>
                                            <input type="text" name="Name" v-model="form.ShopName"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('ShopName') }">
                                            <div class="error" v-if="form.errors.has('ShopName')"
                                                 v-html="form.errors.get('ShopName')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ShopOwner Name</label>
                                            <input type="text" name="Name" v-model="form.ShopOwnerName"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('ShopOwnerName') }">
                                            <div class="error" v-if="form.errors.has('ShopOwnerName')"
                                                 v-html="form.errors.get('ShopOwnerName')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ShopOwner Mobile</label>
                                            <input type="number" name="Mobile" v-model="form.ShopOwnerMobile"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('ShopOwnerMobile') }">
                                            <div class="error" v-if="form.errors.has('ShopOwnerMobile')"
                                                 v-html="form.errors.get('ShopOwnerMobile')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Shop Address</label>
                                            <input type="text" name="Shop" v-model="form.ShopAddress"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('ShopAddress') }">
                                            <div class="error" v-if="form.errors.has('ShopAddress')"
                                                 v-html="form.errors.get('ShopAddress')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>District</label>
                                            <input type="text" name="District" v-model="form.District"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('District') }">
                                            <div class="error" v-if="form.errors.has('District')"
                                                 v-html="form.errors.get('District')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Thana</label>
                                            <input type="text" name="Thana" v-model="form.Thana"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('Thana') }">
                                            <div class="error" v-if="form.errors.has('Thana')"
                                                 v-html="form.errors.get('Thana')"/>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">
                                Close
                            </button>
                            <button :disabled="form.busy" type="submit" class="btn btn-primary">
                                {{ editMode ? "Update" : "Create" }} Shop
                            </button>
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
            shops: [],
            pagination: {
                current_page: 1
            },
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                ID: '',
               ShopName: '',
               ShopOwnerName: '',
                ShopOwnerMobile: '',
                ShopAddress: '',
                District: '',
                Thana: '',
            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllShop();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Shop List | AHMobileApp';
        this.getAllShop();
    },
    methods: {
        getAllShop(){
            this.isLoading = true;
            axios.get('/api/shop?page=' + this.pagination.current_page).then((response) => {
                this.shops = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        searchData() {
            axios.get("/api/search/shop/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.shops = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.getAllShop();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#shopModal").modal("hide");
        },
        createShop() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#shopModal").modal("show");
        },
        store() {
            this.form.busy = true;
            this.form.post("/api/shop").then(response => {
                $("#shopModal").modal("hide");
                this.getAllShop();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(shop) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(shop);
            $("#shopModal").modal("show");
        },
        update() {
            this.form.busy = true;
            this.form.put("/api/shop/" + this.form.ID).then(response => {
                $("#shopModal").modal("hide");
                this.getAllShop();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        destroy(id) {
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
                    axios.delete('api/shop/' + id).then((response) => {
                        this.getAllShop();
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
