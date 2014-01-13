
function insertVacationUse(startDate, endDate)
{
	if(startDate == undefined || endDate == undefined) return;

	var endPlusOneDay = new Date(endDate);
	endPlusOneDay.setDate(endPlusOneDay.getDate() + 1);

	var yyyy = endPlusOneDay.getFullYear().toString();
	var MM = (endPlusOneDay.getMonth()+1).toString();
	var dd = endPlusOneDay.getDate().toString();

	$.post("ical_controller.php", {
		controlType: 'INSERT',
		summary: 'OFF> ' + currentDesignerName,
		startDate: ical_startDate,
		endDate: ical_endDate,
	 	icalCreated: ''
	}, function(icalCreated) {

		if(icalCreated == "" || icalCreated == null) {
			alert("iCal Insert Failed");
			return;
		}
	});

}

function updateIcalDate(created, startDate, endDate, half)
{
	if(startDate == undefined || endDate == undefined) return;

	var endPlusOneDay = new Date(endDate);
	endPlusOneDay.setDate(endPlusOneDay.getDate() + 1);

	var yyyy = endPlusOneDay.getFullYear().toString();
	var MM = (endPlusOneDay.getMonth()+1).toString();
	var dd = endPlusOneDay.getDate().toString();

	var ical_startDate = "20" + startDate.substr(6, 2) + startDate.substr(0, 2) + startDate.substr(3, 2) + "";
	var ical_endDate = "20" + yyyy.substr(2, 2) + MM + dd;
	var summary = "OFF> " + currentDesignerName;
	if(half != "") summary += ", " + half;

	// iCal Insert
	$.post("ical_controller.php", {
		controlType: 'UPDATE',
		startDate: ical_startDate,
		endDate: ical_endDate,
		summary: summary,
	 	icalCreated: created
	}, function(result) {
		return;
	});
}

function deleteIcalDate(created) {
	if(created == undefined || created == "") {
		return;
	}
	// iCal Delete
	$.post("ical_controller.php", {
		controlType: 'DELETE',
	 	icalCreated: created
	}, function(result) {
		return;
	});
}