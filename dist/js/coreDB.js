Date.prototype.today = function () {
	return this.getFullYear() + "-" +(((this.getMonth()+1) < 10)?"0":"") + (this.getMonth()+1) + "-" + ((this.getDate() < 10)?"0":"") + this.getDate();
}

Date.prototype.timeNow = function () {
	return ((this.getHours() < 10)?"0":"") + this.getHours() + ":" + ((this.getMinutes() < 10)?"0":"") + this.getMinutes() + ":" + ((this.getSeconds() < 10)?"0":"") + this.getSeconds();
}

$.fn.select2.defaults.set("theme", "bootstrap-5")
$.fn.select2.defaults.set("width", "100%")
$.fn.select2.defaults.set("allowClear", true)

function inArray(needle, haystack) {
	var length = haystack.length;
	for(var i = 0; i < length; i++) {
    if(haystack[i] == needle) return true;
	}
	return false;
}

function formatBytes(bytes, decimals = 2) {
  if (!+bytes) return '0 Bytes'
  const k = 1024
  const dm = decimals < 0 ? 0 : decimals
  const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`
}

function copyToClipboard(object){
	if(typeof object !== 'undefined' && typeof object !== null && typeof object !== 'function'){
		let string = ''
		let input = $(document.createElement('input')).appendTo('body')
		if(typeof object === 'object'){ string = object.text(); }
		if(typeof object === 'number'){ string = object.toString(); }
		if(typeof object === 'boolean'){ string = object.toString(); }
		if(typeof object === 'string'){ string = object; }
		input.val(string).select()
		document.execCommand("copy")
		navigator.clipboard.writeText(input.val())
		Toast.create({title:'Copied to Clipboard',icon:'clipboard-check',color:'success',close:false})
		input.remove()
	} else {
		Toast.create({title:'Unable to save to Clipboard',icon:'clipboard-x',color:'danger',close:false})
	}
}

class coreDBClock {
	#timeout = null;
	#frequence = 5000;
	#callbacks = [];

	constructor(options = {}){
		this.config(options);
	}
  config(options = {}){
    for(var [option, value] of Object.entries(options)){
      if(option == 'frequence'){ this.#frequence = parseInt(value); }
    }
		return this;
  }
	status(){
		return this.#timeout != null;
	}
	start(){
		const self = this;
		if(self.#timeout == null){
			self.#timeout = setInterval(function(){
				self.exec();
			}, self.#frequence);
		}
		return this;
	}
	stop(){
		const self = this;
		if(self.#timeout != null){
			clearInterval(self.#timeout);
			self.#timeout = null;
		}
		return this;
	}
	exec(){
		const self = this;
		for(var [key, callback] of Object.entries(self.#callbacks)){
			callback();
		}
	}
	add(callback = null){
		if(callback != null && callback instanceof Function){
			this.#callbacks.push(callback);
		}
		return this;
	}
	clear(){
		this.#callbacks = [];
		return this;
	}
}

class coreDBAuth {

	#api = null

	constructor(){
		const self = this
		self.#api = API
	}

	isAuthorized(string, integer, code){
		const self = this
		let name = '', level = 1, callback = null
		if(typeof string === 'string'){ name = string; }
		if(typeof integer === 'string'){ name = integer; }
		if(typeof code === 'string'){ name = code; }
		if(typeof string === 'number'){ level = string; }
		if(typeof integer === 'number'){ level = integer; }
		if(typeof code === 'number'){ level = code; }
		if(typeof string === 'function'){ callback = string; }
		if(typeof integer === 'function'){ callback = integer; }
		if(typeof code === 'function'){ callback = code; }
		self.#api.post("auth/authorization",{name:name,level:level},{success:function(result,status,xhr){
			if(callback != null && typeof callback === 'function'){
				callback(result)
			}
		}})
	}
}

class coreDBFile {

	#api = null

	constructor(){
		const self = this
		self.#api = API
	}

	base64toBlob(base64Data, contentType){
		const self = this
		contentType = contentType || '';
		var sliceSize = 1024;
		var byteCharacters = atob(base64Data);
		var bytesLength = byteCharacters.length;
		var slicesCount = Math.ceil(bytesLength / sliceSize);
		var byteArrays = new Array(slicesCount);

		for (var sliceIndex = 0; sliceIndex < slicesCount; ++sliceIndex) {
			var begin = sliceIndex * sliceSize;
			var end = Math.min(begin + sliceSize, bytesLength);
			var bytes = new Array(end - begin);

			for (var offset = begin, i = 0; offset < end; ++i, ++offset) {
				bytes[i] = byteCharacters[offset].charCodeAt(0);
			}
			byteArrays[sliceIndex] = new Uint8Array(bytes);
		}
		return new Blob(byteArrays, { type: contentType });
	}

	formatBytes(bytes, decimals = 2) {
	  if (!+bytes) return '0 Bytes'
	  const k = 1024
	  const dm = decimals < 0 ? 0 : decimals
	  const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
	  const i = Math.floor(Math.log(bytes) / Math.log(k))
	  return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`
	}

	download(id = null){
		const self = this
		if(id != null){
			self.#api.get("file/download/?id="+id+"&csrf="+CSRF,{success:function(file,status,xhr){
				file.blob = self.base64toBlob(file.content)
				var isIE = false || !!document.documentMode
				if(isIE){
					window.navigator.msSaveBlob(file.blob, file.filename)
				} else {
					var url = window.URL || window.webkitURL
					var link = url.createObjectURL(file.blob)
					var a = $(document.createElement('a')).attr("href", link).attr("download", file.filename)
					$("body").append(a)
					a[0].click()
					$("body").remove(a)
				}
			}})
		}
	}

	upload(dataCallback = null, returnCallback = null){
		const self = this
		Modal.create({title:'Upload',icon:'upload',color:'success',size:'lg',body:''},function(modal){
			modal.body.form = $(document.createElement('div')).addClass('input-group').appendTo(modal.body)
			modal.body.form.input = $(document.createElement('input')).attr('type','file').attr('multiple','multiple').addClass('form-control file').appendTo(modal.body.form)
			modal.body.form.input.fileinput({
				showCaption: false,
				showPreview: true,
				showRemove: false,
				showUpload: false,
				showUploadStats: false,
				showCancel: false,
				showPause: false,
				showClose: false,
				showUploadedThumbs: false,
				showBrowse: false,
				browseOnZoneClick: true,
				dropZoneEnabled: true,
				// captionClass: '', // Additional
				// previewClass: 'm-0 shadow', // Additional
				// mainClass: '', // Additional
				// inputGroupClass: '', // Additional
				frameClass: 'krajee-default coreDBFileThumbnail',
				// previewFileIconClass: 'file-other-icon',
				// buttonLabelClass: 'hidden-xs',
				// browseClass: 'btn btn-primary',
				// removeClass: 'btn btn-default btn-secondary'
				// cancelClass: 'btn btn-default btn-secondary'
				// pauseClass: 'btn btn-default btn-secondary'
				// uploadClass: 'btn btn-default btn-secondary'
				// progressClass: 'progress-bar progress-bar-success progress-bar-striped active',
				// progressCompleteClass: 'progress-bar progress-bar-success',
				// progressErrorClass: 'progress-bar progress-bar-danger',
				// progressClass: '',
				// progressClass: '',
				rotatableFileExtensions: ['jpg', 'jpeg', 'png', 'gif'],
				previewZoomButtonIcons: {
					// prev: '<i class="bi-caret-left-fill"></i>',
					// next: '<i class="bi-caret-right-fill"></i>',
					// rotate: '<i class="bi-arrow-clockwise"></i>',
					// toggleheader: '<i class="bi-arrows-expand"></i>',
					// fullscreen: '<i class="bi-arrows-fullscreen"></i>',
					// borderless: '<i class="bi-arrows-angle-expand"></i>',
					// close: '<i class="bi-x-lg"></i>',
				},
				previewZoomButtonClasses: {
					prev: 'btn btn-navigate btn-light border shadow',
					next: 'btn btn-navigate btn-light border shadow',
					rotate: 'btn btn-kv btn-light border shadow',
					toggleheader: 'btn btn-kv btn-light border shadow',
					fullscreen: 'btn btn-kv btn-light border shadow',
					borderless: 'btn btn-kv btn-light border shadow',
					close: 'btn btn-kv btn-light border shadow',
				},
				previewZoomButtonTitles: {
					// prev: 'View previous file',
					// next: 'View next file',
					// rotate: 'Rotate 90 deg. clockwise',
					// toggleheader: 'Toggle header',
					// fullscreen: 'Toggle full screen',
					// borderless: 'Toggle borderless mode',
					// close: 'Close detailed preview',
				},
				fileActionSettings: {
					// showRemove: true,
					// showUpload: true, // will be always false for resumable uploads
					// showDownload: true,
					// showZoom: true,
					// showDrag: true,
					// removeIcon: '<i class="bi-trash"></i>',
					removeClass: 'btn btn-kv btn-light border shadow',
					// removeErrorClass: 'btn btn-kv btn-danger',
					// removeTitle: 'Remove file',
					// uploadIcon: '<i class="bi-upload"></i>',
					uploadClass: 'btn btn-kv btn-light border shadow',
					// uploadTitle: 'Upload file',
					// uploadRetryIcon: '<i class="bi-arrow-clockwise"></i>',
					// uploadRetryTitle: 'Retry upload',
					// downloadIcon: '<i class="bi-download"></i>',
					downloadClass: 'btn btn-kv btn-light border shadow',
					// downloadTitle: 'Download file',
					// zoomIcon: '<i class="bi-zoom-in"></i>',
					zoomClass: 'btn btn-kv btn-light border shadow',
					// zoomTitle: 'View Details',
					// dragIcon: '<i class="bi-arrows-move"></i>',
					// dragClass: 'text-info',
					// dragTitle: 'Move / Rearrange',
					// dragSettings: {},
					indicatorNew: '<i class="bi-plus-lg"></i>',
					indicatorSuccess: '<i class="bi-check-lg"></i>',
					indicatorError: '<i class="bi-exclamation-lg"></i>',
					indicatorLoading: '<i class="bi-hourglass-bottom"></i>',
					indicatorPaused: '<i class="bi-pause-fill"></i>',
					// indicatorNewTitle: 'Not uploaded yet',
					// indicatorSuccessTitle: 'Uploaded',
					// indicatorErrorTitle: 'Upload Error',
					// indicatorLoadingTitle: 'Uploading ...',
					// indicatorPausedTitle: 'Upload Paused'
				},
			})
			modal.footer.group.primary.click(function(){
				var countFileUpload = Object.entries(modal.body.form.input.prop('files')).length
				for(const [index, file] of Object.entries(modal.body.form.input.prop('files'))){
					const reader = new FileReader()
					reader.onload = function(){
						var object = {
							name: file.name,
							type: file.type,
							size: file.size,
							content: reader.result,
						}
						if(dataCallback != null && typeof dataCallback === 'function'){
							object = dataCallback(object)
						}
						var thumbnail = modal.body.form.find('.coreDBFileThumbnail[data-fileindex="'+index+'"]')
						thumbnail.success = $(document.createElement('div')).attr('data-status','success').addClass('coreDBFileThumbnailStatus position-absolute top-0 start-0 w-100 h-100 d-none text-center text-light opacity-75 rounded text-bg-success').css('padding-top','84px').appendTo(thumbnail)
						thumbnail.success.icon = $(document.createElement('i')).addClass('bi-check2').css('font-size','96px').appendTo(thumbnail.success)
						thumbnail.error = $(document.createElement('div')).attr('data-status','error').addClass('coreDBFileThumbnailStatus position-absolute top-0 start-0 w-100 h-100 d-none text-center text-light opacity-75 rounded text-bg-danger').css('padding-top','72px').appendTo(thumbnail)
						thumbnail.error.icon = $(document.createElement('i')).addClass('bi-x-lg').css('font-size','96px').appendTo(thumbnail.error)
						thumbnail.loader = $(document.createElement('div')).attr('data-status','loader').addClass('coreDBFileThumbnailStatus position-absolute top-0 start-0 w-100 h-100 d-none text-center text-light opacity-75 rounded text-bg-info').css('padding-top','96px').appendTo(thumbnail)
						thumbnail.loader.icon = $(document.createElement('div')).addClass('spinner-border text-light').css('width','96px').css('height','96px').appendTo(thumbnail.loader)
						thumbnail.loader.removeClass('d-none')
						// console.log(index, file, reader, object, thumbnail)
						self.#api.post("file/upload/?csrf="+CSRF,object,{success:function(result,status,xhr){
							thumbnail.loader.addClass('d-none')
							thumbnail.success.removeClass('d-none')
							thumbnail.error.addClass('d-none')
							if(returnCallback != null && typeof returnCallback === 'function'){
								returnCallback(result)
							}
							countFileUpload = (countFileUpload - 1)
							if(countFileUpload <= 0){
								modal.bootstrap.hide()
							}
						},error:function(xhr,status,error){
							thumbnail.loader.addClass('d-none')
							thumbnail.success.addClass('d-none')
							thumbnail.error.removeClass('d-none')
						}})
					}
					reader.readAsDataURL(file);
				}
			})
		})
	}
}

