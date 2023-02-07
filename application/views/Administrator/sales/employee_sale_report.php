<style>
	.v-select{
		margin-bottom: 5px;
	}
	.v-select .dropdown-toggle{
		padding: 0px;
	}
	.v-select input[type=search], .v-select input[type=search]:focus{
		margin: 0px;
	}
	.v-select .vs__selected-options{
		overflow: hidden;
		flex-wrap:nowrap;
	}
	.v-select .selected-tag{
		margin: 2px 0px;
		white-space: nowrap;
		position:absolute;
		left: 0px;
	}
	.v-select .vs__actions{
		margin-top:-5px;
	}
	.v-select .dropdown-menu{
		width: auto;
		overflow-y:auto;
	}
	.table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th{
		border: 1px solid #311919 !important;
	}
</style>
<div class="row" id="employeeSaleReport">
	<div class="col-xs-12 col-md-12 col-lg-12" style="border-bottom:1px #ccc solid;">
		<div class="form-group">
			<label class="col-sm-1 control-label no-padding-right"> Employee </label>
			<div class="col-sm-2">
				<v-select v-bind:options="employees" v-model="selectedEmployee" label="display_name"></v-select>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-1 control-label no-padding-right"> Date from </label>
			<div class="col-sm-2">
				<input type="date" class="form-control" v-model="dateFrom">
			</div>
			<label class="col-sm-1 control-label no-padding-right text-center" style="width:30px"> to </label>
			<div class="col-sm-2">
				<input type="date" class="form-control" v-model="dateTo">
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-1">
				<input type="button" class="btn btn-primary" value="Show" v-on:click="getReport" style="margin-top:0px;border:0px;height:28px;">
			</div>
		</div>
	</div>

	<div class="col-sm-12" style="display:none;" v-bind:style="{display: showTable ? '' : 'none'}">
		<a href="" style="margin: 7px 0;display:block;width:50px;" v-on:click.prevent="print">
			<i class="fa fa-print"></i> Print
		</a>
		<div class="table-responsive" id="reportTable">
			<table class="table table-bordered" style="border: 1px solid #311919 !important;">
				<thead>
					<tr> 
						<th style="border: 1px solid #311919 !important;">Employee Id</th>
                        <th style="border: 1px solid #311919 !important;">Employee Name</th>
                        <th style="border: 1px solid #311919 !important;">Total Sale</th>
                        <th style="border: 1px solid #311919 !important;">Total Paid</th>
                        <th style="border: 1px solid #311919 !important;">Due</th>
                        <th style="border: 1px solid #311919 !important;">Total Due</th>
					</tr>
				</thead>
				<tbody>
                    <tr v-for="report in saleReport" style="text-align: center; font-weight:bold">
                        <td style="border: 1px solid #311919 !important;">{{report.Employee_ID}}</td>
                        <td style="border: 1px solid #311919 !important;">{{report.Employee_Name}}</td>
                        <td style="border: 1px solid #311919 !important;">{{+report.billAmount + +report.previous_due}}</td>
                        <td style="border: 1px solid #311919 !important;">{{report.paidAmount}}</td> 
                        <td style="border: 1px solid #311919 !important;">{{ report.dueAmount }}</td>
                        <td style="border: 1px solid #311919 !important;">{{ +report.previous_date_due + +report.dueAmount }}</td>
                    </tr>
                </tbody>
                <tfoot>
					<tr style="text-align: center; font-weight:bold">
						<td style="border: 1px solid #311919 !important;"></td>
                        <td style="border: 1px solid #311919 !important;">Total : </td> 
                        <td style="border: 1px solid #311919 !important;">{{ saleReport.reduce((p, c) => { return +p + +c.billAmount + +c.previous_due; }, 0) }}</td>
                        <td style="border: 1px solid #311919 !important;">{{ saleReport.reduce((p, c) => { return +p + +c.paidAmount; }, 0) }}</td>
                        <td style="border: 1px solid #311919 !important;">{{ saleReport.reduce((p, c) => { return +p + +c.dueAmount; }, 0) }}</td>
                        <td style="border: 1px solid #311919 !important;">{{ saleReport.reduce((p, c) => { return +p + +c.previous_date_due + +c.dueAmount; }, 0) }}</td>
                    </tr>
                </tfoot>
			</table>
		</div>
	</div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#employeeSaleReport',
		data(){
			return {
                employees: [],
                saleReport: [],
				selectedEmployee: "All Employee",
				dateFrom: null,
				dateTo: null,
                showTable: false,
				totalBill: 0,
				totalPreviousDue:0,
                totalPaid: 0,
                totalDue: 0
			}
		},
		created(){
			let today = moment().format('YYYY-MM-DD');
			this.dateTo = today;
			this.dateFrom = moment().format('YYYY-MM-DD');
            this.getEmployees();
            this.getReport();
		},
		methods:{
			getEmployees(){
				axios.get('/get_employees').then(res => {
                    this.employees = res.data;
                    let allEmployee= {
                        display_name: "All Employee",
                        Employee_SlNo: "All",
                    }
                    this.employees.unshift(allEmployee);
				})
			},
			getReport(){
				if(this.selectedEmployee == null){
					alert('Select employee');
					return;
				}
				let data = {
					dateFrom: this.dateFrom,
					dateTo: this.dateTo,
					employeeId: this.selectedEmployee.Employee_SlNo
				}

				axios.post('/get_employee_sale_report', data).then(res => {
                    this.saleReport = res.data;
    //                 this.saleReport.map((product) => {
				// 		if(product.dueAmount < 0){
				// 			product.dueAmount = 0;
				// 		}
				// 			return product;
				// 	})
                    this.calculate();
                    this.showTable = true;
				})
            }, 
            calculate(){ 
                this.totalBill = this.saleReport.reduce((prev, curr) => {
                    return prev+ +curr.billAmount;
                },0); 
 				this.totalPreviousDue = this.saleReport.reduce((prev, curr) => {
                    return prev+ +curr.previous_due;
                },0);
                this.totalPaid = this.saleReport.reduce((prev, curr) => {
                    return prev+ +curr.paidAmount;
                },0);
			
                // this.totalDue = this.saleReport.reduce((prev, curr) => {
					
				// },0);
				let t = 0;
				this.saleReport.forEach((curr)=>{


					console.log(((parseFloat(curr.billAmount))-curr.paidAmount));
					let prev = ((parseFloat(curr.billAmount))-curr.paidAmount);
					  t = parseFloat(t)+prev;
				})
				this.totalDue = t;

            },
			async print(){
				let reportContent = `
					<div class="container">
						<h4 style="text-align:center">Sale Report</h4>
						<p style="text-align:center">Date: ${this.dateFrom} - ${this.dateTo} </p> <br>
					</div>
					<div class="container">
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#reportTable').innerHTML}
							</div>
						</div>
					</div>
				`;
				var printWindow = window.open('', 'PRINT', `width=${screen.width}, height=${screen.height}`);
				printWindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php');?>
				`);

				printWindow.document.body.innerHTML += reportContent;

				printWindow.focus();
				await new Promise(resolve => setTimeout(resolve, 1000));
				printWindow.print();
				printWindow.close();
			}
		}
	})
</script>