// change timestamps to readable date in selection dropdown
$('#seshidtag option').each(function() {
	entrydate = $(this).text().substr(0, 13);
	if (ld = parseInt(entrydate)) {
		ld = new Date( ld );
		ld = ld.toString().substr(4,17);
		this.text = ld + $(this).text().substr(13);
	}
});

// change url for export when graph has selection
var placeholder = $("#placeholder");
placeholder.bind("plotselected", function (event, ranges) {
	document.getElementById("export").children[0].href = "export.php?sid=" + document.getElementById("seshidtag")[document.getElementById("seshidtag").selectedIndex].value + "&filetype=csv&from=" + ranges.xaxis.from.toFixed(1).substring(0,13) + "&to=" + ranges.xaxis.to.toFixed(1).substring(0,13);
	document.getElementById("export").children[1].href = "export.php?sid=" + document.getElementById("seshidtag")[document.getElementById("seshidtag").selectedIndex].value + "&filetype=json&from=" + ranges.xaxis.from.toFixed(1).substring(0,13) + "&to=" + ranges.xaxis.to.toFixed(1).substring(0,13);
	$("#formdelete").attr('action', "session.php?deletesession=" + document.getElementById("seshidtag")[document.getElementById("seshidtag").selectedIndex].value + "&from=" + ranges.xaxis.from.toFixed(1).substring(0,13) + "&to=" + ranges.xaxis.to.toFixed(1).substring(0,13));
});
placeholder.bind("plotunselected", function (event) {
	document.getElementById("export").children[0].href = "export.php?sid=" + document.getElementById("seshidtag")[document.getElementById("seshidtag").selectedIndex].value + "&filetype=csv";
	document.getElementById("export").children[1].href = "export.php?sid=" + document.getElementById("seshidtag")[document.getElementById("seshidtag").selectedIndex].value + "&filetype=json";
	$("#formdelete").attr('action', "session.php?deletesession=" + document.getElementById("seshidtag")[document.getElementById("seshidtag").selectedIndex].value);
});
