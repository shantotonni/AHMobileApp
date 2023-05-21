<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Category List']"/>
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
                                        <button type="button" class="btn btn-success btn-sm" @click="createCategory">
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

                                            <th class="text-center">Category Name</th>
                                            <th class="text-center">Category Image</th>
                                            <th class="text-center">Details</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Portfolio Name</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(category, i) in categories" :key="category.ID" v-if="categories.length">
                                            <td class="text-center" scope="row">{{ ++i }}</td>

                                            <td class="text-center">{{ category.CategoryName }}</td>

                                            <td class="text-center">
                                                {{ category.CategoryImage }}
                                            </td>
                                            <td class="text-center">{{ category.Details }}</td>

                                            <td class="text-center">
                                                <span class="badge badge-success"
                                                      v-if="category.Active == 'Y'">Active</span>
                                                <span class="badge badge-success" v-else>InActive</span>
                                            </td>
                                            <td class="text-center" scope="row">{{category.PortfolioName}}</td>
                                            <td class="text-center">
                                                <button @click="edit(category)" class="btn btn-success btn-sm"><i
                                                    class="far fa-edit"></i></button>
                                                <button hidden="hidden" @click="destroy(category.ID)" class="btn btn-danger btn-sm"><i
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
                                        @paginate="query === '' ? getAllCategory() : searchData()"
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
        <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }}
                            Category</h5>
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
                                            <label>Category Name</label>
                                            <input type="text" name="Name" v-model="form.CategoryName"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('CategoryName') }">
                                            <div class="error" v-if="form.errors.has('CategoryName')"
                                                 v-html="form.errors.get('CategoryName')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Details</label>
                                            <input type="text" name="Details" v-model="form.Details"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('Details') }">
                                            <div class="error" v-if="form.errors.has('Details')"
                                                 v-html="form.errors.get('Details')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Portfolio</label>
                                            <select name="PortfolioID" id="PortfolioID" class="form-control"
                                                    v-model="form.PortfolioID"
                                                    :class="{ 'is-invalid': form.errors.has('PortfolioID') }">
                                                <option disabled value="">Select Portfolio</option>
                                                <option :value="portfolio.ID" v-for="(portfolio , index) in portfolios"
                                                        :key="index">{{ portfolio.PortfolioName }}
                                                </option>
                                            </select>
                                            <div class="error" v-if="form.errors.has('PortfolioID')"
                                                 v-html="form.errors.get('PortfolioID')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Image</label>
                                            <input @change="changeImage($event)" type="file" name="CategoryImage"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('CategoryImage') }">
                                            <div class="error" v-if="form.errors.has('PortfolioImage')"
                                                 v-html="form.errors.get('CategoryImage')"/>
                                            <img v-if="form.CategoryImage" :src="showImage(form.CategoryImage)" alt=""
                                                 height="40px" width="40px">
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
                                {{ editMode ? "Update" : "Create" }} Category
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
            categories: [],
            portfolios: [],
            pagination: {
                current_page: 1
            },
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                ID: '',
                CategoryName: '',
                Details: '',
                CategoryImage: '',
                PortfolioID: '',
            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllCategory();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Category List | AHMobileApp';
        this.getAllCategory();
    },
    methods: {
        getAllCategory() {
            this.isLoading = true;
            axios.get('/api/category?page=' + this.pagination.current_page).then((response) => {
                this.categories = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        searchData() {
            axios.get("/api/search/category/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.categories = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.getAllCategory();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#categoryModal").modal("hide");
        },
        createCategory() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#categoryModal").modal("show");
            this.getAllPortfolio();
        },
        store() {
            this.form.busy = true;
            this.form.post("/api/category").then(response => {
                $("#categoryModal").modal("hide");
                this.getAllCategory();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(category) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(category);
            this.getAllPortfolio();
            $("#categoryModal").modal("show");
        },
        update() {
            this.form.busy = true;
            this.form.put("/api/category/" + this.form.ID).then(response => {
                $("#categoryModal").modal("hide");
                this.getAllCategory();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        changeImage(event) {
            let file = event.target.files[0];
            let reader = new FileReader();
            reader.onload = event => {
                this.form.CategoryImage = event.target.result;
            };
            reader.readAsDataURL(file);
        },
        showImage() {
            let img = this.form.CategoryImage;
            if (img.length > 100) {
                return this.form.CategoryImage;
            } else {
                return window.location.origin + "/AHMobileApp/images/category/" + this.form.CategoryImage;
            }
        },
        getAllPortfolio() {
            axios.get('/api/get-all-portfolio').then((response) => {
                this.portfolios = response.data.data;
            }).catch((error) => {

            })
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
                    axios.delete('api/category/' + id).then((response) => {
                        this.getAllCategory();
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
