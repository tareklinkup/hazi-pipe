<div id="chalan">
    <div class="row" style="display:none;" v-bind:style="{display: cart.length > 0 ? '' : 'none'}">
        <div class="col-md-8 col-md-offset-2">
            <div class="row">
                <div class="col-xs-12">
                    <a href="" v-on:click.prevent="print"><i class="fa fa-print"></i> Print</a>
                       <a href="" v-on:click.prevent="changeLanguage"><i class="fa fa-language"></i>
                        <template v-if="lang == 'bn'">English</template>
                        <template v-else>Bangla</template>
                    </a>
                </div>
            </div>
            <div id="invoiceContent">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <div _h098asdh>
                            <template v-if="lang == 'bn'">চালান</template>
                            <template v-else>Chalan</template>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <strong>{{ lang == 'bn' ?  'ক্রেতার আইডি' : 'Customer Id' }}:</strong> {{ sales.Customer_Code }}<br>
                        <strong>{{ lang == 'bn' ?  'ক্রেতার নাম' : 'Customer Name' }}:</strong> {{ sales.Customer_Name }}<br>
                        <strong>{{ lang == 'bn' ?  'মালিকের নাম' : 'Owner Name' }}:</strong> {{ sales.owner_name }}<br>
                        <strong>{{ lang == 'bn' ?  'ক্রেতার ঠিকানা' : 'Customer Address' }}:</strong> {{ sales.Customer_Address }}<br>
                        <strong>{{ lang == 'bn' ?  'ক্রেতার মোবাইল' : 'Customer Mobile' }}:</strong> {{ sales.Customer_Mobile }}
                    </div>
                    <div class="col-xs-4 text-right">
                        <strong>{{ lang == 'bn' ?  'বিক্রয়কারী' : 'Sales by' }}:</strong> {{ sales.AddBy }}<br>
                        <strong>{{ lang == 'bn' ?  'মার্কেটিং অফিসার' : 'Marketing Officer' }}:</strong> {{ sales.Employee_Name }}<br>
                        <strong>{{ lang == 'bn' ?  'চালান' : 'Invoice No' }}:</strong> {{ sales.SaleMaster_InvoiceNo }}<br> 
                        <strong>{{ lang == 'bn' ?  'তারিখ' : 'Sales Date' }}:</strong> {{ moment(sales.SaleMaster_SaleDate).format('DD-MM-Y')  }} {{ formatDateTime(sales.AddTime, 'h:mm a') }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div _d9283dsc></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <table _a584de>
                            <thead>
                                <tr>
                                    <td>
                                        <template v-if="lang == 'bn'">ক্রমিক নং</template>
                                        <template v-else>Sl.</template>
                                    </td>
                                    
                                    <td> 
                                        <template v-if="lang == 'bn'">পন্যের বিবরণ</template>
                                        <template v-else>Product Name</template>
                                    </td>
                                    <td>
                                        <template v-if="lang == 'bn'">পরিমাপ</template>
                                        <template v-else>Quantity</template>
                                    </td>
                                    <td v-for="currentBranch != 2">
                                        <template v-if="lang == 'bn'">পরিমান</template>
                                        <template v-else>Feet</template>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(product, sl) in cart">
                                    <td>{{ sl + 1 }}</td>
                                    <td>{{ product.Product_Name }}</td>
                                    <td v-for="currentBranch != 2">
                                        {{ product.SaleDetails_TotalQuantity / product.size }} Pcs
                                    </td>
                                    <td>{{ product.SaleDetails_TotalQuantity }} {{ product.Unit_Name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
    new Vue({
        el: '#chalan',
         props: ['sales_id', 'lang'],
        data() {
            return {
                sales: {
                    SaleMaster_SlNo: parseInt('<?php echo $saleId;?>'),
                    SaleMaster_InvoiceNo: null,
                    SalseCustomer_IDNo: null,
                    SaleMaster_SaleDate: null,
                    Customer_Name: null,
                    Customer_Address: null,
                    Customer_Mobile: null,
                    SaleMaster_TotalSaleAmount: null,
                    SaleMaster_TotalDiscountAmount: null,
                    SaleMaster_TaxAmount: null,
                    SaleMaster_Freight: null,
                    SaleMaster_SubTotalAmount: null,
                    SaleMaster_PaidAmount: null,
                    SaleMaster_DueAmount: null,
                    SaleMaster_Previous_Due: null,
                    SaleMaster_Description: null,
                    AddBy: null
                },
                cart: [],
                style: null,
                companyProfile: null,
                currentBranch: null
            }
        },
        created() {
            this.setStyle();
            this.getSales();
            this.getCompanyProfile();
            this.getCurrentBranch();
        },
        methods: {
            getSales() {
                axios.post('/get_sales', {
                    salesId: this.sales.SaleMaster_SlNo
                }).then(res => {
                    this.sales = res.data.sales[0];
                    this.cart = res.data.saleDetails;
                })
            },
            getCompanyProfile() {
                axios.get('/get_company_profile').then(res => {
                    this.companyProfile = res.data;
                })
            },
            getCurrentBranch() {
                axios.get('/get_current_branch').then(res => {
                    this.currentBranch = res.data.Company_SlNo;
                })
            },
            formatDateTime(datetime, format) {
                return moment(datetime).format(format);
            },
            changeLanguage() {
            this.lang = this.lang == 'bn' ? 'en' : 'bn';
            },
            setStyle() {
                this.style = document.createElement('style');
                this.style.innerHTML = `
                div[_h098asdh]{
                    background-color:#e0e0e0;
                    font-weight: bold;
                    font-size:15px;
                    margin-bottom:15px;
                    padding: 5px;
                }
                div[_d9283dsc]{
                    padding-bottom:25px;
                    border-bottom: 1px solid #000;
                    margin-bottom: 15px;
                }
                table[_a584de]{
                    width: 100%;
                    text-align:center;
                }
                table[_a584de] thead{
                    font-weight:bold;
                }
                table[_a584de] td{
                    padding: 3px;
                    border: 1px solid #000;
                }
                table[_t92sadbc2]{
                    width: 100%;
                }
                table[_t92sadbc2] td{
                    padding: 2px;
                }
            `;
                document.head.appendChild(this.style);
            },
            async print() {
                let reportContent = `
					<div class="container">
						<div class="row">
							<div class="col-xs-12">
                                ${document.querySelector('#invoiceContent').innerHTML}

                                <div class="container" style="bottom:0px;margin-top: 50px;width:100%;position:fixed">
                                    <div class="row" style="border-bottom:1px solid black;margin-bottom:5px;padding-bottom:6px;">
                                        <div class="col-xs-6">
                                            <span style="text-decoration:overline;">${this.lang == 'bn' ? "ক্রেতার স্বাক্ষর" : " Received by "}</span><br><br>
                                            ** THANK YOU FOR YOUR BUSINESS **
                                        </div>
                                        <div class="col-xs-6 text-right">
                                            <span style="text-decoration:overline;">${this.lang == 'bn' ? "বিক্রেতা স্বাক্ষর" : " Authorized Signature "} </span>
                                        </div>
                                    </div>

                                    <div class="row" style="font-size:12px; margin-right:10px">
                                        <div class="col-xs-6">
                                            Print Date: ${moment().format('DD-MM-YYYY h:mm a')}, Printed by: ${this.sales.AddBy}
                                        </div>
                                        <div class="col-xs-6 text-right">
                                            Visit Our Website: www.hazibd.com
                                        </div>
                                    </div>
                                </div>
							</div>
						</div>
					</div>
				`;

                var reportWindow = window.open('', 'PRINT', `height=${screen.height}, width=${screen.width}`);
                reportWindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php'); ?>
				`);

                reportWindow.document.body.innerHTML += reportContent;
                

                if (this.searchType == '' || this.searchType == 'user') {
                    let rows = reportWindow.document.querySelectorAll('.record-table tr');
                    rows.forEach(row => {
                        row.lastChild.remove();
                    })
                }

                let invoiceStyle = reportWindow.document.createElement('style');
                invoiceStyle.innerHTML = this.style.innerHTML;
                reportWindow.document.head.appendChild(invoiceStyle);
                reportWindow.focus();
                await new Promise(resolve => setTimeout(resolve, 1000));
                reportWindow.print();
                reportWindow.close();
                reportWindow.changeLanguage() ();

            }
        }
    })
</script>