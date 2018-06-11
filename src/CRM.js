// JavaScript Document
var divid = 'output';
var loadingmessage = 'Processing...';




function AJAX(){
	var xmlHttp;
	try{
		xmlHttp=new XMLHttpRequest(); // Firefox, Opera 8.0+, Safari
		return xmlHttp;
	}
	catch (e){
		try{
			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); // Internet Explorer
			return xmlHttp;
		}catch (e){
			try{
			xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
			return xmlHttp;
			}
			catch (e){
				alert("Your browser does not support AJAX!");
				return false;
			}
		}
	}

}

function passData(indata){
	if(indata == "Others"){
		//alert("true");
		document.getElementById("ref_other_by").style.visibility='visible';
	}else{
		//alert("flase");
		document.getElementById("ref_other_by").style.visibility='hidden';
	}
}

function formget(f, url) {
	//alert("URL :" + url);
	var poststr = getFormValues(f);
	
	//alert("POST STR :"+ poststr);
	postData(url, poststr);
}

function getXmlHttpRequestObject() {
	if (window.XMLHttpRequest) {
		return new XMLHttpRequest();
	} else if(window.ActiveXObject) {
		return new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		alert("Your Browser Sucks!");
	}
}

//Our XmlHttpRequest object to get the auto suggest
var searchReq = getXmlHttpRequestObject();

//Called from keyup on the search textbox.
//Starts the AJAX request.
function searchSuggest() {
	if (searchReq.readyState == 4 || searchReq.readyState == 0) {
		var str = escape(document.getElementById('prodname').value);
		//alert(str);
		searchReq.open("GET", 'proposal/searchSuggest.php?search=' + str, true);
		searchReq.onreadystatechange = handleSearchSuggest; 
		searchReq.send(null);
	}		
}


function newWindow(tid,qno,lno){
	//alert("Quote No :"+qno);
	//alert("Lead No :"+lno);
	window.open("quotation.php?tempid="+tid+"&quotation_no="+qno+"&lead_no="+lno, "", "width=400,height=500,top=50,left=280,resizable,toolbar,scrollbars,menubar,"); 
}


//Called when the AJAX response is returned.
function handleSearchSuggest() {
	if (searchReq.readyState == 4) {
	    var ss = document.getElementById('layer1');
		var str1 = document.getElementById('prodname');
		var curLeft=0;
		if (str1.offsetParent){
		    while (str1.offsetParent){
			curLeft += str1.offsetLeft;
			str1 = str1.offsetParent;
		    }
		}
		var str2 = document.getElementById('prodname');
		var curTop=20;
		if (str2.offsetParent){
		    while (str2.offsetParent){
			curTop += str2.offsetTop;
			str2 = str2.offsetParent;
		    }
		}
		var str =searchReq.responseText.split("\n");
		if(str.length==1)
		    document.getElementById('layer1').style.visibility = "hidden";
		else
		    ss.setAttribute('style','position:absolute;top:'+curTop+';left:'+curLeft+';width:150;z-index:1;padding:5px;border: 1px solid #000000; overflow:auto; height:105; background-color:#F5F5FF;');
		ss.innerHTML = '';
		for(i=0; i < str.length - 1; i++) {
			//Build our element string.  This is cleaner using the DOM, but
			//IE doesn't support dynamically added attributes.
			var suggest = '<div onmouseover="javascript:suggestOver(this);" ';
			suggest += 'onmouseout="javascript:suggestOut(this);" ';
			suggest += 'onclick="javascript:setSearch(this.innerHTML);" ';
			suggest += 'class="small">' + str[i] + '</div>';
			ss.innerHTML += suggest;
		}
	}
}

//Mouse over function
function suggestOver(div_value) {
	div_value.className = 'suggest_link_over';
}
//Mouse out function
function suggestOut(div_value) {
	div_value.className = 'suggest_link';
}
//Click function
function setSearch(value) {
	document.getElementById('prodname').value = value;
	document.getElementById('layer1').innerHTML = '';
	document.getElementById('layer1').style.visibility = "hidden";
}

function sizeTbl() {
  	var ele = document.getElementById("toggleText");
	var text = document.getElementById("displayText");
	if(ele.style.display == "block") {
    		ele.style.display = "none";
		if(text.value == "-"){
			text.value = "+";
		}
  	}
	else {
		ele.style.display = "block";
		if(text.value == "+"){
			text.value = "-";
		}
	}
}


function uploadFile(fobj,url){
	//alert("URL1 :"+url);
	var myFileList;
	var myFile;
	var status;
	//mydata = document.getElementById('my_data').value;
	var formData = new FormData();
 
 	
	// Append our file to the formData object
	// Notice the first argument "file" and keep it in mind
	//formData.append('my_data', mydata);
	//alert("ArrayLength :"+fobj.elements.length);
	for(var i = 0;i < fobj.elements.length;i++)
	{
		//alert(fobj.elements[i].type);	
		switch(fobj.elements[i].type)
		{
			case "text":
				//alert(fobj.elements[i].name+"::"+escape(fobj.elements[i].value));
				formData.append(fobj.elements[i].name,escape(fobj.elements[i].value));
				break;
			case "textarea":
				formData.append(fobj.elements[i].name,escape(fobj.elements[i].value));
				break;

			case "select-one":

				formData.append(fobj.elements[i].name, fobj.elements[i].options[fobj.elements[i].selectedIndex].value);
				break;
			
			case "hidden":
				//alert("Name :"+fobj.elements[i].name+":Value :"+fobj.elements[i].value);
				if(fobj.elements[i].name == "txtstatus"){
					status = escape(fobj.elements[i].value);
				}

				if(fobj.elements[i].value == "download"){
					document.getElementById("btndownload").disabled=true;
				}
				if(fobj.elements[i].value == "uploadtxt"){
					document.getElementById("btnupload").disabled=true;
				}
				formData.append(fobj.elements[i].name,escape(fobj.elements[i].value));
				break;
			case "button":
				//alert("Name :"+fobj.elements[i].name+":Value :"+fobj.elements[i].value);
				formData.append(fobj.elements[i].name,escape(fobj.elements[i].value));
				
				if(fobj.elements[i].value=="Update"){
					//var btn = "'"+ fobj.elements[i].name +"'";
					document.getElementById('btnedit').style.visibility='hidden';
				}
				if(fobj.elements[i].value=="Save"){
					//var btn = "'"+ fobj.elements[i].name +"'";
					document.getElementById('btnAdd').style.visibility='hidden';
				}
				break;
			case "checkbox":
				//alert("SS :"+ fobj.elements[i].name);
				if(fobj.elements[i].checked == true){
					formData.append(fobj.elements[i].name,escape(fobj.elements[i].value));
					//alert("True");
				}else{
					formData.append(fobj.elements[i].name,"");
				}
				break;
			case "file":
				//alert("Element :"+fobj.elements[i].name+"::"+escape(fobj.elements[i].files));
				myFileList=fobj.elements[i].files;
				myFile = myFileList[0];
				//alert("File :"+myFile.name);
				//alert("File Name :"+fobj.elements[i].name);
				formData.append(fobj.elements[i].name,myFile);
				break;
		}//switch
	}//for
    //formData.append('my_data',myid);
	// Create our XMLHttpRequest Object
	var xmlHttp = new XMLHttpRequest();
 	xmlHttp.onreadystatechange =  function(){
		document.getElementById("loading").style.visibility = "visible";
  		if (xmlHttp.readyState==4 && xmlHttp.status==200){
    		if (xmlHttp.readyState == 4) {
				//alert("Saved :"+ xmlHttp.responseText);
				if(xmlHttp.responseText == "E100"){
					alert("Accepted Successfully");
				}
				if(xmlHttp.responseText == "U100"){
					alert("Updated Sucessfully ");
				}
				if(xmlHttp.responseText == "R100"){
					alert("Rejected Sucessfully ");
				}
				if(xmlHttp.responseText == "R101"){
					alert("Must be Select Payment Option CheckBox ");
				}
				if(xmlHttp.responseText == "A101"){
					alert("Argument missing while adding new record");
				}
				if(xmlHttp.responseText == "A102"){
					alert("Record Already Exists");
				}
				if(xmlHttp.responseText == "S100"){
					alert("Successfully Added");
				}
				if(xmlHttp.responseText == "E101"){
					alert("Argument missing");
				}
				if(xmlHttp.responseText == "D001"){
					alert("Deleted Successfully");
				}
				if(xmlHttp.responseText == "DD01"){
					alert("Must be select Distributor");
				}
				//document.getElementById(divid).innerHTML=xmlHttp.responseText;
				//alert(xmlHttp.responseText);
				//document.getElementById("CENTER_DIV").innerHTML=xmlHttp.responseText;
			}
			
			if(xmlHttp.responseText != ""){
				document.getElementById("output").innerHTML=xmlHttp.responseText;
				document.getElementById("loading").style.visibility = "hidden";
			}
			
			
    	}else if (xmlHttp.readyState==4 && xmlHttp.status != 200){
			var errorText = "<span style='text-align:center; color:#999999; font-size:24px;margin-left:10%'>Connection Failed</span>";
			document.getElementById("output").innerHTML=errorText;
			document.getElementById("loading").style.visibility = "hidden";
		}
	}
	//alert("URL:"+url);
	// Open our connection using the POST method
	xmlHttp.open("POST", url);
 	// Send the file
	//alert("formData :"+formData);
	xmlHttp.send(formData);
}



function dynsizeTbl(dispno){
	var eleid = "dyntoggleText"+dispno;
	//alert("Element ID:"+eleid);
	var txtid = trim("dynDisplayText"+dispno);
	
	var ele = document.getElementById(eleid);
	
	var text = document.getElementById(txtid);
	
	if(ele.style.display == "block") {
    		ele.style.display = "none";
		if(text.value == "-"){
			text.value = "+";
		}
  	}
	else {
		ele.style.display = "block";
		if(text.value == "+"){
			text.value = "-";
		}
	}
}
	
