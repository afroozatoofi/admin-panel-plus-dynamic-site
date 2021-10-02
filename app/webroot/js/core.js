$(document).ajaxError(function(e, request) {
	if (request.status === 200)
		return;
	if (request.status === 403) {
		if (window.parent.$('#loginModal iframe').attr('src')) {
			frameContent = window.parent.$("#loginFrame")[0].contentWindow;
			frameContent.$('.creload').click();
		} else {
			window.parent.$('#loginModal iframe').attr('src', 'login?lov=1');
		}
		window.parent.$('#loginModal').modal();
		return;
	}
	if (request.status !== 500) {
		text = '{"error":["خطا رخ داده است."]}';
	} else if (request.responseText) {
		text = request.responseText;
	}
	showExp(text);
});
$(function() {
	$('.nav-tabs li').click(function(e) {
		if ($(this).hasClass('disabled')) {
			return false;
		}
	});	
	$('.lov').prev('input').not('.ltr').css('padding-left', '35px');
	$('.lov').prev('input.ltr').css('padding-right', '35px');
	$('.lov').css('display', 'block').parent().css('position', 'relative');

	$contextMenu = $('#contextMenu');
	$contextMenu.find('a').on('click', function() {
		$contextMenu.hide();
	});
	$("#grid").on("contextmenu", ".dataTable tbody tr", function(e) {
		if (!$(this).hasClass('selected')) {
			$(this).click();
		}
		$contextMenu.css({
			display : "block",
			left : e.pageX,
			top : e.pageY
		});
		return false;
	});

	$("body").on("click", function() {
		$contextMenu.hide();
	});
});
currentEntity = null;
function loader(show) {
	$('.loader').css('display', show ? 'inline-block' : 'none');
}
function onLoadForm() {
	loader(false);
	$('iframe#mainForm').fadeIn();
	$('.footer td').fadeIn();
}
function showMsg(msg) {
	bootbox.alert({
		"message" : msg
	});
}
function clickMenu(e) {
	link = $(e).attr('href');
	englishName = $(e).attr('englishName');
	$ul = $(e).parent().parent();
	$('#sidebar li').removeClass('selected');
	$(e).parent().addClass('selected');
	showPage(link, englishName, $ul.prev('h3').find('span:first').text(), $(e)
			.text());
}
function showPage(link, englishName, form, subForm) {
	var oldEnglishName = $('iframe#mainForm').attr('englishName');
	if (oldEnglishName === englishName) {
		// return;
	}
	loader(true);
	location.hash = "!/" + englishName;
	$('iframe#mainForm').css('display', 'none').attr('src',link);
	$('iframe#mainForm').attr('englishName', englishName);
	$('.formPath').fadeIn();
	$('#formName').html(form);
	// $('iframe#mainForm').css('height', $('iframe').height()-40+"px"); //
	// FireFox Problem
	$('#subFormName').html(subForm);
	document.title = form + ' - ' + subForm;
}
function checkHash() {
	var hash = location.hash;
	hash = hash.split('/');
	if (hash.length != 2) {
		return;
	}
	$('#sidebar a[englishName=' + hash[1] + ']').click();
}
function clearEntity(noReset) {
	$('input[type!=checkbox],textarea').val('');
	$('input[type=checkbox]').attr('checked', false);
	if (!noReset)
		$('select option').removeAttr('selected');
	btnStatus();
	currentEntity = null;
}
function clearFilter(identifire,noReload) {
	identifire = identifire || '#SearchForm';
	$(identifire + ' input[type!=checkbox]').val('');
	$(identifire + ' input[type=checkbox]').attr('checked', false);
	$(identifire + ' select option').removeAttr('selected');
	$(identifire + ' select').change();
	if(!noReload){
		table.api().ajax.reload(null, true);
	}
}
function progress(show) {
	var t = 'لطفا صبر کنید...';
	if (show)
		$('.inProgress').stop().html(t).fadeIn();
	else
		$('.inProgress').stop().html(t).fadeOut();
}
function btnStatus(identifire, area) {
	identifire = identifire || '#id';
	area = area || '#Form';
	var id = $(identifire).val();
	if (id && id > 0) {
		$(area + ' button').attr('disabled', false);
		$(area + ' .noedit').attr('disabled', true);
		$(area + ' .save').html('ویرایش');
	} else {
		$(area + ' .delete').attr('disabled', true);
		$(area + ' .noedit').attr('disabled', false);
		$(area + ' .save').html('ثبت');
	}
}
function tabStatus(identifire) {
	identifire = identifire || '#id';
	var id = $(identifire).val();
	if (id && id > 0) {
		$('.nav-tabs li').removeClass('disabled');
	} else {
		$('.nav-tabs li').addClass('disabled');
	}
}
function removeEntity(url, identifire, onSuccess) {
	identifire = identifire || '#id';
	url = url || restUrl + 'delete';

	var id = $(identifire).val();
	if (id && id > 0) {
		bootbox.confirm("آیا از حذف این رکورد مطمئن هستید؟", function(result) {
			if (!result)
				return;
			$.ajax({
				url : url + '/' + id,
				type : 'delete',
				beforeSend : function() {
					progress(true);
				},
				success : function() {
					if (onSuccess) {
						onSuccess();
					} else {
						clearEntity();
					}
				},
				complete : function() {
					progress(false);
				}
			});
		});
	}
}
function save(e, url, reset, afterFunc) {
	$.ajax({
		url : restUrl + url,
		type : "post",
		dataType : "json",
		data : $(e.target).serialize(),
		beforeSend : function() {
			progress(true);
		},
		success : function(id) {
			if(reset){
				clearEntity(true);
			} else {
				$('#id').val(id);				
			}
			btnStatus();
			
			if (afterFunc) {
				afterFunc();
			}
		},
		complete : function() {
			progress(false);
		}
	});
}
function convert(str) {
	str = str.replace(/&amp;/g, "&");
	str = str.replace(/&gt;/g, ">");
	str = str.replace(/&lt;/g, "<");
	str = str.replace(/&quot;/g, "\"");
	str = str.replace(/&#039;/g, "'");
	return str;
}
function getException(msg) {
	var json;
	try {
		json = JSON.parse((convert(msg)));
	} catch (e) {
		alert(e);
	}
	var error = '<ul>';
	$.each(json, function(i, f) {
		$.each(f, function(j, e) {
			error += '<li>' + e + '</li>';
		});
	});
	error += '</ul>';
	return error;
}
function showExp(msg) {
	if (!msg) {
		return;
	}
	var error = getException(msg);
	$('#myModal .modal-body p').html(error);
	$('#myModal').modal();	
}
function showForm() {
	$('#grid,.fixedHeader').css('display', 'block');
	$('#Form').css('display', 'none');
}
table = null;
function fillTable(url, orders, noEdit, rowCallBack, multi) {
	multi = multi || false;
	showForm();
	if (table) {
		table.api().ajax.reload(null, false);
		return;
	}
	
	table = $('#grid > table').dataTable({
		processing : true,
		serverSide : true,
		ajax : {
			url : restUrl + url,
			data : function(d) {
				d.filter = $("#SearchForm").serialize();				
			},
			type : "POST"
		},
		fnRowCallback : rowCallBack,
		order : orders,
		language : {
			url : "../file/dataTable.lang.txt"
		},
		sLengthSelect: 'ali',
		bFilter : false,
		bSort : true,
		columns : columns
	});
	if (!noEdit) {
		$("#grid").on('dblclick', '.dataTable tbody tr', _editRecord);
	}
	if ((typeof lov == undefined || typeof lov == "undefined") || lov == '0') {
		if (multi) {
			$('#grid').on('click', 'tr', function() {
				$(this).toggleClass('selected');
			});
		} else {
			$('#grid').on('click', 'tr', function() {
				if ($(this).hasClass('selected')) {
					$(this).removeClass('selected');
				} else {
					table.$('tr.selected').removeClass('selected');
					$(this).addClass('selected');
				}
			});
		}
	}
}
function _editRecord() {
	var id = table.fnGetData($(this).index())['id'];
	loadById(id);
}
function editRecord() {
	var id = gridRow('id');
	if (!id.length)
		return;
	loadById(id[0]);
}
function newRecord() {
	clearEntity();
	showEdit();
}
function removeRecord() {
	var id = gridRow('id');
	if (!id.length)
		return;
	bootbox.confirm("آیا از حذف این رکورد مطمئن هستید؟", function(result) {
		if (!result)
			return;
		$.ajax({
			url : restUrl+'delete/' + id[0],
			type : 'delete',
			beforeSend : function() {
				progress(true);
			},
			success : function() {
				table.api().ajax.reload(null, false);
			},
			complete : function() {
				progress(false);
			}
		});
	});
}
function loadById(id, rest, beforeFn, afterFn) {
	rest = rest || restUrl;
	beforeFn = beforeFn || clearEntity;
	beforeFn();
	showEdit();
	$.ajax({
		url : rest + 'load/' + id,
		dataType : "json",
		beforeSend : function() {
			progress(true);
		},
		success : function(entity) {
			currentEntity = entity;
			if (afterFn) {
				afterFn(entity);
			} else {
				for (model in entity) {
					for (prop in entity[model]) {
						var sel = '[name="data[' + model + '][' + prop + ']"]';
						var val = entity[model][prop];
						if (val)
							$('input[type!=checkbox]'+sel+',select'+sel+',textarea'+sel).val(val).change();
						if (entity[model].length) {
							var s = '[name="data[' + model
									+ '][id][]"] option[value='
									+ entity[model][prop].id + ']';
							$('select' + s).attr('selected', 'selected');
						}
						$('input[type=checkbox]' + sel).prop('checked',
								val == 1 ? true : false);
					}
				}
			}
			btnStatus();
		},
		complete : function() {
			progress(false);
		}
	});
}
function showEdit() {
	$('#grid,.fixedHeader').css('display', 'none');
	$('#Form').css('display', 'block');
}
function fillCombo(selector, jsonUrl, jsonData, itemKey, itemValue,
		firstItemText, afterFunc) {
	$(selector + " > option").remove();
	$(selector).attr('disabled', true);
	$.getJSON(jsonUrl, jsonData, function(entities) {
		var ddlSelectedProduct = $(selector);
		$(selector + " > option").remove();
		if (firstItemText)
			ddlSelectedProduct.append($("<option />").val(-1).text(
					firstItemText));
		$.each(entities, function(i, entityItem) {
			var objInfo = {
				key : itemKey != null ? entityItem[itemKey] : i,
				val : itemValue != null ? entityItem[itemValue] : entityItem
			};
			ddlSelectedProduct.append($("<option />").val(objInfo.key).text(
					objInfo.val));
		});
		$(selector).attr('disabled', false);
		if (afterFunc) {
			afterFunc();
		}
	});
}
function showModalForm(url, onSelect) {
	var html = '<div id="lov" class="modal"><div class="modal-dialog" style="width:92%;height:90%">';
	html += '<div class="modal-content" style="height:100%" >'
	html += '<iframe frameborder="0" width="100%" src="' + url
			+ '" height="100%" ></iframe></div></div></div>';
	$('body').append(html);
	$('#lov').on('show.bs.modal', function() {
		window.$_returnvalue = null;
	});
	$('#lov').modal('show');
	$('#lov').on('hide.bs.modal', function() {
		if (window.$_returnvalue != null && onSelect) {
			onSelect(window.$_returnvalue);
		}
		$('#lov').remove();
	});
}

function gridRow(attr) {
	return _gridRow(table, attr);
}
function _gridRow(table, attr) {
	var attrs = [];
	table.$('tr.selected').each(function(i, e) {
		try {
			var a = table.fnGetData($(e).index())[attr];
			attrs.push(a);
		} catch (err) {
		}
	});
	return attrs;
}