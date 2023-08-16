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
                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                        <tr>
                                            <th class="text-left">Id</th>
                                            <th class="text-left">District Id</th>
                                            <th class="text-left">Upazila Name</th>
                                            <th class="text-left">Upazila NameBn</th>
                                            <th class="text-left">Code</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(upazila, i) in upazilas" :key="upazila.ID" v-if="upazilas.length">
                                            <td class="text-left" scope="row">{{ ++i }}</td>
                                            <td class="text-left">{{ upazila.DistrictID}}</td>
                                            <td class="text-left">{{ upazila.UpazilaName }}</td>
                                            <td class="text-left">{{ upazila.UpazilaNameBn }}</td>
                                            <td class="text-left">{{ upazila.Code }}</td>

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
                DistrictID:'',
                DistrictName:'',
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
        this.getAllDistrict();
    },
    methods: {
        getAllUpazila() {
            this.isLoading = true;
            axios.get('/api/upazila?page=' + this.pagination.current_page).then((response) => {
                console.log(response);
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

        getAllDistrict() {
            axios.get('/api/get-all-district').then((response) => {
                this.districts = response.data.data;
            }).catch((error) => {

            })
        },
    },
}
</script>

<style scoped>

</style>