function trim(s)
{
	var l=0; var r=s.length -1;
	while(l < s.length && s[l] == ' ')
	{	l++; }
	while(r > l && s[r] == ' ')
	{	r-=1;	}
	return s.substring(l, r+1);
}	

function sizeTblMain() {
  	var ele = document.getElementById("toggleTextMain");
	var text = document.getElementById("displayTextMain");
	if(ele.style.display == "block") {
    		ele.style.display = "none";
		if(text.value == "HIDE QUOTATION INFO"){
			text.value = "SHOW QUOTATION INFO";
		}
  	}
	else {
		ele.style.display = "block";
		if(text.value == "SHOW QUOTATION INFO"){
			text.value = "HIDE QUOTATION INFO";
		}
	}
}

function sizeTbl1() {
  	var ele = document.getElementById("toggleText1");
	var text = document.getElementById("displayText1");
	if(ele.style.display == "block") {
    		ele.style.display = "none";
		if(text.value == "HIDE DEMO INFO"){
			text.value = "SHOW DEMO INFO";
		}
  	}
	else {
		ele.style.display = "block";
		if(text.value = "SHOW DEMO INFO"){
			text.value = "HIDE DEMO INFO";
		}
	}
}


function quotePreview() {
  	var ele = document.getElementById("toggleQuote");
	var text = document.getElementById("quotePreivew");
	if(ele.style.display == "block") {
    		ele.style.display = "none";
		if(text.value == "Hide Quote Preview"){
			text.value = "Show Quote Preview";
		}
  	}
	else {
		ele.style.display = "block";
		if(text.value = "Show Quote Preview"){
			text.value = "Hide Quote Preview";
		}
	}
}


function sizeTbl2() {
  	var ele = document.getElementById("toggleText2");
	var text = document.getElementById("displayText2");
	if(ele.style.display == "block") {
    		ele.style.display = "none";
		if(text.value == "-"){
			text.value = "+";
		}
  	}
	else {
		ele.style.display = "block";
		if(text.value = "+"){
			text.value = "-";
		}
	}
}

function sizeTbl3() {
  	var ele = document.getElementById("toggleText3");
	var text = document.getElementById("displayText3");
	if(ele.style.display == "block") {
    	ele.style.display = "none";
		if(text.value == "-"){
			text.value = "+";
		}
  	}
	else {
		ele.style.display = "block";
		if(text.value = "+"){
			text.value = "-";
		}
	}
}

function sizeTbl4() {
  	var ele = document.getElementById("toggleText4");
	var text = document.getElementById("displayText4");
	if(ele.style.display == "block") {
    		ele.style.display = "none";
		if(text.value == "-"){
			text.value = "+";
		}
  	}
	else {
		ele.style.display = "block";
		if(text.value = "+"){
			text.value = "-";
		}
	}
}
 
function postData(url, parameters){
	
	var xmlHttp = AJAX();

	xmlHttp.onreadystatechange =  function(){
		if(xmlHttp.readyState > 0 && xmlHttp.readyState < 4){
			document.getElementById(divid).innerHTML=loadingmessage;
		}
		if (xmlHttp.readyState == 4) {
			//alert("Saved :"+ xmlHttp.responseText);
			if(xmlHttp.responseText == "E100"){
				alert("Accepted Successfully");
			}
			if(xmlHttp.responseText == "U100"){
				alert("Updated Sucessfully ");
			}
			if(xmlHttp.responseText == "R100"){
				alert("Rejected Sucessfully ");
			}
			if(xmlHttp.responseText == "R101"){
				alert("Must be Select Payment Option CheckBox ");
			}
			if(xmlHttp.responseText == "A101"){
				alert("Argument missing while adding new record");
			}
			if(xmlHttp.responseText == "A102"){
				alert("Record Already Exists");
			}
			if(xmlHttp.responseText == "S100"){
				alert("Successfully Added");
			}
			if(xmlHttp.responseText == "E101"){
				alert("Argument missing");
			}
			if(xmlHttp.responseText == "D001"){
				alert("Deleted Successfully");
			}
			if(xmlHttp.responseText == "DD01"){
				alert("Must be select Distributor");
			}
			//document.getElementById(divid).innerHTML=xmlHttp.responseText;
			alert(xmlHttp.responseText);
			document.getElementById(divid).innerHTML=xmlHttp.responseText;
		}
	}

	xmlHttp.open("POST", url, true);
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlHttp.setRequestHeader("Content-length", parameters.length);
	xmlHttp.setRequestHeader("Connection", "close");
	xmlHttp.send(parameters);
}

function startUpload(){
	  document.getElementById("btnsubmit").disabled = true;	
	  //document.getElementById('btnsubmit').style.visibility ='hidden';
      document.getElementById('f1_upload_process').style.visibility = 'visible';
      document.getElementById('f1_upload_form').style.visibility = 'hidden';
      return true;
}

function stopUpload(success){
	
      var result = '';
      if (success == 1){
         result = '<span class="msg">Your mail has been sent.<\/span><br/><br/>';
      }
      else if(success == 0){
         result = '<span class="emsg">Error occurred and your email was not sent.<\/span><br/><br/>';
      }
      document.getElementById('f1_upload_process').style.visibility = 'hidden';
      document.getElementById('f1_upload_form').innerHTML = result ;
      document.getElementById('f1_upload_form').style.visibility = 'visible';   
	  var btn = document.getElementById('submit');
	  btn.disabled= true;   
      return true;   
}

function submitForm() {
	var data, xhr;

    data = new FormData();
    data.append( 'file', $( '#file' )[0].files[0] );

    xhr = new XMLHttpRequest();

    xhr.open( 'POST', 'bulkmail/upload.php', true );
    xhr.onreadystatechange = function ( response ) {};
    xhr.send( data );

}
	
function getFormValues(fobj)
{
	var str = "";
	var valueArr = null;
	var val = "";
	var cmd = "";
	
	for(var i = 0;i < fobj.elements.length;i++)
	{
		//alert("*** "+fobj.elements[i].type);
		switch(fobj.elements[i].type)
		{
			case "text":
				str += fobj.elements[i].name + "=" + escape(fobj.elements[i].value) + "&";
				break;

			case "textarea":
				str += fobj.elements[i].name +
				"=" + escape(fobj.elements[i].value) + "&";
				break;

			case "select-one":

				str += fobj.elements[i].name +
				"=" + fobj.elements[i].options[fobj.elements[i].selectedIndex].value + "&";
				break;
			
			case "hidden":
				str += fobj.elements[i].name + "=" + escape(fobj.elements[i].value) + "&";
				break;
			case "button":
				str += fobj.elements[i].name + "=" + escape(fobj.elements[i].value) + "&";
				if(fobj.elements[i].value=="Update"){
					//var btn = "'"+ fobj.elements[i].name +"'";
					document.getElementById('btnedit').style.visibility='hidden';
				}
				if(fobj.elements[i].value=="Save"){
					//var btn = "'"+ fobj.elements[i].name +"'";
					document.getElementById('btnAdd').style.visibility='hidden';
				}
				break;
			case "checkbox":
				//alert("SS :"+ fobj.elements[i].name);
				if(fobj.elements[i].checked == true){
					str += fobj.elements[i].name + "=" + escape(fobj.elements[i].value) + "&";
					//alert("True");
				}else{
					str += fobj.elements[i].name + "=" + "" + "&";
				}
				break;

		}
	}
	//alert("Inputs area :"+str);
	str = str.substr(0,(str.length - 1));
	//alert("Inputs area :"+str);
	return str;
}


function toggle() {
	var ele = document.getElementById("toggleText");
	var text = document.getElementById("displayText");
	if(ele.style.display == "block") {
    		ele.style.display = "none";
		if(text.value == "HIDE ORDER DETAILS"){
			text.value = "SHOW ORDER DETAILS";
		}else if(text.value == "HIDE PAYMENT DETAILS"){
			text.value = "SHOW PAYMENT DETAILS";
		}
  	}
	else {
		ele.style.display = "block";
		if(text.value == "SHOW PAYMENT DETAILS"){
			text.value = "HIDE PAYMENT DETAILS";
		}else if(text.value = "SHOW ORDER DETAILS"){
			text.value = "HIDE ORDER DETAILS";
		}
	}
} 

function toggleImage(a){
	//alert("Welcome :" + a );
	//alert("Welcome1:"+ b );
	var ele = document.getElementById(a); //div Id
	//alert("Div ID:"+ele);

	//var text = document.getElementById(b);
		//alert("Text :"+text.value);
	if(ele.style.display == "none") {
    		ele.style.display = "block";
	//		text.value = "HIDE";
	}else{
			ele.style.display = "none";
		//	text.value = "SHOW";
	}
}
function checking(){
	var divid = "pob";
	document.getElementId(divid).innerHTML = "selva";

}
function admin(){
	var divId="Center_Content";
	var msg="";
	document.getElementById(divId).innerHTML = "";
	 msg= document.getElementById(divId).innerHTML;
          msg += "welcome to eoxys";
          document.getElementById(divId).innerHTML = msg;
		  
	}

function Home(urlString){
	//alert("selva here");
	if (window.XMLHttpRequest)
  	{// code for IE7+, Firefox, Chrome, Opera, Safari
  		xmlhttp=new XMLHttpRequest();
  	}
	else
  	{// code for IE6, IE5
  		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  	}
	xmlhttp.onreadystatechange=function()
  	{
  		if (xmlhttp.readyState==4 && xmlhttp.status==200)
    	{
    		document.getElementById("Center_Content").innerHTML=xmlhttp.responseText;
			
    	}
  	}
	xmlhttp.open("GET",urlString,true);
	xmlhttp.send();

}

