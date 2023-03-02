Date.prototype.today = function () {
	return this.getFullYear() + "-" +(((this.getMonth()+1) < 10)?"0":"") + (this.getMonth()+1) + "-" + ((this.getDate() < 10)?"0":"") + this.getDate();
}

Date.prototype.timeNow = function () {
	return ((this.getHours() < 10)?"0":"") + this.getHours() + ":" + ((this.getMinutes() < 10)?"0":"") + this.getMinutes() + ":" + ((this.getSeconds() < 10)?"0":"") + this.getSeconds();
}

jQuery.expr[':'].contains = function(a, i, m){
  return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0
}

$.fn.select2.defaults.set("theme", "bootstrap-5")
$.fn.select2.defaults.set("width", "100%")
$.fn.select2.defaults.set("allowClear", true)

function randomNumber(min = -10, max = 10){
	return Math.floor(Math.random() * (max - min + 1) + min)
}

function convertStringToDate(string){
	let year = null, month = null, day = null, hour = null, minute = null, second = null, date = null, time = null, datetime = null, array = []
	if(string.includes(' ') || string.includes('T') || string.includes('t')){
		if(string.includes('T')){
			array = string.split('T')
		}
		if(string.includes('t')){
			array = string.split('t')
		}
		if(string.includes(' ')){
			array = string.split(' ')
		}
		if(array.lenght > 1){
			time = array[1]
		}
		if(time != null && time.toString().includes(':')){
			array = time.toString().split(':')
			if(array.lenght > 1){
				hour = array[0]
				minute = array[1]
				if(array.lenght > 2){
					second = array[2]
				}
			}
		}
	}
	// new Date(datetime)
}

function validateEmail($email) {
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/
	return ( $email.length > 0 && emailReg.test($email))
}

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
		let input = $(document.createElement('textarea')).appendTo('body')
		console.log(object.text())
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

const md5 = (function() {
  var MD5 = function (d) {
    return M(V(Y(X(d), 8 * d.length)))
  }
  function M (d) {
    for (var _, m = '0123456789abcdef', f = '', r = 0; r < d.length; r++) {
      _ = d.charCodeAt(r)
      f += m.charAt(_ >>> 4 & 15) + m.charAt(15 & _)
    }
    return f
  }
  function X (d) {
    for (var _ = Array(d.length >> 2), m = 0; m < _.length; m++) {
      _[m] = 0
    }
    for (m = 0; m < 8 * d.length; m += 8) {
      _[m >> 5] |= (255 & d.charCodeAt(m / 8)) << m % 32
    }
    return _
  }
  function V (d) {
    for (var _ = '', m = 0; m < 32 * d.length; m += 8) _ += String.fromCharCode(d[m >> 5] >>> m % 32 & 255)
    return _
  }
  function Y (d, _) {
    d[_ >> 5] |= 128 << _ % 32
    d[14 + (_ + 64 >>> 9 << 4)] = _
    for (var m = 1732584193, f = -271733879, r = -1732584194, i = 271733878, n = 0; n < d.length; n += 16) {
      var h = m
      var t = f
      var g = r
      var e = i
      f = md5ii(f = md5ii(f = md5ii(f = md5ii(f = md5hh(f = md5hh(f = md5hh(f = md5hh(f = md5gg(f = md5gg(f = md5gg(f = md5gg(f = md5ff(f = md5ff(f = md5ff(f = md5ff(f, r = md5ff(r, i = md5ff(i, m = md5ff(m, f, r, i, d[n + 0], 7, -680876936), f, r, d[n + 1], 12, -389564586), m, f, d[n + 2], 17, 606105819), i, m, d[n + 3], 22, -1044525330), r = md5ff(r, i = md5ff(i, m = md5ff(m, f, r, i, d[n + 4], 7, -176418897), f, r, d[n + 5], 12, 1200080426), m, f, d[n + 6], 17, -1473231341), i, m, d[n + 7], 22, -45705983), r = md5ff(r, i = md5ff(i, m = md5ff(m, f, r, i, d[n + 8], 7, 1770035416), f, r, d[n + 9], 12, -1958414417), m, f, d[n + 10], 17, -42063), i, m, d[n + 11], 22, -1990404162), r = md5ff(r, i = md5ff(i, m = md5ff(m, f, r, i, d[n + 12], 7, 1804603682), f, r, d[n + 13], 12, -40341101), m, f, d[n + 14], 17, -1502002290), i, m, d[n + 15], 22, 1236535329), r = md5gg(r, i = md5gg(i, m = md5gg(m, f, r, i, d[n + 1], 5, -165796510), f, r, d[n + 6], 9, -1069501632), m, f, d[n + 11], 14, 643717713), i, m, d[n + 0], 20, -373897302), r = md5gg(r, i = md5gg(i, m = md5gg(m, f, r, i, d[n + 5], 5, -701558691), f, r, d[n + 10], 9, 38016083), m, f, d[n + 15], 14, -660478335), i, m, d[n + 4], 20, -405537848), r = md5gg(r, i = md5gg(i, m = md5gg(m, f, r, i, d[n + 9], 5, 568446438), f, r, d[n + 14], 9, -1019803690), m, f, d[n + 3], 14, -187363961), i, m, d[n + 8], 20, 1163531501), r = md5gg(r, i = md5gg(i, m = md5gg(m, f, r, i, d[n + 13], 5, -1444681467), f, r, d[n + 2], 9, -51403784), m, f, d[n + 7], 14, 1735328473), i, m, d[n + 12], 20, -1926607734), r = md5hh(r, i = md5hh(i, m = md5hh(m, f, r, i, d[n + 5], 4, -378558), f, r, d[n + 8], 11, -2022574463), m, f, d[n + 11], 16, 1839030562), i, m, d[n + 14], 23, -35309556), r = md5hh(r, i = md5hh(i, m = md5hh(m, f, r, i, d[n + 1], 4, -1530992060), f, r, d[n + 4], 11, 1272893353), m, f, d[n + 7], 16, -155497632), i, m, d[n + 10], 23, -1094730640), r = md5hh(r, i = md5hh(i, m = md5hh(m, f, r, i, d[n + 13], 4, 681279174), f, r, d[n + 0], 11, -358537222), m, f, d[n + 3], 16, -722521979), i, m, d[n + 6], 23, 76029189), r = md5hh(r, i = md5hh(i, m = md5hh(m, f, r, i, d[n + 9], 4, -640364487), f, r, d[n + 12], 11, -421815835), m, f, d[n + 15], 16, 530742520), i, m, d[n + 2], 23, -995338651), r = md5ii(r, i = md5ii(i, m = md5ii(m, f, r, i, d[n + 0], 6, -198630844), f, r, d[n + 7], 10, 1126891415), m, f, d[n + 14], 15, -1416354905), i, m, d[n + 5], 21, -57434055), r = md5ii(r, i = md5ii(i, m = md5ii(m, f, r, i, d[n + 12], 6, 1700485571), f, r, d[n + 3], 10, -1894986606), m, f, d[n + 10], 15, -1051523), i, m, d[n + 1], 21, -2054922799), r = md5ii(r, i = md5ii(i, m = md5ii(m, f, r, i, d[n + 8], 6, 1873313359), f, r, d[n + 15], 10, -30611744), m, f, d[n + 6], 15, -1560198380), i, m, d[n + 13], 21, 1309151649), r = md5ii(r, i = md5ii(i, m = md5ii(m, f, r, i, d[n + 4], 6, -145523070), f, r, d[n + 11], 10, -1120210379), m, f, d[n + 2], 15, 718787259), i, m, d[n + 9], 21, -343485551)
      m = safeadd(m, h)
      f = safeadd(f, t)
      r = safeadd(r, g)
      i = safeadd(i, e)
    }
    return [m, f, r, i]
  }
  function md5cmn (d, _, m, f, r, i) {
    return safeadd(bitrol(safeadd(safeadd(_, d), safeadd(f, i)), r), m)
  }
  function md5ff (d, _, m, f, r, i, n) {
    return md5cmn(_ & m | ~_ & f, d, _, r, i, n)
  }
  function md5gg (d, _, m, f, r, i, n) {
    return md5cmn(_ & f | m & ~f, d, _, r, i, n)
  }
  function md5hh (d, _, m, f, r, i, n) {
    return md5cmn(_ ^ m ^ f, d, _, r, i, n)
  }
  function md5ii (d, _, m, f, r, i, n) {
    return md5cmn(m ^ (_ | ~f), d, _, r, i, n)
  }
  function safeadd (d, _) {
    var m = (65535 & d) + (65535 & _)
    return (d >> 16) + (_ >> 16) + (m >> 16) << 16 | 65535 & m
  }
  function bitrol (d, _) {
    return d << _ | d >>> 32 - _
  }
  function MD5Unicode(buffer){
    if (!(buffer instanceof Uint8Array)) {
      buffer = new TextEncoder().encode(typeof buffer==='string' ? buffer : JSON.stringify(buffer));
    }
    var binary = [];
    var bytes = new Uint8Array(buffer);
    for (var i = 0, il = bytes.byteLength; i < il; i++) {
      binary.push(String.fromCharCode(bytes[i]));
    }
    return MD5(binary.join(''));
  }

  return MD5Unicode;
})()

class coreDBClock {
	#timeout = null;
	#frequence = 5000;
	#callbacks = [];