class coreDBIcon {

	#icons = []

	constructor(){}

	create(name, html = false){
		const self = this
		let icon = $(document.createElement('i')).addClass('bi-'+name)
		if(html){
			return icon.get(0).outerHTML
		}
		return icon
	}
}

class coreDBModal {

	constructor(){}

	#modal(){
    const self = this
		let options = {
			close:true,
			color:null,
			icon:null,
			title: null,
			body: null,
			data: null,
			static: false,
			cancel: true,
			center: false,
			size: 'none',
		}
		let modal = $(document.createElement('div')).addClass('modal fade').attr('tabindex',-1)
		modal.options = options
		modal.dialog = $(document.createElement('div')).addClass('modal-dialog user-select-none').appendTo(modal)
		modal.content = $(document.createElement('div')).addClass('modal-content').appendTo(modal.dialog)
		modal.header = $(document.createElement('div')).addClass('modal-header shadow-sm').appendTo(modal.content)
		modal.header.container = $(document.createElement('h5')).addClass('modal-title fw-light').appendTo(modal.header)
		modal.header.title = $(document.createElement('span')).appendTo(modal.header.container)
		modal.header.icon = Icon.create('').addClass('me-2').prependTo(modal.header.container)
		modal.header.close = $(document.createElement('button')).addClass('btn-close').attr('type','button').attr('data-bs-dismiss','modal').attr('aria-label','Close').appendTo(modal.header)
		modal.body = $(document.createElement('div')).addClass('modal-body').appendTo(modal.content)
		modal.footer = $(document.createElement('div')).addClass('modal-footer p-0').appendTo(modal.content)
		modal.footer.group = $(document.createElement('div')).addClass('btn-group btn-lg w-100 m-0 rounded-bottom').appendTo(modal.footer)
		modal.footer.group.cancel = $(document.createElement('button')).addClass('btn btn-light btn-lg fw-light').css('border-radius', '0px 0px 0px var(--bs-border-radius)').html('Cancel').attr('type','button').attr('data-bs-dismiss','modal').appendTo(modal.footer.group)
		modal.footer.group.primary = $(document.createElement('button')).addClass('btn btn-primary btn-lg fw-light').css('border-radius', '0px 0px var(--bs-border-radius) 0px').html('Ok').attr('type','button').appendTo(modal.footer.group)
		modal.on('hide.bs.modal',function(){
			$(this).remove()
		})
		modal.on('keypress',function(e){
	    if(e.which == 13) {
        modal.footer.group.primary.click();
	    }
		});
		modal.prependTo('body')
		return modal
	}

	create(options = {}, callback = null){
		const self = this
		if(options instanceof Function){ callback = options; options = {}; }
		let modal = self.#modal()
		if(typeof options === 'object'){
			for(const [key, value] of Object.entries(options)){
				if(typeof modal.options[key] !== 'undefined'){
					modal.options[key] = value
				}
			}
		}
		if(modal.options.color != null && typeof modal.options.color === 'string'){
			modal.footer.group.primary.removeClass('btn-primary').addClass('btn-'+modal.options.color)
		}
		if(modal.options.close == null || typeof modal.options.close !== 'boolean' || !modal.options.close){
			modal.header.close.remove()
			delete modal.header.close
		}
		if(modal.options.cancel == null || typeof modal.options.cancel !== 'boolean' || !modal.options.cancel){
			modal.footer.group.cancel.remove()
			delete modal.footer.group.cancel
			modal.footer.group.primary.css('border-radius', '0px 0px var(--bs-border-radius) var(--bs-border-radius)')
		}
		if(modal.options.static != null && typeof modal.options.static === 'boolean' && modal.options.static){
			modal.attr('data-bs-backdrop','static').attr('data-bs-keyboard',false)
		}
		if(modal.options.center != null && typeof modal.options.center === 'boolean' && modal.options.center){
			modal.dialog.addClass('modal-dialog-centered')
		}
		if(modal.options.size != null && typeof modal.options.size === 'string'){
			switch(modal.options.size){
				case"small":
				case"sm":
					modal.dialog.addClass('modal-sm')
					break
				case"default":
				case"none":
					break
				case"large":
				case"lg":
					modal.dialog.addClass('modal-lg')
					break
				case"extra-large":
				case"xl":
					modal.dialog.addClass('modal-xl')
					break
				case"xxl":
				case"fullscreen":
					modal.dialog.addClass('modal-fullscreen')
					break
			}
		}
		if(modal.options.icon != null && typeof modal.options.icon === 'string'){
			modal.header.icon.addClass('bi-'+modal.options.icon)
		} else {
			modal.header.icon.remove()
			delete modal.header.icon
		}
		if(modal.options.title != null && typeof modal.options.title === 'string'){
			modal.header.title.html(modal.options.title)
		}
		if(modal.options.body != null && (typeof modal.options.body === 'string' || typeof modal.options.body === 'object')){
			modal.body.html(modal.options.body)
		}
		modal.bootstrap = new bootstrap.Modal(modal)
		if(typeof callback === 'function'){
			callback(modal)
		}
		modal.bootstrap.show()
		return modal
	}
}

class coreDBToast {

	#container = null

	constructor(){
		const self = this
		self.#build()
	}

