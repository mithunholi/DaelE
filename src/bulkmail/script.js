// JavaScript Document
function input_validate(){
	if(document.frmemail.name.value==""){
		alert("Please enter the name");
		return false;
	}
	if(document.frmemail.email_to.value==""){
		alert("Please enter to address");
		return false;
	}
	if(document.frmemail.subject.value==""){
		alert("Please enter to subject");
		return false;
	}
	return true;
}

function toggle(layer_ref) {
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
	
	if(str == "To"){
		document.getElementById("email_to").value = items;
	}else if(str == "Cc"){
		document.getElementById("email_cc").value = items;
	}else if(str == "Bcc"){
		document.getElementById("email_bcc").value=items;
	}
    //return items;
}
function showCustomer(str){
	
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
  		alert ("Your browser does not support AJAX!");
  		return;
  	}

	var url="serverData.php";
	url=url+"?q="+str;
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