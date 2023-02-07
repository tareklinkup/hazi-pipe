<div id="graph">
    <div class="row" style="margin-bottom: 25px;">
        <div class="col-md-12">
            <h3 class="text-center">This Month's Sale</h3>
            <sales-chart
            type="ColumnChart"
            :data="salesData"
            :options="salesChartOptions"
            />
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h3 class="text-center">Top Sold Products</h3>
            <top-product-chart
            type="PieChart"
            :data="topProducts"
            :options="topProductsOptions"
            />
        </div>
        <div class="col-md-6">
            <h3 class="text-center">Top Customers</h3>
            <top-customer-chart
            type="PieChart"
            :data="topCustomers"
            :options="topCustomersOptions"
            />
        </div>
    </div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/components/vue-google-charts.browser.js"></script>

<script>
    let googleChart = VueGoogleCharts.GChart;
    new Vue({
        el: '#graph',
        components: {
            'sales-chart': googleChart,
            'top-product-chart': googleChart,
            'top-customer-chart': googleChart
        },
        data () {
            return {
                salesData: [
                    ['Date', 'Sales']
                ],
                salesChartOptions: {
                    chart: {
                        title: 'Sales',
                        subtitle: "This month's sales data",
                    }
                },
                topProducts: [
                    ['Product', 'Quantity']
                ],
                topProductsOptions: {
                    chart: {
                        title: 'Top Sold Products',
                        subtitle: "Top sold products"
                    }
                },
                topCustomers: [
                    ['Customer', 'Amount']
                ],
                topCustomersOptions: {
                    chart: {
                        title: 'Top Sold Products',
                        subtitle: "Top sold products"
                    }
                }
            }
        },
        created(){
            this.getSalesData();
            this.getTopProducts();
            this.getTopCustomers();
        },
        methods: {
            getSalesData(){
                axios.get('/sales_graph_data').then(res => {
                    res.data.forEach(d => {
                        this.salesData.push(d);
                    })
                })
            },
            getTopProducts(){
                axios.get('/get_top_products').then(res => {
                    res.data.forEach(p => {
                        this.topProducts.push([p.product_name, parseFloat(p.sold_quantity)]);
                    })
                })
            },
            getTopCustomers(){
                axios.get('/get_top_customers').then(res => {
                    res.data.forEach(c => {
                        this.topCustomers.push([c.customer_name, parseFloat(c.amount)]);
                    })
                })
            }
        }
    })
</script>