	#build(){
		const self = this
		if(self.#container == null){
			self.#container = $(document.createElement('div')).addClass('position-relative').attr('aria-live','polite').attr('aria-atomic','true').appendTo('body');
			self.#container.list = $(document.createElement('div')).addClass('toast-container bottom-0 end-0 p-3 user-select-none').appendTo(self.#container);
		}
	}

	#toast(){
		const self = this
		let options = {
			animation:true,
			autohide:true,
			delay:5000,
			time:true,
			close:true,
			color:null,
			icon:null,
			title:null,
			body:null,
			datetime:null,
		}
		let toast = $(document.createElement('div')).addClass('toast shadow').attr('role','status').attr('aria-live','polite').attr('aria-atomic','true').appendTo(self.#container.list)
		toast.options = options
		toast.header = $(document.createElement('div')).addClass('toast-header shadow').appendTo(toast)
		toast.header.icon = Icon.create('').addClass('me-2').appendTo(toast.header)
		toast.header.title = $(document.createElement('strong')).addClass('me-auto').appendTo(toast.header)
		toast.header.timezone = $(document.createElement('small')).appendTo(toast.header)
		toast.header.timezone.icon = Icon.create('clock').addClass('me-1').appendTo(toast.header.timezone)
		toast.header.timezone.time = $(document.createElement('time')).addClass('timeago').appendTo(toast.header.timezone)
		toast.header.close = $(document.createElement('button')).addClass('btn-close').attr('type','button').attr('data-bs-dismiss','toast').attr('aria-label','Close').appendTo(toast.header)
		toast.body = $(document.createElement('div')).addClass('toast-body').appendTo(toast)
		return toast
	}

	create(options = {}, callback = null){
		const self = this
		if(options instanceof Function){ callback = options; options = {}; }
		let toast = self.#toast()
		if(typeof options === 'object'){
			for(const [key, value] of Object.entries(options)){
				if(typeof toast.options[key] !== 'undefined'){
					toast.options[key] = value
				}
			}
		}
		let datetime = new Date().today() + " " + new Date().timeNow();
		if(toast.options.datetime != null && typeof toast.options.datetime === 'string'){
			datetime = toast.options.datetime
		}
		if(toast.options.color != null && typeof toast.options.color === 'string'){
			toast.addClass('text-bg-'+toast.options.color)
			toast.header.addClass('text-bg-'+toast.options.color)
		}
		if(toast.options.close == null || typeof toast.options.close !== 'boolean' || !toast.options.close){
			toast.header.close.remove()
			delete toast.header.close
		}
		if(toast.options.icon != null && typeof toast.options.icon === 'string'){
			toast.header.icon.addClass('bi-'+toast.options.icon)
		} else {
			toast.header.icon.remove()
			delete toast.header.icon
		}
		if(toast.options.time != null && typeof toast.options.time === 'boolean' && toast.options.time){
			toast.header.timezone.time.attr('datetime',datetime).html(datetime).timeago()
		} else {
			toast.header.timezone.remove()
			delete toast.header.timezone
		}
		if(toast.options.title != null && typeof toast.options.title === 'string'){
			toast.header.title.html(toast.options.title)
		}
		if(toast.options.body != null && (typeof toast.options.body === 'string' || typeof toast.options.body === 'object')){
			toast.body.html(toast.options.body)
		} else {
			toast.header.addClass('rounded').removeClass('shadow')
			toast.body.remove()
			delete toast.body
		}
		toast.bootstrap = new bootstrap.Toast(toast, toast.options)
		if(typeof callback === 'function'){
			callback(toast)
		}
		toast.bootstrap.show()
		return toast
	}
}

class coreDBDropdown {

	constructor(){}

	create(actions = {}, callback = null){
		const self = this
    let object = $(document.createElement('div')).addClass('dropdown')
    object.btn = $(document.createElement('a')).addClass('link-dark').attr('href','').attr('data-bs-toggle','dropdown').attr('aria-expanded','false').appendTo(object)
    object.btn.icon = Icon.create('three-dots-vertical').appendTo(object.btn)
    object.menu = $(document.createElement('ul')).addClass('dropdown-menu').appendTo(object)
		for(var [action, properties] of Object.entries(actions)){
			object.menu[action] = $(document.createElement('li')).appendTo(object.menu)
			object.menu[action].btn = $(document.createElement('button')).attr('type','button').attr('data-action',action).addClass('dropdown-item').html(properties.label).appendTo(object.menu[action])
			if(typeof properties.color === "string"){
				object.menu[action].btn.addClass('text-bg-'+properties.color)
			}
			if(typeof properties.icon === "string"){
				object.menu[action].btn.icon = Icon.create(properties.icon).addClass('me-2').prependTo(object.menu[action].btn)
			}
			if(typeof properties.action === "function"){
				object.menu[action].btn.click(function(){
					properties.action($(this),object)
				})
			}
			if(typeof properties.visible === "function"){
				if(!properties.visible()){
					object.menu[action].hide()
				} else {
					object.menu[action].show()
				}
			}
			if(typeof properties.visible === "boolean"){
				if(!properties.visible){
					object.menu[action].hide()
				} else {
					object.menu[action].show()
				}
			}
		}
		object.getHTML = function(){
			return object.get(0).outerHTML
		}
		if(typeof callback === 'function'){
			callback(object)
		}
		return object
	}
}

class coreDBTable {