	constructor(options = {}){
		this.config(options)
	}
  config(options = {}){
    for(var [option, value] of Object.entries(options)){
      if(option == 'frequence'){
				this.#frequence = parseInt(value)
			}
    }
		return this
  }
	status(){
		return this.#timeout != null
	}
	start(){
		const self = this
		if(self.#timeout == null){
			self.#timeout = setInterval(function(){
				self.exec()
			}, self.#frequence)
		}
		return this
	}
	stop(){
		const self = this
		if(self.#timeout != null){
			clearInterval(self.#timeout)
			self.#timeout = null
		}
		return this
	}
	exec(){
		const self = this
		for(var [key, callback] of Object.entries(self.#callbacks)){
			callback()
		}
	}
	add(callback = null){
		if(callback != null && callback instanceof Function){
			this.#callbacks.push(callback)
		}
		return this;
	}
	clear(){
		this.#callbacks = []
		return this
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

	base64toSimple(base64Data){
		base64Data = atob(base64Data).toString()
		return base64Data
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

	preview(id = null){
		const self = this
		if(id != null){
			Modal.create({title:'Preview',icon:'eye',color:'primary',size:'lg',body:''},function(modal){
				modal.header.addClass('text-bg-primary')
				modal.footer.group.primary.remove()
				modal.footer.group.cancel.html('Close').css('border-radius', '0px 0px var(--bs-border-radius) var(--bs-border-radius)')
				modal.body.addClass('p-0')
				modal.body.content = $(document.createElement('div')).addClass('d-flex align-items-center justify-content-center h-100 w-100 overflow-auto').css('max-height','calc(100vh - 179px)').appendTo(modal.body)
				modal.header.group.expand.click(function(){
					if(modal.dialog.hasClass('modal-fullscreen')){
						modal.body.content.css('max-height','calc(100vh - 120px)')
					} else {
						modal.body.content.css('max-height','calc(100vh - 179px)')
					}
				})
				self.#api.get("file/download/?id="+id+"&csrf="+CSRF,{success:function(file,status,xhr){
					modal.header.title.append(' - '+file.name+' ('+self.formatBytes(file.size)+')')
					var type = ''
					switch(file.type.toString().toUpperCase()){
						case "PNG":
						case "GIF":
						case "GIFF":
						case "TIF":
						case "TIFF":
						case "JPG":
						case "JPEG":
							type = 'image/'+file.type
							break
						case "PDF":
							type = 'application/'+file.type
							break
					}
					file.simple = self.base64toSimple(file.content)
					file.blob = self.base64toBlob(file.content, type)
					file.url = URL.createObjectURL(file.blob)
					console.log(file)
					switch(file.type.toString().toUpperCase()){
						case "PNG":
						case "GIF":
						case "GIFF":
						case "TIF":
						case "TIFF":
						case "JPG":
						case "JPEG":
							modal.body.content.addClass('p-3')
							modal.body.content.image = $(document.createElement('img')).addClass('border shadow rounded p-3').attr('src',file.url).attr('alt',file.name).attr('title',file.name).addClass('mw-100 mh-100').appendTo(modal.body.content)
							break;
						case "HTM":
						case "HTML":
							modal.body.content.object = $(document.createElement('div')).html(file.simple).attr('title',file.name).addClass('mw-100 mh-100 h-100 w-100').css('min-height','500px').appendTo(modal.body.content)
							break;
						case "PDF":
							modal.body.content.iframe = $(document.createElement('iframe')).attr('src',file.url).attr('type','application/pdf').attr('title',file.name).addClass('mw-100 mh-100 w-100 h-100').css('min-height','500px').appendTo(modal.body.content)
							break;
						case "DOC":
						case "DOCX":
						case "CSV":
						case "XLS":
						case "XLSX":
						case "XLSM":
						case "PPT":
						case "PPTX":
							// modal.body.addClass('p-0')
							// modal.body.content.iframe = $(document.createElement('iframe')).attr('src','https://view.officeapps.live.com/op/embed.aspx?src=' + file.url).attr('type','application/pdf').attr('title',file.name).addClass('mw-100 mh-100 w-100').css('height','500px').appendTo(modal.body.content)
							// modal.body.content.iframe = $(document.createElement('iframe')).attr('src','https://docs.google.com/gview?url=' + file.url).attr('type','application/pdf').attr('title',file.name).addClass('mw-100 mh-100 w-100').css('height','500px').appendTo(modal.body.content)
							// break;
						default:
							modal.body.content.pre = $(document.createElement('pre')).text(file.simple).attr('title',file.name).addClass('mw-100 mh-100 w-100 overflow-auto m-0 p-3 text-break').attr('style', 'min-height: 179px;max-height: calc(100vh - 179px) !important;').appendTo(modal.body.content)
							modal.header.group.expand.click(function(){
								if(modal.dialog.hasClass('modal-fullscreen')){
									modal.body.content.pre.attr('style', 'min-height: 179px;max-height: calc(100vh - 120px) !important;')
								} else {
									modal.body.content.pre.attr('style', 'min-height: 179px;max-height: calc(100vh - 179px) !important;')
								}
							})
							break;
					}
				}})
			})
		}
	}

	upload(dataCallback = null, returnCallback = null){
		const self = this
		Modal.create({title:'Upload',icon:'upload',color:'success',size:'lg',body:''},function(modal){
			modal.header.addClass('text-bg-success')
			modal.footer.group.primary.html('Upload')
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
				previewClass: 'm-0 rounded border shadow', // Additional
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
					prev: 'btn btn-navigate btn-light border shadow ms-4',
					next: 'btn btn-navigate btn-light border shadow me-4',
					rotate: 'btn btn-kv btn-light border shadow',
					toggleheader: 'btn btn-kv btn-light border shadow d-none',
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
						if(file.type.toString().includes("openxmlformats")){
							var array = file.name.toString().split('.')
							object.type = array[array.length - 1]
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

class coreDBSearch {

	#field = null

	constructor(object = null){
		const self = this
		if(typeof object === 'string'){
			object = $(object)
		}
		if(typeof object === 'object' && object != null){
			self.#field = object
		} else {
			self.#field = $('#coreDBSearch')
		}
	}

	get(){
		const self = this
		return self.#field
	}

	set(object){
		const self = this
		if(typeof object === 'string'){
			object = $(object)
		}
		if(typeof object === 'object' && object != null){
			object.attr('data-search',object.text().toString().toUpperCase())
		}
	}

	add(object){
		const self = this
		if(typeof object === 'string'){
			object = $(object)
		}
		if(typeof object === 'object' && object != null){
			self.#field.keyup(function(){
				if($(this).val() !== ''){
			    object.find('[data-search]').hide()
			    object.find('[data-search*="'+$(this).val().toString().toUpperCase()+'"]').show()
				} else {
					object.find('[data-search]').show()
				}
			})
		}
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

	#count = 0

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
		self.#count++
		let modal = $(document.createElement('div')).addClass('modal fade').attr('id','modal' + self.#count).attr('tabindex',-1)
		modal.id = modal.attr('id')
		modal.options = options
		modal.dialog = $(document.createElement('div')).addClass('modal-dialog user-select-none').appendTo(modal)
		modal.content = $(document.createElement('div')).addClass('modal-content').appendTo(modal.dialog)
		modal.header = $(document.createElement('div')).addClass('modal-header shadow-sm').appendTo(modal.content)
		modal.header.container = $(document.createElement('h5')).addClass('modal-title fw-light').appendTo(modal.header)
		modal.header.title = $(document.createElement('span')).appendTo(modal.header.container)
		modal.header.icon = Icon.create('').addClass('me-2').prependTo(modal.header.container)
		modal.header.group = $(document.createElement('div')).addClass('btn-group shadow').appendTo(modal.header)
		modal.header.group.expand = $(document.createElement('button')).addClass('btn btn-light border').html('<i class="bi-fullscreen"></i>').attr('type','button').appendTo(modal.header.group)
		modal.header.group.close = $(document.createElement('button')).addClass('btn btn-light border').html('<i class="bi-x-lg"></i>').attr('type','button').attr('data-bs-dismiss','modal').attr('aria-label','Close').appendTo(modal.header.group)
		modal.body = $(document.createElement('div')).addClass('modal-body').appendTo(modal.content)
		modal.footer = $(document.createElement('div')).addClass('modal-footer p-0').appendTo(modal.content)
		modal.footer.group = $(document.createElement('div')).addClass('btn-group btn-lg w-100 m-0 rounded-bottom').appendTo(modal.footer)
		modal.footer.group.cancel = $(document.createElement('button')).addClass('btn btn-light btn-lg fw-light').css('border-radius', '0px 0px 0px var(--bs-border-radius)').html('Cancel').attr('type','button').attr('data-bs-dismiss','modal').appendTo(modal.footer.group)
		modal.footer.group.primary = $(document.createElement('button')).addClass('btn btn-primary btn-lg fw-light').css('border-radius', '0px 0px var(--bs-border-radius) 0px').html('Ok').attr('type','button').appendTo(modal.footer.group)
		modal.on('hide.bs.modal',function(){
			$(this).remove()
		})
		modal.header.group.expand.click(function(){
			if(modal.dialog.hasClass('modal-fullscreen')){
				modal.dialog.removeClass('modal-fullscreen')
				modal.header.group.expand.html('<i class="bi-fullscreen"></i>')
			} else {
				modal.dialog.addClass('modal-fullscreen')
				modal.header.group.expand.html('<i class="bi-fullscreen-exit"></i>')
			}
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
			modal.header.addClass('text-bg-'+modal.options.color)
		}
		if(modal.options.close == null || typeof modal.options.close !== 'boolean' || !modal.options.close){
			modal.header.group.close.remove()
			delete modal.header.group.close
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
	#count = 0

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
		self.#count++
		let toast = $(document.createElement('div')).attr('id','toast' + self.#count).addClass('toast shadow').attr('role','status').attr('aria-live','polite').attr('aria-atomic','true').appendTo(self.#container.list)
		toast.id = toast.attr('id')
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

	#count = 0

	constructor(){}

	create(actions = {}, callback = null){
		const self = this
		self.#count++
    let object = $(document.createElement('div')).addClass('dropdown').attr('id','dropdown' + self.#count)
		object.id = object.attr('id')
    object.btn = $(document.createElement('a')).addClass('link-dark').attr('href','').attr('data-bs-toggle','dropdown').attr('data-bs-popper-config','{"strategy":"fixed"}').attr('aria-expanded','false').appendTo(object)
    object.btn.icon = Icon.create('three-dots-vertical').appendTo(object.btn)
    object.menu = $(document.createElement('ul')).addClass('dropdown-menu').appendTo(object)
		for(var [action, properties] of Object.entries(actions)){
			object.menu[action] = $(document.createElement('li')).appendTo(object.menu)
			object.menu[action].btn = $(document.createElement('button')).attr('type','button').attr('data-action',action).addClass('dropdown-item').html(properties.label).appendTo(object.menu[action])
			if(typeof properties.bgColor === "string"){
				object.menu[action].btn.addClass('text-bg-'+properties.bgColor)
			}
			if(typeof properties.textColor === "string"){
				object.menu[action].btn.addClass('text-'+properties.textColor)
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

	#count = 0

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
		self.#Dropdown = Dropdown
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
		self.#count++
		let table = $(document.createElement('table')).attr('id','table' + self.#count).addClass('table table-striped m-0 w-100 user-select-none')
		table.id = table.attr('id')
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
					Search.get().keyup(function(){
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

	#count = 0

	constructor(){}

	#timeline(options = {}){
		const self = this
		let defaults = {
			class: {
				timeline: null,
        item: null,
        icon: null,
        header: null,
        body: null,
        footer: null,
			},
			order: 'DESC',
		}
		if(typeof defaults === 'object'){
			for(const [key, value] of Object.entries(options)){
				if(typeof defaults[key] !== 'undefined'){
					defaults[key] = value
				}
			}
		}
		self.#count++
		let timeline = $(document.createElement('div')).attr('id','timeline' + self.#count).addClass('timeline')
		timeline.id = timeline.attr('id')
		timeline.options = defaults
		if(timeline.options.class.timeline){
			timeline.addClass(timeline.options.class.timeline)
		}
		timeline.filters = self.#filters(timeline)
		timeline.clear = function(){
			self.#clear(timeline)
			return timeline
		}
		timeline.sort = function(){
			self.#sort(timeline, timeline.options.order)
			return timeline
		}
		timeline.label = function(datetime, color = 'primary'){
			self.#label(timeline, datetime, color)
		}
		timeline.object = function(options = {}, callback = null){
			self.#object(timeline, options, callback)
		}
		timeline.appendTo = function(object){
			object.append(timeline)
			return timeline
		}
		timeline.prependTo = function(object){
			object.prepend(timeline)
			return timeline
		}
		Search.add(timeline)
		self.#clear(timeline)
		return timeline
	}

	#filters(timeline){
		const self = this
		let filters = $(document.createElement('div')).addClass('btn-group border shadow').attr('data-filters','').attr('role','group').attr('aria-label','Filters')
		filters.all = $(document.createElement('button')).addClass('btn btn-primary text-capitalize').html('all').attr('data-type',null).attr('data-label','all').attr('type','button').appendTo(filters)
		filters.all.click(function(){
			filters.attr('data-filters','')
			filters.filter()
		})
		filters.timeline = timeline
		filters.add = function(type = '', string = null){
			let label = string
			if(label == null && type != ''){
				label = type
			}
			if(label != null && filters.children('[data-label="' + label + '"]').length <= 0){
				let filter = $(document.createElement('button')).addClass('btn btn-light text-capitalize').html(label).attr('data-type',type).attr('data-label',label).attr('type','button').appendTo(filters)
				filter.click(function(){
					let current = filters.attr('data-filters').split(',')
					if(inArray(type,current)){
						current = current.filter(function(value){
						   return value != type;
						})
					} else {
						current.push(type)
					}
					let filterString = current.toString()
					if(filterString.charAt(0) == ','){
						filterString = filterString.substring(1)
					}
					filters.attr('data-filters',filterString)
					filters.filter()
				})
			}
		}
		filters.filter = function(){
			filters.find('button').removeClass('btn-primary').addClass('btn-light')
			let current = filters.attr('data-filters').split(',')
			if(filters.attr('data-filters') != ''){
				filters.timeline.find('[data-type]').hide()
				for(const [key, filter] of Object.entries(current)){
					filters.find('button[data-type="' + filter + '"]').addClass('btn-primary').removeClass('btn-light')
					filters.timeline.find('[data-type="' + filter + '"]').show()
				}
			} else {
				filters.timeline.find('[data-type]').show()
				filters.find('button[data-label="all"]').addClass('btn-primary').removeClass('btn-light')
			}
		}
		filters.appendTo = function(object){
			object.append(filters)
			return filters
		}
		filters.prependTo = function(object){
			object.prepend(filters)
			return filters
		}
		return filters
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
			type: '',
			datetime: Date.parse(new Date()),
			header: null,
			body: null,
			footer: null,
			order: null,
			label: true,
			id:null,
			class: {
        item: null,
        icon: null,
        header: null,
        body: null,
        footer: null,
			},
		}
		if(typeof options === 'object'){
			for(const [key, value] of Object.entries(timeline.options)){
				if(typeof object.options[key] !== 'undefined'){
					switch(key){
						case"class":
							for(const [section, classes] of Object.entries(value)){
								if(object.options[key][section] != null){
									object.options[key][section] += ' ' + classes
								} else {
									object.options[key][section] = classes
								}
							}
							break
						default:
							object.options[key] = value
							break
					}
				}
			}
			for(const [key, value] of Object.entries(options)){
				if(typeof object.options[key] !== 'undefined'){
					switch(key){
						case"class":
							for(const [section, classes] of Object.entries(value)){
								if(object.options[key][section] != null){
									object.options[key][section] += ' ' + classes
								} else {
									object.options[key][section] = classes
								}
							}
							break
						default:
							object.options[key] = value
							break
					}
				}
			}
		}
		let datetime = new Date(object.options.datetime)
		let order = Date.parse(datetime)
		if(object.options.order != null){
			order = object.options.order
		}
		object.attr('data-order',order)
		if(object.options.type != null){
			object.attr('data-type',object.options.type)
			timeline.filters.add(object.options.type)
		} else {
			object.attr('data-type','')
		}
		if(object.options.id != null){
			object.attr('data-id',object.options.id)
		}
		object.icon = Icon.create(object.options.icon).addClass('text-bg-'+object.options.color).addClass('shadow').appendTo(object)
		if(object.options.class.icon){
			object.icon.addClass(object.options.class.icon)
		}
		object.item = $(document.createElement('div')).addClass('timeline-item shadow border rounded').appendTo(object)
		if(object.options.class.item){
			object.item.addClass(object.options.class.item)
		}
		object.item.time = $(document.createElement('span')).addClass('time').attr('title',datetime.toLocaleString()).attr('data-bs-placement','top').appendTo(object.item)
		object.item.time.icon = Icon.create('clock').addClass('me-2').appendTo(object.item.time)
		object.item.time.timeago = $(document.createElement('time')).attr('datetime',datetime.toLocaleString()).appendTo(object.item.time).timeago()
		object.item.header = $(document.createElement('h3')).addClass('timeline-header').appendTo(object.item)
		if(object.options.class.header){
			object.item.header.addClass(object.options.class.header)
		}
		object.item.body = $(document.createElement('div')).addClass('timeline-body').appendTo(object.item)
		if(object.options.class.body){
			object.item.body.addClass(object.options.class.body)
		}
		object.item.footer = $(document.createElement('div')).addClass('timeline-footer').appendTo(object.item)
		if(object.options.class.footer){
			object.item.footer.addClass(object.options.class.footer)
		}
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
		Search.set(object)
		if(object.options.label){
			self.#label(timeline,order)
		}
		self.#sort(timeline, timeline.options.order)
		if(typeof callback === 'function'){
			callback(object,timeline)
		}
		return object
	}

	#sort(timeline, order = 'DESC'){
		const self = this
		let objects = timeline.children('div').detach().get()
		objects.sort(function(a, b){
			if(order == 'ASC'){
				return new Date($(a).data('order')) - new Date($(b).data('order'))
			} else {
				return new Date($(b).data('order')) - new Date($(a).data('order'))
			}
		});
		timeline.append(objects)
	}

	#clear(timeline){
		const self = this
		timeline.children().remove()
		self.#object(timeline,{order:'0000000000000',icon:'clock-history',label: false},function(object){
			object.item.remove()
			object.removeAttr('data-search').removeAttr('data-type')
		})
		self.#object(timeline,{order:'9999999999999',color:'success',icon:'clock',label: false},function(object){
			object.item.remove()
			object.removeAttr('data-search').removeAttr('data-type')
		})
	}

	create(options = {}, callback = null){
		const self = this
		if(options instanceof Function){ callback = options; options = {}; }
		let timeline = self.#timeline(options)
		if(typeof callback === 'function'){
			callback(timeline)
		}
		return timeline
	}
}

class coreDBComments {

	#count = 0

	constructor(){}

	get(options = {}, callback = null){
		const self = this
		if(options instanceof Function){ callback = options; options = {}; }
		let defaults = {
			id: null,
			linkTo: null,
		}
		if(typeof options === 'object'){
			for(const [key, value] of Object.entries(options)){
				if(typeof defaults[key] !== 'undefined'){
					defaults[key] = value
				}
			}
		}
		if(defaults.id || defaults.linkTo){
			if(typeof defaults.linkTo === "object"){
				defaults.linkTo = JSON.stringify(defaults.linkTo)
			}
			API.post("comment/read/?csrf="+CSRF,defaults,{
				success:function(result,status,xhr){
					if(typeof callback === 'function'){
						callback(result)
					}
				},
				error:function(xhr,status,error){
					if(xhr.status != 404){
						console.log(xhr,status,error)
						if(typeof xhr.responseJSON !== 'undefined'){
							Toast.create({title:xhr.status+': '+error,body:xhr.responseJSON.error,icon:'x-octagon',color:'danger',autohide:true,close:true,delay:30000})
						} else {
							Toast.create({title:xhr.status+': '+error,body:xhr.responseText,icon:'x-octagon',color:'danger',autohide:true,close:true,delay:30000})
						}
					}
				}
			})
		}
	}

	#sort(container, order = 'DESC'){
		const self = this
		if(order == 'DESC' || order == 'ASC'){
			let objects = container.list.children('li').detach().get()
			objects.sort(function(a, b){
				if(order == 'DESC'){
					return new Date($(b).data('order')) - new Date($(a).data('order'))
				} else {
					return new Date($(a).data('order')) - new Date($(b).data('order'))
				}
			});
			container.list.append(objects)
		}
	}

	#recount(container){
		const self = this
		container.button.count.html('(' + container.list.find('li').length + ')')
	}

	#comment(container, options = {}, callback = null){
		const self = this
		if(options instanceof Function){ callback = options; options = {}; }
		let defaults = {
			id: null,
			created: null,
			modified: null,
			owner: null,
			content: null,
			likes: [],
			linkTo: null,
			extra: null,
		}
		if(typeof options === 'object'){
			for(const [key, value] of Object.entries(options)){
				if(typeof defaults[key] !== 'undefined'){
					defaults[key] = value
				}
			}
		}
		defaults.date = new Date(defaults.created)
		let order = Date.parse(defaults.date)
		if(container.find('[data-comment-id="' + defaults.id + '"]').length <= 0){
			self.#count++
			let object = $(document.createElement('li')).attr('data-order',order).addClass('list-group-item border-0 p-0 d-flex mt-3').attr('id','Comment' + self.#count).appendTo(container.list)
			object.id = object.attr('id')
			object.options = defaults
			object.section = $(document.createElement('div')).addClass('flex-shrink avatar-block px-3 pt-3').appendTo(object)
			object.gravatar = $(document.createElement('img')).addClass('img-circle rounded-circle shadow-sm img-bordered-sm').attr('alt','Avatar').attr('width',65).appendTo(object.section)
			object.main = $(document.createElement('div')).addClass('flex-fill').appendTo(object)
			object.main.card = $(document.createElement('div')).addClass('card shadow').appendTo(object.main)
			object.main.card.body = $(document.createElement('div')).addClass('card-body').appendTo(object.main.card)
			object.main.card.body.header = $(document.createElement('h5')).addClass('username user-select-none m-0').appendTo(object.main.card.body)
			object.username = $(document.createElement('a')).addClass('text-decoration-none link-primary').css('font-size','16px').appendTo(object.main.card.body.header)
			object.main.card.body.header.time = $(document.createElement('p')).addClass('user-select-none text-secondary mt-1').css('font-size','13px').appendTo(object.main.card.body.header)
			object.main.card.body.header.time.icon = $(document.createElement('i')).addClass('bi-clock me-1').appendTo(object.main.card.body.header.time)
			object.created = $(document.createElement('time')).addClass('timeago').appendTo(object.main.card.body.header.time)
			object.content = $(document.createElement('p')).addClass('content comment m-0 text-secondary').appendTo(object.main.card.body)
			if(container.options.edit && defaults.owner.users == Username){
				object.textarea = $(document.createElement('textarea')).attr('placeholder','Write a comment...').attr('rows',3).css('resize','none').addClass('form-control d-none').appendTo(object.main.card.body)
			}
			object.controls = $(document.createElement('p')).addClass('controls user-select-none mt-3 mb-0 text-secondary').appendTo(object.main.card.body)
			if(container.options.share){
				object.controls.share = $(document.createElement('a')).addClass('text-decoration-none cursor-pointer link-secondary text-sm me-2').appendTo(object.controls)
				object.controls.share.icon = $(document.createElement('i')).addClass('me-2 bi-share').appendTo(object.controls.share)
				object.controls.share.text = $(document.createElement('span')).html('Share').appendTo(object.controls.share)
			}
			if(container.options.like){
				object.controls.like = $(document.createElement('a')).addClass('text-decoration-none cursor-pointer link-secondary text-sm me-2').appendTo(object.controls)
				object.controls.like.icon = $(document.createElement('i')).addClass('me-2 bi-hand-thumbs-up').appendTo(object.controls.like)
				object.controls.like.text = $(document.createElement('span')).html('Like').appendTo(object.controls.like)
				object.controls.like.count = $(document.createElement('span')).addClass('ms-1').html('(0)').appendTo(object.controls.like)
				object.controls.like.click(function(){
					API.post("comment/like/?csrf="+CSRF,defaults,{
						success:function(result,status,xhr){
							object.setLikes(result.likes)
						}
					})
				})
			}
			if(container.options.note){
				let linkTo = {comments:object.id}
				if(defaults.id){
					linkTo = {comments:defaults.id}
				}
				object.controls.note = Note.create('link',{
					color: 'secondary',
					addClass: 'text-sm me-2',
					linkTo: JSON.stringify(linkTo),
					colored: true,
				}).appendTo(object.controls)
			}
			if(container.options.edit && defaults.owner.users == Username){
				object.controls.edit = $(document.createElement('a')).addClass('text-decoration-none cursor-pointer link-secondary text-sm me-2').appendTo(object.controls)
				object.controls.edit.icon = $(document.createElement('i')).addClass('me-2 bi-pencil-square').appendTo(object.controls.edit)
				object.controls.edit.text = $(document.createElement('span')).html('Edit').appendTo(object.controls.edit)
				object.textarea.keyup(function (e) {
					var code = (e.keyCode ? e.keyCode : e.which)
					if(code == 13){
						let content = object.textarea.val().replace(/\n/g, '')
						object.textarea.val(content).html(content)
						object.content.removeClass('d-none')
						object.textarea.addClass('d-none')
						if(defaults.content != btoa(content)){
							defaults.content = btoa(content)
							if(defaults.content != '' && defaults.content != ' '){
								API.post("comment/update/?csrf="+CSRF,defaults,{
									success:function(result,status,xhr){
										object.setContent(result.content)
									}
								})
							} else {
								API.post("comment/delete/?csrf="+CSRF,defaults,{
									success:function(result,status,xhr){
										object.remove()
										self.#recount(container)
									}
								})
							}
						}
					}
				})
				object.controls.edit.click(function(){
					object.content.addClass('d-none')
					object.textarea.removeClass('d-none')
				})
			}
			if(defaults.id){
				object.attr('data-comment-id',defaults.id)
			}
			if(defaults.owner){
				object.username.attr('href','user/details?id=' + defaults.owner.users).html(defaults.owner.users)
				object.gravatar.attr('src',Gravatar.url(defaults.owner.users))
			}
			if(defaults.created){
				object.created.attr('datetime',defaults.date.toLocaleString()).attr('title',defaults.date.toLocaleString()).html(defaults.date.toLocaleString()).timeago()
			}
			object.setContent = function(string){
				object.content.html(atob(string))
				if(container.options.edit && defaults.owner.users == Username){
					object.textarea.val(atob(string))
				}
			}
			object.setLikes = function(array = []){
				if(container.options.like){
					object.controls.like.count.html('(' + array.length + ')')
					if(inArray(Username,array)){
						object.controls.like.icon.removeClass('bi-hand-thumbs-up').addClass('bi-hand-thumbs-up-fill text-primary')
					} else {
						object.controls.like.icon.removeClass('bi-hand-thumbs-up-fill text-primary').addClass('bi-hand-thumbs-up')
					}
				}
			}
			if(defaults.content){
				object.setContent(defaults.content)
			}
			if(defaults.likes){
				object.setLikes(defaults.likes)
			}
			if(typeof callback === 'function'){
				callback(object)
			}
			self.#sort(container,container.options.order)
			self.#recount(container)
			return object
		}
	}

	create(options = {}, callback = null){
		const self = this
		if(options instanceof Function){ callback = options; options = {}; }
		let defaults = {
			id: null,
			linkTo: null,
			note:false,
			share:false,
			like:false,
			edit:false,
			collapsed:false,
			order: 'DESC',
		}
		if(typeof options === 'object'){
			for(const [key, value] of Object.entries(options)){
				if(typeof defaults[key] !== 'undefined'){
					defaults[key] = value
				}
			}
		}
		self.#count++
		let container = $(document.createElement('div')).attr('id','Comments' + self.#count)
		container.id = container.attr('id')
		container.options = defaults
		container.form = $(document.createElement('form')).attr('method','post').attr('target','_blank').attr('novalidate','novalidate').attr('autocomplete','off').addClass('m-0 p-0').appendTo(container)
		container.collapse = $(document.createElement('div')).addClass('collapse show').attr('id',container.id + 'Collapse').appendTo(container)
		container.collapse.id = container.collapse.attr('id')
		container.list = $(document.createElement('ul')).attr('aria-expanded',true).addClass('comments list-group rounded-0').appendTo(container.collapse)
		container.textarea = $(document.createElement('textarea')).css('transition','all 400ms ease').attr('placeholder','Write a comment...').attr('rows',1).css('resize','none').addClass('form-control').appendTo(container.form)
		container.button = $(document.createElement('a')).addClass('link-secondary text-decoration-none user-select-none text-sm cursor-pointer me-2').html('Comments').attr('data-bs-toggle','collapse').attr('data-bs-target','#' + container.collapse.id).attr('role','button').attr('aria-expanded',true)
		container.button.count = $(document.createElement('span')).addClass('ms-1').html('(0)').appendTo(container.button)
		container.button.icon = $(document.createElement('i')).addClass('bi-chat-text me-1').prependTo(container.button)
		container.textarea.focus(function(){
			this.rows=3
			let content = container.textarea.val().replace(/\n/g, '')
			container.textarea.val(content).html(content)
		}).blur( function(){
			this.rows=1
			let content = container.textarea.val().replace(/\n/g, '')
			container.textarea.val(content).html(content)
		})
		container.form.submit(function(e){
			e.preventDefault()
			return false
		})
		if(defaults.collapsed){
			container.collapse.removeClass('show').attr('aria-expanded',false)
			container.button.attr('aria-expanded',false)
		}
		if(defaults.id){
			container.attr('data-comment-id',defaults.id)
		}
		if(defaults.linkTo){
			let linkTo = JSON.stringify(defaults.linkTo)
			container.attr('data-comment-linkTo',linkTo)
		}
		if(defaults.owner){
			container.attr('data-comment-username',defaults.owner)
		}
		container.comment = function(options,callback){
			self.#comment(container,options,callback)
		}
		container.textarea.keyup(function (e) {
			var code = (e.keyCode ? e.keyCode : e.which)
			if(code == 13){
				let content = container.textarea.val().replace(/\n/g, '')
				container.textarea.val(content).html(content)
				let comment = {
					content: btoa(content),
					linkTo: JSON.stringify(defaults.linkTo),
				}
				if(comment.content != '' && comment.content != ' '){
					container.textarea.val('')
					API.post("comment/create/?csrf="+CSRF,comment,{
						success:function(result,status,xhr){
							container.comment(result)
						}
					})
				}
			}
		})
		container.appendTo = function(obj){
			obj.append(container)
			return container
		}
		container.prependTo = function(obj){
			obj.prepend(container)
			return container
		}
		self.get(defaults,function(result){
			for(const [key, comment] of Object.entries(result)){
				container.comment(comment)
			}
		})
		if(typeof callback === 'function'){
			callback(container)
		}
		return container
	}
}

class coreDBNote {

	#count = 0

	constructor(){}

	#open(object, callback = null){
		const self = this
		Modal.create({
			icon: 'sticky',
			title: 'Note',
			color: 'warning',
		},function(modal){
			modal.footer.group.primary.html('Post')
			modal.body.addClass('p-0').attr('style','min-height: 300px!important;max-height: 300px!important;')
			modal.body.textarea = $(document.createElement('textarea')).attr('id','noteModalTextarea'+self.#count).addClass('form-control').appendTo(modal.body)
			modal.body.textarea.settings = {
				focus: true,
				disableResizeEditor: true,
				dialogsInBody: true,
				dialogsFade: false,
				focus: true,
				minHeight: '100%',
				maxHeight: '100%',
				fontNames: ["Arial", "Arial Black", "Comic Sans MS", "Courier New",
										"Helvetica Neue", "Helvetica", "Impact", "Lucida Grande",
										"Tahoma", "Times New Roman", "Verdana"],
				fontNamesIgnoreCheck: ["Arial", "Arial Black", "Comic Sans MS", "Courier New",
															 "Helvetica Neue", "Helvetica", "Impact", "Lucida Grande",
															 "Tahoma", "Times New Roman", "Verdana"],
				addDefaultFonts: false,
				toolbar: [
					['font', ['fontname','fontsize']],
					['style', ['bold', 'italic', 'underline', 'clear']],
					['color', ['color']],
					['para', ['ul', 'ol']],
					['code', ['code']],
				],
			}
			modal.body.textarea.summernote(modal.body.textarea.settings)
			modal.body.find('.note-editor').css('font-family','Verdana').css('font-size','14px')
			modal.body.find('.note-editor .note-btn.btn.dropdown-toggle').removeAttr('data-toggle').attr('data-bs-toggle','dropdown').attr('data-bs-popper-config','{"strategy":"fixed"}')
			modal.body.find('div.note-editor').addClass('rounded-0 border-0')
			modal.body.find('div.note-editable').attr('style','min-height: calc(300px - 53px)!important;max-height: calc(300px - 53px)!important;')
			modal.header.group.expand.click(function(){
				if(modal.dialog.hasClass('modal-fullscreen')){
					modal.body.removeAttr('style').attr('style','')
					var height = Math.ceil(modal.body.height())
					height = height - Math.ceil(modal.body.find('div.note-toolbar').outerHeight())
					height = height - Math.ceil(modal.body.find('div.note-resizebar').outerHeight())
					height += 'px'
					modal.body.find('div.note-editable').attr('style','min-height: ' + height + '!important;max-height: ' + height + '!important;')
				} else {
					modal.body.attr('style','min-height: 300px!important;max-height: 300px!important;')
					var height = Math.ceil(modal.body.height())
					height = height - Math.ceil(modal.body.find('div.note-toolbar').outerHeight())
					height = height - Math.ceil(modal.body.find('div.note-resizebar').outerHeight())
					height += 'px'
					modal.body.find('div.note-editable').attr('style','min-height: ' + height + '!important;max-height: ' + height + '!important;')
				}
			})
			if(typeof object.note !== 'undefined'){
				modal.body.textarea.summernote('code',atob(object.note.content))
				object.icon.removeClass('bi-sticky').addClass('bi-stickies-fill')
				if(object.options.colored){
					object.icon.addClass('text-warning')
				}
				let datetime = new Date(object.note.modified)
				let info = $(document.createElement('div')).addClass('d-flex justify-content-between user-select-none')
				info.owner = $(document.createElement('div')).addClass('px-1').appendTo(info)
				info.owner.icon = $(document.createElement('i')).addClass('bi-person me-1').appendTo(info.owner)
				info.owner.username = $(document.createElement('span')).html(object.note.owner.users).appendTo(info.owner)
				info.date = $(document.createElement('div')).addClass('px-1').appendTo(info)
				info.date.icon = $(document.createElement('i')).addClass('bi-clock me-1').appendTo(info.date)
				info.date.time = $(document.createElement('time')).attr('title',datetime.toLocaleString()).attr('data-bs-placement','top').attr('datetime',datetime.toLocaleString()).html(datetime.toLocaleString()).appendTo(info.date)
				modal.body.find('.note-resizebar').addClass('h-auto').html(info)
				modal.on('show.bs.modal',function(){
					info.date.time.timeago()
				})
				modal.on('shown.bs.modal',function(){
					var height = Math.ceil(modal.body.height())
					height = height - Math.ceil(modal.body.find('div.note-toolbar').outerHeight())
					height = height - Math.ceil(modal.body.find('div.note-resizebar').outerHeight())
					height += 'px'
					modal.body.find('div.note-editable').attr('style','min-height: ' + height + '!important;max-height: ' + height + '!important;')
				})
			}
			modal.footer.group.primary.click(function(){
				let action = null
				if(typeof object.note === 'undefined'){
					object.note = {
						content: btoa(modal.body.textarea.summernote('code')),
					}
					if(object.options.linkTo){
						if(typeof object.options.linkTo !== 'string'){
					    object.options.linkTo = JSON.stringify(object.options.linkTo)
					  }
						object.note.linkTo = object.options.linkTo
					}
					action = 'create'
				} else {
					if(modal.body.textarea.summernote('isEmpty')){
						action = 'delete'
					} else {
						action = 'update'
					}
				}
			  if(action){
					let url = 'note/' + action + '?csrf=' + CSRF
			    API.post(url,object.note,{
			      success:function(result,status,xhr){
							if(action != 'delete'){
								Toast.create({title:'Saved',icon:'check2',color:'success',close:false})
								if(typeof result.linkTo !== 'string' && result.linkTo != null){
									let key = Object.keys(result.linkTo)[0]
									if(result.linkTo[key] != null){
										result.linkTo[key] = result.linkTo[key].toString()
									} else {
										result.linkTo[key] = null
									}
									result.linkTo = JSON.stringify(result.linkTo)
								}
								object.note = result
								object.icon.removeClass('bi-sticky').addClass('bi-stickies-fill')
								if(object.options.colored){
									object.icon.addClass('text-warning')
								}
								object.attr('data-note-id',result.id).attr('data-note-linkTo',result.linkTo)
							} else {
								Toast.create({title:'Deleted',icon:'trash',color:'success',close:false})
								if(typeof object.note !== 'undefined'){
									delete object.note
								}
								object.icon.removeClass('bi-stickies-fill text-warning').addClass('bi-sticky')
								object.removeAttr('data-note-id').removeAttr('data-note-linkTo')
							}
							if(typeof callback === 'function'){
								callback(object, modal)
							}
			      }
			    })
			    modal.bootstrap.hide()
			  }
			})
		})
		return object
	}

	get(options = {}, callback = null){
		const self = this
		if(options instanceof Function){ callback = options; options = {}; }
		let defaults = {
			id: null,
			linkTo: null,
		}
		if(typeof options === 'object'){
			for(const [key, value] of Object.entries(options)){
				if(typeof defaults[key] !== 'undefined'){
					defaults[key] = value
				}
			}
		}
		if(defaults.id || defaults.linkTo){
			API.post("note/read/?csrf="+CSRF,defaults,{
				success:function(result,status,xhr){
					if(typeof callback === 'function'){
						callback(result)
					}
				},
				error:function(xhr,status,error){
					if(xhr.status != 404){
						console.log(xhr,status,error)
						if(typeof xhr.responseJSON !== 'undefined'){
							Toast.create({title:xhr.status+': '+error,body:xhr.responseJSON.error,icon:'x-octagon',color:'danger',autohide:true,close:true,delay:30000})
						} else {
							Toast.create({title:xhr.status+': '+error,body:xhr.responseText,icon:'x-octagon',color:'danger',autohide:true,close:true,delay:30000})
						}
					}
				}
			})
		}
	}

	create(type, options = {}, callback = null){
		const self = this
		if(options instanceof Function){ callback = options; options = {}; }
		let defaults = {
			id: null,
			linkTo: null,
			color: null,
			addClass: null,
			removeClass: null,
			colored: false,
		}
		if(typeof options === 'object'){
			for(const [key, value] of Object.entries(options)){
				if(typeof defaults[key] !== 'undefined'){
					defaults[key] = value
				}
			}
		}
		self.#count++
		let object = $(document.createElement('button')).addClass('btn shadow border').attr('id','note'+self.#count).html('Note')
		let prefix = 'btn'
		if(typeof type === 'string'){
			switch(type){
				case"link":
					object = $(document.createElement('a')).addClass('text-decoration-none user-select-none cursor-pointer').attr('id','noteLink'+self.#count).html('Note')
					prefix = 'link'
					break
			}
			object.id = object.attr('id')
			object.options = defaults
			object.icon = $(document.createElement('i')).addClass('bi-sticky me-2').prependTo(object)
			if(defaults.color){
				object.addClass(prefix + '-' + defaults.color)
			} else {
				object.addClass(prefix + '-warning')
			}
			if(defaults.addClass){
				object.addClass(defaults.addClass)
			}
			if(defaults.removeClass){
				object.removeClass(defaults.removeClass)
			}
			if(defaults.linkTo){
				if(typeof defaults.linkTo !== 'string'){
					defaults.linkTo = JSON.stringify(defaults.linkTo)
				}
				object.attr('data-note-linkTo',defaults.linkTo)
			}
			if(typeof object.note === 'undefined'){
				self.get(defaults,function(result){
					if(typeof result.linkTo !== 'string'){
						let key = Object.keys(result.linkTo)[0]
						result.linkTo[key] = result.linkTo[key].toString()
						result.linkTo = JSON.stringify(result.linkTo)
					}
					object.note = result
					object.icon.removeClass('bi-sticky').addClass('bi-stickies-fill')
					if(object.options.colored){
						object.icon.addClass('text-warning')
					}
					object.attr('data-note-id',result.id).attr('data-note-linkTo',result.linkTo)
				})
			}
			object.appendTo = function(obj){
				obj.append(object)
				return object
			}
			object.prependTo = function(obj){
				obj.prepend(object)
				return object
			}
			object.click(function(){
				object = self.#open(object,callback)
			})
			return object
		}
	}
}

class coreDBCard {

	#count = 0

	constructor(){}

	create(options = {}, callback = null){
		const self = this
		if(options instanceof Function){ callback = options; options = {}; }
		let defaults = {
			icon: false,
			title: false,
			body: false,
			footer: false,
			strech: false,
			hideHeader: false,
			hideFooter: true,
			close:false,
			fullscreen: false,
			collapse: false,
			collapsed: false,
			classCard: false,
			classHeader: false,
			classBody: false,
			classFooter: false,
		}
		if(typeof options === 'object'){
			if(typeof options.footer !== 'undefined'){
				defaults.hideFooter = false
			}
			for(const [key, value] of Object.entries(options)){
				if(typeof defaults[key] !== 'undefined'){
					defaults[key] = value
				}
			}
		}
		self.#count++
		let card = $(document.createElement('div')).addClass('card shadow').attr('id','card'+self.#count)
		card.id = card.attr('id')
		card.options = defaults
		let collapse = $(document.createElement('div')).addClass('collapse show').attr('id',card.id + 'collapse').append(card)
		collapse.id = collapse.attr('id')
		card.collapse = collapse
		card.header = $(document.createElement('div')).addClass('card-header user-select-none').appendTo(card)
		card.body = $(document.createElement('div')).addClass('card-body')
		card.body.collapse = $(document.createElement('div')).addClass('collapse show').attr('id',card.id + 'body' + self.#count + 'collapse').appendTo(card)
		card.body.collapse.id = card.body.collapse.attr('id')
		card.body.appendTo(card.body.collapse)
		card.footer = $(document.createElement('div')).addClass('card-footer').appendTo(card)
		card.header.heading = $(document.createElement('h5')).addClass('card-title d-flex align-items-center my-2 fw-light').appendTo(card.header)
		card.header.icon = $(document.createElement('i')).appendTo(card.header.heading)
		card.header.title = $(document.createElement('span')).appendTo(card.header.heading)
		card.controls = $(document.createElement('span')).addClass('ms-auto d-flex align-items-center').appendTo(card.header.heading)
		card.controls.collapse = $(document.createElement('a')).addClass('ms-3 link-dark text-decoration-none cursor-pointer').appendTo(card.controls)
		card.controls.collapse.icon = $(document.createElement('i')).addClass('bi-chevron-bar-contract').appendTo(card.controls.collapse)
		card.controls.fullscreen = $(document.createElement('a')).addClass('ms-3 link-dark text-decoration-none cursor-pointer').appendTo(card.controls)
		card.controls.fullscreen.icon = $(document.createElement('i')).addClass('bi-fullscreen').appendTo(card.controls.fullscreen)
		card.controls.close = $(document.createElement('a')).addClass('ms-3 link-dark text-decoration-none cursor-pointer').appendTo(card.controls)
		card.controls.close.icon = $(document.createElement('i')).addClass('bi-x-lg').appendTo(card.controls.close)
		if(defaults.icon){
			card.header.icon.addClass('me-2').addClass('bi-'+defaults.icon)
		}
		if(defaults.title){
			card.header.title.html(defaults.title)
		}
		if(defaults.body){
			card.body.html(defaults.body)
		}
		if(defaults.hideHeader){
			card.header.addClass('d-none')
		}
		if(defaults.hideFooter){
			card.footer.addClass('d-none')
		}
		if(defaults.footer){
			card.footer.html(defaults.footer)
		}
		if(defaults.classCard){
			card.addClass(defaults.classCard)
		}
		if(defaults.classHeader){
			card.body.addClass(defaults.classHeader)
		}
		if(defaults.classBody){
			card.body.addClass(defaults.classBody)
		}
		if(defaults.classFooter){
			card.body.addClass(defaults.classFooter)
		}
		if(defaults.strech){
			card.collapse.addClass('h-100')
		  card.addClass('h-100')
		  card.body.collapse.addClass('h-100')
		  card.body.addClass('d-flex h-100 overflow-auto')
		}
		if(defaults.close){
			card.collapse.bs = new bootstrap.Collapse(card.collapse,{toggle:false})
			card.controls.close.click(function(){
				card.collapse.bs.hide()
				card.collapse.on('hidden.bs.collapse',function(){
					card.collapse.remove()
				})
			})
		} else {
			card.controls.close.addClass('d-none')
		}
		if(defaults.fullscreen){
			card.css('transition','all 400ms ease')
			card.body.css('transition','all 400ms ease')
			card.body.collapse.css('transition','all 400ms ease')
			card.controls.fullscreen.click(function(){
				if(card.controls.fullscreen.icon.hasClass('bi-fullscreen')){
					card.addClass('position-fixed top-0 start-0 w-100 h-100 rounded-0').css('z-index', 1050)
					card.body.addClass('h-100')
					card.body.collapse.addClass('h-100 overflow-auto')
					card.controls.fullscreen.icon.removeClass('bi-fullscreen').addClass('bi-fullscreen-exit')
					if(defaults.collapse){
						card.controls.collapse.addClass('d-none')
					}
				} else {
					card.removeClass('position-fixed top-0 start-0 w-100 h-100 rounded-0').css('z-index', '')
					card.body.removeClass('h-100')
					card.body.collapse.removeClass('h-100 overflow-auto')
					card.controls.fullscreen.icon.removeClass('bi-fullscreen-exit').addClass('bi-fullscreen')
					if(defaults.collapse){
						card.controls.collapse.removeClass('d-none')
					}
				}
			})
		} else {
			card.controls.fullscreen.addClass('d-none')
		}
		if(defaults.collapse){
			card.body.collapse.bs = new bootstrap.Collapse(card.body.collapse,{toggle:false})
			card.controls.collapse.click(function(){
				if(card.controls.collapse.icon.hasClass('bi-chevron-bar-expand')){
					card.body.collapse.bs.show()
					card.controls.collapse.icon.removeClass('bi-chevron-bar-expand').addClass('bi-chevron-bar-contract')
					if(defaults.hideFooter && !defaults.hideHeader){
						card.header.removeClass('rounded border-0')
					}
					if(!defaults.hideFooter && !defaults.hideHeader){
						card.footer.removeClass('border-0')
					}
				} else {
					card.body.collapse.bs.hide()
					card.controls.collapse.icon.removeClass('bi-chevron-bar-contract').addClass('bi-chevron-bar-expand')
					if(defaults.hideFooter && !defaults.hideHeader){
						card.header.addClass('rounded border-0')
					}
					if(!defaults.hideFooter && !defaults.hideHeader){
						card.footer.addClass('border-0')
					}
				}
			})
		} else {
			card.controls.collapse.addClass('d-none')
		}
		if(defaults.collapsed){
			card.body.collapse.removeClass('show')
			card.controls.collapse.icon.removeClass('bi-chevron-bar-contract').addClass('bi-chevron-bar-expand')
		}
		card.appendTo = function(object){
			card.collapse.appendTo(object)
			return card
		}
		card.prependTo = function(object){
			card.collapse.prependTo(object)
			return card
		}
		if(typeof callback === 'function'){
			callback(card)
		}
		return card
	}
}

class coreDBCode {

	#count = 0

	constructor(){}

	create(options = {}, callback = null){
		const self = this
		if(options instanceof Function){ callback = options; options = {}; }
		let defaults = {
			language: false,
			title: false,
			code: false,
			clipboard: false,
			fullscreen: false,
			highlight: true,
		}
		if(typeof options === 'object'){
			for(const [key, value] of Object.entries(options)){
				if(typeof defaults[key] !== 'undefined'){
					defaults[key] = value
				}
			}
		}
		self.#count++
		let code = $(document.createElement('div')).addClass('card text-bg-dark shadow').attr('id','code'+self.#count)
		code.id = code.attr('id')
		code.options = defaults
		code.header = $(document.createElement('div')).addClass('card-header user-select-none').appendTo(code)
		code.header.heading = $(document.createElement('h5')).addClass('card-title d-flex align-items-center my-2 fw-light').appendTo(code.header)
		code.header.icon = $(document.createElement('i')).addClass('bi-code-slash me-2').appendTo(code.header.heading)
		code.header.language = $(document.createElement('samp')).addClass('mx-1 fw-light text-uppercase').appendTo(code.header.heading)
		code.header.title = $(document.createElement('small')).addClass('mx-1').appendTo(code.header.heading)
		code.controls = $(document.createElement('span')).addClass('ms-auto d-flex align-items-center').appendTo(code.header.heading)
		code.controls.clipboard = $(document.createElement('a')).addClass('ms-3 link-light text-decoration-none cursor-pointer').appendTo(code.controls)
		code.controls.clipboard.icon = $(document.createElement('i')).addClass('bi-clipboard').appendTo(code.controls.clipboard)
		code.controls.fullscreen = $(document.createElement('a')).addClass('ms-3 link-light text-decoration-none cursor-pointer').appendTo(code.controls)
		code.controls.fullscreen.icon = $(document.createElement('i')).addClass('bi-fullscreen').appendTo(code.controls.fullscreen)
		code.body = $(document.createElement('div')).addClass('card-body rounded p-0').appendTo(code)
		code.pre = $(document.createElement('pre')).addClass('m-0 rounded p-3 h-100').appendTo(code.body)
		code.code = $(document.createElement('code')).addClass('language-*').appendTo(code.pre)
		if(defaults.title){
			code.header.title.html(defaults.title)
		}
		if(defaults.language){
			defaults.language = defaults.language.toString().toLowerCase()
			if(typeof Prism.languages[defaults.language] !== 'undefined'){
				code.header.language.html(defaults.language)
				code.code.addClass('language-' + defaults.language)
			}
		}
		if(defaults.fullscreen){
			code.css('transition','all 400ms ease')
			code.code.css('transition','all 400ms ease')
			code.controls.fullscreen.click(function(){
				if(code.controls.fullscreen.icon.hasClass('bi-fullscreen')){
					code.addClass('position-fixed top-0 start-0 w-100 h-100 rounded-0').css('z-index', 1050)
					code.body.addClass('h-100 overflow-auto')
					code.controls.fullscreen.icon.removeClass('bi-fullscreen').addClass('bi-fullscreen-exit')
				} else {
					code.removeClass('position-fixed top-0 start-0 w-100 h-100 rounded-0').css('z-index', '')
					code.body.removeClass('h-100 overflow-auto')
					code.controls.fullscreen.icon.removeClass('bi-fullscreen-exit').addClass('bi-fullscreen')
				}
			})
		} else {
			code.controls.fullscreen.addClass('d-none')
		}
		if(defaults.clipboard){
			code.controls.clipboard.click(function(){
				copyToClipboard(code.code)
			})
		} else {
			code.controls.clipboard.addClass('d-none')
		}
		if(defaults.code){
			code.code.text(defaults.code)
			if(defaults.highlight && defaults.language && typeof Prism.languages[defaults.language] !== 'undefined'){
				code.code.html(Prism.highlight(code.code.html(),Prism.languages[defaults.language]))
			}
		}
		if(typeof callback === 'function'){
			callback(code)
		}
		return code
	}
}

class coreDBGravatar {

	constructor(){}

	url(email, options = {}, callback = null){
		const self = this
		if(options instanceof Function){ callback = options; options = {}; }
		let arrays = {
			extensions: ['jpg','jpeg','png','gif'],
			defaults: ['404','mp','identicon','monsterid','wavatar','retro','robohash','blank'],
			ratings: ['g','pg','r','x'],
		}
		let defaults = {
			extension: false, //in request
			size: false, //s
			default: 'mp', //d
			force: false, //f
			rating: false, //r
		}
		if(typeof options === 'object'){
			for(const [key, value] of Object.entries(options)){
				if(typeof defaults[key] !== 'undefined'){
					defaults[key] = value
				}
			}
		}
		let url = 'https://www.gravatar.com/avatar/' + md5(email)
		for(const [key, value] of Object.entries(defaults)){
			if(value){
				switch(key){
					case"extension":
						if(inArray(value,arrays.extensions)){
							url += '.' + value
						}
						break;
					case"size":
						if(url.toLowerCase().indexOf("?") >= 0){
							url +=  '&s=' + parseInt(value)
						} else {
							url +=  '?s=' + parseInt(value)
						}
						break;
					case"default":
						if(inArray(value,arrays.defaults)){
							if(url.toLowerCase().indexOf("?") >= 0){
								url +=  '&d=' + value
							} else {
								url +=  '?d=' + value
							}
						}
						break;
					case"force":
						if(url.toLowerCase().indexOf("?") >= 0){
							url +=  '&f=y'
						} else {
							url +=  '?f=y'
						}
						break;
					case"rating":
						if(inArray(value,arrays.ratings)){
							if(url.toLowerCase().indexOf("?") >= 0){
								url +=  '&r=' + value
							} else {
								url +=  '?r=' + value
							}
						}
						break;
				}
			}
		}
		if(typeof callback === 'function'){
			callback(url)
		}
		return url
	}
}

class coreDBFeed {

	#count = 0

	constructor(){}

	get(options = {}, callback = null){
		const self = this
		if(options instanceof Function){ callback = options; options = {}; }
		let defaults = {
			id: null,
			linkTo: null,
		}
		if(typeof options === 'object'){
			for(const [key, value] of Object.entries(options)){
				if(typeof defaults[key] !== 'undefined'){
					defaults[key] = value
				}
			}
		}
		if(defaults.id || defaults.linkTo){
			if(typeof defaults.linkTo === "object"){
				defaults.linkTo = JSON.stringify(defaults.linkTo)
			}
			API.post("post/read/?csrf="+CSRF,defaults,{
				success:function(result,status,xhr){
					if(typeof callback === 'function'){
						callback(result)
					}
				},
				error:function(xhr,status,error){
					if(xhr.status != 404){
						console.log(xhr,status,error)
						if(typeof xhr.responseJSON !== 'undefined'){
							Toast.create({title:xhr.status+': '+error,body:xhr.responseJSON.error,icon:'x-octagon',color:'danger',autohide:true,close:true,delay:30000})
						} else {
							Toast.create({title:xhr.status+': '+error,body:xhr.responseText,icon:'x-octagon',color:'danger',autohide:true,close:true,delay:30000})
						}
					}
				}
			})
		}
	}

	#feed(options = {}, callback = null){
		const self = this
		if(options instanceof Function){ callback = options; options = {}; }
		let defaults = {
			id: null,
			linkTo: null,
			note: false,
			share: false,
			like: false,
			edit: false,
			comment: false,
			order: 'DESC',
		}
		if(typeof options === 'object'){
			for(const [key, value] of Object.entries(options)){
				if(typeof defaults[key] !== 'undefined'){
					defaults[key] = value
				}
			}
		}
		self.#count++
		let feed = $(document.createElement('div')).attr('id','feed' + self.#count).addClass('feed')
		feed.id = feed.attr('id')
		feed.options = defaults
		self.#clear(feed)
		feed.post = function(options = {}, callback = null){
			self.#post(feed, options, callback)
		}
		feed.clear = function(){
			self.#clear(feed)
		}
		feed.sort = function(){
			self.#sort(feed)
		}
		feed.appendTo = function(obj){
			obj.append(feed)
			return feed
		}
		feed.prependTo = function(obj){
			obj.prepend(feed)
			return feed
		}
		if(defaults.id){
		  feed.attr('data-feed-id',defaults.id)
		}
		if(defaults.linkTo){
		  let linkTo = JSON.stringify(defaults.linkTo)
		  feed.attr('data-feed-linkTo',linkTo)
		}
		return feed
	}

	#post(feed, options = {}, callback = null){
		const self = this
		if(options instanceof Function){ callback = options; options = {}; }
		let defaults = {
			id: null,
			created: null,
			modified: null,
			owner: null,
			content: null,
			likes: null,
			sharedTo: null,
			linkTo: null,
		}
		if(typeof feed.options === 'object'){
			for(const [key, value] of Object.entries(feed.options)){
				if(typeof defaults[key] !== 'undefined'){
					defaults[key] = value
				}
			}
		}
		if(typeof options === 'object'){
			for(const [key, value] of Object.entries(options)){
				if(typeof defaults[key] !== 'undefined'){
					defaults[key] = value
				}
			}
		}
		self.#count++
		let post = $(document.createElement('div')).addClass('post').attr('id',feed.id + 'Post' + self.#count).appendTo(feed)
		post.id = post.attr('id')
		post.options = defaults
		let datetime = new Date(defaults.created)
		let order = Date.parse(datetime)
		if(defaults.order != null){
			order = defaults.order
		}
		post.attr('data-order',order)
		post.identifier = order
		if(defaults.id != null){
			post.attr('data-id',defaults.id)
			post.identifier = defaults.id
		}
		let linkTo = {posts:post.id}
		if(defaults.id){
			linkTo = {posts:defaults.id}
		}
		post.user = $(document.createElement('div')).addClass('user-block user-select-none').appendTo(post)
		post.user.avatar = $(document.createElement('img')).addClass('img-circle rounded-circle shadow-sm img-bordered-sm').attr('alt','Avatar').appendTo(post.user)
		post.user.username = $(document.createElement('span')).addClass('username mt-2').appendTo(post.user)
		post.user.link = $(document.createElement('a')).addClass('text-decoration-none').css('font-weight',500).appendTo(post.user.username)
		if(defaults.owner != null){
			post.user.link.attr('href','/users/details?id=' + defaults.owner.users).html(defaults.owner.users)
			post.user.avatar.attr('src',Gravatar.url(defaults.owner.users))
		}
		post.user.date = $(document.createElement('span')).addClass('description mt-1').attr('title',datetime.toLocaleString()).attr('data-bs-placement','top').appendTo(post.user)
		post.user.date.icon = $(document.createElement('i')).addClass('bi-clock me-1').appendTo(post.user.date)
		post.user.date.timeago = $(document.createElement('time')).attr('datetime',datetime.toLocaleString()).html(datetime.toLocaleString()).appendTo(post.user.date).timeago()
		post.content = $(document.createElement('p')).addClass('content').appendTo(post)
		// if(feed.options.edit && defaults.owner.users == Username){
		// 	post.textarea = $(document.createElement('textarea')).attr('placeholder','Write a comment...').attr('rows',10).css('resize','none').addClass('form-control mb-3 d-none').appendTo(post)
		// }
		post.setContent = function(string){
			post.content.html(atob(string))
			// if(feed.options.edit && defaults.owner.users == Username){
			// 	post.textarea.val(atob(string))
			// }
		}
		if(defaults.content != null){
			post.setContent(defaults.content)
		}
		post.controls = $(document.createElement('p')).addClass('controls user-select-none').appendTo(post)
		if(feed.options.share){
			post.controls.share = $(document.createElement('a')).addClass('link-secondary text-decoration-none text-sm cursor-pointer me-2').html('Share').appendTo(post.controls)
			post.controls.share.icon = $(document.createElement('i')).addClass('bi-share me-1').prependTo(post.controls.share)
		}
		if(feed.options.like){
			post.controls.like = $(document.createElement('a')).addClass('text-decoration-none cursor-pointer link-secondary text-sm me-2').appendTo(post.controls)
		  post.controls.like.icon = $(document.createElement('i')).addClass('me-2 bi-hand-thumbs-up').appendTo(post.controls.like)
		  post.controls.like.text = $(document.createElement('span')).html('Like').appendTo(post.controls.like)
		  post.controls.like.count = $(document.createElement('span')).addClass('ms-1').html('(0)').appendTo(post.controls.like)
			post.setLikes = function(array = []){
				if(feed.options.like){
					post.controls.like.count.html('(' + array.length + ')')
					if(inArray(Username,array)){
						post.controls.like.icon.removeClass('bi-hand-thumbs-up').addClass('bi-hand-thumbs-up-fill text-primary')
					} else {
						post.controls.like.icon.removeClass('bi-hand-thumbs-up-fill text-primary').addClass('bi-hand-thumbs-up')
					}
				}
			}
			post.setLikes(defaults.likes)
		  post.controls.like.click(function(){
		    API.post("post/like/?csrf="+CSRF,defaults,{
		      success:function(result,status,xhr){
		        post.setLikes(result.likes)
		      }
		    })
		  })
		}
		if(feed.options.note){
			post.note = Note.create('link',{
		    color: 'secondary',
		    addClass: 'text-sm me-2',
		    linkTo: JSON.stringify(linkTo),
		    colored: true,
		  }).appendTo(post.controls)
		}
		// if(feed.options.edit && defaults.owner.users == Username){
		// 	post.controls.edit = $(document.createElement('a')).addClass('text-decoration-none cursor-pointer link-secondary text-sm me-2').appendTo(post.controls)
		// 	post.controls.edit.icon = $(document.createElement('i')).addClass('me-2 bi-pencil-square').appendTo(post.controls.edit)
		// 	post.controls.edit.text = $(document.createElement('span')).html('Edit').appendTo(post.controls.edit)
		// 	post.textarea.keyup(function (e) {
		// 		var code = (e.keyCode ? e.keyCode : e.which)
		// 		if(code == 13){
		// 			let content = post.textarea.val().replace(/\n/g, '')
		// 			post.textarea.val(content).html(content)
		// 			post.content.removeClass('d-none')
		// 			post.textarea.addClass('d-none')
		// 			if(defaults.content != btoa(content)){
		// 				defaults.content = btoa(content)
		// 				if(defaults.content != '' && defaults.content != ' '){
		// 					API.post("post/update/?csrf="+CSRF,defaults,{
		// 						success:function(result,status,xhr){
		// 							post.setContent(result.content)
		// 						}
		// 					})
		// 				} else {
		// 					API.post("post/delete/?csrf="+CSRF,defaults,{
		// 						success:function(result,status,xhr){
		// 							post.remove()
		// 						}
		// 					})
		// 				}
		// 			}
		// 		}
		// 	})
		// 	post.controls.edit.click(function(){
		// 		post.content.addClass('d-none')
		// 		post.textarea.removeClass('d-none')
		// 	})
		// }
		post.controls.end = $(document.createElement('span')).addClass('float-end').prependTo(post.controls)
		if(feed.options.comment){
			post.comments = Comments.create({
			  linkTo: JSON.stringify(linkTo),
			  note: feed.options.note,
			  share: feed.options.share,
			  like: feed.options.like,
			  edit: feed.options.edit,
				collapsed: true,
			},function(comments){
			  comments.button.appendTo(post.controls.end)
			}).appendTo(post)
		}
		if(typeof callback === 'function'){
			callback(post,feed)
		}
		self.#sort(feed, feed.options.order)
		Search.set(post)
		return post
	}

	#sort(feed, order = 'DESC'){
		const self = this
		if(order == 'DESC' || order == 'ASC'){
			let objects = feed.children('div').detach().get()
			objects.sort(function(a, b){
				if(order == 'DESC'){
					return new Date($(b).data('order')) - new Date($(a).data('order'))
				} else {
					return new Date($(a).data('order')) - new Date($(b).data('order'))
				}
			});
			feed.append(objects)
		}
	}

	#clear(feed){
		const self = this
		feed.children().remove()
	}

	create(options = {}, callback = null){
		const self = this
		if(options instanceof Function){ callback = options; options = {}; }
		let feed = self.#feed(options)
		Search.add(feed)
		if(typeof callback === 'function'){
			callback(feed)
		}
		self.get(options,function(result){
		  for(const [key, post] of Object.entries(result)){
		    feed.post(post)
		  }
		})
		return feed
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
			self.#offcanvas.timeline = Timeline.create(function(timeline){
				timeline.filters.appendTo(self.#offcanvas.body)
			}).appendTo(self.#offcanvas.body)
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
						modal.header.addClass('text-bg-primary')
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
						modal.header.addClass('text-bg-primary')
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
						modal.header.addClass('text-bg-primary')
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
							modal.header.addClass('text-bg-primary')
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
							modal.header.addClass('text-bg-primary')
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
							modal.header.addClass('text-bg-primary')
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
							modal.header.addClass('text-bg-primary')
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

// Core Utilities
const API = new phpAPI('/api.php')
const Cookie = new phpAuthCookie()
const Clock = new coreDBClock({frequence:30000})
const Auth = new coreDBAuth()
const SystemStatus = new coreDBSystemStatus()
const Search = new coreDBSearch()
// Core Components
const Icon = new coreDBIcon()
const Gravatar = new coreDBGravatar()
const Dropdown = new coreDBDropdown()
const Card = new coreDBCard()
const Code = new coreDBCode()
const Modal = new coreDBModal()
const Timeline = new coreDBTimeline()
const Table = new coreDBTable()
// Core Elements
const Notifications = new coreDBNotifications()
const Toast = new coreDBToast()
const Activity = new coreDBActivity()
const Dashboard = new coreDBDashboard()
const File = new coreDBFile()
const Note = new coreDBNote()
const Comments = new coreDBComments()
const Feed = new coreDBFeed()
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
