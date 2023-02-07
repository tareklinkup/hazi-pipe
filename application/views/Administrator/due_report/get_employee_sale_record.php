<div class="row" id="employeeSaleRecord">
	<div class="col-md-12" style="margin-top: 10px;">
		<a href="" v-on:click.prevent="print"><i class="fa fa-print"></i> Print</a>
	</div>
	<div class="col-md-12"  style="margin-top: 10px">
		
		<div class="table-responsive" id="reportTable">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Employee Id</th>
						<th>Employee Name</th>
						<th>Total Sale</th>
						<th>Total Paid</th>
						<th>Due Amount</th>
					</tr>
				</thead>
				<tbody>
					<?php          
					$TSaleAmo = 0; 
					$TPaidAmo = 0; 
					$TDueAmo = 0;  
					foreach ($result as $key => $value)  {
						$TSaleAmo = $TSaleAmo+$value->SaleMaster_TotalSaleAmount;
						$TPaidAmo = $TPaidAmo+$value->SaleMaster_PaidAmount;
						$TDueAmo = $TDueAmo+$value->SaleMaster_DueAmount;
					 ?>
					<tr>
						<td><?= $value->Employee_ID?></td>
						<td><?= $value->Employee_Name?></td>
						<td><?= $value->SaleMaster_TotalSaleAmount?></td>
						<td><?= $value->SaleMaster_PaidAmount?></td>
						<td><?= $value->SaleMaster_DueAmount?></td>
					</tr>
					<?php } ?>
					<tr>
						<td></td>
						<td style="text-align: right">Total : </td>
						<td><?= $TSaleAmo?></td>
						<td><?= $TPaidAmo?></td>
						<td><?= $TDueAmo?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script>
	new Vue({
		el: "#employeeSaleRecord",
		methods:{
			async print(){
				let reportTables = `
                <div class="container">
                    <h4 style="text-align:center">Customer Due</h4 style="text-align:center">
					<div class="row">
						<div class="col-xs-12">
							${document.querySelector('#reportTable').innerHTML}
						</div>
					</div>
                </div>
            `;

            let printWindow = window.open('', 'Print', `width=${screen.width}, height=${screen.height}`);
            printWindow.document.write(`
                <?php $this->load->view('Administrator/reports/reportHeader.php'); ?>
            `);

            printWindow.document.body.innerHTML += reportTables;
            printWindow.focus();
            await new Promise(r => setTimeout(r, 1000));
            printWindow.print();
            printWindow.close();
			}
		}
	})
</script>