	#language = {
		"decimal":        "",
		"emptyTable":     "No data available in table",
		// "info":           "Showing _START_ to _END_ of _TOTAL_ entries",
		// "infoEmpty":      "Showing 0 to 0 of 0 entries",
		// "infoFiltered":   "(filtered from _MAX_ total entries)",
		"info":           "_START_ to _END_ of _TOTAL_",
		"infoEmpty":      "0 to 0 of 0",
		"infoFiltered":   "(filtered)",
		"infoPostFix":    "",
		"thousands":      ",",
		// "lengthMenu":     "Show _MENU_ entries",
		"lengthMenu":     "_MENU_",
		"loadingRecords": "Loading...",
		"processing":     "",
		"search":         "Search:",
		"zeroRecords":    "No matching records found",
		"paginate": {
			"first":      "First",
			"last":       "Last",
			"next":       "Next",
			"previous":   "Previous"
		},
		"aria": {
			"sortAscending":  ": activate to sort column ascending",
			"sortDescending": ": activate to sort column descending"
		},
		"searchBuilder": {
			"add": "Add Condition",
			"button": {
				0: "Search Builder",
				"_": "Search Builder (%d)",
			},
			"clearAll": "Clear All",
			"condition": "Condition",
			"conditions": {
				"array": {
	        "contains": "Contains",
	        "empty": "Empty",
	        "equals": "Equals",
	        "not": "Not",
	        "notEmpty": "Not Empty",
	        "without": "Without"
        },
				"date": {
	        "after": "After",
	        "before": "Before",
	        "between": "Between",
	        "empty": "Empty",
	        "equals": "Equals",
	        "not": "Not",
	        "notBetween": "Not Between",
	        "notEmpty": "Not Empty"
        },
				"number": {
          "between": "Between",
          "empty": "Empty",
          "equals": "Equals",
          "gt": "Greater Than",
          "gte": "Greater Than Equal To",
          "lt": "Less Than",
          "lte": "Less Than Equal To",
          "not": "Not",
          "notBetween": "Not Between",
          "notEmpty": "Not Empty",
        },
				"string": {
          "contains": "Contains",
          "empty": "Empty",
          "endsWith": "Ends With",
          "equals": "Equals",
          "not": "Not",
          "notContains": "Does Not Contain",
          "notEmpty": "Not Empty",
          "notEndsWith": "Does Not End With",
          "notStartsWith": "Does Not Start With",
          "startsWith": "Starts With",
        },
			},
			"data": "Data",
			"delete": "&times",
			"deleteTitle": "Delete filtering rule",
			"left": "<",
			"leftTitle": "Outdent criteria",
			"logicAnd": "And",
			"logicOr": "Or",
			"right": ">",
			"rightTitle": "Indent criteria",
			// "title": {
			// 	0: "Search Builder",
			// 	"_": "Search Builder (%d)",
			// },
			"title": {
				0: "",
				"_": "",
			},
			"value": "Value",
			"valueJoiner": "and",
		},
	}
	#buttons = {
		columnsVisibility:{
			label:{
				extend: 'colvis',
				text: '<i class="bi-layout-sidebar-inset me-2"></i>Columns',
			},
			icon:{
				extend: 'colvis',
				text: '<i class="bi-layout-sidebar-inset"></i>',
			},
		},
		selectTools:{
			label:{
				extend: 'collection',
				text: '<i class="bi-check2-square me-2"></i>Select',
				buttons: [
					{
						extend: 'selectAll',
						text: '<i class="bi-check2-all me-2"></i>All',
					},
					{
						extend: 'selectNone',
						text: '<i class="bi-x-square me-2"></i>None',
					},
					// 'selected', // Enabled only when one or more items are selected
					// 'selectedSingle', // Enabled only when a single item is selected
					// 'selectRows', // Select rows
					// 'selectColumns', // Select columns
					// 'selectCells', // Select cells
				],
			},
			icon:{
				extend: 'collection',
				text: '<i class="bi-check2-square"></i>',
				buttons: [
					{
						extend: 'selectAll',
						text: '<i class="bi-check2-all me-2"></i>All',
					},
					{
						extend: 'selectNone',
						text: '<i class="bi-x-square me-2"></i>None',
					},
					// 'selected', // Enabled only when one or more items are selected
					// 'selectedSingle', // Enabled only when a single item is selected
					// 'selectRows', // Select rows
					// 'selectColumns', // Select columns
					// 'selectCells', // Select cells
				],
			},
		},
		advancedSearch:{
			label:{
				extend: 'collection',
				text: '<i class="bi-search me-2"></i>Advanced Search',
				action:function(e, dt, node, config){
					const SearchBuilder = new bootstrap.Collapse(node.closest('div.dataTables_wrapper').find('#SearchBuilder.collapse'))
					SearchBuilder.toggle()
				},
			},
			icon:{
				extend: 'collection',
				text: '<i class="bi-search"></i>',
				action:function(e, dt, node, config){
					const SearchBuilder = new bootstrap.Collapse(node.closest('div.dataTables_wrapper').find('#SearchBuilder.collapse'))
					SearchBuilder.toggle()
				},
			},
		},
		exportTools:{
			label:{
				extend: 'collection',
				text: '<i class="bi-arrow-bar-down me-2"></i>Export',
				buttons: [
					{
						extend: 'copy',
						text: '<i class="bi-clipboard me-2"></i>Clipboard',
						exportOptions: {
              columns: ':visible:not(:last-child)',
            },
					},
					{
						extend: 'excel',
						text: '<i class="bi-filetype-xlsx me-2"></i>Excel',
						exportOptions: {
              columns: ':visible:not(:last-child)',
            },
					},
					{
						extend: 'csv',
						text: '<i class="bi-filetype-csv me-2"></i>CSV',
						exportOptions: {
              columns: ':visible:not(:last-child)',
            },
					},
					{
						extend: 'pdf',
						text: '<i class="bi-filetype-pdf me-2"></i>PDF',
						exportOptions: {
              columns: ':visible:not(:last-child)',
            },
					},
				],
			},
			icon:{
				extend: 'collection',
				text: '<i class="bi-arrow-bar-down"></i>',
				buttons: [
					{
						extend: 'copy',
						text: '<i class="bi-clipboard me-2"></i>Clipboard',
						exportOptions: {
              columns: ':visible:not(:last-child)',
            },
					},
					{
						extend: 'excel',
						text: '<i class="bi-filetype-xlsx me-2"></i>Excel',
						exportOptions: {
              columns: ':visible:not(:last-child)',
            },
					},
					{
						extend: 'csv',
						text: '<i class="bi-filetype-csv me-2"></i>CSV',
						exportOptions: {
              columns: ':visible:not(:last-child)',
            },
					},
					{
						extend: 'pdf',
						text: '<i class="bi-filetype-pdf me-2"></i>PDF',
						exportOptions: {
              columns: ':visible:not(:last-child)',
            },
					},
				],
			},
		}
	}
	#Dropdown = null

	constructor(){
		const self = this
		self.#Dropdown = new coreDBDropdown()
	}

	#table(options = {}){
    const self = this
		let defaults = {
			card:false,
			advancedSearch:true,
			exportTools:true,
			columnsVisibility:true,
			selectTools:false,
			showButtons:true,
			showButtonsLabel:true,
			pagination:true,
			information:true,
			lengthMenu:true,
			buttons: [],
			columnDefs: [],
			actions:false,
			dblclick: null,
			// editor:false,
		}
		let cardOptions = {
			title: null,
			icon: null,
		}
		if(typeof options === 'object'){
			for(const [key, value] of Object.entries(options)){
				if(typeof defaults[key] !== 'undefined'){
					defaults[key] = value
				}
			}
		}
		let datatableOptions = {
			//Features
			autoWidth: true, //boolean //Enable or disable automatic column width calculation.
			// deferRender: false, //boolean //This option allows DataTables to create the nodes only when they are needed for a draw.
			// info: true, //boolean //Enable or disable information about the table including information about filtered data if that action is being performed.
			lengthChange: true, //boolean //When pagination is enabled, this option will control the display of an option for the end user to change the number of records to be shown per page.
			ordering: true, //boolean //Enable or disable ordering of columns.
			paging: true, //boolean //Enable or disable table pagination
			// processing: false, //boolean //Enable or disable the display of a 'processing' indicator when the table is being processed for server-side processing.
			// scrollX: false, //boolean //Enable horizontal scrolling.
			// scrollY: undefined, //string //Enable vertical scrolling. Vertical scrolling will constrain the DataTable to the given height, and enable scrolling for any data which overflows the current viewport.
			searching: true, //boolean //This option allows the search abilities of DataTables to be enabled or disabled.
			// serverSide: false, //boolean //Feature control DataTables' server-side processing mode.
			// stateSave: false, //boolean //Enable or disable state saving.
			//Data
			// data: [], //array //Data to use as the display data for the table.
			//Options
			// deferLoading: null, //integer|array //Delay the loading of server-side data until second draw
			// destroy: true, //boolean //Destroy any existing table matching the selector and replace with the new options.
			// displayStart: 0, //integer //Initial paging start point
			dom: 'B<"#SearchBuilder.collapse py-2 pt-3"<"card card-body"Q>>rtlip', //string //Define the table control elements to appear on the page and in what order
			lengthMenu: [ 10, 25, 50, 100 ], //array //Change the options in the page length select list.
			order: [[0, 'asc']], //array //Initial order (sort) to apply to the table
			// orderCellsTop: false, //boolean //Control which cell the order event handler will be applied to in a column
			// orderClasses: true, //boolean //Highlight the columns being ordered in the table's body
			// orderFixed: undefined, //array|object //Ordering to always be applied to the table
			// orderMulti: true, //boolean //Multiple column ordering ability control.
			pageLength: 10, //integer //Change the initial page length (number of rows per page)
			// numbers - Page number buttons only (1.10.8)
			// simple - 'Previous' and 'Next' buttons only
			// simple_numbers - 'Previous' and 'Next' buttons, plus page numbers
			// full - 'First', 'Previous', 'Next' and 'Last' buttons
			// full_numbers - 'First', 'Previous', 'Next' and 'Last' buttons, plus page numbers
			// first_last_numbers - 'First' and 'Last' buttons, plus page numbers
			pagingType: 'simple_numbers', //string //Pagination button display options
			renderer: 'bootstrap', //string|object //Display component renderer types
			// retrieve: false, //boolean //Retrieve an existing DataTables instance
			// rowId: 'DT_RowId', //string //Data property name that DataTables will use to set tr element DOM IDs
			// scrollCollapse: false, //boolean //Allow the table to reduce in height when a limited number of rows are shown.
			// search.caseInsensitive: true, //boolean //Control case-sensitive filtering option.
			// search.regex: false, //boolean //Enable / disable escaping of regular expression characters in the search term.
			// search.return: false, //boolean //Enable / disable DataTables' search on return
			// search.search: null, //string //Set an initial filtering condition on the table.
			// search.smart: true, //boolean //Enable / disable DataTables' smart filtering
			// search: {}, //object //Set an initial filter in DataTables and / or filtering options.
			// searchCols: [], //array //Define an initial search for individual columns.
			// searchDelay: null, //integer //Set a throttle frequency for searching
			// stateDuration: 7200, //integer //Saved state validity duration
			// stripeClasses: [], //array //Set the zebra stripe class names for the rows in the table.
			// tabIndex: 0, //integer //Tab index control for keyboard navigation
			//Columns
			columnDefs: defaults.columnDefs,
			//Internationalisation
			language:self.#language,
			// ColReorder
			colReorder: false,
			// FixedColumns
			fixedColumns: false,
			// Select
			select: false,
			//Buttons
			buttons: defaults.buttons,
			//Responsive
			responsive: true,
		}
		if(typeof options.DataTable !== 'undefined'){
			for(const [key, value] of Object.entries(options.DataTable)){
				if(typeof datatableOptions[key] !== 'undefined'){
					datatableOptions[key] = value
				}
			}
		}
		let table = $(document.createElement('table')).addClass('table table-striped m-0 w-100 user-select-none')
		table.options = defaults
		table.datatableOptions = datatableOptions
		if(typeof table.options.card === 'object'){
			for(const [key, value] of Object.entries(table.options.card)){
				if(typeof cardOptions[key] !== 'undefined'){
					cardOptions[key] = value
				}
			}
			table.cardOptions = cardOptions
		}
		if(typeof table.options.actions === 'object'){
			table.actions = self.#Dropdown.create(table.options.actions, function(object){
				object.addClass('dropstart')
				object.btn.addClass('px-3 py-2')
			})
			table.datatableOptions.columnDefs.push({ target: table.datatableOptions.columnDefs.length, visible: true, responsivePriority: 1, title: "Action", data: null, width: '80px', defaultContent: table.actions.getHTML() })
		}
		if(typeof table.options.card === 'boolean' && table.options.card){
			table.cardOptions = cardOptions
		}
		if(table.options.selectTools){
			table.datatableOptions.select = table.options.selectTools
			if(table.options.showButtonsLabel){
				table.datatableOptions.buttons.push(self.#buttons.selectTools.label)
			} else {
				table.datatableOptions.buttons.push(self.#buttons.selectTools.icon)
			}
		}
		if(table.options.exportTools){
			if(table.options.showButtonsLabel){
				table.datatableOptions.buttons.push(self.#buttons.exportTools.label)
			} else {
				table.datatableOptions.buttons.push(self.#buttons.exportTools.icon)
			}
		}
		if(table.options.columnsVisibility){
			if(table.options.showButtonsLabel){
				table.datatableOptions.buttons.push(self.#buttons.columnsVisibility.label)
			} else {
				table.datatableOptions.buttons.push(self.#buttons.columnsVisibility.icon)
			}
		}
		if(table.options.advancedSearch){
			if(table.options.showButtonsLabel){
				table.datatableOptions.buttons.push(self.#buttons.advancedSearch.label)
			} else {
				table.datatableOptions.buttons.push(self.#buttons.advancedSearch.icon)
			}
		}
		if(typeof table.cardOptions !== 'undefined'){
			table.datatableOptions.dom = '<"card shadow user-select-none"<"card-header"'
			if(table.options.showButtons){
				table.datatableOptions.dom += 'B'
			}
			if(table.options.advancedSearch){
				table.datatableOptions.dom += '<"#SearchBuilder.collapse py-2 pt-3"<"card card-body"Q>>'
			}
			if(table.options.searchPanes){
				table.datatableOptions.dom += '<"#searchPanes.collapse py-2 pt-3"<"card card-body"P>>'
			}
			table.datatableOptions.dom += '><"card-body p-0"t><"card-footer d-flex justify-content-between align-items-center"'
			if(table.options.lengthMenu){
				table.datatableOptions.dom += 'l'
			}
			if(table.options.information){
				table.datatableOptions.dom += 'i'
			}
			if(table.options.pagination){
				table.datatableOptions.dom += 'p'
			}
			table.datatableOptions.dom += '>>'
		}
		return table
	}

	create(options = {}, callback = null){
		const self = this
		if(options instanceof Function){ callback = options; options = {}; }
		let table = self.#table(options)
		table.prependTo = function(object){
			object.prepend(table)
			return table
		}
		table.appendTo = function(object){
			object.append(table)
			return table
		}
		table.add = function(data){
			table.dt.row.add(data).draw()
		}
		table.update = function(row, data){
			table.dt.row(row).data(data).draw()
		}
		table.delete = function(row){
			table.dt.row(row).remove().draw()
		}
		table.init = function(){
			if(table.datatableOptions.columnDefs.length > 0){
				if(typeof table.dt === 'undefined'){
					table.datatableOptions.drawCallback = function(){
						if(typeof table.dt !== 'undefined'){
							table.init()
						}
					}
					table.dt = table.DataTable(table.datatableOptions)
					if(typeof table.cardOptions !== 'undefined'){
						table.card = table.closest('.card')
						table.card.header = table.card.find('.card-header')
						table.card.body = table.card.find('.card-body')
						table.card.footer = table.card.find('.card-footer')
						table.card.header.find('.dropdown-toggle').removeClass('dropdown-toggle')
						table.card.header.find('.btn-group').first().addClass('border')
						table.card.header.find('.btn-group').first().find('.btn.btn-secondary').removeClass('btn-secondary').addClass('btn-light')
						table.card.header.title = $(document.createElement('h5')).addClass('card-title my-2 fw-light')
						table.card.header.icon = $(document.createElement('i')).addClass('me-2')
						if(typeof table.cardOptions.title === 'string'){
							table.card.header.title.html(table.cardOptions.title).prependTo(table.card.header)
							if(typeof table.cardOptions.icon === 'string'){
								table.card.header.icon.addClass('bi-'+table.cardOptions.icon).prependTo(table.card.header.title)
							}
						}
					}
					$('#coreDBSearch').keyup(function(){
						table.dt.search($(this).val()).draw()
					})
					if(typeof callback === 'function'){
						callback(table)
					}
				}
				if(typeof table.options.dblclick === 'function'){
					table.find('tr').off().dblclick(function(event){
						let node = $(this)
						let data = table.dt.row(node).data();
						table.options.dblclick(event, table, node, data)
					})
				}
				table.find('button').each(function(){
					let node = $(this)
					let li = node.parents('li')
					let action = node.attr('data-action')
					let row = node.parents('tr')
					let data = table.dt.row(row).data()
					node.off().click(function(event){
						if(typeof table.options.actions[action].action === 'function'){
							table.options.actions[action].action(event, table, node, row, data)
						}
					})
					if(typeof table.options.actions[action].visible === 'function'){
						if(!table.options.actions[action].visible(li, table, node, row, data)){
							li.hide()
						} else {
							li.show()
						}
					}
					if(typeof table.options.actions[action].visible === 'boolean'){
						if(!table.options.actions[action].visible){
							li.hide()
						} else {
							li.show()
						}
					}
				})
				return table
			} else {
				return false
			}
		}
		return table
	}
}

class coreDBTimeline {

	constructor(){}

	#timeline(){
		const self = this
		let options = {}
		let timeline = $(document.createElement('div')).addClass('timeline')
		timeline.options = options
		self.#clear(timeline)
		timeline.label = function(datetime, color = 'primary'){
			self.#label(timeline, datetime, color)
		}
		timeline.object = function(options = {}, callback = null){
			self.#object(timeline, options, callback)
		}
		timeline.clear = function(){
			self.#clear(timeline)
		}
		timeline.sort = function(){
			self.#sort(timeline)
		}
		return timeline
	}

	#label(timeline, timestamp, color = 'primary'){
		const self = this
		let datetime = new Date(timestamp);
		let order = datetime.setHours(0,0,0,0);
		if(timeline.find('div.time-label[data-order="'+order+'"]').length > 0){
			return false
		}
		let label = $(document.createElement('div')).addClass('time-label').attr('data-order',order).prependTo(timeline)
		label.time = $(document.createElement('span')).addClass('text-bg-'+color).addClass('shadow').html(datetime.toLocaleDateString('en-US',{day: 'numeric', month: 'long', year: 'numeric'})).attr('title',datetime.toLocaleString('en-US')).attr('data-bs-placement','right').appendTo(label);
		return label;
	}

	#object(timeline, options = {}, callback = null){
		const self = this
		if(options instanceof Function){ callback = options; options = {}; }
		let object = $(document.createElement('div')).addClass('timeline-object').appendTo(timeline)
		object.options = {
			icon: 'circle',
			color: 'secondary',
			type: null,
			datetime: Date.parse(new Date()),
			header: null,
			body: null,
			footer: null,
			order: null,
			label: true,
			after:null,
			id:null,
		}
		if(typeof options === 'object'){
			for(const [key, value] of Object.entries(options)){
				if(typeof object.options[key] !== 'undefined'){
					object.options[key] = value
				}
			}
		}
		let datetime = new Date(object.options.datetime)
		let order = Date.parse(datetime)
		if(object.options.order != null){
			order = object.options.order
		}
		if(object.options.after != null){
			object.attr('data-after',object.options.after)
		}
		if(object.options.id != null){
			object.attr('data-id',object.options.id)
		}
		object.attr('data-type',object.options.type).attr('data-order',order)
		object.icon = Icon.create(object.options.icon).addClass('text-bg-'+object.options.color).addClass('shadow').appendTo(object)
		object.item = $(document.createElement('div')).addClass('timeline-item shadow border rounded').appendTo(object)
		object.item.time = $(document.createElement('span')).addClass('time').attr('title',datetime.toLocaleString()).attr('data-bs-placement','top').appendTo(object.item)
		object.item.time.icon = Icon.create('clock').addClass('me-2').appendTo(object.item.time)
		object.item.time.timeago = $(document.createElement('time')).attr('datetime',datetime.toLocaleString()).appendTo(object.item.time).timeago()
		object.item.header = $(document.createElement('h3')).addClass('timeline-header').appendTo(object.item)
		object.item.body = $(document.createElement('div')).addClass('timeline-body').appendTo(object.item)
		object.item.footer = $(document.createElement('div')).addClass('timeline-footer').appendTo(object.item)
		if(object.options.header != null && (typeof object.options.header === 'string' || typeof object.options.header === 'object')){
			object.item.header.html(object.options.header)
		} else {
			object.item.header.hide()
		}
		if(object.options.body != null && (typeof object.options.body === 'string' || typeof object.options.body === 'object')){
			object.item.body.html(object.options.body)
		} else {
			object.item.body.hide()
		}
		if(object.options.footer != null && (typeof object.options.footer === 'string' || typeof object.options.footer === 'object')){
			object.item.footer.html(object.options.footer)
		} else {
			object.item.footer.hide()
		}
		object.attr('data-search',object.text().toString().toUpperCase())
		if(object.options.label){
			self.#label(timeline,order)
		}
		self.#sort(timeline)
		if(typeof callback === 'function'){
			callback(object,timeline)
		}
		return object
	}

	#sort(timeline){
		const self = this
		let objects = timeline.children('div').detach().get()
		objects.sort(function(a, b){
			return new Date($(b).data('order')) - new Date($(a).data('order'))
		});
		timeline.append(objects)
		timeline.find('[data-after]').each(function(){
			let object = $(this)
			// object.removeAttr('data-order')
			if(object.attr('data-after').toString().includes(':')){
				let type = object.attr('data-after').toString().split(':')[0], id = object.attr('data-after').toString().split(':')[1]
				let parent = timeline.find('[data-type="'+type+'"][data-id="'+id+'"]')
				// if(type == 'comment'){
				// 	console.log(type,id)
				// 	console.log(parent,object)
				// }
				if(parent.length > 0){
					parent.after(object)
				}
			}
		})
	}

	#clear(timeline){
		const self = this
		timeline.children().remove()
		self.#object(timeline,{order:'0000000000000',icon:'clock',label: false},function(object){
			object.item.remove()
		})
	}

	create(options = {}, callback = null){
		const self = this
		if(options instanceof Function){ callback = options; options = {}; }
		let timeline = self.#timeline()
		if(typeof options === 'object'){
			for(const [key, value] of Object.entries(options)){
				if(typeof timeline.options[key] !== 'undefined'){
					timeline.options[key] = value
				}
			}
		}
		timeline.sort = function(){
			self.#sort(timeline)
		}
		$('#coreDBSearch').keyup(function(){
			if($(this).val() !== ''){
				timeline.find('.timeline-object[data-search]').hide()
				timeline.find('.timeline-object[data-search*="'+$(this).val().toString().toUpperCase()+'"]').show()
			} else {
				timeline.find('.timeline-object').show()
			}
		})
		if(typeof callback === 'function'){
			callback(timeline)
		}
		return timeline
	}
}

