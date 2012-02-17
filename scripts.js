var stdlen = 40;
var bookkeeping = new Array();
var classes = new Array();
var plots = new Array();
var classids = new Array();

function getClasses()
{
	var mysubject = $('#subject option:selected').val();
	var myterm = $('#term option:selected').val();

	if(mysubject != "" && myterm != "")
	{
		$('#divclasses').html('Loading...<br /><br />');
		$('#divsections').html('');
		$.post(
			'/parse.php',
			{term: myterm, subj: mysubject}, 
			function(data)
			{
				$('#desc').html('<i>course description will appear here</i>');
				$('#divsections').html();
				$('#divclasses').html(data);
			},
			"html"
		);
	}
}

function getSections()
{
	var myclass= $('#class option:selected').val();
	var myterm = $('#term option:selected').val();
	if(myclass != "" && myterm != "")
	{
		$('#divsections').html('Loading...<br /><br />');
		$.post(
			'/sections.php',
			{id: myclass, term: myterm}, 
			function(data)
			{
				$('#desc').html('<i>course description will appear here</i>');
				$('#divsections').html(data);
			},
			"html"
		);
	}
}

function showClass(myid,mypos,inlist)
{
	if(myid != "")
	{
		$.post(
			'/times.php',
			{id: myid}, 
			function(data)
			{
				var conflict = false;
				$.each(data.lectures.lecture,function()
				{
					if($('#' + this.cell).html() != "")
					{
						alert("Scheduling conflict.");
						conflict = true;
						return false;
					}
				});

				if(conflict == false)
				{
					if(mypos == -1)
						showDesc(myid);

					plots[data.lectures.crn] = new Array(data.lectures.lecture.length);
					bookkeeping[data.lectures.crn] = classes.length;
					classids[classes.length] = data.lectures.id;
					classes[classes.length] = data.lectures.crn;

					var i = 0;
					$.each(data.lectures.lecture,function()
					{
						plots[data.lectures.crn][i] = this.cell;
						i++;
						$('#' + this.cell).html('<div style="position: relative; top: ' + (this.offset*stdlen) + 'px; margin: 0 auto 0 auto; width: 116px; height: ' + (this.classlength*stdlen) + 'px; background-color: #efefef; border: 1px dotted black; font-size: 11px;">' + data.lectures.subj + ' ' + data.lectures.classnum + ' <span style="font-size: 9px; vertical-align: text-top;"><a style="cursor: pointer;" onclick="showDesc(' + (data.lectures.id) + ')">?</a> - <a style="cursor:pointer;" onclick="hideClass(' + data.lectures.crn + ')">x</a></span><br /><span style="font-family: verdana; font-size: 9px;">crn #' + data.lectures.crn + '</span></div');
					});

					if(mypos == -1)
						$('#divsave').html('<a style="cursor:pointer;" onclick="saveSchedule()">Click here to save this schedule.</a><br /><br />');

					if(mypos == inlist-1)
					{
						document.getElementById("loading").style.visibility = "hidden";
					}
				}
			},
			"json"
		);
	}
}

function saveSchedule()
{
	var i = 0;
	var myclasses = "";
	for(i = 0; i < classids.length; i++)
	{
		if(classids[i] != 0)
		{
			myclasses = myclasses + classids[i] + "-";
		}
	}

	$.post(
		'/save.php',
		{classes: myclasses},
		function(data)
		{
			$('#divsave').html(data);
		},
		"html"
	);
}

function hideClass(mycrn)
{
	var i = 0;
	for(i = 0; i < plots[mycrn].length; i++)
	{
		$('#' + plots[mycrn][i]).html('');
	}
	plots[mycrn] = new Array();
	classnum = bookkeeping[mycrn];
	classes[classnum] = 0;
	classids[classnum] = 0;
	bookkeeping[mycrn] = 0;
}

function showDesc(myid)
{
	$.post(
		'/desc.php',
		{id: myid},
		function (data)
		{
				$('#desc').html(data);
		},
		"html"
	);
}

function loadSaved(arr)
{
	var i = 0;
	document.getElementById("loading").style.visibility = "visible";
	for(i = 0; i < arr.length; i++)
	{
		showClass(arr[i],i,arr.length);
	}

}
