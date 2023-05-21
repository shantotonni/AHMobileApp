<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Thana List']"/>
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
                                        <button type="button" class="btn btn-success btn-sm" @click="createThana">
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
                                            <th class="text-center"> ThanaCode</th>
                                            <th class="text-center"> ThanaName</th>
                                            <th class="text-center">ThanaNameBn</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(thana, i) in thanas" :key="thana.ID" v-if="thanas.length">
                                            <td class="text-center" scope="row">{{ ++i }}</td>
                                            <td class="text-center">{{ thana.ThanaCode }}</td>
                                            <td class="text-center">{{ thana.ThanaName }}</td>
                                            <td class="text-center">{{ thana.ThanaNameBn }}</td>
                                            <td class="text-center">
                                                <button @click="edit(thana)" class="btn btn-success btn-sm"><i
                                                    class="far fa-edit"></i></button>
                                                <button hidden="hidden" @click="destroy(thana.ID)"
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
                                        @paginate="query === '' ? getAllThana() : searchData()"
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
        <div class="modal fade" id="thanaModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Thana</h5>
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
                                            <label>Thana Code</label>
                                            <input type="text" name="Code" v-model="form.ThanaCode"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('ThanaCodee') }">
                                            <div class="error" v-if="form.errors.has('ThanaCode')"
                                                 v-html="form.errors.get('ThanaCode')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Thana Name </label>
                                            <input type="text" name="name" v-model="form.ThanaName"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('ThanaName') }">
                                            <div class="error" v-if="form.errors.has('ThanaName')"
                                                 v-html="form.errors.get('ThanaName')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Thana NameBn</label>
                                            <input type="text" name="name" v-model="form.ThanaNameBn"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('ThanaNameBn') }">
                                            <div class="error" v-if="form.errors.has('ThanaNameBn')"
                                                 v-html="form.errors.get('ThanaNameBn')"/>
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
                                {{ editMode ? "Update" : "Create" }} Thana
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
           thanas: [],
            pagination: {
                current_page: 1
            },
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                ID: '',
                ThanaCode: '',
                ThanaName: '',
                ThanaNameBn: ''
            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllThana();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Thana List | AHMobileApp';
        this.getAllThana();
    },
    methods: {
        getAllThana() {
            this.isLoading = true;
            axios.get('/api/thana?page=' + this.pagination.current_page).then((response) => {
                this.thanas = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },

        searchData() {
            axios.get("/api/search/thana/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.thanas = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.getAllThana();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#thanaModal").modal("hide");
        },
        createThana() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#thanaModal").modal("show");
        },
        store() {
            this.form.busy = true;
            this.form.post("/api/thana").then(response => {
                $("#thanaModal").modal("hide");
                this.getAllThana();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(thana) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(thana);
            $("#thanaModal").modal("show");
        },
        update() {
            this.form.busy = true;
            this.form.put("/api/thana/" + this.form.ID).then(response => {
                $("#thanaModal").modal("hide");
                this.getAllThana();
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
                    axios.delete('api/thana/' + id).then((response) => {
                        this.getAllThana();
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