class coreDBNotifications {

  #area = null
  #button = null
  #badge = null
  #banner = null
  #timeline = null
  #clearAll = null
  #api = null
  #clock = null

  constructor(){
    const self = this
    self.#area = $('#NotificationArea')
    self.#button = $('#NotificationsMenu')
    self.#badge = $('#NotificationsMenu span')
    self.#banner = $('#NotificationArea ul li').first()
    self.#banner.count = self.#banner.find('strong')
    self.#timeline = $('#NotificationArea .tl')
    self.#clearAll = $('#NotificationAreaClearAll')
		self.#clearAll.click(function(){
			self.#readAll()
		})
		self.#setCount()
		self.#api = API
		let SystemStatusClock = new coreDBClock({frequence:100})
		SystemStatusClock.add(function(){
			if(SystemStatus.status('maintenance') != null && SystemStatus.status('auth') != null){
				self.#retrieve()
				SystemStatusClock.stop()
			}
		})
		SystemStatusClock.start()
		self.#clock = Clock
		self.#clock.add(function(){
			self.#retrieve()
		})
  }

  #retrieve(){
    const self = this
		if(SystemStatus.status('maintenance') != null && SystemStatus.status('auth') != null){
	    if(self.#api != null && !SystemStatus.status('maintenance') && SystemStatus.status('auth')){
	      self.#api.get('notification/list',{success:function(result,status,xhr){
	        for(var [key, notification] of Object.entries(result)){
	          self.#add(notification)
	        }
	      }})
	    }
		}
  }

  #create(){
    let item = $(document.createElement('div')).addClass('tl-item')
    item.dot = $(document.createElement('div')).addClass('tl-dot').appendTo(item)
    item.content = $(document.createElement('div')).addClass('tl-content ms-2').appendTo(item)
    item.notification = $(document.createElement('div')).appendTo(item.content)
    item.span = $(document.createElement('span')).addClass('tl-date text-muted mt-1').appendTo(item.content)
    item.span.icon = Icon.create('clock').addClass('me-1').appendTo(item.span)
    item.date = $(document.createElement('time')).addClass('timeago').appendTo(item.span)
    return item
  }

  #setCount(){
    const self = this
		const count = self.#timeline.find('div.tl-item[data-isread="0"]').length
    self.#badge.html(count)
    self.#banner.count.html(count)
    if(count > 0){
      self.#badge.show()
    } else {
      self.#badge.hide()
    }
  }

  #read(notification, callback = null){
    const self = this
    if(self.#api != null && !notification.data.isRead){
			if(typeof CSRF !== 'undefined' && CSRF != ''){
	      self.#api.get('notification/read?id='+notification.data.id+'&csrf='+CSRF,{success:function(result,status,xhr){
	        if(notification.find('.b-primary').length > 0){
	          notification.dot.removeClass('b-primary')
	          notification.dot.addClass('b-secondary')
	        }
					notification.data.isRead = 1
					notification.attr('data-isread',1)
	        self.#setCount()
	        if(typeof callback === 'function'){
	          callback(notification)
	        }
	      }})
			}
    }
  }

  #readAll(){
    const self = this
    if(self.#api != null){
			if(typeof CSRF !== 'undefined' && CSRF != ''){
	      self.#api.get('notification/readAll?csrf='+CSRF,{success:function(result,status,xhr){
					self.#timeline.find('[data-isread]').each(function(){
						var notification = $(this)
						if(notification.find('.b-primary').length > 0){
		          notification.find('.b-primary').removeClass('b-primary').addClass('b-secondary')
		        }
						notification.attr('data-isread',1)
					})
	        self.#setCount()
	      }})
			}
    }
  }

  #add(record){
    const self = this
    if(self.#timeline.find('div.tl-item[data-id="'+record.id+'"]').length <= 0){
      let notification = self.#create()
      notification.data = record
      notification.attr('data-id',record.id)
      notification.id = notification.attr('data-id')
      if(record.color.toLowerCase() == 'primary' && record.isRead){
        record.color = 'secondary'
      }
      notification.attr('data-isread',record.isRead)
      notification.dot.addClass('b-'+record.color.toLowerCase())
      notification.notification.html(record.content)
      notification.date.attr('datetime',record.created).html(record.created)
      notification.prependTo(self.#timeline)
			self.#setCount()
      notification.date.timeago()
      if(record.route != null && typeof record.route === 'string'){
        notification.click(function(){
          self.#read(notification,function(item){
            window.open(window.location.protocol+"//"+window.location.hostname+item.data.route,"_self")
          })
        })
      }
      notification.hover(function(){
        notification.timeout = setTimeout(function(){
          self.#read(notification)
        }, 1000);
      }, function(){
        clearTimeout(notification.timeout);
      })
    }
  }
}