function Test(e) {
	var keynum;
	var keychar;
	var numcheck;

	if(window.event) // IE
	{
		keynum = e.keyCode;
	}
	else if(e.which) // Netscape/Firefox/Opera
	{
		keynum = e.which;
	}
	
	if(keynum ==13) {
			return true;

	}

}

function stopRKey(evt) {
	var evt  = (evt) ? evt : ((event) ? event : null);
	var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
	if ((evt.keyCode == 13) && (node.type=="text")) { return false; }
}
document.onkeypress = stopRKey;

function onDelete(data)  
{  
    //var a = document.frm.undo.value;
	var msg='';
	if(data=='Delete'){
		msg = 'Do you want to delete ?';
	}else if(data=='Undo'){
		msg = 'Do you want to undo ?';
	}
	if(confirm(msg)==true)  
	{  
		return true;  
	}else{  
		return false;  
	}  
}

function onDeletes()  
{  
    //var a = document.frm.undo.value;
	
	if(confirm('Do you want to delete ?')==true)  
	{  
		return true;  
	}else{  
		return false;  
	}  
}

function onPosting()
{
	if(confirm('Do you wnat to posting ?')==true)
	{
		return true;
	}else{
		return false;
	}
}
	
function onConfirm()  
{  
    //var a = document.frm.undo.value;
	
	if(confirm('Do you want to reject this record ?')==true)  
	{  
		return true;  
	}else{  
		return false;  
	}  
}

function calculate(qty,price,tax){
	var amt = parseFloat(price) * parseFloat(qty);
	var orgamt = amt + (amt * (parseFloat(tax)/100));
	document.getElementById("pamount").value = orgamt; 
}

function calcDiscounts(discount,qty,amount){
	//alert("Qty :"+qty);
	//alert("Amount :"+amount);
	if(qty != 0){
		var amt = amount-discount;
		document.getElementById("pamount").value = amt; 
	}
}

function calcTotalAmount(discount,qty,price,tax){
	var amt = qty * price;
	//alert("Amount :"+amt);
	var totamt = amt + (amt * (tax/100))- discount;
	//alert("Total Amount :"+totamt);
	document.getElementById("pamount").value = totamt;
}
function Discount(discount,totalamount){
	alert("Discount :"+discount);
	var amount = parseFloat(totalamount) - parseFloat(discount);
	document.getElementById("txttotalamt").value = amount;
}

function calc(a,b,p,t){
	/*alert("A :"+a);
	alert("B :"+b);
	alert("P :"+p);
	alert("T :"+t);*/
	
	var c = "amount"+b;
	var ttax = "tax"+b;
	//alert("TAX :"+ttax);
	var amt = parseFloat(a) * parseFloat(p);
	//alert(amt);
	var tax = amt * t /100;
	
	document.getElementById(ttax).value = parseFloat(tax);
	document.getElementById(c).value = parseFloat(amt);
	var taxamount = document.getElementById("txtTax").value;
	//alert("TAx Amount :"+ taxamount);
	var pamt = parseFloat(document.getElementById("totamt").value);
	//alert(pamt);
	pamt = pamt + amt;
	taxamount = parseFloat(taxamount) + (amt * t /100);
	taxwithbasic = pamt + taxamount;
	document.getElementById("totamt").value = pamt;
	document.getElementById("txtTax").value = taxamount;
	document.getElementById("txtTotalTax").value = taxwithbasic;
	var discount = parseFloat(document.getElementById("txtDiscount").value);
	if(discount >0){
		granttotal = taxwithbasic - discount ;
		document.getElementById("txtGrant").value = granttotal;
	}else{
		document.getElementById("txtGrant").value = taxwithbasic;
	}
	
	
}

function calcDiscount(d){
	var gamt = parseFloat(document.getElementById("txtGrant").value);
	grant_total = gamt - d;
	document.getElementById("txtGrant").value = grant_total;
}

function ClickCheckAll(frm)  
{  
	//alert("Selva "+document.getElementById("hdnCount").value);
	var i;  
	
	for(i=0;i<document.getElementById("hdnCount").value;i++)  
	{  
		if(document.getElementById("CheckAll").checked == true)  
		{  
			document.getElementById("userbox"+i).checked = true;
			
		}  
		else  
		{  
			document.getElementById("userbox"+i).checked = false;  
		}  
	}  
	
	
}  
  
function printHeader(strText){
	
	/*var centerDiv=document.getElementById("Center_H");
	centerDiv.style.text-align="center";
	centerDiv.style.color = "white";
	centerDiv.font-size="14";*/
	document.getElementById("Center_Text").innerHTML=strText;
}

function SearchData(form,sourceurl){
	//alert("selva here"+form.filter.value+"source url="+sourceurl);
	var xmlhttp1;
	
	var filter1 = form.filter.value;
 
	var filterfield1 = form.filter_field.value;
	
	sourceurl = sourceurl + "&filter=" +filter1+"&filter_field="+filterfield1;
	//alert("Source= "+sourceurl);
	if (window.XMLHttpRequest)
  	{// code for IE7+, Firefox, Chrome, Opera, Safari
  		xmlhttp1=new XMLHttpRequest();
  	}
	else
  	{// code for IE6, IE5
  		xmlhttp1=new ActiveXObject("Microsoft.XMLHTTP");
  	}
	xmlhttp1.onreadystatechange=function()
  	{
		document.getElementById("loading").style.visibility = "visible";
  		if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
    	{
    		document.getElementById("Center_Content").innerHTML=xmlhttp1.responseText;
			document.getElementById("loading").style.visibility = "hidden";
    	}else if (xmlhttp1.readyState==4 && xmlhttp1.status != 200){
			var errorText = "<span style='text-align:center; color:#999999; font-size:24px;margin-left:10%'>Connection Failed</span>";
			document.getElementById("Center_Content").innerHTML=errorText;
			document.getElementById("loading").style.visibility = "hidden";
		}
	}
	xmlhttp1.open("GET",sourceurl,true);
	xmlhttp1.send();  
}


function findData(form,sourceurl){
	//alert("selva here");
	var xmlhttp1;
	var filter = "";
	var fromdate = "";
	var todate = "";
	//var sourceurl = "tbl_user.php?a=filter";
	
	fromdate = form.fromdate.value;
	todate = form.todate.value;
	var fdate = fromdate.split("-");
	var tdate = todate.split("-");
 
 	//alert("From Date :"+fdate[0]+ " M="+fdate[1] + " Y="+fdate[2]);
	//alert("T0 Date :"+tdate[0]+ " M="+tdate[1] + " Y="+tdate[2]);
	//alert("1 :"+fdate[0].parseInt());
	//alert(parseInt("8",10));
	var dt1 = parseInt(fdate[0],10);
	var mn1 = parseInt(fdate[1],10);
	var yr1 = parseInt(fdate[2]);
	//alert ("From Date :"+dt1+mn1+yr1);
	var dt2 = parseInt(tdate[0],10);
	var mn2 = parseInt(tdate[1],10);
	var yr2 = parseInt(tdate[2]);
	//alert ("To Date :"+dt2+mn2+yr2);
	var date1 = new Date(yr1,mn1,dt1);
	var date2 = new Date(yr2,mn2,dt2);
	
	
	
	//alert("To Date :"+todate);
	var datediff = date1.getTime() - date2.getTime();
	//alert("Date Diff :"+datediff);
	if(datediff > 0){
		alert("Invalid Date Range");
	}
	//alert("Diff :"+datediff);
	filter1 = form.filter.value;
 	//alert("Filer :"+ filter1);
	
	var filterfield1 = form.filter_field.value;
	
	sourceurl = sourceurl + "&filter=" +filter1+"&filter_field="+filterfield1+"&fromdate="+fromdate+"&todate="+todate;
	//alert("Source= "+sourceurl);
	if (window.XMLHttpRequest)
  	{// code for IE7+, Firefox, Chrome, Opera, Safari
  		xmlhttp1=new XMLHttpRequest();
  	}
	else
  	{// code for IE6, IE5
  		xmlhttp1=new ActiveXObject("Microsoft.XMLHTTP");
  	}
	xmlhttp1.onreadystatechange=function()
  	{
		document.getElementById("loading").style.visibility = "visible";
  		if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
    	{
    		document.getElementById("Center_Content").innerHTML=xmlhttp1.responseText;
			document.getElementById("loading").style.visibility = "hidden";
    	}else if (xmlhttp1.readyState==4 && xmlhttp1.status != 200){
			var errorText = "<span style='text-align:center; color:#999999; font-size:24px;margin-left:10%'>Connection Failed</span>";
			document.getElementById("Center_Content").innerHTML=errorText;
			document.getElementById("loading").style.visibility = "hidden";
		}
  	}
	
	//sourceurl = sourceurl + "&filter=" + filter + "&filterfiled ="+ filterfiled;
	xmlhttp1.open("GET",sourceurl,true);
	xmlhttp1.send();  
}


