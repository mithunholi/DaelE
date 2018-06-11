// JavaScript Document

function checkCategoryForm()
{
    with (window.document.frmCategory) {
		if (isEmpty(txtName, 'Enter category name')) {
			return;
		} else if (isEmpty(mtxDescription, 'Enter category description')) {
			return;
		} else {
			submit();
		}
	}
}

function addCategory(parentId)
{
	alert("Selva");
	targetUrl = 'category/index.php?view=add';
	if (parentId != 0) {
		targetUrl += '&parentId=' + parentId;
	}
	alert(targetUrl);
	window.location.href = targetUrl;
}

function modifyCategory(catId)
{
	window.location.href = 'index.php?view=modify&catId=' + catId;
}

function deleteCategory(catId)
{
	if (confirm('Deleting category will also delete all products in it.\nContinue anyway?')) {
		window.location.href = 'processCategory.php?action=delete&catId=' + catId;
	}
}


