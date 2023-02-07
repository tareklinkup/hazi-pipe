const salesInvoice = Vue.component('sales-invoice', {
    template: `
        <div>
            <div class="row">
                <div class="col-xs-12">
                    <a href="" v-on:click.prevent="print"><i class="fa fa-print"></i> Print</a>&nbsp;&nbsp;
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
                            <template v-if="lang == 'bn'">ক্যাশ মেমো</template>
                            <template v-else>Sales Invoice</template>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-7">
                        <span v-if="lang == 'bn'">গ্রাহকের আইডি:</span>
                        <span v-else>Customer Id:</span> 
                        {{ sales.Customer_Code }}<br>

                        <span v-if="lang == 'bn'">গ্রাহকের নাম:</span> 
                        <span v-else>Customer Name:</span> 
                        {{ sales.Customer_Name }}<br>
                        <span v-if="lang == 'bn'">মালিকের নাম:</span> 
                        <span v-else>Owner Name:</span> 
                        {{ sales.owner_name }}<br>

                        <span v-if="lang == 'bn'">গ্রাহকের ঠিকানা:</span> 
                        <span v-else>Customer Address:</span> 
                        {{ sales.Customer_Address }}<br>

                        <span v-if="lang == 'bn'">মোবাইল নাম্বার:</span> 
                        <span v-else>Customer Mobile:</span>
                        {{ sales.Customer_Mobile }}
                    </div>
                    <div class="col-xs-5 text-right">
                        <span v-if="lang == 'bn'">বিক্রয়কারী:</span>
                        <span v-else>Sales by:</span>
                        {{ sales.AddBy }}<br>

                        <span v-if="lang == 'bn'">মার্কেটিং অফিসার:</span>
                        <span v-else>Marketing Office:</span>
                        {{ sales.Employee_Name }}<br>

                        <span v-if="lang == 'bn'">মেমো নাম্বার:</span>
                        <span v-else>Invoice No.:</span>
                        {{ sales.SaleMaster_InvoiceNo }}<br>

                        <span v-if="lang == 'bn'">তারিখ:</span>
                        <span v-else>Sales Date:</span> 
                        {{ moment(sales.SaleMaster_SaleDate).format('DD-MM-Y')}} 
                         {{ sales.AddTime | formatDateTime('h:mm a') }}
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
                                    <td style="display:none">
                                        <template v-if="lang == 'bn'">মোট পন্য</template>
                                        <template v-else>Total Quantity</template>
                                    </td>

                                    <td v-if="companyId != 2">
                                        <template v-if="lang == 'bn'">পরিমাপ </template>
                                        <template v-else>Qty</template>
                                    </td>
                                    
                                    <td v-if="companyId == 2">
                                        <template v-if="lang == 'bn'">পরিমাপ </template>
                                        <template v-else>Qty</template>
                                    </td>
                                  

                                    <td v-else>
                                        <template v-if="lang == 'bn'">পরিমাণ </template>
                                        <template v-else>Total Size (ft.)</template>
                                    </td>
                                 
                                    <td>
                                        <template v-if="lang == 'bn'">দর</template>
                                        <template v-else>Unit Price</template>
                                    </td>
                                    <td>
                                        <template v-if="lang == 'bn'">মোট মূল্য</template>
                                        <template v-else>Total</template>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(product, sl) in cart">
                                    <td>{{ sl + 1 }}</td>
                                    <td>{{ product.Product_Name }}</td>
                                    <td v-bind:style=" (companyId != 2) ? '' : 'display:none' ">{{ (product.SaleDetails_TotalQuantity / product.size).toFixed(2) }} Pcs</td>
                                    <td  v-bind:style=" (companyId == 2) ? '' : 'display:none' ">{{ product.SaleDetails_TotalQuantity }} {{ product.Unit_Name }}</td>

                                    <td  v-bind:style=" (companyId != 2) ? 'display:none' : 'display:none' ">{{ product.SaleDetails_TotalQuantity }} {{ product.Unit_Name }}</td>
                                    <td v-bind:style=" (companyId == 2) ? 'display:none' : '' ">{{ product.total_size }} {{ product.Unit_Name }}</td>
                                    <td>{{ product.SaleDetails_Rate }}</td>
                                    <td align="right">{{ product.SaleDetails_TotalAmount }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <br>
                        <table class="pull-left">
                            <tr>
                                <td>
                                    <strong v-if="lang == 'bn'">পূর্বের বকেয়া:</strong>
                                    <strong v-else>Previous Due:</strong>
                                </td>
                                
                                <td style="text-align:right">{{ sales.SaleMaster_Previous_Due == null ? '0.00' : sales.SaleMaster_Previous_Due  }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong v-if="lang == 'bn'">বর্তমান বকেয়া:</strong>
                                    <strong v-else>Current Due:</strong>
                                </td>
                                
                                <td style="text-align:right">{{ sales.SaleMaster_DueAmount == null ? '0.00' : sales.SaleMaster_DueAmount  }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border-bottom: 1px solid black;"></td>
                            </tr>
                            <tr>
                                <td>
                                    <strong v-if="lang == 'bn'">মোট বকেয়া:</strong>
                                    <strong v-else>Total Due:</strong>
                                </td>
                                
                                <td style="text-align:right">{{ (parseFloat(sales.SaleMaster_Previous_Due) + parseFloat(sales.SaleMaster_DueAmount == null ? 0.00 : sales.SaleMaster_DueAmount)).toFixed(2) }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-xs-6">
                        <table _t92sadbc2>
                            <tr>
                                <td>
                                    <strong v-if="lang == 'bn'">মোট:</strong>
                                    <strong v-else>Sub Total:</strong>
                                </td>
                                <td style="text-align:right">{{ sales.SaleMaster_SubTotalAmount }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong v-if="lang == 'bn'">ভ্যাট:</strong>
                                    <strong v-else>VAT:</strong>
                                </td>
                                <td style="text-align:right">{{ sales.SaleMaster_TaxAmount }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong v-if="lang == 'bn'">কমিশন ({{ discountPercent }}%):</strong>
                                    <strong v-else>Discount ({{ discountPercent }}%):</strong>
                                </td>
                                <td style="text-align:right">{{ sales.SaleMaster_TotalDiscountAmount }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong v-if="lang == 'bn'">পরিবহণ খরচ:</strong>
                                    <strong v-else>Transport Cost:</strong>
                                </td>
                                <td style="text-align:right">{{ sales.SaleMaster_Freight }}</td>
                            </tr>
                            <tr><td colspan="2" style="border-bottom: 1px solid black"></td></tr>
                            <tr>
                                <td>
                                    <strong v-if="lang == 'bn'">সর্বমোট:</strong>
                                    <strong v-else>Total:</strong>
                                </td>
                                <td style="text-align:right">{{ sales.SaleMaster_TotalSaleAmount }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong v-if="lang == 'bn'">প্রদান:</strong>
                                    <strong v-else>Paid:</strong>
                                </td>
                                <td style="text-align:right">{{ sales.SaleMaster_PaidAmount }}</td>
                            </tr>
                            <tr><td colspan="2" style="border-bottom: 1px solid black"></td></tr>
                            <tr>
                                <td>
                                    <strong v-if="lang == 'bn'">বকেয়া:</strong>
                                    <strong v-else>Due:</strong>
                                </td>
                                <td style="text-align:right">{{ sales.SaleMaster_DueAmount }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <strong v-if="lang == 'bn'">কথায়: </strong> 
                        <strong v-else>In Word: </strong> 
                        {{ convertNumberToWords(sales.SaleMaster_TotalSaleAmount) }}<br><br>
                        <strong v-if="lang == 'bn'">নোট: </strong>
                        <strong v-else>Note: </strong>
                        <p style="white-space: pre-line">{{ sales.SaleMaster_Description }}</p>
                    </div>
                </div>
            </div>
        </div>
    `,
    props: ['sales_id', 'lang'],
    data(){
        return {
            sales:{
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
                total_size: null,
                Employee_Name: null,
                AddBy: null
            },
            cart: [],
            style: null,
            companyProfile: null,
            currentBranch: null,
            companyId: null
        }
    },
    filters: {
        formatDateTime(dt, format) {
            return dt == '' || dt == null ? '' : moment(dt).format(format);
        }
    },
    computed: {
        discountPercent() {
            return ((parseFloat(this.sales.SaleMaster_TotalDiscountAmount)  * 100 ) / parseFloat(this.sales.SaleMaster_SubTotalAmount)).toFixed(2); 
        }
    },
    created(){
        this.setStyle();
        this.getSales();
        this.getCurrentBranch();
    },
    methods:{
        getSales(){
            axios.post('/get_sales', {salesId: this.sales_id}).then(res=>{
                this.sales = res.data.sales[0];
                this.cart = res.data.saleDetails;
            })
        },
        getCurrentBranch() {
            axios.get('/get_current_branch').then(res => {
                this.currentBranch = res.data;
                this.companyId = this.currentBranch.Company_SlNo;
            })
        },
        setStyle(){
            this.style = document.createElement('style');
            this.style.innerHTML = `
                div[_h098asdh]{
                    /*background-color:#e0e0e0;*/
                    font-weight: bold;
                    font-size:15px;
                    margin-bottom:15px;
                    padding: 5px;
                    border-top: 1px dotted black;
                    border-bottom: 1px dotted black;
                }
                div[_d9283dsc]{
                    padding-bottom:25px;
                    border-bottom: 1px solid black;
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
                    border: 1px solid black;
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
        changeLanguage() {
            this.lang = this.lang == 'bn' ? 'en' : 'bn';
        },
        convertNumberToWords(amountToWord) {
            var words = new Array();
            words[0] = '';
            words[1] = this.lang == 'bn' ? 'এক' : 'One';
            words[2] = this.lang == 'bn' ? 'দুই' : 'Two';
            words[3] = this.lang == 'bn' ? 'তিন' : 'Three';
            words[4] = this.lang == 'bn' ? 'চার' : 'Four';
            words[5] = this.lang == 'bn' ? 'পাঁচ' : 'Five';
            words[6] = this.lang == 'bn' ? 'ছয়' : 'Six';
            words[7] = this.lang == 'bn' ? 'সাত' :'Seven';
            words[8] = this.lang == 'bn' ? 'আঠ' : 'Eight';
            words[9] = this.lang == 'bn' ? 'নয়' : 'Nine';
            words[10] = this.lang == 'bn' ? 'দশ' : 'Ten';
            words[11] = this.lang == 'bn' ? 'এগার' : 'Eleven';
            words[12] = this.lang == 'bn' ? 'বার' : 'Twelve';
            words[13] = this.lang == 'bn' ? 'তের' : 'Thirteen';
            words[14] = this.lang == 'bn' ? 'চৌদ্দ' : 'Fourteen';
            words[15] = this.lang == 'bn' ? 'পনের' : 'Fifteen';
            words[16] = this.lang == 'bn' ? 'ষোল' : 'Sixteen';
            words[17] = this.lang == 'bn' ? 'সতের' : 'Seventeen';
            words[18] = this.lang == 'bn' ? 'আঠার' : 'Eighteen';
            words[19] = this.lang == 'bn' ? 'উনিশ' : 'Nineteen';
            words[20] = this.lang == 'bn' ? 'বিশ' : 'Twenty';
            words[30] = this.lang == 'bn' ? 'ত্রিশ' : 'Thirty';
            words[40] = this.lang == 'bn' ? 'চল্লিশ' : 'Forty';
            words[50] = this.lang == 'bn' ? 'পঞ্চাশ' : 'Fifty';
            words[60] = this.lang == 'bn' ? 'ষাইট' : 'Sixty';
            words[70] = this.lang == 'bn' ? 'সত্তর' : 'Seventy';
            words[80] = this.lang == 'bn' ? 'আশি' : 'Eighty';
            words[90] = this.lang == 'bn' ? 'নব্বই' : 'Ninety';
            amount = amountToWord == null ? '0.00' : amountToWord.toString();
            var atemp = amount.split(".");
            var number = atemp[0].split(",").join("");
            var n_length = number.length;
            var words_string = "";
            if (n_length <= 9) {
                var n_array = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0);
                var received_n_array = new Array();
                for (var i = 0; i < n_length; i++) {
                    received_n_array[i] = number.substr(i, 1);
                }
                for (var i = 9 - n_length, j = 0; i < 9; i++, j++) {
                    n_array[i] = received_n_array[j];
                }
                for (var i = 0, j = 1; i < 9; i++, j++) {
                    if (i == 0 || i == 2 || i == 4 || i == 7) {
                        if (n_array[i] == 1) {
                            n_array[j] = 10 + parseInt(n_array[j]);
                            n_array[i] = 0;
                        }
                    }
                }
                value = "";
                for (var i = 0; i < 9; i++) {
                    if (i == 0 || i == 2 || i == 4 || i == 7) {
                        value = n_array[i] * 10;
                    } else {
                        value = n_array[i];
                    }
                    if (value != 0) {
                        words_string += words[value] + " ";
                    }
                    if ((i == 1 && value != 0) || (i == 0 && value != 0 && n_array[i + 1] == 0)) {
                        words_string +=  this.lang == 'bn' ? " কোটি " :"Crores ";
                    }
                    if ((i == 3 && value != 0) || (i == 2 && value != 0 && n_array[i + 1] == 0)) {
                        words_string += this.lang == 'bn' ? " লক্ষ " : "Lakhs ";
                    }
                    if ((i == 5 && value != 0) || (i == 4 && value != 0 && n_array[i + 1] == 0)) {
                        words_string +=  this.lang == 'bn' ? " হাজার " : "Thousand ";
                    }
                    if (i == 6 && value != 0 && (n_array[i + 1] != 0 && n_array[i + 2] != 0)) {
                        words_string +=  this.lang == 'bn' ? " শত এবং " : "Hundred and ";
                    } else if (i == 6 && value != 0) {
                        words_string +=  this.lang == 'bn' ? " শত " : "Hundred ";
                    }
                }
                words_string = words_string.split("  ").join(" ");
            }
            return words_string +  (this.lang == 'bn' ? " টাকা মাত্র " : ' Taka Only');
        },
        async print(){
            let invoiceContent = document.querySelector('#invoiceContent').innerHTML;
            let printWindow = window.open('', 'PRINT', `width=${screen.width}, height=${screen.height}, left=0, top=0`);
            if (this.currentBranch.print_type == '3') {
                printWindow.document.write(`
                    <html>
                        <head>
                            <title>Invoice</title>
                            <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
                            <style>
                                body, table{
                                    font-size:11px;
                                }
                            </style>
                        </head>
                        <body>
                            <div class="row">
                            <div class="col-xs-2" style="margin-left: 19%;"><img  src="/uploads/company_profile_thum/${this.currentBranch.Company_Logo_thum}" alt="Logo" style="height:105px;    margin-top: 32px;" /></div>
                            <div class="col-xs-5" style="padding-top:32px;">
                                <strong style="font-size:33px;">${this.currentBranch.Company_Name}</strong><br>
                                <p style="white-space:pre-line;">${this.currentBranch.Repot_Heading}</p>
                            </div>
                        </div>
                            ${invoiceContent}
                        </body>
                    </html>
                `);
            } else if (this.currentBranch.print_type == '2') {
                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <meta http-equiv="X-UA-Compatible" content="ie=edge">
                        <title>Invoice</title>
                        <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
                        <style>
                            html, body{
                                width:500px!important;
                            }
                            body, table{
                                font-size: 13px;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="row">
                            <div class="col-xs-2" style="margin-left: 19%;"><img  src="/uploads/company_profile_thum/${this.currentBranch.Company_Logo_thum}" alt="Logo" style="height:105px;    margin-top: 32px;" /></div>
                            <div class="col-xs-5" style="padding-top:32px;">
                                <strong style="font-size:33px;">${this.currentBranch.Company_Name}</strong><br>
                                <p style="white-space:pre-line;">${this.currentBranch.Repot_Heading}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div style="border-bottom: 4px double #454545;margin-top:7px;margin-bottom:7px;"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                ${invoiceContent}
                            </div>
                        </div>
                    </body>
                    </html>
				`);
            } else {
				printWindow.document.write(`
                    <!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <meta http-equiv="X-UA-Compatible" content="ie=edge">
                        <title>Invoice</title>
                        <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
                        <style>
                            body, table{
                                font-size: 13px;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                        <div class="row" style="text-align:center;">
                            <div class="col-xs-2"><img  src="/uploads/company_profile_thum/${this.currentBranch.Company_Logo_thum}" alt="Logo" style="height:105px;margin-top: 0;" /></div>
                            <div class="col-xs-10" style="position: relative;left: 34%;transform: translateX(-50%);">
                                <span style="font-size:18px;font-weight:bold;">${this.currentBranch.Company_Name}</span><br>
                                <p style="white-space:pre-line;">${this.currentBranch.Repot_Heading}</p>
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div style="border-bottom: 4px double #454545;margin-top:7px;margin-bottom:7px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-12">
                                    ${invoiceContent}
                                </div>
                            </div>
                        </div>
                        <div class="container" style="bottom:0px;margin-top: 50px;width:100%;">
                            <div class="row" style="border-bottom:1px solid black;margin-bottom:5px;padding-bottom:6px;">
                                <div class="col-xs-6">
                                    <span style="text-decoration:overline;">${this.lang == 'bn' ? "ক্রেতার স্বাক্ষর" : " Received by "}</span><br><br>
                                    ** THANK YOU FOR YOUR BUSINESS **
                                </div>
                                <div class="col-xs-6 text-right">
                                    <span style="text-decoration:overline;">${this.lang == 'bn' ? "বিক্রেতা স্বাক্ষর" : " Authorized Signature "} </span>
                                </div>
                            </div>

                            <div class="row" style="font-size:12px;">
                                <div class="col-xs-6">
                                    Print Date: ${moment().format('DD-MM-YYYY h:mm a')}, Printed by: ${this.sales.AddBy}
                                </div>
                                <div class="col-xs-6 text-right">
                                    Visit Our Website: www.hazibd.com
                                </div>
                            </div>
                        </div>
                    </body>
                    </html>
				`);
            }
            let invoiceStyle = printWindow.document.createElement('style');
            invoiceStyle.innerHTML = this.style.innerHTML;
            printWindow.document.head.appendChild(invoiceStyle);
            printWindow.moveTo(0, 0);
            
            printWindow.focus();
            await new Promise(resolve => setTimeout(resolve, 1000));
            printWindow.print();
            printWindow.close();
        }
    }
})