function findData1(form,sourceurl){
	//alert("selva here");
	var xmlhttp1;
	var filter = "";
	var fromdate = "";
	//var todate = "";
	//var sourceurl = "tbl_user.php?a=filter";
	
	fromdate = form.fromdate.value;
	//alert("From Date :"+fromdate);
	//todate = form.todate.value;
	var fdate = fromdate.split("-");
	//var tdate = todate.split("-");
 
 	//alert("From Date :"+fdate[0]+ " M="+fdate[1] + " Y="+fdate[2]);
	//alert("T0 Date :"+tdate[0]+ " M="+tdate[1] + " Y="+tdate[2]);
	//alert("1 :"+fdate[0].parseInt());
	//alert(parseInt("8",10));
	var dt1 = parseInt(fdate[0],10);
	var mn1 = parseInt(fdate[1],10);
	var yr1 = parseInt(fdate[2]);
	//alert ("From Date :"+dt1+mn1+yr1);
	//var dt2 = parseInt(tdate[0],10);
	//var mn2 = parseInt(tdate[1],10);
	//var yr2 = parseInt(tdate[2]);
	//alert ("To Date :"+dt2+mn2+yr2);
	var date1 = new Date(yr1,mn1,dt1);
	//var date2 = new Date(yr2,mn2,dt2);
	
	
	
	//alert("Diff :"+datediff);
	///filter1 = form.filter.value;
 	//alert("Filer :"+ filter1);
	
	//var filterfield1 = form.filter_field.value;
	
	sourceurl = sourceurl + "&fromdate="+fromdate;
	//alert("Source= "+sourceurl);
	if (window.XMLHttpRequest)
  	{// code for IE7+, Firefox, Chrome, Opera, Safari
  		xmlhttp1=new XMLHttpRequest();
  	}
	else
  	{// code for IE6, IE5
  		xmlhttp1=new ActiveXObject("Microsoft.XMLHTTP");
  	}
	xmlhttp1.onreadystatechange=function()
  	{
		document.getElementById("loading").style.visibility = "visible";
  		if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
    	{
    		document.getElementById("Center_Content").innerHTML=xmlhttp1.responseText;
			document.getElementById("loading").style.visibility = "hidden";
    	}else if (xmlhttp1.readyState==4 && xmlhttp1.status != 200){
			var errorText = "<span style='text-align:center; color:#999999; font-size:24px;margin-left:10%'>Connection Failed</span>";
			document.getElementById("Center_Content").innerHTML=errorText;
			document.getElementById("loading").style.visibility = "hidden";
		}
  	}
	
	//sourceurl = sourceurl + "&filter=" + filter + "&filterfiled ="+ filterfiled;
	xmlhttp1.open("GET",sourceurl,true);
	xmlhttp1.send();  
}

function LoadDiv(divId, urlstring){
	//alert("Div :"+divId + " = "+urlstring);
	 var xmlhttp1;
	 if (window.XMLHttpRequest)
  	{// code for IE7+, Firefox, Chrome, Opera, Safari
  		xmlhttp1=new XMLHttpRequest();
  	}
	else
  	{// code for IE6, IE5
  		xmlhttp1=new ActiveXObject("Microsoft.XMLHTTP");
  	}
	xmlhttp1.onreadystatechange=function()
  	{
		document.getElementById("loading").style.visibility = "visible";
  		if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
    	{
    		document.getElementById(divId).innerHTML=xmlhttp1.responseText;
			document.getElementById("loading").style.visibility = "hidden";
    	}else if (xmlhttp1.readyState==4 && xmlhttp1.status != 200){
			var errorText = "<span style='text-align:center; color:#999999; font-size:24px;margin-left:10%'>Connection Failed</span>";
			document.getElementById(divId).innerHTML=errorText;
			document.getElementById("loading").style.visibility = "hidden";
		}
		//document.getElementById("loading").style.visibility = "hidden";
  	}
	//document.getElementById("loading").style.visibility = "visible";
	
	xmlhttp1.open("GET",urlstring,true);
	xmlhttp1.send();  
	//window.location.href = s;
}

function inbox(){
	var divId="Center_Content";
	var msg="";
	document.getElementById(divId).innerHTML = "";
	 msg= document.getElementById(divId).innerHTML;
          msg += "Inbox";
          document.getElementById(divId).innerHTML = msg;
	}
function reports(){
	var divId="Center_Content";
	var msg="";
	document.getElementById(divId).innerHTML = "";
	 msg= document.getElementById(divId).innerHTML;
          msg += "Reports";
          document.getElementById(divId).innerHTML = msg;
	}
function log(){
	var divId="Center_Content";
	var msg="";
	document.getElementById(divId).innerHTML = "";
	 msg= document.getElementById(divId).innerHTML;
          msg += "Logout";
          document.getElementById(divId).innerHTML = msg;
	}
	
function show_calendar(str_target, str_date) {
	//alert(str_date);
	var strtarget = "'"+str_target+"'";
	//alert(strtarget);
	str_datetime = str_date; //document.getElementById(str_date).value;
	//alert(str_datetime);
  var arr_months = ["January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"];
  var week_days = ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"];
  var n_weekstart = 1; // day week starts from (normally 0 or 1)

  var dt_datetime = (str_datetime == null || str_datetime =="" ?  new Date() : str2dt(str_datetime));
  var dt_prev_month = new Date(dt_datetime);
  dt_prev_month.setMonth(dt_datetime.getMonth()-1);
  var dt_next_month = new Date(dt_datetime);
  dt_next_month.setMonth(dt_datetime.getMonth()+1);
  var dt_firstday = new Date(dt_datetime);
  dt_firstday.setDate(1);
  dt_firstday.setDate(1-(7+dt_firstday.getDay()-n_weekstart)%7);
  var dt_lastday = new Date(dt_next_month);
  dt_lastday.setDate(0);
  
  //alert(document.cal.time.value);
  // html generation (feel free to tune it for your particular application)
  // print calendar header
  var str_buffer = new String (
    "<html>\n"+
    "<head>\n"+
    "  <title>Calendar</title>\n"+
    "</head>\n"+
    "<body bgcolor=\"White\">\n"+
    "<table class=\"clsOTable\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n"+
    "<tr><td bgcolor=\"#4682B4\">\n"+
    "<table cellspacing=\"1\" cellpadding=\"3\" border=\"0\" width=\"100%\">\n"+
    "<tr>\n  <td bgcolor=\"#4682B4\"><a href=\"javascript:window.opener.show_calendar('"+
    str_target+"', '"+ dt2dtstr(dt_prev_month)+"'+document.cal.time.value);\">"+
    "<img src=\"images/prev.gif\" width=\"16\" height=\"16\" border=\"0\""+
    " alt=\"previous month\"></a></td>\n"+
    "  <td bgcolor=\"#4682B4\" colspan=\"5\">"+
    "<font color=\"white\" face=\"tahoma, verdana\" size=\"2\">"
    +arr_months[dt_datetime.getMonth()]+" "+dt_datetime.getFullYear()+"</font></td>\n"+
    "  <td bgcolor=\"#4682B4\" align=\"right\"><a href=\"javascript:window.opener.show_calendar('"
    +str_target+"', '"+dt2dtstr(dt_next_month)+"'+document.cal.time.value);\">"+
    "<img src=\"images/next.gif\" width=\"16\" height=\"16\" border=\"0\""+
    " alt=\"next month\"></a></td>\n</tr>\n"
  );

  var dt_current_day = new Date(dt_firstday);
  // print weekdays titles
  str_buffer += "<tr>\n";
  for (var n=0; n<7; n++)
    str_buffer += "  <td bgcolor=\"#87CEFA\">"+
    "<font color=\"white\" face=\"tahoma, verdana\" size=\"2\">"+
    week_days[(n_weekstart+n)%7]+"</font></td>\n";
  // print calendar table
  str_buffer += "</tr>\n";
  while (dt_current_day.getMonth() == dt_datetime.getMonth() ||
    dt_current_day.getMonth() == dt_firstday.getMonth()) {
    // print row heder
    str_buffer += "<tr>\n";
    for (var n_current_wday=0; n_current_wday<7; n_current_wday++) {
        if (dt_current_day.getDate() == dt_datetime.getDate() &&
          dt_current_day.getMonth() == dt_datetime.getMonth())
          // print current date
          str_buffer += "  <td bgcolor=\"#FFB6C1\" align=\"right\">";
        else if (dt_current_day.getDay() == 0 || dt_current_day.getDay() == 6)
          // weekend days
          str_buffer += "  <td bgcolor=\"#DBEAF5\" align=\"right\">";
        else
          // print working days of current month
          str_buffer += "  <td bgcolor=\"white\" align=\"right\">";

        if (dt_current_day.getMonth() == dt_datetime.getMonth())
          // print days of current month
          str_buffer += "<a href=\"javascript:window.opener."+str_target+
          ".value='"+dt2dtstr(dt_current_day)+"'+document.cal.time.value; window.close();\">"+
          "<font color=\"black\" face=\"tahoma, verdana\" size=\"2\">";
        else 
          // print days of other months
          str_buffer += "<a href=\"javascript:window.opener."+str_target+
          ".value='"+dt2dtstr(dt_current_day)+"'+document.cal.time.value; window.close();\">"+
          "<font color=\"gray\" face=\"tahoma, verdana\" size=\"2\">";
        str_buffer += dt_current_day.getDate()+"</font></a></td>\n";
        dt_current_day.setDate(dt_current_day.getDate()+1);
    }
	
    // print row footer
    str_buffer += "</tr>\n";
	//alert(str_buffer);
  }
  // print calendar footer
  str_buffer +=
    "<form name=\"cal\">\n<tr><td colspan=\"7\" bgcolor=\"#87CEFA\">"+
    "<font color=\"White\" face=\"tahoma, verdana\" size=\"2\">"+
    "Time: <input type=\"text\" name=\"time\" value=\""+
    "\" size=\"8\" maxlength=\"8\"></font></td></tr>\n</form>\n" +
    "</table>\n" +
    "</tr>\n</td>\n</table>\n" +
    "</body>\n" +
    "</html>\n";

  var vWinCal = window.open("", "Calendar", 
    "width=200,height=250,status=no,resizable=yes,top=200,left=200");
  vWinCal.opener = self;
  var calc_doc = vWinCal.document;
  calc_doc.write (str_buffer);
  calc_doc.close();
}
// datetime parsing and formatting routimes. modify them if you wish other datetime format
function str2dt (str_datetime) {
  //var re_date = /^(\d+)\-(\d+)\-(\d+)\s+(\d+)\:(\d+)\:(\d+)$/;
  var re_date = /^(\d+)\-(\d+)\-(\d+)/;
  if (!re_date.exec(str_datetime))
    return alert("Invalid Datetime format: "+ str_datetime);
  return (new Date (RegExp.$3, RegExp.$2-1, RegExp.$1));
}
function dt2dtstr (dt_datetime) {
  return (new String (
      dt_datetime.getDate()+"-"+(dt_datetime.getMonth()+1)+"-"+dt_datetime.getFullYear()+" "));
}
function dt2tmstr (dt_datetime) {
  return (new String (
      dt_datetime.getHours()+":"+dt_datetime.getMinutes()+":"+dt_datetime.getSeconds()));
}

