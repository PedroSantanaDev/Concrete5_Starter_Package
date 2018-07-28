$(document).ready(function(){

	$(document).on("click",".col-sort", function() {
		//Sorts the contact_list table
		$('#sort').val(this.value);
		$("form.column-sort").submit();
	});

	/*$('.datepicker').datepicker();*/

	$(".action-delete").click(function(){
		if (!confirm("Are you sure you want to delete this record? This action cannot be undone.")){
			return false;
		}
	});

	$(".datetime-picker").datetimepicker({
		"pickDate":true,
		"pickTime":true,
		"useMinutes":true,
		"useSeconds":false,
		"useCurrent":true,
		"showToday":true,
		"useStrict":false,
		"sideBySide":false,
		"minuteStepping":1,
		"minDate":"",
		"defaultDate":"",
		"icons ":{
		"time":"glyphicon glyphicon-time",
		"date":"glyphicon glyphicon-calendar",
		"up":"glyphicon glyphicon-chevron-up",
		"down":"glyphicon glyphicon-chevron-down"},
		"language":"en",
		"disabledDates":[],
		"enabledDates":[],
		"daysOfWeekDisabled":[]
	});

});
