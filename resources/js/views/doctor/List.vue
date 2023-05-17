<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Doctor List']"/>
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
                                        <button type="button" class="btn btn-success btn-sm" @click="createDoctor">
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
                                            <th class="text-center">Doctor Name</th>
                                            <th class="text-center">Designation</th>
                                            <th class="text-center">Details</th>
                                            <th class="text-center">Address one</th>
                                            <th class="text-center">Address two</th>
                                            <th class="text-center">District</th>
                                            <th class="text-center">Thana</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(doctor, i) in doctors" :key="doctor.ID" v-if="doctors.length">
                                            <td class="text-center" scope="row">{{ ++i }}</td>
                                            <td class="text-center">{{ doctor.DoctorName }}</td>
                                            <td class="text-center">{{ doctor.Designation }}</td>
                                            <td class="text-center">{{ doctor.Details }}</td>
                                            <td class="text-center">{{ doctor.AddressOne }}</td>
                                            <td class="text-center">{{ doctor.AddressTwo }}</td>
                                            <td class="text-center">{{ doctor.District }}</td>
                                            <td class="text-center">{{ doctor.Thana }}</td>
                                            <td class="text-center">
                                                <button @click="edit(doctor)" class="btn btn-success btn-sm"><i
                                                    class="far fa-edit"></i></button>
                                                <button @click="destroy(doctor.ID)" class="btn btn-danger btn-sm"><i
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
                                        @paginate="query === '' ? getAllDoctor() : searchData()"
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
        <div class="modal fade" id="doctorModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Doctor</h5>
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
                                            <label>Doctor Name</label>
                                            <input type="text" name="Name" v-model="form.DoctorName"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('DoctorName') }">
                                            <div class="error" v-if="form.errors.has('DoctorName')"
                                                 v-html="form.errors.get('DoctorName')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Designation</label>
                                            <input type="text" name="Designation" v-model="form.Designation"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('Designation') }">
                                            <div class="error" v-if="form.errors.has('Designation')"
                                                 v-html="form.errors.get('Designation')"/>
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
                                            <label>Address One</label>
                                            <input type="text" name="Name" v-model="form.AddressOne"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('AddressOne') }">
                                            <div class="error" v-if="form.errors.has('AddressOne')"
                                                 v-html="form.errors.get('AddressOne')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Address Two</label>
                                            <input type="text" name="AddressTwo" v-model="form.AddressTwo"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('AddressTwo') }">
                                            <div class="error" v-if="form.errors.has('AddressTwo')"
                                                 v-html="form.errors.get('AddressTwo')"/>
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
                                {{ editMode ? "Update" : "Create" }} Doctor
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
            doctors: [],
            pagination: {
                current_page: 1
            },
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                ID: '',
                DoctorName: '',
                Details: '',
                AddressOne: '',
                AddressTwo: '',
                District: '',
                Thana: '',
            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllDoctor();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Doctor List | AHMobileApp';
        this.getAllDoctor();
    },
    methods: {
        getAllDoctor() {
            this.isLoading = true;
            axios.get('/api/doctor?page=' + this.pagination.current_page).then((response) => {
                this.doctors = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        searchData() {
            axios.get("/api/search/doctor/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.doctors = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.getAllDoctor();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#doctorModal").modal("hide");
        },
        createDoctor() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#doctorModal").modal("show");
        },
        store() {
            this.form.busy = true;
            this.form.post("/api/doctor").then(response => {
                $("#doctorModal").modal("hide");
                this.getAllDoctor();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(doctor) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(doctor);
            $("#doctorModal").modal("show");
        },
        update() {
            this.form.busy = true;
            this.form.put("/api/doctor/" + this.form.ID).then(response => {
                $("#doctorModal").modal("hide");
                this.getAllDoctor();
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
                    axios.delete('api/doctor/' + id).then((response) => {
                        this.getAllDoctor();
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
