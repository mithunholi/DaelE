$().ready(function() {
	alert("selva");
	$("#prodname").autocomplete("proposal/searchSuggest.php", {
		width: 260,
		matchContains: true,
		selectFirst: false
	});
});