class coreDBActivity {

	#object = null
	#offcanvas = null
	#button = null
	#fields = {
		"Activity": "Activity",
		"Close": "Close"
	}
  #api = null

  constructor(){
    const self = this
    self.#api = API
		self.#build()
  }

  #retrieve(type = null,id = null){
    const self = this
		let url = 'activity/list/?type='+type+'&id='+id
		if(type == null && id == null){
			url = 'activity/list/?current'
		}
    if(self.#api != null){
      self.#api.get(url,{success:function(result,status,xhr){
        for(var [key, activity] of Object.entries(result)){
          self.#add(activity)
        }
      }})
    }
  }

	show(type = null,id = null){
    const self = this
		self.#offcanvas.timeline.clear()
		self.#retrieve(type,id)
		self.#offcanvas.bootstrap.show()
	}

	#clear(){
    const self = this
		self.#offcanvas.timeline.html('')
	}

  #add(record){
    const self = this
    if(self.#offcanvas.timeline.find('[data-id="'+record.id+'"]').length <= 0){
      self.#offcanvas.timeline.object({
				icon: record.icon,
				color: record.color,
				type: record.type,
				datetime: record.created,
				header: record.header,
				body: record.body,
				footer: record.footer,
			},function(object){
				object.data = record
				object.attr('data-id',record.id)
				if(record.route != null && typeof record.route === 'string'){
	        object.item.addClass('cursor-pointer').click(function(){
	          window.open(window.location.protocol+"//"+window.location.hostname+record.route,"_self")
	        })
				}
				if(record.callback != null && typeof record.callback === 'string'){
					const callback = eval('('+record.callback+')')
					if(callback != null && callback instanceof Function){ callback(object); }
				}
			})
    }
  }

	#build(){
		const self = this;
		if(self.#offcanvas == null){
			self.#offcanvas = $(document.createElement('div')).addClass('offcanvas offcanvas-end user-select-none').attr('tabindex','-1').attr('id','offcanvasActivity').attr('aria-labelledby','offcanvasActivityLabel');
			self.#offcanvas.id = self.#offcanvas.attr('id');
			self.#offcanvas.header = $(document.createElement('div')).addClass('offcanvas-header bg-image shadow text-light px-4').appendTo(self.#offcanvas);
			self.#offcanvas.title = $(document.createElement('h5')).addClass('offcanvas-title fs-2 fw-light mt-3 ms-2').attr('id','offcanvasActivityLabel').html('<i class="bi-activity me-2"></i>'+self.#fields['Activity']).appendTo(self.#offcanvas.header);
			self.#offcanvas.body = $(document.createElement('div')).addClass('offcanvas-body pt-5').appendTo(self.#offcanvas);
			self.#offcanvas.timeline = Timeline.create()
			self.#offcanvas.timeline.appendTo(self.#offcanvas.body)
			self.#offcanvas.appendTo('body');
		}
		if(self.#object == null){
			if(typeof bootstrap !== 'undefined'){
				self.#offcanvas.bootstrap = new bootstrap.Offcanvas(self.#offcanvas)
			}
		}
		if(self.#button == null){
			self.#button = $(document.createElement('button')).addClass('btn btn-light shadow').attr('type','button').attr('aria-controls',self.#offcanvas.id);
			self.#button.html('<i class="bi-activity me-2"></i>'+self.#fields['Activity'])
			$('.dropdown.profile ul li').last().prepend(self.#button)
			self.#button.click(function(){
				self.show()
			})
		}
	}
}

class coreDBDashboard {