var expandFirstItemAutomatically = false;	// Expand first menu item automatically ?
var initMenuIdToExpand = false;	// Id of menu item that should be initially expanded. the id is defined in the <li> tag.
var expandMenuItemByUrl = true;	// Menu will automatically expand by url - i.e. if the href of the menu item is in the current location, it will expand
var initialMenuItemAlwaysExpanded = true;	// NOT IMPLEMENTED YET




var dhtmlgoodies_slmenuObj;
var divToScroll = false;
var ulToScroll = false;
var divCounter = 1;
var otherDivsToScroll = new Array();
var divToHide = false;
var parentDivToHide = new Array();
var ulToHide = false;
var offsetOpera = 0;
if(navigator.userAgent.indexOf('Opera')>=0)offsetOpera=1;
var slideMenuHeightOfCurrentBox = 0;
var objectsToExpand = new Array();
var initExpandIndex = 0;
var alwaysExpanedItems = new Array();

var dg_activeItem = null;

function popMenusToShow()
{
	
	var obj = divToScroll;
	var endArray = new Array();
	while(obj && obj.tagName!='BODY'){
		if(obj.tagName=='DIV' && obj.id.indexOf('slideDiv')>=0){
			var objFound = -1;
			for(var no=0;no<otherDivsToScroll.length;no++){
				if(otherDivsToScroll[no]==obj){
					objFound = no;
				}
			}
			
			if(objFound>=0){
				
				otherDivsToScroll.splice(objFound,1);
				//alert("poplength:"+otherDivsToScroll.length);
			}
			
		}
		obj = obj.parentNode;
	}
}
// Build the message by reading a JS object in a recursive manner
function showObject( obj )
{

  var txt = "<table>";
  try { 
  //while (( item = obj.getNext()) != undefined ){
     for(var key in obj) {
		 
        txt +=  "<tr> <td>" + key + ":" +"</td>";
        txt += "<td>" + obj[key]  + "</td></tr>";
		//txt +=  "Key="+key + ":" +"";
        //txt += "Val=" + obj[key]  + "\n";
        //txt += '<BR/>';
      }
  //}
  txt += "</table>";
  
  }catch (e)
  {
    alert("showObject: " + e);
  }
  /*try { 
    if ( typeof obj != 'object' )
      return "<td>" + obj + "</td></tr>";
    else {
      for(var key in obj) {
        txt +=  "<tr> <td>" + key + ":" +"</td>";
        txt += showObject( obj[key] );
        //txt += '<BR/>';
      }
      txt += "</table>";
    }
  }
  catch (e)
  {
    alert("showObject: " + e);
  }*/
  return txt;
}

function showSubMenu(e,inputObj)
{
//alert("step0");
	if(this && this.tagName)inputObj = this.parentNode;
	//alert("Length of otherdiv:"+otherDivsToScroll.length);
	if(inputObj && inputObj.tagName=='LI'){
		divToScroll = inputObj.getElementsByTagName('DIV')[0];
		 //alert("step10.1 ="+divToScroll.value );
		/*var txt="----divToScroll"+divToScroll.length;

				 txt+= showObject(divToScroll);

		txt+="----otherDivToScroll";
				for(var no=0;no<otherDivsToScroll.length;no++){
				 txt+= showObject(otherDivsToScroll[no]);
				 txt+="-------------------------------------";
				}
			//document.getElementById('Empty').innerHTML = txt;*/
		for(var no=0;no<otherDivsToScroll.length;no++){
			
			if(otherDivsToScroll[no]==divToScroll && no==0)
			{
				hidingInProcess = false;
				//alert("step44");
				/*if(otherDivsToScroll.length>0){
					alert("popmenu-start");
					popMenusToShow();
					alert("popmenu-end");
					}
					alert("popmenu-out"+otherDivsToScroll.length);
					*/
				if(otherDivsToScroll.length>0){
					//alert("autohide-start");
					var hideSameDiv=true;
					autoHideMenus(hideSameDiv);
					hidingInProcess = true;
				//alert("step55");
				}
				return;
			}
			//alert("step0-1"+otherDivsToScroll.length);
		}
		
	}
	hidingInProcess = false;
	//alert("Length="+otherDivsToScroll.length+" "+"DIVTOSCROLL:"+divToScroll);
	if(otherDivsToScroll.length>0){
		//alert("step1");
		if(divToScroll){
			//alert("step2");
			if(otherDivsToScroll.length>0){
				popMenusToShow();
				//alert("step3");
			}
			if(otherDivsToScroll.length>0){
				//alert("step4");
				var hideSameDiv=false;
				autoHideMenus(hideSameDiv);
				
				hidingInProcess = true;
				//alert("step5");
			}
			
		}
	}
	if(divToScroll && !hidingInProcess){
		//alert("step6");
		divToScroll.style.display='';
		otherDivsToScroll.length = 0;
		otherDivToScroll = divToScroll.parentNode;
		otherDivsToScroll.push(divToScroll);
		//alert("step7"+" "+"divtoscroll:"+divToScroll);
		while(otherDivToScroll && otherDivToScroll.tagName!='BODY'){
			if(otherDivToScroll.tagName=='DIV' && otherDivToScroll.id.indexOf('slideDiv')>=0){
				otherDivsToScroll.push(otherDivToScroll);
				//alert("step8");

			}
			otherDivToScroll = otherDivToScroll.parentNode;
		}
		ulToScroll = divToScroll.getElementsByTagName('UL')[0];
		//alert("step9");
		if(divToScroll.style.height.replace('px','')/1<=1)scrollDownSub();
		//alert("step9.1");
	}

	if(e || inputObj) {

		if(dg_activeItem) {
			dg_activeItem.className = dg_activeItem.className.replace('dhtmlgoodies_activeItem','');
			//alert("step10");
		}
		var aTags = inputObj.getElementsByTagName('A');
		if(aTags.length>0) {
			aTags[0].className = aTags[0].className + ' dhtmlgoodies_activeItem';
			dg_activeItem = aTags[0];
			
			if(aTags[0].href.indexOf('#') == -1 || aTags[0].href.indexOf('#') < aTags[0].href.length-1){
				return true;
				//alert("step11");
			}
		}

	}

	return false;
//alert("step12");

}



function autoHideMenus(hideSameDiv)
{
	if(otherDivsToScroll.length>0){
		if(hideSameDiv==true){
			divToHide = otherDivsToScroll[0];
		}else{
		    divToHide = otherDivsToScroll[otherDivsToScroll.length-1];
		}
		parentDivToHide.length=0;
		var obj = divToHide.parentNode.parentNode.parentNode;
		while(obj && obj.tagName=='DIV'){
			if(obj.id.indexOf('slideDiv')>=0)parentDivToHide.push(obj);
			obj = obj.parentNode.parentNode.parentNode;
		}
		var tmpHeight = (divToHide.style.height.replace('px','')/1 - slideMenuHeightOfCurrentBox);
		if(tmpHeight<0)tmpHeight=0;
		if(slideMenuHeightOfCurrentBox)divToHide.style.height = tmpHeight  + 'px';
		ulToHide = divToHide.getElementsByTagName('UL')[0];
		slideMenuHeightOfCurrentBox = ulToHide.offsetHeight;
		scrollUpMenu();
	}else{
		slideMenuHeightOfCurrentBox = 0;
		showSubMenu();
	}
}


function scrollUpMenu()
{

	var height = divToHide.offsetHeight;
	//alert("Height:"+height);
	height-=15;
	if(height<0)height=0;
	divToHide.style.height = height + 'px';
	
	for(var no=0;no<parentDivToHide.length;no++){
		parentDivToHide[no].style.height = parentDivToHide[no].getElementsByTagName('UL')[0].offsetHeight + 'px';
	}
	if(height>0){
		setTimeout('scrollUpMenu()',5);
		//alert("scrollupmenu-if");
	}else{
		//alert("scrollupmenu-else");
		var SameDiv=false;
		if(otherDivsToScroll[otherDivsToScroll.length-1]==divToScroll){
			//alert("same");
			SameDiv=true;
		}
		divToHide.style.display='none';
		otherDivsToScroll.length = otherDivsToScroll.length-1;
		if(!SameDiv)
			{
				autoHideMenus();
			}
	}
}

function scrollDownSub()
{
	if(divToScroll){
		var height = divToScroll.offsetHeight/1;
		var offsetMove =Math.min(15,(ulToScroll.offsetHeight - height));
		height = height +offsetMove ;
		divToScroll.style.height = height + 'px';

		for(var no=1;no<otherDivsToScroll.length;no++){
			var tmpHeight = otherDivsToScroll[no].offsetHeight/1 + offsetMove;
			otherDivsToScroll[no].style.height = tmpHeight + 'px';
		}
		if(height<ulToScroll.offsetHeight)setTimeout('scrollDownSub()',5); else {
			divToScroll = false;
			ulToScroll = false;
			if(objectsToExpand.length>0 && initExpandIndex<(objectsToExpand.length-1)){
				initExpandIndex++;

				showSubMenu(false,objectsToExpand[initExpandIndex]);
			}
		}
	}
}

