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
                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                        <tr>
                                            <th class="text-left">SN</th>
                                            <th class="text-left"> DistrictName</th>
                                            <th class="text-left">DistrictNameBn</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(district, i) in districts" :key="district.ID"
                                            v-if="districts.length">
                                            <td class="text-left" scope="row">{{ ++i }}</td>
                                            <td class="text-left">{{ district.DistrictName }}</td>
                                            <td class="text-left">{{ district.DistrictNameBn }}</td>
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
    },
}
</script>

<style scoped>

</style>
