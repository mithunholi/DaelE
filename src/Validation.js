// JavaScript Document
//to validate all fields
function isEmpty(strfield1, strfield2) {
	//change "field1, field2 and field3" to your field names
	strfield1 = document.forms[0].field1.value 
	strfield2 = document.forms[0].field2.value
	//strfield3 = document.forms[0].field3.value

  //name field
    if (strfield1 == "" || strfield1 == null || !isNaN(strfield1) || strfield1.charAt(0) == ' ')
    {
    	alert("\"Field 1\" is a mandatory field.\nPlease amend and retry.")
    	return false;
    }

  //url field 
    if (strfield2 == "" || strfield2 == null || strfield2.charAt(0) == ' ')
    {
    	alert("\"Field 2\" is a mandatory field.\nPlease amend and retry.")
    	return false;
    }

  //title field 
   /* if (strfield3 == "" || strfield3 == null || strfield3.charAt(0) == ' ')
    {
    	alert("\"Field 3\" is a mandatory field.\nPlease amend and retry.")
    	return false;
    }*/
    return true;
}

//function to check valid email address
function isValidEmail(strEmail){
  validRegExp = /^[^@]+@[^@]+.[a-z]{2,}$/i;
  strEmail = document.forms[0].email.value;

   // search email text for regular exp matches
    if (strEmail.search(validRegExp) == -1) 
   {
      alert('A valid e-mail address is required.\nPlease amend and retry');
      return false;
    } 
    return true; 
}

//function that performs all functions, defined in the onsubmit event handler

function check(form)){
	//if (isEmpty(form.id)){
		alert(form.design_name);
  		if (isEmpty(form.design_name)){
    		if (isEmpty(form.design_desc)){
				//if (isValidEmail(form.email)){
		  		//	return true;
				//}
				return true;
	  		}
  		}
	//}
	return false;
}

function formValidation(){
	
}