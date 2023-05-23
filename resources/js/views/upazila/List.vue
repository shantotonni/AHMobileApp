<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Upazila List']"/>
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
                                        <button type="button" class="btn btn-success btn-sm" @click="createUpazila">
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
                                            <th class="text-center"> District Name</th>
                                            <th class="text-center"> Upazila Name</th>
                                            <th class="text-center">Upazila NameBn</th>
                                            <th class="text-center">Code</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(upazila, i) in upazilas" :key="upazila.ID" v-if="upazilas.length">
                                            <td class="text-center" scope="row">{{ ++i }}</td>
                                            <td class="text-center">{{ upazila.DistrictName}}</td>
                                            <td class="text-center">{{ upazila.UpazilaName }}</td>
                                            <td class="text-center">{{ upazila.UpazilaNameBn }}</td>
                                            <td class="text-center">{{ upazila.Code }}</td>
                                            <td class="text-center">
                                                <button @click="edit(upazila)" class="btn btn-success btn-sm"><i
                                                    class="far fa-edit"></i></button>
                                                <button hidden="hidden" @click="destroy(upazila.ID)"
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
                                        @paginate="query === '' ? getAllUpazila() : searchData()"
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
        <div class="modal fade" id="upazilaModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Upazila</h5>
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
                                            <label>District</label>
                                            <select name="District" id="District" class="form-control"
                                                    v-model="form.District"
                                                    :class="{ 'is-invalid': form.errors.has('District') }">
                                                <option disabled value="">Select District</option>
                                                <option :value="district.ID" v-for="(district , index) in districts"
                                                        :key="index">{{ district.DistrictName }}
                                                </option>
                                            </select>
                                            <div class="error" v-if="form.errors.has('District')"
                                                 v-html="form.errors.get('District')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Upazila Name </label>
                                            <input type="text" name="name" v-model="form.UpazilaName"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('UpazilaName') }">
                                            <div class="error" v-if="form.errors.has('UpazilaName')"
                                                 v-html="form.errors.get('UpazilaName')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Upazila NameBn</label>
                                            <input type="text" name="name" v-model="form.UpazilaNameBn"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('UpazilaNameBn') }">
                                            <div class="error" v-if="form.errors.has('UpazilaNameBn')"
                                                 v-html="form.errors.get('UpazilaNameBn')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> Code</label>
                                            <input type="text" name="code" v-model="form.Code"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('Code') }">
                                            <div class="error" v-if="form.errors.has('Code')"
                                                 v-html="form.errors.get('Code')"/>
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
                                {{ editMode ? "Update" : "Create" }} Upazila
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
           upazilas: [],
            districts: [],
            pagination: {
                current_page: 1
            },
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                ID: '',
                DistrictId:'',
                UpazilaName: '',
                UpazilaNameBn: '',
                Code: '',
            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllUpazila();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Upazila List | AHMobileApp';
        this.getAllUpazila();
    },
    methods: {
        getAllUpazila() {
            this.isLoading = true;
            axios.get('/api/upazila?page=' + this.pagination.current_page).then((response) => {
                this.upazilas = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        searchData() {
            axios.get("/api/search/upazila/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.upazilas = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.getAllUpazila();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#upazilaModal").modal("hide");
        },
        createUpazila() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#upazilaModal").modal("show");
        },
        store() {
            this.form.busy = true;
            this.form.post("/api/upazila").then(response => {
                $("#upazilaModal").modal("hide");
                this.getAllUpazila();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(upazila) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(upazila);
            $("#upazilaModal").modal("show");
        },
        update() {
            this.form.busy = true;
            this.form.put("/api/upazila/" + this.form.ID).then(response => {
                $("#upazilaModal").modal("hide");
                this.getAllUpazila();
            }).catch(e => {
                this.isLoading = false;
            });
        },

        getAllDistrict() {
            axios.get('/api/get-all-district').then((response) => {
                this.districts = response.data.data;
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
                    axios.delete('api/upazila/' + id).then((response) => {
                        this.getAllUpazila();
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
