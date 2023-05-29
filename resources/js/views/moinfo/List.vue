<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['MOInfo List']"/>
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
                                            <button type="button" class="btn btn-success btn-sm" @click="createMOInfo">
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
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Mobile No</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Address</th>
                                            <th class="text-center">District</th>
                                            <th class="text-center">Upazila</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(moinfo, i) in moinfos" :key="moinfo.ID" v-if="moinfos.length">
                                            <td class="text-center" scope="row">{{ ++i }}</td>
                                            <td class="text-center">{{ moinfo.Name }}</td>
                                            <td class="text-center">{{ moinfo.Mobile }}</td>
                                            <td class="text-center">{{ moinfo.Email }}</td>
                                            <td class="text-center">{{ moinfo.Address}}</td>
                                            <td class="text-center">{{ moinfo.DistrictName }}</td>
                                            <td class="text-center">{{ moinfo.UpazilaName }}</td>
                                            <td class="text-center">
                                                <button @click="edit(moinfo)" class="btn btn-success btn-sm"><i
                                                    class="far fa-edit"></i></button>
                                                <button hidden="hidden" @click="destroy(moinfo.ID)" class="btn btn-danger btn-sm"><i
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
                                        @paginate="query === '' ? getAllMOInfo() : searchData()"
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
        <div class="modal fade" id="moinfoModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} MOInfo</h5>
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
                                            <label>Name</label>
                                            <input type="text" name="Name" v-model="form.Name"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('Name') }">
                                            <div class="error" v-if="form.errors.has('Name')"
                                                 v-html="form.errors.get('Name')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mobile</label>
                                            <input type="number" name="Mobile" v-model="form.Mobile"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('Mobile') }">
                                            <div class="error" v-if="form.errors.has('Mobile')"
                                                 v-html="form.errors.get('Mobile')"/>
                                        </div>
                                    </div>   <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="Email" v-model="form.Email"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('Email') }">
                                            <div class="error" v-if="form.errors.has('Email')"
                                                 v-html="form.errors.get('Email')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" name="Name" v-model="form.Address"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('Address') }">
                                            <div class="error" v-if="form.errors.has('Address')"
                                                 v-html="form.errors.get('Address')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>District</label>
                                            <select name="DistrictID" id="DistrictID" class="form-control" v-model="form.DistrictID" :class="{ 'is-invalid': form.errors.has('DistrictID') }" @change="getAllUpazilaByDistrict">
                                                <option disabled value="">Select District</option>
                                                <option :value="district.ID" v-for="(district , index) in districts" :key="index">{{ district.DistrictName }}
                                                </option>
                                            </select>
                                            <div class="error" v-if="form.errors.has('DistrictID')" v-html="form.errors.get('DistrictID')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Upazila</label>
                                            <select name="text" id="upazilaID" class="form-control" v-model="form.upazilaID" :class="{ 'is-invalid': form.errors.has('upazilaID') }">
                                                <option disabled value="">Select Upazila</option>
                                                <option :value="upazila.ID" v-for="(upazila , index) in upazilas" :key="index">{{ upazila.UpazilaName }}
                                                </option>
                                            </select>
                                            <div class="error" v-if="form.errors.has('upazilaID')" v-html="form.errors.get('upazilaID')"/>
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
                                {{ editMode ? "Update" : "Create" }} MOInfo
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
            moinfos: [],
            districts: [],
            upazilas:[],
            pagination: {
                current_page: 1
            },
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                ID: '',
               Name: '',
                Mobile: '',
                Email: '',
                Address: '',
                DistrictID: '',
                UpazilaID: '',
            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllMOInfo();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'MOInfo List | AHMobileApp';
        this.getAllMOInfo();
    },
    methods: {
        getAllMOInfo() {
            this.isLoading = true;
            axios.get('/api/moinfo?page=' + this.pagination.current_page).then((response) => {
                this.moinfos = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        searchData() {
            axios.get("/api/search/moinfo/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.moinfos = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.getAllMOInfo();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#moinfoModal").modal("hide");
        },
        createMOInfo() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#moinfoModal").modal("show");
            this.getAllDistrict();
            this.getAllUpazila();
        },
        store() {
            this.form.busy = true;
            this.form.post("/api/moinfo").then(response => {
                $("#moinfoModal").modal("hide");
                this.getAllMOInfo();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(moinfo) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(moinfo);
            $("#moinfoModal").modal("show");
            this.getAllDistrict();
            this.getAllUpazila();
        },
        update() {
            this.form.busy = true;
            this.form.put("/api/moinfo/" + this.form.ID).then(response => {
                $("#moinfoModal").modal("hide");
                this.getAllMOInfo();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        getAllDistrict() {
            axios.get('/api/get-all-district').then((response) => {
                console.log(response)
                this.districts = response.data.data;
            }).catch((error) => {

            })
        },
        getAllUpazilaByDistrict() {
            console.log(this.form.DistrictID)
            axios.get( '/api/district-wise-upazila/' + this.form.DistrictID).then((response) => {
                //console.log(response)
                this.upazilas = response.data.data;
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
                    axios.delete('api/moinfo/' + id).then((response) => {
                        this.getAllMOInfo();
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
