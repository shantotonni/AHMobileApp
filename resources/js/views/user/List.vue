<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['User List']"/>
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
                                        <button type="button" class="btn btn-success btn-sm" @click="createUser">
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
                                            <th class="text-left">SN</th>
                                            <th class="text-left">Name</th>
                                            <th class="text-left">User ID</th>
                                            <th class="text-left">Role</th>
                                            <th class="text-left">Designation</th>
                                            <th class="text-left">Mobile</th>
                                            <th class="text-left">Email</th>
                                            <th class="text-left">Status</th>
                                            <th class="text-left">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(user, i) in users" :key="user.ID" v-if="users.length">
                                            <th class="text-left" scope="row">{{ ++i }}</th>
                                            <td class="text-left">{{ user.Name }}</td>
                                            <td class="text-left">{{ user.UserID }}</td>
                                            <td class="text-left">{{ user.role_name }}</td>
                                            <td class="text-left">{{ user.Designation }}</td>
                                            <td class="text-left">{{ user.Mobile }}</td>
                                            <td class="text-left">{{ user.Email }}</td>
                                            <td class="text-left">
                                                <span class="badge badge-success" v-if="user.Active == 1">Active</span>
                                                <span class="badge badge-success" v-else>InActive</span>
                                            </td>
                                            <td class="text-left">
                                                <button @click="edit(user)" class="btn btn-success btn-sm"><i class="far fa-edit"></i></button>
                                                <button @click="destroy(user.ID)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="query === '' ? getAllUser() : searchData()"
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
        <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">×</button>
                    </div>
                    <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>User Name</label>
                                            <input type="text" name="Name" v-model="form.Name" class="form-control" :class="{ 'is-invalid': form.errors.has('Name') }">
                                            <div class="error" v-if="form.errors.has('Name')" v-html="form.errors.get('Name')" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>UserID</label>
                                            <input type="text" name="UserID" v-model="form.UserID" class="form-control" :class="{ 'is-invalid': form.errors.has('UserID') }">
                                            <div class="error" v-if="form.errors.has('UserID')" v-html="form.errors.get('UserID')" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Designation</label>
                                            <input type="text" name="Designation" v-model="form.Designation" class="form-control" :class="{ 'is-invalid': form.errors.has('Designation') }">
                                            <div class="error" v-if="form.errors.has('Designation')" v-html="form.errors.get('Designation')" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mobile</label>
                                            <input type="text" name="Mobile" v-model="form.Mobile" class="form-control" :class="{ 'is-invalid': form.errors.has('Mobile') }">
                                            <div class="error" v-if="form.errors.has('Mobile')" v-html="form.errors.get('Mobile')" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="text" name="Email" v-model="form.Email" class="form-control" :class="{ 'is-invalid': form.errors.has('Email') }">
                                            <div class="error" v-if="form.errors.has('Email')" v-html="form.errors.get('Email')" />
                                        </div>
                                    </div>
                                    <div class="col-md-6" v-if="!editMode">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" name="Password" v-model="form.Password" class="form-control" :class="{ 'is-invalid': form.errors.has('Password') }">
                                            <div class="error" v-if="form.errors.has('Password')" v-html="form.errors.get('Password')" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Role</label>
                                            <select name="RoleId" id="RoleId" class="form-control" v-model="form.RoleId" :class="{ 'is-invalid': form.errors.has('RoleId') }">
                                                <option disabled value="">Select Role</option>
                                                <option :value="role.ID" v-for="(role , index) in roles" :key="index">{{ role.Name }}</option>
                                            </select>
                                            <div class="error" v-if="form.errors.has('RoleId')" v-html="form.errors.get('RoleId')" />
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
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Close</button>
                            <button :disabled="form.busy" type="submit" class="btn btn-primary">{{ editMode ? "Update" : "Create" }} User</button>
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
            users: [],
            roles: [],
            pagination: {
                current_page: 1
            },
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                ID:'',
                Name:'',
                UserID: '',
                RoleId: '',
                Email:'',
                Designation: '',
                Mobile: '',
                Image: '',
            }),
        }
    },
    watch: {
        query: function(newQ, old) {
            if (newQ === "") {
                this.getAllUser();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'User List | AHMobileApp';
        this.getAllUser();
    },
    methods: {
        getAllUser(){
            this.isLoading = true;
            axios.get('/api/user?page='+ this.pagination.current_page).then((response)=>{
                console.log(response.data.data)
                this.users = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error)=>{

            })
        },
        searchData(){
            axios.get("/api/search/user/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.users = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload(){
            this.getAllUser();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal(){
            $("#userModal").modal("hide");
        },
        createUser(){
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#userModal").modal("show");
            this.getAllRole();
        },
        getAllRole(){
            axios.get('/api/role?page='+ 1).then((response)=>{
                this.roles = response.data.data;
            }).catch((error)=>{

            })
        },
        store(){
            this.form.busy = true;
            this.form.post("/api/user").then(response => {
                $("#userModal").modal("hide");
                this.getAllUser();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(user) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(user);
            $("#userModal").modal("show");
            this.getAllRole();
        },
        update(){
            this.form.busy = true;
            this.form.put("/api/user/" + this.form.ID).then(response => {
                $("#userModal").modal("hide");
                this.getAllUser();
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
                return window.location.origin + "/AHMobileApp/images/user/" + this.form.Image;
            }
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
                    axios.delete('api/user/'+ id).then((response)=>{
                        this.getAllUser();
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