function initSubItems(inputObj,currentDepth)
{
	divCounter++;
	var div = document.createElement('DIV');	// Creating new div
	div.style.overflow = 'hidden';
	div.style.position = 'relative';
	div.style.display='none';
	div.style.height = '1px';
	div.id = 'slideDiv' + divCounter;
	div.className = 'slideMenuDiv' + currentDepth;
	inputObj.parentNode.appendChild(div);	// Appending DIV as child element of <LI> that is parent of input <UL>
	div.appendChild(inputObj);	// Appending <UL> to the div
	var menuItem = inputObj.getElementsByTagName('LI')[0];
	while(menuItem){
		if(menuItem.tagName=='LI'){
			var aTag = menuItem.getElementsByTagName('A')[0];
			aTag.className='slMenuItem_depth'+currentDepth;
			var subUl = menuItem.getElementsByTagName('UL');
			if(subUl.length>0){
				initSubItems(subUl[0],currentDepth+1);
			}
			aTag.onclick = showSubMenu;
		}
		menuItem = menuItem.nextSibling;
	}
}

function initSlideDownMenu()
{
	dhtmlgoodies_slmenuObj = document.getElementById('dhtmlgoodies_slidedown_menu');
	dhtmlgoodies_slmenuObj.style.visibility='visible';
	var mainUl = dhtmlgoodies_slmenuObj.getElementsByTagName('UL')[0];

	var mainMenuItem = mainUl.getElementsByTagName('LI')[0];
		
	mainItemCounter = 1;
	while(mainMenuItem){
		if(mainMenuItem.tagName=='LI'){
			var aTag = mainMenuItem.getElementsByTagName('A')[0];
			
			aTag.className='slMenuItem_depth1';
			var subUl = mainMenuItem.getElementsByTagName('UL');
			if(subUl.length>0){
				mainMenuItem.id = 'mainMenuItem' + mainItemCounter;
				initSubItems(subUl[0],2);
				aTag.onclick = showSubMenu;
				mainItemCounter++;
			}
		}
		mainMenuItem = mainMenuItem.nextSibling;
	}	

	if(location.search.indexOf('mainMenuItemToSlide')>=0){
		var items = location.search.split('&');
		for(var no=0;no<items.length;no++){
			if(items[no].indexOf('mainMenuItemToSlide')>=0){
				values = items[no].split('=');
				//alert("show1");
				showSubMenu(false,document.getElementById('mainMenuItem' + values[1]));
				initMenuIdToExpand = false;
			}
		}
	}else if(expandFirstItemAutomatically>0		){
		if(document.getElementById('mainMenuItem' + expandFirstItemAutomatically)){
			//alert("show2");
			showSubMenu(false,document.getElementById('mainMenuItem' + expandFirstItemAutomatically));
			initMenuIdToExpand = false;
		}
	}


	if(expandMenuItemByUrl)
	{
		var aTags = dhtmlgoodies_slmenuObj.getElementsByTagName('A');
		var currentLocation = location.pathname;
		for(var no=0;no<aTags.length;no++){
			var hrefToCheckOn = aTags[no].href;
			if(hrefToCheckOn.indexOf(currentLocation)>=0 && hrefToCheckOn.indexOf('#')<hrefToCheckOn.length-1){
				initMenuIdToExpand = false;
				var obj = aTags[no].parentNode;
				while(obj && obj.id!='dhtmlgoodies_slidedown_menu'){
					if(obj.tagName=='LI'){
						var subUl = obj.getElementsByTagName('UL');
						if(initialMenuItemAlwaysExpanded)alwaysExpanedItems[obj.parentNode] = true;
						if(subUl.length>0){
							objectsToExpand.unshift(obj);
						}
					}
					obj = obj.parentNode;
				}
				//alert("show3");
				showSubMenu(false,objectsToExpand[0]);
				break;
			}
		}
	}

	if(initMenuIdToExpand)
	{
		objectsToExpand = new Array();
		var obj = document.getElementById(initMenuIdToExpand)
		while(obj && obj.id!='dhtmlgoodies_slidedown_menu'){
			if(obj.tagName=='LI'){
				var subUl = obj.getElementsByTagName('UL');
		
				if(initialMenuItemAlwaysExpanded)alwaysExpanedItems[obj.parentNode] = true;
				if(subUl.length>0){
					objectsToExpand.unshift(obj);
				}
			}
			obj = obj.parentNode;
		}
		//alert("show4");
		showSubMenu(false,objectsToExpand[0]);

	}

}

/*******************************Validation****************************************************/
/*******************************Validation****************************************************/

//template validate

function templateValidate(){
	if(document.frmLead.cmbtemplate.value==""){
		alert("Please choose one of the template");
		return false;
	}
	return true;
}

//to Validate invoice number in Sandbox

function InvoiceAccept(){
	//alert("Accept");
	//alert(document.frmInventDetails.txtinvoiceno.value);
	if(document.frmInvAccept.txtinvoiceno.value==""){
		alert("Please enter the Invoice number");
		return false;
	}
	return true;
}

//Purchase validate
function purchase_validate(){
	if(document.products.cboSupplier.value=="" || document.products.cboSupplier.value=="0"){
		alert("please choose the Supplier Name");
		return false;
	}else if(document.products.purc_billno.value=="" || document.products.cboSupplier.value=="0"){
		alert("please enter the bill number");
		return false;
	}else if(document.products.record_found.value==""){
		alert("please enter products");
		return false;
	}
	return true;
}

//Email Settings validate

function esetting_validate(){
	if(document.company_validate.host_name.value=="" || document.company_validate.host_name.value=="0"){
		alert("please enter host name");
		return false;
	}else if(document.company_validate.port_no.value=="" || document.company_validate.port_no.value=="0"){
		alert("please enter port number");
		return false;
	}else if(document.company_validate.user_name.value==""){
		alert("please enter user name");
		return false;
	}else if(document.company_validate.user_password.value==""){
		alert("please enter password");
		return false;
	}else if(document.company_validate.from_mail.value==""){
		alert("please enter from email");
		return false;
	}else if(document.company_validate.from_name.value==""){
		alert("please enter from name");
		return false;
	}
	return true;
}


//New order book validate
function newOrderValidate(){
	if(document.frmNewOrderBooking.txtremark.value==""){
		alert("Please enter the remarks");
		return false;
	}
	if(document.frmNewOrderBooking.cboRegion.selectedIndex == 0){
		alert("Please select the region");
		return false;
	}
	if(document.frmNewOrderBooking.cboDistributor.selectedIndex == 0){
		alert("Please select the Distributor");
		return false;
	}
	if(document.frmNewOrderBooking.cboSalesman.selectedIndex == 0){
		alert("Please select the Salesman");
		return false;
	}
	return true;
}
//New Product Validation in OrderBooking

function productValidate(){
	if(document.frmnewAddProduct.txtremark.value==""){
		alert("Please enter the remarks");
		return false;
	}
	return true;
}
	
//Designation
function designation_validate(){
	//alert("Validation");
	//valid = true;

	if(document.designation.design_name.value==""){
		alert("Please enter the designation name");
		return false;
		//alert(valid);
		}
	if(document.designation.levels.value==""){
		alert("Please enter the Level");
		return false;
		//alert(valid);
	}
	return true ;
	//alert(valid);
}

function invDetailValidate(){
	if(document.frmInventDetails.remark.value==""){
		alert("Please enter the remark")
		return false;
	}
	return true;
}

function orderDetailValidate(){
	if(document.frmOrderDetails.remark.value==""){
		alert("Please enter the remark")
		return false;
	}
	return true;
}	

//Lead Payment Validate
function lead_payment_validate(){
	if(document.frmLead.cmbpaytype.value==""){
		alert("Please select payment type");
		return false;
	}
	if(document.frmLead.cmbpaymethod.value==""){
		alert("Please select payment type");
		return false;
	}
	if(document.frmLead.cmbpaymethod.value!="" && document.frmLead.cmbpaymethod.value!="cash"){
		if(document.frmLead.paymentnumber.value==""){
			alert("Please enter cheque/dd number");
			return false;
		}
		if(document.frmLead.bankinfo.value==""){
			alert("Please enter bank info");
			return false;
		}
	}
	if(document.frmLead.paymentamount.value=="" || document.frmLead.paymentamount.value =="0"){
		alert("Please enter payment amount");
		return false;
	}
	return true;
}
//Employee
function employee_validate(){
	if(document.employee.emp_fname.value==""){
		alert("Please enter the first name");
		return false;
		//alert(valid);
		}
	
	if(document.employee.emp_dob.value==""){
		alert("Please enter the date of birth");
		return false;
		//alert(valid);
		}
	if(document.employee.emp_address.value==""){
		alert("Please enter the address");
		return false;
		//alert(valid);
		}
	if(document.employee.emp_doj.value==""){
		alert("Please enter the date of joining");
		return false;
		//alert(valid);
		}
	if(document.employee.emp_dest.selectedIndex == 0){
		alert("Please choose the designation");
		return false;
		//alert(valid);
		}
		var x=document.employee.emp_email.value;
		var atpos=x.indexOf("@");
        var dotpos=x.lastIndexOf(".");
	if(atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length){
		alert("Please enter  the valid E-mail");
		return false;
		//alert(valid);
		}
		var mobno=document.employee.emp_mobno.value;
	if(isNaN(mobno)||mobno.indexOf(" ")!=-1){
		alert("Please enter the valid mobile no");
		return false;
		//alert(valid);
		}
		if(mobno.length<10){
			alert("Mobile no should contain 10 digits ");
			return false;
			}
		
		return true ;
	}
	
