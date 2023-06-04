<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Product List']"/>
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
                                        <button type="button" class="btn btn-success btn-sm" @click="createProduct">
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
                                            <th class="text-left">Id</th>
                                            <th class="text-left">Product Name</th>
                                            <th class="text-left">Portfolio Name</th>
                                            <th class="text-left">Category Name</th>
                                            <th class="text-left">Product Attribute</th>
                                            <th class="text-left">Product Image</th>
                                            <th class="text-left">Details</th>
                                            <th class="text-left">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(product, i) in products" :key="product.ID" v-if="products.length">
                                            <td class="text-left" scope="row">{{ ++i }}</td>
                                            <td class="text-left">{{ product.ProductName }}</td>
                                            <td class="text-left" scope="row">{{ product.PortfolioName }}</td>
                                            <td class="text-left" scope="row">{{ product.CategoryName }}</td>

                                            <td class="text-left">
                                                {{ product.ProductAttribute }}
                                            </td>
                                            <td class="text-left">
                                                <img v-if="product.ProductImage" height="40" width="40"
                                                     :src="tableImage(product.ProductImage)" alt="">
                                            </td>
                                            <td class="text-left">
                                                {{ product.Details }}
                                            </td>
                                            <td class="text-left">
                                                <button @click="edit(product)" class="btn btn-success btn-sm"><i
                                                    class="far fa-edit"></i></button>
                                                <button hidden="hidden" @click="destroy(product.ID)"
                                                        class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="query === '' ? getAllProduct() : searchData()"
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
        <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">
                            ×
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)"
                          enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Product Name</label>
                                                    <input type="text" name="Name" v-model="form.ProductName"
                                                           class="form-control"
                                                           :class="{ 'is-invalid': form.errors.has('ProductName') }">
                                                    <div class="error" v-if="form.errors.has('ProductName')"
                                                         v-html="form.errors.get('ProductName')"/>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Portfolio</label>
                                                    <select name="Portfolio" id="PortfolioID" class="form-control"
                                                            v-model="form.PortfolioID"
                                                            :class="{ 'is-invalid': form.errors.has('PortfolioID') }">
                                                        <option disabled value="">Select Portfolio</option>
                                                        <option :value="portfolio.ID"
                                                                v-for="(portfolio , index) in portfolios"
                                                                :key="index">{{ portfolio.PortfolioName }}
                                                        </option>
                                                    </select>
                                                    <div class="error" v-if="form.errors.has('PortfolioID')"
                                                         v-html="form.errors.get('PortfolioID')"/>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Category</label>
                                                    <select name="CategoryID" id="CategoryID" class="form-control"
                                                            v-model="form.CategoryID"
                                                            :class="{ 'is-invalid': form.errors.has('CategoryID') }">
                                                        <option disabled value="">Select Category</option>
                                                        <option :value="category.ID"
                                                                v-for="(category , index) in categories"
                                                                :key="index">{{ category.CategoryName }}
                                                        </option>
                                                    </select>
                                                    <div class="error" v-if="form.errors.has('CategoryID')"
                                                         v-html="form.errors.get('CategoryID')"/>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Product Attribute</label>
                                                    <input type="text" name="Attribute" v-model="form.ProductAttribute"
                                                           class="form-control"
                                                           :class="{ 'is-invalid': form.errors.has('ProductAttribute') }">
                                                    <div class="error" v-if="form.errors.has('ProductAttribute')"
                                                         v-html="form.errors.get('ProductAttribute')"/>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Image</label>
                                                    <input @change="changeImage($event)" type="file" name="ProductImage"
                                                           class="form-control"
                                                           :class="{ 'is-invalid': form.errors.has('ProductImage') }">
                                                    <div class="error" v-if="form.errors.has('ProductImage')"
                                                         v-html="form.errors.get('ProductImage')"/>
                                                    <img v-if="form.ProductImage" :src="showImage(form.ProductImage)"
                                                         alt=""
                                                         height="40px" width="40px">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label>Details</label>
                                    <vue-editor  name="Details" v-model="form.Details"
                                                 :class="{ 'is-invalid': form.errors.has('Details') }"></vue-editor>

                                    <div class="error" v-if="form.errors.has('Details')"
                                         v-html="form.errors.get('Details')"/>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">
                                Close
                            </button>
                            <button :disabled="form.busy" type="submit" class="btn btn-primary">
                                {{ editMode ? "Update" : "Create" }} Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {VueEditor} from "vue2-editor";

export default {
    components: {
        VueEditor
    },
    name: "List",
    data() {
        return {
            products: [],
            portfolios: [],
            categories: [],
            pagination: {
                current_page: 1
            },
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                ID: '',
                ProductName: '',
                ProductImage: '',
                ProductAttribute: '',
                Details: '',
                PortfolioID: '',
                CategoryID: '',
            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllProduct();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Product List | AHMobileApp';
        this.getAllProduct();

    },
    methods: {
        getAllProduct() {
            this.isLoading = true;
            axios.get('/api/product?page=' + this.pagination.current_page).then((response) => {
                this.products = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        searchData() {
            axios.get("/api/search/product/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.products = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.getAllProduct();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#productModal").modal("hide");
        },
        createProduct() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#productModal").modal("show");
            this.getAllPortfolio();
            this.getAllCategory();
        },
        store() {
            this.form.busy = true;
            this.form.post("/api/product").then(response => {
                $("#productModal").modal("hide");
                this.getAllProduct();
            }).catch(e => {
                this.isLoading = false;
            });
        },

        edit(product) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.ProductImage = '';
            this.form.fill(product);
            this.getAllPortfolio();
            this.getAllCategory();
            $("#productModal").modal("show");
        },
        update() {
            this.form.busy = true;
            this.form.put("/api/product/" + this.form.ID).then(response => {
                $("#productModal").modal("hide");
                this.getAllCategory();
                this.getAllProduct();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        changeImage(event) {
            let file = event.target.files[0];
            let reader = new FileReader();
            reader.onload = event => {
                this.form.ProductImage = event.target.result;
            };
            reader.readAsDataURL(file);
        },
        showImage() {
            let img = this.form.ProductImage;
            if (img.length > 100) {
                return this.form.ProductImage;
            } else {
                return window.location.origin + "/AHMobileApp/images/product/" + this.form.ProductImage;
            }
        },

        getAllPortfolio() {
            axios.get('/api/get-all-portfolio').then((response) => {
                this.portfolios = response.data.data;
            }).catch((error) => {

            })
        },
        getAllCategory() {
            axios.get('/api/get-all-category').then((response) => {
                this.categories = response.data.data;
            }).catch((error) => {

            })
        },
        tableImage(image) {
            return window.location.origin + "/AHMobileApp/images/product/" + image;
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
                    axios.delete('api/product/' + id).then((response) => {
                        this.getAllProduct();
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
