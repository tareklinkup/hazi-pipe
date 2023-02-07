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

</style>

<div class="row" id="customerDueList" style="margin-bottom: 500px">
	<div class="col-xs-12 col-md-12 col-lg-12" style="border-bottom:1px #ccc solid;">
		<div class="form-group">
			<label class="col-sm-1 control-label no-padding-right">Search Type</label>
			<div class="col-sm-2">
				<select class="form-control chosen-select " id="search_type" style="padding:0px;">
					<option value="all">All Employee</option>
					<?php $employee = $this->db->query("SELECT Employee_SlNo,Employee_Name FROM tbl_employee WHERE status='a' ")->result();
                      foreach ($employee as $key => $value) {?>
                      	<option value="<?= $value->Employee_SlNo?>"><?= $value->Employee_Name?></option>
                     <?php  }
					 ?>
					   
				</select>

			</div>
			<label style="margin-right: -59px;" class="col-sm-1 control-label no-padding-right">Start</label>
			<div class="col-sm-2">

				<input class="form-control date-picker" id="startdate" type="text" data-date-format="yyyy-mm-dd" style="border-radius: 5px 0px 0px 5px !important;" value="<?= date('Y-m-d')?>">
			</div>
			<label style="margin-right: -59px;" class="col-sm-1 control-label no-padding-right">End</label>
			<div class="col-sm-2">
				<input class="form-control date-picker" id="enddate" type="text" data-date-format="yyyy-mm-dd" style="border-radius: 5px 0px 0px 5px !important;" value="<?= date('Y-m-d')?>">
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-2">
				<button class="btn btn-success" style="height: 34px;" id="search_emp_sale_record" >Search</button>
			
			</div>
		</div>
		
	</div>

	<span id="search_emp_sale_record_show"> 
		
	</span>
</div>

<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script>
	$(document).ready(function(){

		$(document).on('click','#search_emp_sale_record',function(e){
			let search_type  = $('#search_type').chosen().val();
			let startdate  = $('#startdate').val();
			let enddate  = $('#enddate').val();
			$.ajax({
				url:'/get_emp_sale_record',
				method:"POST",
				data:{search_type:search_type,enddate:enddate,startdate:startdate},
				success:function(data){
					$('#search_emp_sale_record_show').html(data);
				}
			})
		});
	})
</script>