//User
function user_validate(){
	if(document.user.user_name.value==""){
		alert("Please enter the user name");
		return false;
		//alert(valid);
	}
	if(document.user.pass_word.value==""){
		alert("Please enter the password");
		return false;
		//alert(valid);
	}
	var imeino=document.user.imei_no.value;
	if(isNaN(imeino)||imeino.indexOf(" ")!=-1){
		alert("Please enter the valid imei no");
		return false;
		//alert(valid);
	}
	if(imeino.length<15){
		alert("IMEI no should contain 15 digits");
		return false;
	}
	if(document.user.design_name.selectedIndex==0){
		alert("Please choose the Display Name");
		return false;
		//alert(valid);
	}
	
	if(document.user.emp_name.selectedIndex==0){
		alert("Please choose the Display Name");
		return false;
		//alert(valid);
	}
	return true ;
}

//Category Type
function cat_type_validate(){
	if(document.category.cat_type.value==""){
		alert("Please enter the category type name");
		return false;
		//alert(valid);
	}
	
	return true ;
}

//Category
function cat_validate(){
	/*if(document.category.cat_type.value=="0"){
		alert("Please choose the category type");
		return false;
		//alert(valid);
	}*/
	if(document.category.cat_name.value==""){
		alert("Please enter the category name");
		return false;
		//alert(valid);
	}
	
	/*var tax=document.category.cat_tax.value;
	if(tax==""){
		alert("Please enter the category tax");
		return false;
		//alert(valid);
	}
	if(isNaN(tax)||tax.indexOf(" ")!=-1){
		alert("Please enter the valid tax");
		return false;
		//alert(valid);
	}
	
	
	if(tax.length>2){
		alert("Tax should contain 2 digits");
		return false;
	}*/
	return true ;
}

//Unit
function unit_validate(){
	
	if(document.category.unit_name.value==""){
		alert("Please enter the unit name");
		return false;
		//alert(valid);
	}
	
	
	return true;
}

//Task
function task_validate(){
	
	if(document.category.task_name.value==""){
		alert("Please enter the task name");
		return false;
		//alert(valid);
	}
	
	
	return true;
}

//Task Lead
function task_lead_validate(){
	
	if(document.frmLead.cmbtaskname.value=="" || document.frmLead.cmbtaskname.value=="0"){
		alert("Please select task name");
		return false;
		//alert(valid);
	}else if(document.frmLead.txttasksummary.value==""){
		alert("Please enter the summary");
		return false;
	}
	return true;
}
	
//Product
function prod_validate(){
	if(document.products.cboCategory.selectedIndex == 0){
		alert("Please choose the category");
		return false;
		}
	if(document.products.cboUnit.selectedIndex == 0){
		alert("Please choose the unit");
		return false;
		}
	if(document.products.prod_name.value==""){
		alert("Please enter the product name");
		return false;
		}
	
		var price=document.products.prod_price.value;
	if(price==""){
		alert("Please enter the product price");
		return false;
		}
		if(isNaN(price)||price.indexOf(" ")!=-1){
		alert("Please enter the numeric value of product price");
		return false;
		//alert(valid);
		}
		var discount=document.products.prod_discount.value;
	if(discount==""){
		alert("Please enter the discount");
		return false;
		}
		if(isNaN(discount)||discount.indexOf(" ")!=-1){
		alert("Please enter the numeric value of discount");
		return false;
		//alert(valid);
		}
		return true;
	}
//regions validation
function regionvalidate(){
	if(document.regions_validate.region_name.value==""){
   		alert("please enter the region code");
   		return false;
 	}

 	return true;
}
 
 //area validation
 function areavalidate(){
 if(document.area_validate.region_id.selectedIndex==0){
  alert("please select the region name");
  return false;
 }
 if(document.area_validate.area_name.value==""){
   alert("please enter the area code");
   return false;
 }
 

 return true;
 }
 
 //route validation
 function routevalidate(){
 
 if(document.route_validate.cboRegion.selectedIndex==0){
  alert("please select the region");
  return false;
 }
 if(document.route_validate.cboArea.selectedIndex==0){
  alert("please select the area");
  return false;
 }
 if(document.route_validate.route_code.value==""){
 alert("please enter the route code");
 return false;
 }
 if(document.route_validate.route_name.value==""){
 alert("please enter the route name");
 return false;
 }
 
 return true;
 }
 
 //tour validation
function tourplanvalidate(){
 	if(document.tourplan_validate.rpname.value==""){
 		alert("please enter the tourplan");
 		return false;
 	}
 	if(document.tourplan_validate.rpid.value==""){
 		alert("please enter the tourplan id");
 		return false;
 	}
 	return true;
}
 
 //distributor validation
function distributorvalidate(){
	if(document.distributor_validate.region_code.selectedIndex==0){
 		alert("please select the region code");
 		return false;
 	}
 	if(document.distributor_validate.area_code.selectedIndex==0){
 		alert("please select the area code");
 		return false;
 	}
 	if(document.distributor_validate.dist_name.value==""){
 		alert("please enter the distributor name");
 		return false;
 	}
  	if(document.distributor_validate.dist_tin.value==""){
 		alert("please enter the distributor tin number");
 		return false;
 	}
  	if(document.distributor_validate.dist_address.value==""){
 		alert("please enter the distributor address");
 		return false;
 	}
  	if(document.distributor_validate.dist_city.value==""){
 		alert("please enter the distributor city");
 		return false;
 	}
 	var pincode=document.distributor_validate.dist_pin.value;
	 ///^ *[0-9]+ *$/.test(str)
	 //strng.search(/[0-9]+/) > -1)
 	var testdegit = /^[0-9]$/;
  	if(pincode==""){
   		alert("please enter the  pincode");
   		return false;
 	}
 
 	if(isNaN(pincode)||pincode.indexOf(" ")!=-1){
    	alert("please enter the valid pincode number")
      	return false;
    }
   	if(document.distributor_validate.dist_state.value==""){
    	alert("please enter the distributor state");
    	return false;
 	}
   	if(document.distributor_validate.dist_cperson.value==""){
    	alert("please enter the distributor contact person");
    	return false;
 	}
 
    
	var mobno =document.distributor_validate.dist_mobno.value;
   	if(mobno==""){
   		alert("please enter the mobile number");
    	return false;
    }
    if(isNaN(mobno)||mobno.indexOf(" ")!=-1){
    	alert("please enter the valid mobile number")
      	return false;
    }
    if (mobno.length<10){
    	alert("mobile no should contain 10 digits");
       	return false;
    }
    return true;
}
 
//outlet validation 
function outletvalidate(){
 	if(document.outlet_validate.cboRegion.selectedIndex==0){
 		alert("please select the region");
 		return false;
 	}

  	if(document.outlet_validate.cboDistributor.selectedIndex==0){
 		alert("please select the distributor");
 		return false;
 	}
  	if(document.outlet_validate.cboRoute.selectedIndex==0){
 		alert("please select the route");
 		return false;
 	}
  	if(document.outlet_validate.outlet_name.value==""){
 		alert("please enter the outlet name");
 		return false;

 	}
   	if(document.outlet_validate.outlet_address.value==""){
 		alert("please enter the outlet address");
 		return false;
 	}
    if(document.outlet_validate.outlet_city.value==""){
 		alert("please enter the outlet city");
 		return false;
 	}
    if(document.outlet_validate.outlet_cperson.value==""){
 		alert("please enter the outlet contact person");
 		return false;
 	}
    var mobileno=document.outlet_validate.outlet_mobno.value;
    if(mobileno==""){
      	alert("please enter the outlet mobile number");
    	return false;
    }
    if(isNaN(mobileno)||mobileno.indexOf(" ")!=-1){
      	alert("please enter the valid mobile number")
    	return false;
	}
    if (mobileno.length<10){
       	alert("mobile no should contain 10 digits");
    	return false;
	}
	return true;
}


//info validation
function infovalidate(){
	if(document.customer_validate.info_name.value==""){
 		alert("please enter the template name");
 		return false;

 	}
	if(document.customer_validate.info_desc.value==""){
 		alert("please enter the general terms description");
 		return false;

 	}
	return true;
}


//customer validation 
function customervalidate(){
	
 	/*if(document.customer_validate.cboRegion.selectedIndex==0){
 		alert("please select the region");
 		return false;
 	}

  	if(document.customer_validate.cboDistributor.selectedIndex==0){
 		alert("please select the distributor");
 		return false;
 	}
  	if(document.customer_validate.cboRoute.selectedIndex==0){
 		alert("please select the route");
 		return false;
 	}*/
  	if(document.customer_validate.customer_name.value==""){
 		alert("please enter the customer name");
 		return false;

 	}
   	if(document.customer_validate.customer_address.value==""){
 		alert("please enter the customer address");
 		return false;
 	}
    if(document.customer_validate.customer_city.value==""){
 		alert("please enter the customer city");
 		return false;
 	}
    if(document.customer_validate.customer_cperson.value==""){
 		alert("please enter the ocontact person");
 		return false;
 	}
    var mobileno=document.customer_validate.customer_mobno.value;
    if(mobileno==""){
      	alert("please enter the mobile number");
    	return false;
    }
    if(isNaN(mobileno)||mobileno.indexOf(" ")!=-1){
      	alert("please enter the valid mobile number")
    	return false;
	}
    if (mobileno.length<10){
       	alert("mobile no should contain 10 digits");
    	return false;
	}
	return true;
}