	#object = null
	#offcanvas = null
	#button = null
	#fields = {
		"Activity": "Activity",
		"Close": "Close"
	}
  #api = null
	#container = null
	#editBtn = null
	#saveBtn = null
	#widgets = null

  constructor(){
    const self = this
    self.#api = API
		self.#container = $('#dashboard')
		self.#editBtn = $('#dashboardEditBtn')
		self.#saveBtn = $('#dashboardSaveBtn')
		self.#update()
		self.#events()
  }

	init(organization = ''){
    const self = this
		self.#widget(function(){
			self.#retrieve(organization)
		})
	}

	#events(){
    const self = this
		self.#editBtn.click(function(event){
      event.preventDefault();
			self.#edit()
    })
    self.#saveBtn.click(function(event){
      event.preventDefault();
			self.#save()
    })
	}

	#edit(){
    const self = this
		self.#container.addClass('edit')
		self.#update()
		if(self.#container.find('.row.placeholder').length <= 0){
			self.#container.append(self.#placeholder('row'))
		}
		self.#container.find('.row').each(function(){
			const row = $(this)
			if(row.find('.placeholder').length <= 0 && !row.hasClass('placeholder')){
				row.append(self.#placeholder('col'))
				row.prepend(self.#handle())
			}
		})
		self.#container.find('.col').each(function(){
			const col = $(this)
			if(col.find('.placeholder').length <= 0 && !col.hasClass('placeholder')){
				col.append(self.#placeholder())
			}
			if(!col.hasClass('placeholder')){
				col.prepend(self.#handle())
				self.#sortable(col)
			}
		})
	}

	#save(){
    const self = this
		self.#container.removeClass('edit')
		self.#update()
		self.#container.find('.col').each(function(){
			const col = $(this)
			if(!col.hasClass('placeholder')){
				self.#unsortable(col,true)
			}
		})
		self.#unsortable(self.#container.find('.ui-sortable'),true)
		if(self.#container.hasClass('ui-sortable')){
			self.#unsortable(self.#container,true)
		}
		self.#container.find('.placeholder').remove()
		self.#container.find('.handleCtn').remove()
		if(self.#api != null){
			if(typeof CSRF !== 'undefined' && CSRF != ''){
				let url = 'dashboard/save/?csrf=' + CSRF + '&current'
	      self.#api.post(url,{layout:JSON.stringify(self.#layout())},{success:function(result,status,xhr){
					Toast.create({title:'Saved!',icon:'check-lg',color:'success',close:false})
				}})
			}
    }
	}

  #retrieve(organization = ''){
    const self = this
		let url = 'dashboard/get/?current'
		if(organization != ''){
			url = 'dashboard/get/?type=organizations&id='+organization
		}
    if(self.#api != null){
      self.#api.get(url,{success:function(result,status,xhr){
				if(typeof result[0] !== "undefined"){
					self.#load(JSON.parse(result[0].layout))
				}
      }})
    }
  }

  #widget(name = null){
    const self = this
		if(self.#widgets == null){
			if(self.#api != null){
				let url = 'widget/list/?limit=0'
	      self.#api.get(url,{success:function(result,status,xhr){
					self.#widgets = {}
					for(var [key, widget] of Object.entries(result)){
						self.#widgets[widget.name] = widget
					}
					if(name != null && typeof name === 'function'){
						name()
					}
	      }})
	    }
		}
		if(name != null && typeof name === 'string' && typeof self.#widgets[name] !== 'undefined'){
			return self.#widgets[name]
		}
		return self.#widgets
  }

	#clear(){
    const self = this
		self.#container.html('')
	}

	#unsortable(object, destroy = false){
		if(object.hasClass('ui-sortable')){
			if(destroy){
				object.sortable("destroy")
			} else {
				object.sortable("disable")
			}
		}
	}

	#sortable(object, handle = null){
		const self = this
		let options = {cursor: "move"}
		options.beforeStop = function(event, ui){
			if(ui.item.hasClass('delete')){
				ui.item.remove()
			}
		}
		options.out = function(event, ui){
			ui.item.addClass('delete')
		}
		options.over = function(event, ui){
			ui.item.removeClass('delete')
		}
		options.stop = function(event, ui){
			if(ui.item.hasClass('delete')){
				ui.item.removeClass('delete')
			}
		}
		if(handle != null){
			options.handle = handle
			options.cancel = ''
		}
		object.sortable(options).sortable("enable")
	}

	#update(){
    const self = this
		if(self.#container.hasClass('edit')){
			self.#editBtn.hide()
			self.#saveBtn.show()
		} else {
			self.#saveBtn.hide()
			self.#editBtn.show()
		}
	}

	#handle(){
    const self = this
		let item = $(document.createElement('div')).addClass('btn-group handleCtn shadow fs-4 fw-light w-100').attr('role','group')
		item.move = $(document.createElement('button')).attr('type','button').addClass('btn btn-light text-center').appendTo(item)
		item.move.icon = Icon.create('arrows-move').appendTo(item.move)
		item.delete = $(document.createElement('button')).attr('type','button').addClass('btn btn-danger text-center').appendTo(item)
		item.delete.icon = Icon.create('trash').appendTo(item.delete)
		item.move.mousedown(function(){
			self.#sortable(item.parent().parent(),item.move)
		})
		item.delete.click(function(){
			item.parent().remove()
		})
		return item
	}

	#placeholder(type = null){
    const self = this
		let item = $(document.createElement('div')).addClass('card placeholder')
		item.div = $(document.createElement('div')).appendTo(item)
		switch(type){
			case"row":
				item.icon = Icon.create('columns').appendTo(item.div)
				item.addClass(type)
				item.click(function(){
					let body = $(document.createElement('div')).addClass('row row-cols-2')
					let col = $(document.createElement('div')).addClass('col my-2')
					let radio = $(document.createElement('input')).addClass('btn-check').attr('type','radio')
					let label = $(document.createElement('label')).addClass('btn btn-outline-primary border w-100 shadow-sm')
					label.row = $(document.createElement('div')).addClass('row h-100 px-2')
					label.col = $(document.createElement('div')).addClass('col bg-secondary py-2 rounded m-2')
					for(var id=1;id <= 4;id++){
						const option = col.clone().appendTo(body)
						option.option = radio.clone().attr('data-value','row-cols-'+id).attr('id','option'+id).attr('name','row').appendTo(option)
						option.label = label.clone().attr('for','option'+id).appendTo(option)
						option.label.row = label.row.clone().appendTo(option.label)
						for(var count=1;count <= id;count++){
							label.col.clone().appendTo(option.label.row)
						}
					}
					Modal.create({title:'Row',icon:'columns',size:'lg',body:body},function(modal){
						modal.footer.group.primary.click(function(){
							let row = $(document.createElement('div')).addClass('row').append(self.#placeholder('col'))
							body.find('input[type="radio"]:checked').each(function(){
								row.addClass($(this).attr('data-value'))
							})
							item.before(row)
							row.prepend(self.#handle())
							modal.bootstrap.hide()
						})
					})
				})
				break
			case"col":
				item.icon = Icon.create('collection').appendTo(item.div)
				item.addClass(type)
				item.click(function(){
					let body = $(document.createElement('div')).addClass('row row-cols-2')
					let col = $(document.createElement('div')).addClass('col my-2')
					let radio = $(document.createElement('input')).addClass('btn-check').attr('type','radio')
					let label = $(document.createElement('label')).addClass('btn btn-outline-primary border w-100 shadow-sm')
					label.row = $(document.createElement('div')).addClass('row h-100 px-2')
					label.col = $(document.createElement('div')).addClass('col bg-secondary py-2 rounded m-2')
					if($(this).parent().hasClass('row-cols-4')){
						let option = col.clone().appendTo(body)
						option.option = radio.clone().attr('data-value','col').attr('id','option-3').attr('name','row').appendTo(option)
						option.label = label.clone().attr('for','option-3').appendTo(option)
						option.label.row = label.row.clone().appendTo(option.label)
						label.col.clone().addClass('col-3').appendTo(option.label.row)
						option = col.clone().appendTo(body)
						option.option = radio.clone().attr('data-value','col-6').attr('id','option-6').attr('name','row').appendTo(option)
						option.label = label.clone().attr('for','option-6').appendTo(option)
						option.label.row = label.row.clone().appendTo(option.label)
						label.col.clone().addClass('col-6').appendTo(option.label.row)
						option = col.clone().appendTo(body)
						option.option = radio.clone().attr('data-value','col-9').attr('id','option-9').attr('name','row').appendTo(option)
						option.label = label.clone().attr('for','option-9').appendTo(option)
						option.label.row = label.row.clone().appendTo(option.label)
						label.col.clone().addClass('col-9').appendTo(option.label.row)
						option = col.clone().appendTo(body)
						option.option = radio.clone().attr('data-value','col-12').attr('id','option-12').attr('name','row').appendTo(option)
						option.label = label.clone().attr('for','option-12').appendTo(option)
						option.label.row = label.row.clone().appendTo(option.label)
						label.col.clone().removeClass('m-2').addClass('my-2 col-12').appendTo(option.label.row)
					}
					if($(this).parent().hasClass('row-cols-3')){
						let option = col.clone().appendTo(body)
						option.option = radio.clone().attr('data-value','col').attr('id','option-4').attr('name','row').appendTo(option)
						option.label = label.clone().attr('for','option-4').appendTo(option)
						option.label.row = label.row.clone().appendTo(option.label)
						label.col.clone().addClass('col-4').appendTo(option.label.row)
						option = col.clone().appendTo(body)
						option.option = radio.clone().attr('data-value','col-8').attr('id','option-8').attr('name','row').appendTo(option)
						option.label = label.clone().attr('for','option-8').appendTo(option)
						option.label.row = label.row.clone().appendTo(option.label)
						label.col.clone().addClass('col-8').appendTo(option.label.row)
						option = col.clone().appendTo(body)
						option.option = radio.clone().attr('data-value','col-12').attr('id','option-12').attr('name','row').appendTo(option)
						option.label = label.clone().attr('for','option-12').appendTo(option)
						option.label.row = label.row.clone().appendTo(option.label)
						label.col.clone().removeClass('m-2').addClass('my-2 col-12').appendTo(option.label.row)
					}
					if($(this).parent().hasClass('row-cols-2')){
						let option = col.clone().appendTo(body)
						option.option = radio.clone().attr('data-value','col').attr('id','option-6').attr('name','row').appendTo(option)
						option.label = label.clone().attr('for','option-6').appendTo(option)
						option.label.row = label.row.clone().appendTo(option.label)
						label.col.clone().addClass('col-6').appendTo(option.label.row)
						option = col.clone().appendTo(body)
						option.option = radio.clone().attr('data-value','col-12').attr('id','option-12').attr('name','row').appendTo(option)
						option.label = label.clone().attr('for','option-12').appendTo(option)
						option.label.row = label.row.clone().appendTo(option.label)
						label.col.clone().removeClass('m-2').addClass('my-2 col-12').appendTo(option.label.row)
					}
					if($(this).parent().hasClass('row-cols-1')){
						let option = col.clone().appendTo(body)
						option.option = radio.clone().attr('data-value','col').attr('id','option-12').attr('name','row').appendTo(option)
						option.label = label.clone().attr('for','option-12').appendTo(option)
						option.label.row = label.row.clone().appendTo(option.label)
						label.col.clone().removeClass('m-2').addClass('my-2 col-12').appendTo(option.label.row)
					}
					Modal.create({title:'Col',icon:'collection',size:'lg',body:body},function(modal){
						modal.footer.group.primary.click(function(){
							let col = $(document.createElement('div')).addClass('col').append(self.#placeholder())
							body.find('input[type="radio"]:checked').each(function(){
								col.addClass($(this).attr('data-value'))
							})
							item.before(col)
							self.#sortable(col)
							col.prepend(self.#handle())
							modal.bootstrap.hide()
						})
					})
				})
				break
			default:
				item.icon = Icon.create('rocket-takeoff').appendTo(item.div)
				item.click(function(){
					let body = $(document.createElement('div'))
					body.preview = $(document.createElement('div')).addClass('card shadow').appendTo(body)
					body.preview.header = $(document.createElement('div')).addClass('card-header').appendTo(body.preview)
					body.preview.header.title = $(document.createElement('h5')).addClass('card-title fw-light').html('Preview').appendTo(body.preview.header)
					body.preview.header.title.icon = Icon.create('eye').addClass('me-2').prependTo(body.preview.header.title)
					body.preview.body = $(document.createElement('div')).addClass('card-body p-4').appendTo(body.preview)
					body.selector = $(document.createElement('div')).addClass('mt-3').appendTo(body)
					body.select = $(document.createElement('select')).addClass('form-select shadow').attr('aria-label','Select a Widget').appendTo(body.selector)
					$(document.createElement('option')).html('Select a Widget').appendTo(body.select)
					for(var [name, widget] of Object.entries(self.#widget())){
						$(document.createElement('option')).attr('value',name).html(name).appendTo(body.select)
					}
					Modal.create({title:'Widget',icon:'rocket-takeoff',size:'lg',body:body},function(modal){
						modal.find('select').select2({dropdownParent: modal})
						modal.widget = null
						body.select.change(function(){
							modal.widget = $(self.#widgets[$(this).val()].element)
							modal.widget.attr('data-widget',$(this).val())
							body.preview.body.html(modal.widget)
						});
						modal.footer.group.primary.click(function(){
							if(modal.widget != null){
								item.before(modal.widget)
								modal.bootstrap.hide()
							}
						})
					})
				})
				break
		}
		return item
	}

	#load(widgets){
    const self = this
		self.#clear()
		for(const [rowKey, row] of Object.entries(widgets)){
			const rowClass = Object.keys(row)[0];
			const rowCols = row[rowClass];
			const rowObj = $(document.createElement('div')).addClass('row').addClass(rowClass);
			rowObj.appendTo(self.#container)
			for(const [colKey, col] of Object.entries(rowCols)){
				const colClass = Object.keys(col)[0];
				const colWidgets = col[colClass];
				const colObj = $(document.createElement('div')).addClass('col').addClass(colClass);
				colObj.appendTo(rowObj)
				for(const [widgetKey, widget] of Object.entries(colWidgets)){
					const widgetObj = self.#widget(widget);
					widgetObj.obj = $(widgetObj.element)
					widgetObj.obj.attr('data-widget',widget).appendTo(colObj)
					if(widgetObj.callback != null){
						const callback = eval('('+widgetObj.callback+')')
						if(callback != null && callback instanceof Function){ callback(widgetObj.obj); }
					}
				}
			}
		}
	}

	#layout(){
    const self = this
		let rows = [], widgets = []
		self.#container.find('.row').each(function(){
			const thisRow = $(this)
			const row = {}
			let rowClassList = thisRow.attr("class")
			let rowClassArr = rowClassList.split(/\s+/)
			for(const [key, rowClassName] of Object.entries(rowClassArr)){
				switch(rowClassName){
					case"row-cols-1":
					case"row-cols-2":
					case"row-cols-3":
					case"row-cols-4":
						row[rowClassName] = []
						thisRow.find('.col').each(function(){
							const thisCol = $(this)
							const col = {}
							let thisCololClassName = 'col'
							let colClassList = thisCol.attr("class")
							let colClassArr = colClassList.split(/\s+/)
							if(colClassArr.length > 1){
								for(const [key, colClassName] of Object.entries(colClassArr)){
									switch(colClassName){
										case"col-6":
										case"col-8":
										case"col-9":
										case"col-12":
											thisCololClassName = colClassName
											break
									}
								}
							}
							col[thisCololClassName] = []
							thisCol.find('[data-widget]').each(function(){
								const thisWidget = $(this)
								col[thisCololClassName].push(thisWidget.attr('data-widget'))
							})
							row[rowClassName].push(col)
						})
						rows.push(row)
						break
				}
			}
		})
		return rows
	}
}

class coreDBSystemStatus {

  #api = null
  #clock = null
  #status = {}
  #modal = null
	#logged = null

  constructor(){
    const self = this
		self.#api = API
		self.#retrieve()
		self.#clock = Clock
		self.#clock.add(function(){
			self.#retrieve()
		})
  }

  #retrieve(){
    const self = this
    if(self.#api != null){
      self.#api.get('status/list',{success:function(result,status,xhr){
				self.#status = result
				self.#callback()
      }})
    }
  }

	#callback(){
    const self = this
		if(typeof self.#status.maintenance !== 'undefined'){
			if(self.#status.maintenance && window.location.pathname != '/maintenance'){
				window.open(window.location.protocol+"//"+window.location.hostname+'/maintenance',"_self")
			}
			if(!self.#status.maintenance && window.location.pathname == '/maintenance'){
				window.open(window.location.protocol+"//"+window.location.hostname,"_self")
			}
		}
		if(typeof self.#status.user !== 'undefined'){
			switch(self.#status.user){
				case null:
					if(self.#modal == null && self.#logged){
						self.#modal = Modal.create({title:'Account Disconnected',center:true,icon:'info-circle',body:'Your account was disconnected.',close:false,cancel:false,static:true},function(modal){
							modal.footer.group.primary.html('<i class="bi-box-arrow-right me-2"></i>Sign out')
							modal.footer.group.primary.click(function(){
								if(typeof CSRF !== 'undefined' && CSRF != ''){
									window.open(window.location.protocol+"//"+window.location.hostname+window.location.pathname+'?signout&csrf='+CSRF,"_self")
								}
							})
						})
					}
					break
				case 3:
					if(self.#modal != null){
						self.#modal.bootstrap.hide()
						self.#modal = null
					}
					self.#logged = true
					break
				case 2:
					if(self.#modal == null){
						self.#modal = Modal.create({title:'Account Suspended',center:true,icon:'exclamation-diamond',body:'Your account was suspended for security reasons. We detected some unusual activity with your account. Please contact the support team.',close:false,cancel:false,static:true},function(modal){
							modal.footer.group.primary.html('<i class="bi-box-arrow-right me-2"></i>Sign out')
							modal.footer.group.primary.click(function(){
								if(typeof CSRF !== 'undefined' && CSRF != ''){
									window.open(window.location.protocol+"//"+window.location.hostname+window.location.pathname+'?signout&csrf='+CSRF,"_self")
								}
							})
						})
					}
					break
				case 1:
					if(self.#modal == null){
						self.#modal = Modal.create({title:'Account Disabled',center:true,icon:'exclamation-triangle',body:'Your account was disabled by an administrator. Please contact the support team.',close:false,cancel:false,static:true},function(modal){
							modal.footer.group.primary.html('<i class="bi-box-arrow-right me-2"></i>Sign out')
							modal.footer.group.primary.click(function(){
								if(typeof CSRF !== 'undefined' && CSRF != ''){
									window.open(window.location.protocol+"//"+window.location.hostname+window.location.pathname+'?signout&csrf='+CSRF,"_self")
								}
							})
						})
					}
					break
				case 0:
					if(self.#modal == null){
						self.#modal = Modal.create({title:'Account Deactivated',center:true,icon:'exclamation-circle',body:'Your account is deactivated. Please contact the support team.',close:false,cancel:false,static:true},function(modal){
							modal.footer.group.primary.html('<i class="bi-box-arrow-right me-2"></i>Sign out')
							modal.footer.group.primary.click(function(){
								if(typeof CSRF !== 'undefined' && CSRF != ''){
									window.open(window.location.protocol+"//"+window.location.hostname+window.location.pathname+'?signout&csrf='+CSRF,"_self")
								}
							})
						})
					}
					break
			}
		}
	}

	status(name = null){
    const self = this
		if(name != null && typeof name === 'string' && typeof self.#status[name] !== 'undefined'){ return self.#status[name]; }
		return null
	}
}

