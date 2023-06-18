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
                                                <input v-model="query" type="text" class="form-control" placeholder="Search">
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
                                    <table class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                        <tr>
                                            <th class="text-left">Id</th>
                                            <th class="text-left">Doctor Name</th>
                                            <th class="text-left">Designation</th>
                                            <th class="text-left">Mobile No</th>
                                            <th class="text-left">Email</th>
                                            <th class="text-left">Details</th>
                                            <th class="text-left">Image</th>
                                            <th class="text-left">Address one</th>
                                            <th class="text-left">Address two</th>
                                            <th class="text-left">District</th>
                                            <th class="text-left">Upazila</th>
                                            <th class="text-left">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(doctor, i) in doctors" :key="doctor.ID" v-if="doctors.length">
                                            <td class="text-left">{{ ++i }}</td>
                                            <td class="text-left">{{ doctor.DoctorName }}</td>
                                            <td class="text-left">{{ doctor.Designation }}</td>
                                            <td class="text-left">{{ doctor.Mobile }}</td>
                                            <td class="text-left">{{ doctor.Email }}</td>
                                            <td class="text-left" v-html="doctor.Details"></td>
                                            <td class="text-left">
                                                <img v-if="doctor.DoctorImage" height="40" width="40" :src="tableImage(doctor.DoctorImage)" alt="">
                                            </td>
                                            <td class="text-left">{{ doctor.AddressOne }}</td>
                                            <td class="text-left" >{{ doctor.AddressTwo }}</td>
                                            <td class="text-left">{{ doctor.DistrictName }}</td>
                                            <td class="text-left">{{ doctor.UpazilaName }}</td>
                                            <td class="text-left">
                                                <button @click="edit(doctor)" class="btn btn-success btn-sm"><i class="far fa-edit"></i></button>
                                                <button hidden="hidden" @click="destroy(doctor.ID)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
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
                                               <label>Mobile</label>
                                               <input type="text" name="Mobile" v-model="form.Mobile"
                                                      class="form-control"
                                                      :class="{ 'is-invalid': form.errors.has('Mobile') }">
                                               <div class="error" v-if="form.errors.has('Mobile')"
                                                    v-html="form.errors.get('Mobile')"/>
                                           </div>
                                       </div>
                                       <div class="col-md-6">
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
                                               <label>Image</label>
                                               <input @change="changeImage($event)" type="file" name="DoctorImage" class="form-control" :class="{ 'is-invalid': form.errors.has('DoctorImage') }">
                                               <div class="error" v-if="form.errors.has('DoctorImage')" v-html="form.errors.get('DoctorImage')" />
                                               <img v-if="form.DoctorImage" :src="showImage(form.DoctorImage)" alt="" height="40px" width="40px">
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
                                               <select name="text" id="UpazilaID" class="form-control" v-model="form.UpazilaID" :class="{ 'is-invalid': form.errors.has('UpazilaID') }">
                                                   <option disabled value="">Select Upazila</option>
                                                   <option :value="upazila.ID" v-for="(upazila , index) in upazilas" :key="index">{{ upazila.UpazilaName }}
                                                   </option>
                                               </select>
                                               <div class="error" v-if="form.errors.has('UpazilaID')" v-html="form.errors.get('UpazilaID')"/>
                                           </div>
                                       </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Details</label>
                                            <vue-editor  name="Details" v-model="form.Details"
                                                         :class="{ 'is-invalid': form.errors.has('Details') }"></vue-editor>

                                            <div class="error" v-if="form.errors.has('Details')"
                                                 v-html="form.errors.get('Details')"/>
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
import {VueEditor} from "vue2-editor";
import {baseurl} from '../../base_url'

export default {
    components: {
        VueEditor
    },
    name: "List",
    data() {
        return {
            doctors: [],
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
                DoctorName: '',
                Designation: '',
                Mobile: '',
                Email: '',
                Details: '',
                DoctorImage:'',
                AddressOne: '',
                AddressTwo: '',
                DistrictID: '',
                UpazilaID: '',
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
                console.log(response)
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
            this.getAllDistrict();
         this.getAllUpazilaByDistrict();
        },
        store() {
            this.form.busy = true;
            this.form.post("/api/doctor").then(response => {
                $("#doctorModal").modal("hide");
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(doctor) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(doctor);
            this.getAllDistrict();
          this.getAllUpazilaByDistrict();
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
        changeImage(event){
            let file = event.target.files[0];
            let reader = new FileReader();
            reader.onload = event => {
                this.form.DoctorImage = event.target.result;
            };
            reader.readAsDataURL(file);
        },
        showImage(){
            let img = this.form.DoctorImage;
            if (img.length > 100){
                return this.form.DoctorImage;
            }else{
                return window.location.origin + "/AHMobileApp/images/doctor/" + this.form.DoctorImage;
            }
        },
        tableImage(image){
            return window.location.origin + "/AHMobileApp/images/doctor/"+ image;
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
