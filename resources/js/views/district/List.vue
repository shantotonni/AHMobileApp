<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['District List']"/>
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
                                        <button type="button" class="btn btn-success btn-sm" @click="createDistrict">
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
                                            <th class="text-left"> DistrictName</th>
                                            <th class="text-left">DistrictNameBn</th>
                                            <th class="text-left">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(district, i) in districts" :key="district.ID" v-if="districts.length">
                                            <td class="text-left" scope="row">{{ ++i }}</td>
                                            <td class="text-left">{{ district.DistrictName }}</td>
                                            <td class="text-left">{{ district.DistrictNameBn }}</td>
                                            <td class="text-left">
                                                <button @click="edit(district)" class="btn btn-success btn-sm"><i
                                                    class="far fa-edit"></i></button>
                                                <button hidden="hidden" @click="destroy(district.ID)"
                                                        class="btn btn-danger btn-sm"><i
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
                                        @paginate="query === '' ? getAllDistrict() : searchData()"
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
        <div class="modal fade" id="districtModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} District</h5>
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
                                            <label>District Code</label>
                                            <input type="number" name="Code" v-model="form.DistrictCode"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('DistrictCodee') }">
                                            <div class="error" v-if="form.errors.has('DistrictCode')"
                                                 v-html="form.errors.get('DistrictCode')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>District Name </label>
                                            <input type="text" name="name" v-model="form.DistrictName"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('DistrictName') }">
                                            <div class="error" v-if="form.errors.has('DistrictName')"
                                                 v-html="form.errors.get('DistrictName')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>District NameBn</label>
                                            <input type="text" name="name" v-model="form.DistrictNameBn"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('DistrictNameBn') }">
                                            <div class="error" v-if="form.errors.has('DistrictNameBn')"
                                                 v-html="form.errors.get('DistrictNameBn')"/>
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
                                {{ editMode ? "Update" : "Create" }} District
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
           districts: [],
            pagination: {
                current_page: 1
            },
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                ID: '',
                DistrictName: '',
                DistrictNameBn: ''
            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllDistrict();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'District List | AHMobileApp';
        this.getAllDistrict();
    },
    methods: {
        getAllDistrict() {
            this.isLoading = true;
            axios.get('/api/district?page=' + this.pagination.current_page).then((response) => {
                this.districts = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        searchData() {
            axios.get("/api/search/district/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.districts = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.getAllDistrict();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#districtModal").modal("hide");
        },
        createDistrict() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#districtModal").modal("show");
        },
        store() {
            this.form.busy = true;
            this.form.post("/api/district").then(response => {
                $("#districtModal").modal("hide");
                this.getAllDistrict();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(district) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(district);
            $("#districtModal").modal("show");
        },
        update() {
            this.form.busy = true;
            this.form.put("/api/district/" + this.form.ID).then(response => {
                $("#districtModal").modal("hide");
                this.getAllDistrict();
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
                    axios.delete('api/district/' + id).then((response) => {
                        this.getAllDistrict();
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