function companyvalidate(){
	if(document.company_validate.comp_name.value==""){
 		alert("please enter the company name");
 		return false;

 	}
   	if(document.company_validate.comp_add.value==""){
 		alert("please enter the company address");
 		return false;
 	}
    if(document.company_validate.comp_city.value==""){
 		alert("please enter the city");
 		return false;
 	}
	if(document.company_validate.comp_zip.value==""){
 		alert("please enter the zip");
 		return false;
 	}
    if(document.company_validate.comp_cperson.value==""){
 		alert("please enter the contact person");
 		return false;
 	}
    var mobileno=document.company_validate.comp_mobile.value;
    if(mobileno==""){
      	alert("please enter the mobile number");
    	return false;
    }
    if(isNaN(mobileno)||mobileno.indexOf(" ")!=-1){
      	alert("please enter the valid mobile number")
    	return false;
	}
    if (mobileno.length<10){
       	alert("mobile no should contain 10 digits");
    	return false;
	}
	return true;
	
}


function reject_validate(){
	if(document.frmLead.remark.value==""){
 		alert("please enter the reason");
 		return false;
 	}	
	return true;
}


//lead validation
function lead_validate(){
	
	if(document.frmLead.cust_name.value==""){
 		alert("please enter the customer name");
 		return false;
 	}	
	
	if(document.frmLead.cust_add.value==""){
 		alert("please enter the customer addres");
 		return false;
 	}	
	
	if(document.frmLead.cust_city.value==""){
 		alert("please enter the customer city");
 		return false;
 	}	
	
	if(document.frmLead.cust_cperson.value==""){
 		alert("please enter the contact person");
 		return false;
 	}	
	
	if(document.frmLead.cust_mobno.value==""){
 		alert("please enter the mobile number");
 		return false;
 	}
	
	
	if(document.frmLead.lead_date.value==""){
		alert("please enter the lead date");
		return false;
	}
	
	if(document.frmLead.prod_desc.value==""){
		alert("please enter the product description");
		return false;
	}
	
	if(document.frmLead.ref_by.value=="0" || document.frmLead.ref_by.value==""){
		alert("please select the Referred By");
		return false;
	}
	
	
	
	if(document.frmLead.follow_up_date.value==""){
		alert("please enter follow up date");
		return false;
	}
	
	return true;
}
//vendor validation
function vendorvalidate(){
	
 	
 	if(document.vendor_validate.supp_name.value==""){
 		alert("please enter the vendor name");
 		return false;
 	}
  	
  	if(document.vendor_validate.supp_address.value==""){
 		alert("please enter the vendor address");
 		return false;
 	}
  	if(document.vendor_validate.supp_city.value==""){
 		alert("please enter the vendor city");
 		return false;
 	}
 	var pincode=document.vendor_validate.supp_pin.value;
 	///^ *[0-9]+ *$/.test(str)
 	//strng.search(/[0-9]+/) > -1)
 	var testdegit = /^[0-9]$/;
  	if(pincode==""){
   		alert("please enter the  pincode");
   		return false;
 	}
 	if(isNaN(pincode)||pincode.indexOf(" ")!=-1){
      	alert("please enter the valid pincode number")
    	return false;
    }
   	if(document.vendor_validate.supp_state.value==""){
    	alert("please enter the state");
    	return false;
 	}
	
	if(document.vendor_validate.supp_country.value==""){
    	alert("please enter the country");
    	return false;
 	}
   	if(document.vendor_validate.supp_cperson.value==""){
    	alert("please enter the contact person");
    	return false;
 	}

    var mobno = document.vendor_validate.supp_mobno.value;
	//alert("Mobile No :"+mobno);
    if(mobno==""){
      	alert("please enter the mobile number");
    	return false;
    }
   
    if (mobno.length<10){
    	alert("mobile no should contain 10 digits");
       	return false;
    }
	return true;
}
 
 
//assignroute validation
function assignroute_validate(){

	if(document.assign_route.cboRegion.selectedIndex==0){
		alert("Please choose the region");
		return false;
		}
	
	if(document.assign_route.cboDistributor.selectedIndex==0){
		alert("Please choose the distributor");
		return false;
		}
	//if(document.assign_route.cboRoute.selectedIndex==0){
		//alert("Please choose the route");
		//return false;
		//}
	
	
	if(document.assign_route.cboSalesman.selectedIndex==0){
		alert("Please choose the salesman");
		return false;
		}
		return true;
}
	
//Invoice
function invoice_validate(){
	if(document.invoice.prefix.value==""){
		alert("Please enter the Invoice prefix");
		return false;
		}
		var invno=document.invoice.invno.value;
	if(invno==""){
		alert("Please enter the Invoice no");
		return false;
		}
	if(isNaN(invno)||invno.indexOf(" ")!=-1){
        alert("please enter the valid Invoice no")
        return false;
        }
		return true;
}

//assign TP
function assignrouteTP_validate(){

	if(document.assign_route.cboSalesman.selectedIndex==0){
		alert("Please choose the region");
		return false;
	}
	
	if(document.assign_route.fromdate.value==0 || document.assign_route.fromdate.value==""){
		alert("Please choose the from date");
		return false;
	}
	if(document.assign_route.cboDistributor.selectedIndex==0){
		alert("Please choose the distributor");
		return false;
	}
	if(document.assign_route.cboRoute.selectedIndex==0){
		alert("Please choose the route");
		return false;
	}
	return true;
}
	

	
	
//Cellid
	
function cell_validate(){
	var cellid=document.cell.cell_id.value;
	if(cellid==""){
		alert("Please enter the cell id");
		return false;
		}
		if(isNaN(cellid)||cellid.indexOf(" ")!=-1){
			alert("Please enter the numeric value of cell id");
			return false;
			}
	if(document.cell.loc_name.value==""){
		alert("Please enter the Location name");
		return false;
		}
	if(document.cell.city.value==""){
		alert("Please enter the city");
		return false;
		}
	if(document.cell.region.value==""){
		alert("Please enter the region");
		return false;
		}
		return true;
	}
	
	
//Opening stock
function openstock_validate(){
	if(document.open_stock.cboCategory.selectedIndex==0){
		alert("Please choose the category");
		return false;
		}
	if(document.open_stock.prod_name.selectedIndex==0){
		alert("Please choose the product name");
		return false;
		}
		var qty=document.open_stock.prod_qty.value;
	if(qty==""){
		alert("Please enter the product quantity");
		return false;
		}
		if(isNaN(qty)||qty.indexOf(" ")!=-1){
		alert("Please enter the numeric value of product quantity");
		return false;
		//alert(valid);
		}
		return true;
}

// JavaScript Document
function email_input_validate(){
	/*if(document.frmemail.name.value==""){
		alert("Please enter the name");
		return false;
	}*/
	if(document.frmemail.email_to.value==""){
		alert("Please enter to address");
		return false;
	}
	if(document.frmemail.subject.value==""){
		alert("Please enter to subject");
		return false;
	}
	var fup = document.getElementById('upload_filename');
	var fileName = fup.value;
	var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
	if(ext != "gif" || ext != "GIF" || ext != "JPEG" || ext != "jpeg" || ext != "jpg" || ext != "JPG")
	{
		alert("Please upload JPG or GIF or JPEG format image only");
		return false;
	} 
	
	return true;
}

function sendMail(){
	var name=document.frmemail.name.value;
	var to = document.frmemail.email_to.value;
	var subject = document.frmemail.subject.value;
	
}

function toggleEmail(layer_ref) {
 var ele = document.getElementById(layer_ref);
 
 if(ele.style.display == "block") {
    ele.style.display = "none";
 }else{
	ele.style.display = "block"; 
 }

}

function test(imgValue){
	//alert("Testing.."+imgValue);
	var imgelement = document.getElementById("bgimage");
	imgelement.src = imgValue;
}
	


function getSelectedItems(str){
	//alert("selva"+str);
	var items = "";
	var frm = document.getElementById("emailAddress");
	var len = frm.length;
	//alert("Length :"+len);
	for(i=0;i<len;i++){
        if(frm[i].selected){
			
            items += frm[i].value + ";";
        }
    }
	items = items.substr(0,items.length-1);
	
	if(str == "To"){
		document.getElementById("email_to").value = items;
	}else if(str == "Cc"){
		document.getElementById("email_cc").value = items;
	}else if(str == "Bcc"){
		document.getElementById("email_bcc").value=items;
	}else if(str == "Done"){
		var splits = items.split(";");
		var name="";
		var email ="";
		for(var i=0;i<splits.length;i++){
			var split_str = splits[i].split("-");
			name += split_str[0]+";";
			email += split_str[1]+";";
		}
		
		document.getElementById("hidden_email_to").value = email.substr(0,email.length-1);
		document.getElementById("email_to").value =  name.substr(0,name.length-1);
	}
    //return items;
}
function showCustomer(str){
	//alert("ShowCustomer :"+str);
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
  		alert ("Your browser does not support AJAX!");
  		return;
  	}

	var url="bulkmail/serverData.php";
	url=url+"?q="+str;
	//alert("url :"+url);
	//url=url+"&sid="+Math.random();
	//alert(url);

	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function stateChanged(){
	if (xmlHttp.readyState==4){
		
		document.getElementById("emailAddress").innerHTML=xmlHttp.responseText;
		//alert(document.getElementById("txtHint").innerHTML);
	}
}

function GetXmlHttpObject(){
	var xmlHttp=null;
	try {
  		// Firefox, Opera 8.0+, Safari
  		xmlHttp=new XMLHttpRequest();
  	}catch (e){
	  // Internet Explorer
  		try{
   		 xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
    	}catch (e){
   			xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
    	}
  	}	
	return xmlHttp;
}
