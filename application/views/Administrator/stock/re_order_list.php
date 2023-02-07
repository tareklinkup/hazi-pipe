<style>
    .v-select {
        margin-bottom: 5px;
    }

    .v-select .dropdown-toggle {
        padding: 0px;
    }

    .v-select input[type=search],
    .v-select input[type=search]:focus {
        margin: 0px;
    }

    .v-select .vs__selected-options {
        overflow: hidden;
        flex-wrap: nowrap;
    }

    .v-select .selected-tag {
        margin: 2px 0px;
        white-space: nowrap;
        position: absolute;
        left: 0px;
    }

    .v-select .vs__actions {
        margin-top: -5px;
    }

    .v-select .dropdown-menu {
        width: auto;
        overflow-y: auto;
    }
@media print {
    body {
        background-color: #1a4567 !important;
        -webkit-print-color-adjust: exact; 
    }
}

</style>
<div id="stock">
	<input type="button" onclick="printDiv('stockContent')" value="Print" />
<div class="row" >
	
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="table-responsive" id="stockContent">
				  <div class="row print" style="display: none; text-align:center;">
				  	<?php $result = $this->db->query("SELECT * FROM tbl_company WHERE company_BrunchId=".$this->session->userdata("BRANCHid")."")->result_array(); ?>
                            <div class="col-xs-2"><img  src="/uploads/company_profile_thum/<?= $result[0]['Company_Logo_thum']?>" alt="Logo" style="height:80px;    margin-top: 20px;" /></div>
                            <div class="col-xs-10" style="padding-top:20px;position: relative;left: 34%;transform: translateX(-50%);">
                                <strong style="font-size:18px;"><?= $result[0]['Company_Name']?></strong><br>
                                <p style="white-space:pre-line;"><?= $result[0]['Repot_Heading']?></p>
                            </div>
                        </div>
				<table class="table table-bordered" >
					<thead>
						<tr style="">
							<th>Product Id</th>
							<th>Product Name</th>
							<th>Category</th>
							<th>Current Quantity</th>
							<th>Stock Value</th>
						</tr>
					</thead>
					<tbody>
						<?php $reorder_list = $this->db->query("select * from(
                select
                    ci.*,
                    (select (ci.purchase_quantity + ci.sales_return_quantity + ci.transfer_to_quantity) - (ci.sales_quantity + ci.purchase_return_quantity + ci.damage_quantity + ci.transfer_from_quantity)) as current_quantity,
                    p.Product_Name,
                    p.Product_Code,
                    p.Product_ReOrederLevel,
                    (select (p.Product_Purchase_Rate * current_quantity)) as stock_value,
                    pc.ProductCategory_Name,
                    b.brand_name,
                    u.Unit_Name
                from tbl_currentinventory ci
                join tbl_product p on p.Product_SlNo = ci.product_id
                left join tbl_productcategory pc on pc.ProductCategory_SlNo = p.ProductCategory_ID
                left join tbl_brand b on b.brand_SiNo = p.brand
                left join tbl_unit u on u.Unit_SlNo = p.Unit_ID
                where p.status = 'a'
                and p.is_service = 'false'
                and ci.branch_id = ".$this->session->userdata('BRANCHid')."
            ) as tbl
            where 1 = 1
            and current_quantity < Product_ReOrederLevel")->result();
				$stock_value_t = 0;
				foreach ($reorder_list as $key => $value) {
					$stock_value_t = $stock_value_t + $value->stock_value;
             ?>
						<tr >
							<td><?= $value->Product_Code?></td>
							<td><?= $value->Product_Name?></td>
							<td><?= $value->ProductCategory_Name?></td>
							<td><?= $value->current_quantity?></td>
							<td><?= $value->stock_value?></td>
						</tr>

					<?php }?>
					</tbody>
					<tfoot>
						<tr >
							<td colspan="4" style="text-align:right;">Total Stock Value</td>
							<td><?= $stock_value_t?></td>
						</tr>
					</tfoot>
				</table>

				
			</div>
		</div>
	</div>
</div>





<script type="text/javascript">
	window.onbeforeprint = function() {
    $('.print').css('display','block')
};
window.onafterprint = function() {
     $('.print').css('display','none')
};
	function printDiv(stockContent) {
     var printContents = document.getElementById('stockContent').innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;
     window.moveTo(0, 0);
      window.focus();

     window.print();


     document.body.innerHTML = originalContents;
}
</script>


<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vue-select.min.js"></script>