// Components
const Icon = new coreDBIcon()
const Modal = new coreDBModal()
const Toast = new coreDBToast()
const Timeline = new coreDBTimeline()
// const Card = new coreDBCard()
const Dropdown = new coreDBDropdown()
const Table = new coreDBTable()
// Core Utilities
const API = new phpAPI('/api.php')
if(typeof phpAuthCookie === 'function'){
	const Cookie = new phpAuthCookie()
}
const Clock = new coreDBClock({frequence:30000})
const SystemStatus = new coreDBSystemStatus()
const Auth = new coreDBAuth()
const File = new coreDBFile()
// Core Elements
const Notifications = new coreDBNotifications()
const Activity = new coreDBActivity()
const Dashboard = new coreDBDashboard()
// Core Theme
const Style = getComputedStyle(document.body);
const Theme = {
  primary: Style.getPropertyValue('--bs-primary'),
  secondary: Style.getPropertyValue('--bs-secondary'),
  success: Style.getPropertyValue('--bs-success'),
  info: Style.getPropertyValue('--bs-info'),
  warning: Style.getPropertyValue('--bs-warning'),
  danger: Style.getPropertyValue('--bs-danger'),
  light: Style.getPropertyValue('--bs-light'),
  dark: Style.getPropertyValue('--bs-dark'),
};

// Configure API Requests
API.setDefaults({
	error:function(xhr,status,error){
		console.log(xhr,status,error)
		if(typeof xhr.responseJSON !== 'undefined'){
			Toast.create({title:xhr.status+': '+error,body:xhr.responseJSON.error,icon:'x-octagon',color:'danger',autohide:true,close:true,delay:30000})
		} else {
			Toast.create({title:xhr.status+': '+error,body:xhr.responseText,icon:'x-octagon',color:'danger',autohide:true,close:true,delay:30000})
		}
	}
})

// Start Internal Clock
Clock.start()

// Document is Ready
$.holdReady(false)
