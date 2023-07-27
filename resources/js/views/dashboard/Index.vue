<template>
    <div class="content">
        <div class="container-fluid">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4 class="page-title">Dashboard</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active">Welcome to Animal Health Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stat bg-primary text-white">
                        <div class="card-body" style="padding: 10px">
                            <div class="mb-4">

                                <h2 class="font-12 text-uppercase mt-0 text-white-50">Portfolios</h2>
                                <h4 class="font-500">{{ allData.Portfolio}} <i class="mdi mdi-arrow-up text-success ml-2"></i></h4>
                            </div>
                            <div class="pt-2">
                                <div class="float-right">
                                    <router-link :to="{name : 'Portfolio'}" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></router-link>
                                </div>
                                <p class="text-white-50 mb-0">Go</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stat bg-primary text-white">
                        <div class="card-body" style="padding: 10px">
                            <div class="mb-4">
                                <h5 class="font-12 text-uppercase mt-0 text-white-50">Categories</h5>
                                <h4 class="font-500">{{ allData.Category }} <i class="mdi mdi-arrow-down text-danger ml-2"></i>
                                </h4>
                            </div>
                            <div class="pt-2">
                                <div class="float-right">
                                    <router-link :to="{name : 'Category'}" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></router-link>
                                </div>
                                <p class="text-white-50 mb-0">Go</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stat bg-primary text-white">
                        <div class="card-body" style="padding: 10px">
                            <div class="mb-4">
                                <h5 class="font-12 text-uppercase mt-0 text-white-50">Banners</h5>
                                <h4 class="font-500">{{ allData.Banner }} <i class="mdi mdi-arrow-up text-success ml-2"></i>
                                </h4>
                            </div>
                            <div class="pt-2">
                                <div class="float-right">
                                    <router-link :to="{name : 'Banner'}" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></router-link>
                                </div>
                                <p class="text-white-50 mb-0">Go</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stat bg-primary text-white">
                        <div class="card-body" style="padding: 10px">
                            <div class="mb-4">
                                <h5 class="font-12 text-uppercase mt-0 text-white-50">Doctors</h5>
                                <h4 class="font-500">{{ allData.Doctor }} <i class="mdi mdi-arrow-up text-success ml-2"></i>
                                </h4>
                            </div>
                            <div class="pt-2">
                                <div class="float-right">
                                    <router-link :to="{name : 'Doctors'}" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></router-link>
                                </div>
                                <p class="text-white-50 mb-0">Go</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stat bg-primary text-white">
                        <div class="card-body" style="padding: 10px">
                            <div class="mb-4">
                                <h5 class="font-12 text-uppercase mt-0 text-white-50">MOInfos</h5>
                                <h4 class="font-500">{{ allData.MOInfo }} <i class="mdi mdi-arrow-up text-success ml-2"></i>
                                </h4>
                            </div>
                            <div class="pt-2">
                                <div class="float-right">
                                    <router-link :to="{name : 'MOInfo'}" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></router-link>
                                </div>
                                <p class="text-white-50 mb-0">Go</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stat bg-primary text-white">
                        <div class="card-body" style="padding: 10px">
                            <div class="mb-4">
                                <h5 class="font-12 text-uppercase mt-0 text-white-50">Shops</h5>
                                <h4 class="font-500">{{ allData.Shop }} <i class="mdi mdi-arrow-up text-success ml-2"></i>
                                </h4>
                            </div>
                            <div class="pt-2">
                                <div class="float-right">
                                    <router-link :to="{name : 'Shop'}" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></router-link>
                                </div>
                                <p class="text-white-50 mb-0">Go</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stat bg-primary text-white">
                        <div class="card-body" style="padding: 10px">
                            <div class="mb-4">
                                <h5 class="font-12 text-uppercase mt-0 text-white-50">Districts</h5>
                                <h4 class="font-500">{{ allData.District }} <i class="mdi mdi-arrow-up text-success ml-2"></i>
                                </h4>
                            </div>
                            <div class="pt-2">
                                <div class="float-right">
                                    <router-link :to="{name : 'District'}" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></router-link>
                                </div>
                                <p class="text-white-50 mb-0">Go</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stat bg-primary text-white">
                        <div class="card-body" style="padding: 10px">
                            <div class="mb-4">
                                <h5 class="font-12 text-uppercase mt-0 text-white-50">Upazila</h5>
                                <h4 class="font-500">{{ allData.Upazila }} <i class="mdi mdi-arrow-up text-success ml-2"></i>
                                </h4>
                            </div>
                            <div class="pt-2">
                                <div class="float-right">
                                    <router-link :to="{name : 'Upazila'}" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></router-link>
                                </div>
                                <p class="text-white-50 mb-0">Go</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-md-12">
                    <div class="card mini-stat text-white">
                        <div class="card-body" style="padding: 10px">
                            <barChart v-if="isLoading" :labels="labels" :datasets="datasets" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "Dashboard",
    data() {
        return {
            allData: [],
            labels:[],
            datasets:[
                {
                    label: "12 Months Service Request Chart",
                    data: [],
                    backgroundColor: "rgba(38, 198, 218, 1)",
                    hoverBackgroundColor: "#7E57C2",
                    hoverBorderWidth: 0,
                }],
            isLoading : false
        }
    },
    mounted() {
        document.title = 'Dashboard | AHMobileApp';
        this.getAllDashboardData();
    },
    methods: {
        getAllDashboardData(){
            axios.get('/api/get-all-dashboard-data').then((response)=>{
                console.log(response)
                this.allData = response.data.data;
                this.labels = response.data.data.label;
                this.datasets[0].data = response.data.data.value;
                this.isLoading = true;
            }).catch((error)=>{

            })
        },
    }
}
</script>

