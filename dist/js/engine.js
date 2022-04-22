const Engine = {
	initiated:false,
	installed:false,
	Logger:{
		status:false,
		init:function(){
			console=(function(oldCons){
				return {
					log:function(text){
						if(Engine.Debug.logger){
							var e = new Error();
							if(!e.stack){
								try { throw e; } catch (e) { if(!e.stack){} }
							}
							var stack = e.stack.toString().split(/\r\n|\n/);
							for(var [key, step] of Object.entries(stack)){ stack[key] = step.trim(); }
							if(text === ''){ text = '""'; }
							var timeElapsed = Date.now();
							var now = new Date(timeElapsed);
							var date = Engine.Helper.toString(now);
							if(Engine.Debug.status){ oldCons.log('['+date+']',text);oldCons.log(stack); }
						}
					},
					info:function(text){ oldCons.info(text); },
					warn:function(text){ oldCons.warn(text); },
					error:function(text){
						if(Engine.Debug.logger){
							oldCons.error(text);
						}
					}
				};
			}(window.console));
			window.console = console;
		},
		enable:function(){
			Engine.Debug.logger = true;
		},
		disable:function(){
			Engine.Debug.logger = false;
		},
	},
	Debug:{
		status: false,
		enable:function(){ Engine.Debug.status = true;Engine.Debug.render(); },
		disable:function(){ Engine.Debug.status = false;Engine.Debug.render(); },
		request:{
			parameters:{},
			dataset:{},
		},
		action:function(){
			Engine.Toast.warning({
				warning:'Debug data sent to console',
				request:{
					parameters:Engine.Debug.request.parameters,
					dataset:Engine.Debug.request.dataset,
				},
				builder:{count:Engine.Builder.count},
				layout:Engine.Layout,
			});
		},
		render:function(){
			if(Engine.Debug.status){
				Engine.Logger.enable();
				if(typeof Engine.Debug.icon === 'undefined'){
					Engine.Debug.icon = $(document.createElement('span')).addClass('debug fa-stack fa-2x rounded-circle').attr('title',Engine.Translate('Debug is enabled')).attr('data-bs-toggle','tooltip').attr('data-bs-placement','left').tooltip().appendTo('body');
					$(document.createElement('i')).addClass('far fa-circle fa-stack-2x text-orange rounded-circle').appendTo(Engine.Debug.icon);
					$(document.createElement('i')).addClass('fas fa-exclamation fa-stack-1x text-warning rounded-circle').appendTo(Engine.Debug.icon);
					Engine.Debug.icon.click(function(){
						Engine.Debug.action();
					});
					Engine.Helper.blink(Engine.Debug.icon,'1s');
				}
			} else {
				Engine.Logger.disable();
				if(Engine.Helper.isSet(Engine,['Debug','icon'])){ Engine.Debug.icon.remove();delete Engine.Debug.icon; }
			}
		},
	},
	database:sessionStorage,
	cache:localStorage,
	Clock:{
		stop:function(){
			if(typeof Engine.Clock.timeout !== 'undefined'){ clearInterval(Engine.Clock.timeout);delete Engine.Clock.timeout; }
		},
		start:function(){
			if(typeof Engine.Clock.timeout === 'undefined'){
				Engine.Clock.timeout = setInterval(function(){
					Engine.init();
				}, 5000);
			}
		},
	},
	init:function(){
		var initSettings = {toast: false};
		if(Engine.initiated){ initSettings.pace = false; }
		else { Engine.Logger.init(); }
		Engine.request(initSettings).then(function(dataset){
			if(!Engine.Helper.isSet(Engine,['Layout','body'])){
				Engine.Layout.body = $(document.createElement('section')).addClass('vh-100 vw-100').appendTo('body');
				Engine.Layout.main = Engine.Layout.body;
			}
			Engine.Debug.status = dataset.debug;
      Engine.installed = dataset.installed;
			var language = {
				current:dataset.language,
				list:dataset.languages,
				fields:dataset.fields,
			}
      Engine.Storage.set('language',language);
			var timezone = {
				current:dataset.timezone,
				list:dataset.timezones,
			}
      Engine.Storage.set('timezone',timezone);
      Engine.Storage.set('license',dataset.license);
      Engine.Storage.set('country','list',dataset.countries);
      Engine.Storage.set('state','list',dataset.states);
      Engine.Storage.set('brand',dataset.brand);
      if(Engine.Helper.isSet(dataset,['administration'])){ Engine.Storage.set('administration',dataset.administration); }
      if(Engine.Helper.isSet(dataset,['user'])){ Engine.Storage.set('user',dataset.user);Engine.Auth.isLogin = true; }
      if(Engine.Helper.isSet(dataset,['groups'])){ Engine.Storage.set('groups',dataset.groups); }
      if(Engine.Helper.isSet(dataset,['roles'])){ Engine.Storage.set('roles',dataset.roles); }
      if(Engine.Helper.isSet(dataset,['permissions'])){ Engine.Storage.set('permissions',dataset.permissions); }
      if(Engine.Helper.isSet(dataset,['options'])){ Engine.Storage.set('options',dataset.options); }
      if(Engine.Helper.isSet(dataset,['tables'])){ Engine.Storage.set('tables',dataset.tables); }
      if(Engine.Helper.isSet(dataset,['notifications'])){ Engine.Storage.set('notification',{list:dataset.notifications}); }
			if(Engine.initiated && Engine.Storage.get('user','status') > 2){ Engine.Application.disabled(); }
			else if(Engine.initiated && Engine.Storage.get('user','status') > 1){ Engine.Application.reactivate(); }
			Pace.on('hide', function(){
				if(!Engine.initiated){
				  $('body').removeClass('content-loading').addClass('content-loaded');
					if(!Engine.installed){ Engine.Application.installer(); }
		      else if(!Engine.Auth.isLogin){ Engine.Application.login(); }
		      else if(Engine.Storage.get('user','status') < 1){ Engine.Application.activate(); }
		      else if(Engine.Storage.get('user','status') > 1){ Engine.Application.reactivate(); }
		      else { Engine.Application.init(); }
					Engine.initiated = true;
					Engine.Clock.start();
					Engine.Notification.render();
				}
			});
			if(Engine.initiated){ Engine.Notification.render(); }
			Engine.Debug.render();
		});
	},
	reload:function(){
		localStorage.clear();
		sessionStorage.clear();
		window.location = window.location.origin+window.location.pathname;
	},
  Auth:{
    isLogin:false,
		isAllowed:function(permission, level = 1){
			var result = ((Engine.Storage.get('permissions',permission) !== undefined && Engine.Storage.get('permissions',permission) >= level) || (Engine.Storage.get('permissions','isAdministrator') !== undefined && Engine.Storage.get('permissions','isAdministrator')));
			if(!result){
				var log = Engine.Storage.get('user','username')+' is requesting permission for ['+permission+']. User is member of ';
				log += JSON.stringify(Engine.Storage.get('groups'));
				console.warn(log);
			}
			return result;
		},
    logout:function(){
      Engine.request('api','logout',{toast: false,pace: false}).then(function(){
        Engine.reload();
      });
    },
  },
  Translate:function(field){
		if(Engine.Storage.get('language')){
			var text = Engine.Storage.get('language',['fields',field]);
	    if(text !== undefined){ return text; } else {
	      console.warn('Language field: "'+field+'" is not available in '+Engine.Storage.get('language','current'));
	      return field;
	    }
		} else {
			console.error('Unable to retrieve language data');
			return field;
		}
  },
	Storage:{
		get:function(object,keyPath = null){
			if(keyPath == null){
				if(Engine.Helper.isSet(Engine.database,[object])){ return Engine.Helper.decode(Engine.database[object]); } else { return {}; }
			} else {
				if(typeof keyPath === 'string'){ keyPath = [keyPath]; }
				if(Engine.Helper.isSet(Engine.database,[object])){
					var obj = Engine.Helper.decode(Engine.database[object]);
					lastKeyIndex = keyPath.length-1;
					for(var i = 0; i < lastKeyIndex; ++ i){
						key = keyPath[i];
						if(!(key in obj)){obj[key] = {};}
						obj = obj[key];
					}
					return obj[keyPath[lastKeyIndex]];
				} else { return {}; }
			}
		},
		set:function(object,keyPath,value = null){
			if(value == null){
				if(Engine.Helper.isJson(keyPath)){ keyPath = JSON.parse(keyPath); }
				Engine.Helper.set(Engine.database,[object],Engine.Helper.encode(keyPath));
			} else {
				if(typeof keyPath === 'string'){ keyPath = [keyPath]; }
				if(Engine.Helper.isJson(value)){ value = JSON.parse(value); }
				if(Engine.Helper.isSet(Engine.database,[object])){ var obj = Engine.Helper.decode(Engine.database[object]); } else { var obj = {}; }
				lastKeyIndex = keyPath.length-1;
				for(var i = 0; i < lastKeyIndex; ++ i){
					key = keyPath[i];
					if(!(key in obj)){obj[key] = {};}
					obj = obj[key];
				}
				obj[keyPath[lastKeyIndex]] = value;
				Engine.Helper.set(Engine.database,[object],Engine.Helper.encode(obj));
			}
		},
	},
	request:function(api = 'api', method = 'init', options = {},callback = null){
		if(api instanceof Function){ callback = api; api = 'api'; }
    if(api instanceof Object){ options = api; api = 'api'; }
		if(method instanceof Function){ callback = method; method = 'init'; }
    if(method instanceof Object){ options = method; method = 'init'; }
		if(options instanceof Function){ callback = options; options = {}; }
		var defaults = {
			toast: false,
			pace: true,
			report: false,
			data: null,
		};
		for(var [key, option] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[key])){ defaults[key] = option; } }
		if(Engine.Debug.status){ defaults.report = true; }
    if(defaults.pace){ track='track'; } else { track='ignore'; }
		return new Promise(function(resolve, reject) {
      Pace[track](function(){
  			var params = {
  				method:'session',
  				request:api,
  				type:method,
  			};
  			if(defaults.data != null){ params.data = defaults.data; }
  			params = Engine.Helper.formatOBJ(params);
				Engine.Debug.request.parameters = {api:api,method:method,options:options,defaults:defaults};
        $.post('./api.php',params,function(data,status,xhr){
					if(!jQuery.isEmptyObject(data)){
						Engine.Debug.request.dataset = data;
	          if(defaults.toast){
							Engine.Toast.success(data);
	          }
						Engine.Toast.warning(data);
	          Engine.Toast.error(data);
						if(typeof data.output !== 'undefined'){ resolve(data.output); }
	          if(callback != null){
	            delete data.output;
	            callback(data);
	          }
					}
        }, "json" ).fail(function(xhr,status,data){
          if((defaults.report && defaults.toast) || Engine.Debug.status){ Engine.Toast.report({status:status,xhr:xhr,data:data}); }
        });
      });
		});
	},
	Toast:{
		set:{
			toast: true,
			position: 'bottom',
			showConfirmButton: false,
			timer: 2000,
		},
		save:function(success = null,denied = null){
			Swal.fire({
			  title: Engine.Translate('Do you want to save the changes?'),
			  icon: 'question',
				iconColor:Engine.Colors.info,
			  showDenyButton: true,
			  showCancelButton: true,
			  confirmButtonText: Engine.Translate('Save'),
			  denyButtonText: Engine.Translate("Don't save"),
				customClass:{
					confirmButton: 'btn btn-success mx-1',
					denyButton: 'btn btn-primary mx-1',
					cancelButton: 'btn btn-gray-300 mx-1'
				},
				buttonsStyling: false,
			}).then((result) => {
			  if(result.isConfirmed){
					function fire(){
						Swal.fire({
						  title: Engine.Translate('Saved!'),
						  text: '',
						  icon: 'success',
							iconColor:Engine.Colors.success,
							customClass:{
								title: 'pb-4',
							},
							showConfirmButton: false,
							timer: 950,
							timerProgressBar: true,
						});
					}
					if(success instanceof Function){ $.when(success()).then(fire()); }
			    else { fire(); }
			  } else if (result.isDenied) {
					function fire(){
						Swal.fire({
						  title: Engine.Translate('Changes were not saved'),
						  text: '',
						  icon: 'info',
							iconColor:Engine.Colors.info,
							customClass:{
								title: 'pb-4',
							},
							showConfirmButton: false,
							timer: 950,
							timerProgressBar: true,
						});
					}
					if(denied instanceof Function){ $.when(denied()).then(fire()); }
			    else { fire(); }
			  }
			})
		},
		delete:function(success = null){
			Swal.fire({
			  title: Engine.Translate('Are you sure?'),
			  text: Engine.Translate("You won't be able to revert this!"),
			  icon: 'warning',
				iconColor:Engine.Colors.danger,
			  showCancelButton: true,
				customClass:{
					confirmButton: 'btn btn-danger mx-1',
					cancelButton: 'btn btn-gray-300 mx-1'
				},
			  confirmButtonText: Engine.Translate('Yes, delete it!'),
				buttonsStyling: false,
			}).then((result) => {
			  if (result.isConfirmed) {
					function fire(){
						Swal.fire({
						  title: Engine.Translate('Deleted!'),
						  text: '',
						  icon: 'success',
							iconColor:Engine.Colors.success,
							customClass:{
								title: 'pb-4',
							},
							showConfirmButton: false,
							timer: 950,
							timerProgressBar: true,
						});
					}
					if(success instanceof Function){ $.when(success()).then(fire()); }
			    else { fire(); }
			  }
			})

		},
		success:function(dataset){
			if(Engine.Helper.isSet(dataset,['success'])){
				Swal.fire({
					icon: 'success',
					iconColor:Engine.Colors.success,
					title: dataset.success,
					text: '',
					toast: true,
					position: 'bottom',
					showConfirmButton: false,
					timer: 2000,
					timerProgressBar: true,
				});
				if(Engine.Debug.status && !Engine.Helper.isSet(dataset,['output'])){ console.log(dataset); }
			}
		},
		warning:function(dataset){
			if(Engine.Helper.isSet(dataset,['warning'])){
				Swal.fire({
					icon: 'warning',
					iconColor:Engine.Colors.warning,
					title: dataset.warning,
					text: '',
					toast: true,
					position: 'bottom',
					showConfirmButton: false,
					timer: 2000,
					timerProgressBar: true,
				});
				if(Engine.Debug.status && !Engine.Helper.isSet(dataset,['output'])){ console.log(dataset); }
			}
		},
		error:function(dataset){
			if(Engine.Helper.isSet(dataset,['error'])){
				Swal.fire({
					icon: 'error',
					iconColor:Engine.Colors.danger,
					title: dataset.error,
					text: '',
					toast: true,
					position: 'bottom',
					showConfirmButton: false,
					timer: 2000,
					timerProgressBar: true,
				});
				if(Engine.Debug.status && !Engine.Helper.isSet(dataset,['output'])){ console.log(dataset); }
			}
		},
		report:function(dataset){
			if(Engine.Debug.status){
				Swal.fire({
					icon: 'error',
					iconColor:Engine.Colors.danger,
					title: Engine.Translate('Error'),
					text: Engine.Translate('An error occured in the execution of this API request. See the console(F12) for more details.'),
					showConfirmButton: true,
					customClass:{
						confirmButton: 'btn btn-primary ms-auto',
					},
					buttonsStyling: false,
					width:'600px',
					timer: 0,
					toast: true,
					position: 'bottom',
				});
				if(Engine.Helper.isSet(dataset,['xhr','responseText'])){ console.log($(dataset.xhr.responseText).text()); }
				else { console.log(dataset); }
			}
		},
	},
	Colors:{
		blue:getComputedStyle(document.documentElement).getPropertyValue('--bs-blue'),
		indigo:getComputedStyle(document.documentElement).getPropertyValue('--bs-indigo'),
		purple:getComputedStyle(document.documentElement).getPropertyValue('--bs-purple'),
		pink:getComputedStyle(document.documentElement).getPropertyValue('--bs-pink'),
		red:getComputedStyle(document.documentElement).getPropertyValue('--bs-red'),
		orange:getComputedStyle(document.documentElement).getPropertyValue('--bs-orange'),
		yellow:getComputedStyle(document.documentElement).getPropertyValue('--bs-yellow'),
		green:getComputedStyle(document.documentElement).getPropertyValue('--bs-green'),
		teal:getComputedStyle(document.documentElement).getPropertyValue('--bs-teal'),
		cyan:getComputedStyle(document.documentElement).getPropertyValue('--bs-cyan'),
		white:getComputedStyle(document.documentElement).getPropertyValue('--bs-white'),
		gray:getComputedStyle(document.documentElement).getPropertyValue('--bs-gray'),
		primary:getComputedStyle(document.documentElement).getPropertyValue('--bs-primary'),
		secondary:getComputedStyle(document.documentElement).getPropertyValue('--bs-secondary'),
		success:getComputedStyle(document.documentElement).getPropertyValue('--bs-success'),
		info:getComputedStyle(document.documentElement).getPropertyValue('--bs-info'),
		warning:getComputedStyle(document.documentElement).getPropertyValue('--bs-warning'),
		danger:getComputedStyle(document.documentElement).getPropertyValue('--bs-danger'),
		light:getComputedStyle(document.documentElement).getPropertyValue('--bs-light'),
		dark:getComputedStyle(document.documentElement).getPropertyValue('--bs-dark'),
	},
	Helper:{
		setPageTitle:function(title){
			document.title = Engine.Translate(title)+' · '+Engine.Storage.get('brand');
		},
		blink:function(object, transition = "0.5s", opacity = 0){
			object.css("transition", transition);
			var timer = setInterval(function(){
				if(object.css('opacity') == opacity){ object.css("opacity", 1); }
				else if(object.css('opacity') == 1) { object.css("opacity", opacity); }
			}, 100);
		},
		isJson:function(json){
			if(typeof json === 'string'){
				try { JSON.parse(json); } catch (e) { return false; }
		    return true;
			} else { return false; }
		},
		json:{
			encode:function(object){
				if(object instanceof Object){ return JSON.stringify(object); }
				else { return object; }
			},
			decode:function(json){
				if(typeof json === 'string'){
					try { JSON.parse(json); } catch (e) { return json; }
			    return JSON.parse(json);
				} else { return json; }
			},
		},
		parse:function(json){
			if(typeof json === 'string'){
				try { JSON.parse(json); } catch (e) { return json; }
		    return JSON.parse(json);
			} else { return json; }
		},
		encode:function(decoded){
			try { window.btoa(unescape(encodeURIComponent(JSON.stringify(Engine.Helper.parse(decoded))))); } catch (error) { console.log(decoded);console.error(error);return false; }
			return window.btoa(unescape(encodeURIComponent(JSON.stringify(Engine.Helper.parse(decoded)))));
		},
		decode:function(encoded){
			try { Engine.Helper.parse(decodeURIComponent(escape(window.atob(encoded)))); } catch (error) { console.log(encoded);console.error(error);return false; }
			return Engine.Helper.parse(decodeURIComponent(escape(window.atob(encoded))));
		},
		formatURL:function(params){
			return Object.keys(params).map(function(key){ return key+"="+Engine.Helper.encode(params[key]) }).join("&");
		},
		formatOBJ:function(params){
      for(var [key, value] of Object.entries(params)){
        params[key] = Engine.Helper.encode(value);
      }
      return params;
		},
		copyToClipboard:function(text){
		  var aux = document.createElement("input");
		  aux.setAttribute("value", text);
		  document.body.appendChild(aux);
		  aux.select();
		  document.execCommand("copy");
		  document.body.removeChild(aux);
			Swal.fire({
				title: Engine.Translate('Copied to clipboard!'),
				text: '',
				icon: 'success',
				iconColor:Engine.Colors.success,
				toast:true,
				showConfirmButton: false,
				timer: 950,
				timerProgressBar: true,
				position: 'bottom',
			});
		},
		toCSV:function(array,options = {}){
			var url = new URL(window.location.href);
			var defaults = {plugin:url.searchParams.get("p")};
			for(var [key, option] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[key])){ defaults[key] = option; } }
			var csv = '';
			for(var [key, value] of Object.entries(array)){
				if(value == null){ value = '';};
				value = String(value).toLowerCase();
				if(value != ''){
					if(csv != ''){ csv += ','; }
					csv += value;
				}
			}
			return csv;
		},
		toString:function(date){
			var day = String(date.getDate()).padStart(2, '0');
			var month = String(date.getMonth() + 1).padStart(2, '0');
			var year = date.getFullYear();
			var hours = String(date.getHours()).padStart(2, '0');
			var minutes = String(date.getMinutes()).padStart(2, '0');
			var secondes = String(date.getSeconds()).padStart(2, '0');
			return year+'-'+month+'-'+day+' '+hours+':'+minutes+':'+secondes;
		},
		html2text:function(html){
			return $(html).text();
		},
		htmlentities:function(obj){
			for(var key in obj){
	      if(typeof obj[key] == "object" && obj[key] !== null){ Engine.Helper.htmlentities(obj[key]); }
	      else { if(typeof obj[key] == "string" && obj[key] !== null){ obj[key] = he.encode(obj[key],{ 'useNamedReferences': true }); } }
	    }
			return obj;
		},
		ucfirst:function(s){ if (typeof s !== 'string') return s; return s.charAt(0).toUpperCase() + s.slice(1); },
		clean:function(s){ if (typeof s !== 'string') return s; return s.replace(/_/g, " ").replace(/\./g, " "); },
		isOdd:function(num) { return num % 2;},
		trim:function(string,character){
			while(string.charAt(0) == character){
			  string = string.substring(1);
			}
			while(string.slice(-1) == character){
			  string = string.slice(0,-1);
			}
			return string;
		},
		isInt:function(num){
			if((num+"").match(/^\d+$/)){ return true; } else { return false; }
		},
		padNumber:function(num, targetLength){
		  return num.toString().length < targetLength ? num.toString().padStart(targetLength, 0) : num;
		},
		padString:function(string, targetLength, character){
		  return string.toString().length < targetLength ? string.toString().padStart(targetLength, character) : string;
		},
		set:function(obj, keyPath, value) {
			lastKeyIndex = keyPath.length-1;
			for(var i = 0; i < lastKeyIndex; ++ i){
				key = keyPath[i];
				if(!(key in obj)){obj[key] = {};}
				obj = obj[key];
			}
			obj[keyPath[lastKeyIndex]] = value;
		},
		isSet:function(obj, keyPath) {
			if(typeof obj !== 'undefined' && !jQuery.isEmptyObject(obj)){
				var v = true;
				lastKeyIndex = keyPath.length;
				for(var i = 0; i < lastKeyIndex; ++ i){
					key = keyPath[i];
					if(typeof obj[key] === 'undefined'){ v = false; break; }
					obj = obj[key];
				}
				return v;
			} else { return false; }
		},
		addZero:function(i){
		  if (i < 10) { i = "0" + i; }
		  return i;
		},
		now:function(type = 'UTF8'){
			var currentDate = new Date();
			switch(type){
				case'ISO_8601':
					var datetime = currentDate.getFullYear() + "-"
		        + (currentDate.getMonth()+1)  + "-"
		        + currentDate.getDate() + "T"
		        + Engine.Helper.addZero(currentDate.getHours()) + ":"
		        + Engine.Helper.addZero(currentDate.getMinutes()) + ":"
		        + Engine.Helper.addZero(currentDate.getSeconds());
					break;
				default:
					var datetime = currentDate.getFullYear() + "-"
		        + (currentDate.getMonth()+1)  + "-"
		        + currentDate.getDate() + " "
		        + Engine.Helper.addZero(currentDate.getHours()) + ":"
		        + Engine.Helper.addZero(currentDate.getMinutes()) + ":"
		        + Engine.Helper.addZero(currentDate.getSeconds());
					break;
			}
			return datetime;
		},
		getUrlVars:function() {
	    var vars = {};
	    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
	        vars[key] = value;
	    });
	    return vars;
		},
		getFileSize:function(bytes, si=false, dp=1) {
		  const thresh = si ? 1000 : 1024;
		  if (Math.abs(bytes) < thresh) { return bytes + ' B'; }
		  const units = si
		    ? ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
		    : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
		  let u = -1;
		  const r = 10**dp;
		  do { bytes /= thresh; ++u; }
			while (Math.round(Math.abs(bytes) * r) / r >= thresh && u < units.length - 1);
		  return bytes.toFixed(dp) + ' ' + units[u];
		},
		isFuture:function(date){
			var futureDate = new Date(date);
			var currentDate = new Date();
			if(futureDate > currentDate){ return true; } else { return false; }
		},
		download:function(url, filename = null){
			console.info('Downloading '+url);
		  fetch(url).then(resp => resp.blob()).then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
				if(filename != null){ a.download = filename; }
				else { a.download = url.substring(url.lastIndexOf('/')+1); }
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
	    }).catch(() => Engine.Toast.report('Unable to download the file at '+url));
		},
	},
	Cookie:{
		create:function(name, value, days = 30){
		  var expires;
		  if(days){
		    var date = new Date();
		    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
		    expires = "; expires=" + date.toGMTString();
		  } else {
		    expires = "";
		  }
		  document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(Engine.Helper.json.encode(value)) + expires + "; path=/";
		},
		read:function(name) {
		  var nameEQ = encodeURIComponent(name) + "=";
		  var ca = document.cookie.split(';');
		  for (var i = 0; i < ca.length; i++) {
		    var c = ca[i];
		    while (c.charAt(0) === ' ')
		        c = c.substring(1, c.length);
		    if (c.indexOf(nameEQ) === 0)
		        return Engine.Helper.json.decode(decodeURIComponent(c.substring(nameEQ.length, c.length)));
		  }
		  return null;
		},
		delete:function(name) {
		  Engine.Cookie.create(name, "", -1);
		},
	},
	Form:{
		save:function(){
			var data = {};
			$(':input').each(function(){
				if($(this).attr('id') && $(this).parents('form').first().attr('name')){
					Engine.Helper.set(data,[$(this).parents('form').first().attr('name'),$(this).attr('name')],$(this).val());
				}
			});
			Engine.Cookie.create('forms',data);
			console.log(data);
		},
		track:function(){
			$(':input').each(function(){
				if(!$(this).hasClass('form-tracking')){
					$(this).addClass('form-tracking');
					$(this).on('propertychange input',function(e){
						var valueChanged = false;
						if(e.type=='propertychange'){
							valueChanged = e.originalEvent.propertyName=='value';
						} else { valueChanged = true; }
						if(valueChanged){ Engine.Form.save(); }
					});
				}
			});
		},
		restore:function(){
			if(Engine.Cookie.read('forms')){
				for(var [form, values] of Object.entries(Engine.Cookie.read('forms'))){
					for(var [input, value] of Object.entries(values)){
						$('form[name="'+form+'"]').find('[name="'+input+'"]').val(value);
						if($('form[name="'+form+'"]').find('[name="'+input+'"]').hasClass('select2-hidden-accessible')){
							$('form[name="'+form+'"]').find('[name="'+input+'"]').trigger('change');
						}
					}
				}
			}
		},
		validate:{
			email:function(email){
				var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  			return regex.test(email);
			},
			password:function(password, length = 8){
				var regex = /[;+*?#$%^&)(=~`\':_\-!@\"\]\[]/;
				return(password.match(/[a-z]/) && password.match(/[A-Z]/) && password.match(/\d/) && regex.test(password) && password.length >= length)
			},
		},
	},
	Layout:{
		load:function(layout, options = {}){
			var defaults = {
				title: 'Unknown',
			};
			if(Engine.Helper.isSet(layout,['title'])){ defaults.title = layout.title; }
			for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
			Engine.Layout.current = layout;
			var load = function(layout){
				if(typeof Engine.Layout.main !== 'undefined'){
					Engine.Layout.main.html(layout).find('time').timeago();
				}
			}
			var loading = function(title,layout){
				Engine.Helper.setPageTitle(title);
				load(layout);
			}
			loading(defaults.title,layout);
		},
		clear:function(){
			Engine.Layout.current = Engine.Layout.main.html('');
		},
	},
  Builder:{
    count: 0,
    layouts:{
      installer:function(options = {}, callback = null){
        if(options instanceof Function){ callback = options; options = {}; }
        var defaults = {
          backgroundPosition: 'start',
        };
        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
        Engine.Builder.count++;
        var layout = $(document.createElement('section')).attr('id','installer'+Engine.Builder.count);
        layout.id = layout.attr('id');
				layout.title = "Installer";
        layout.background = $(document.createElement('div')).addClass('background background-'+defaults.backgroundPosition).appendTo(layout);
        layout.main = $(document.createElement('main')).addClass('box').appendTo(layout);
        layout.main.box = $(document.createElement('div')).addClass('box-middle installer').appendTo(layout.main);
        if(callback != null){ callback(layout); }
        return layout;
      },
      login:function(options = {}, callback = null){
        if(options instanceof Function){ callback = options; options = {}; }
        var defaults = {
          backgroundPosition: 'start',
          formPosition: 'end',
        };
        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
        Engine.Builder.count++;
        var layout = $(document.createElement('section')).attr('id','login'+Engine.Builder.count);
        layout.id = layout.attr('id');
				layout.title = "Sign in";
        layout.background = $(document.createElement('div')).addClass('background background-'+defaults.backgroundPosition).appendTo(layout);
        layout.main = $(document.createElement('main')).addClass('box').appendTo(layout);
        layout.main.box = $(document.createElement('div')).addClass('text-center box-'+defaults.formPosition).appendTo(layout.main);
        layout.main.box.theme = $(document.createElement('div')).addClass('form-signin').appendTo(layout.main.box);
        layout.main.box.theme.form = $(document.createElement('form')).attr('method','post').appendTo(layout.main.box.theme);
        layout.main.box.theme.form.title = $(document.createElement('div')).addClass('form-floating pb-4 title').html(Engine.Storage.get('brand')).appendTo(layout.main.box.theme.form);
        layout.main.box.theme.form.username = $(document.createElement('div')).addClass('form-floating').appendTo(layout.main.box.theme.form);
        $(document.createElement('input')).addClass('form-control').attr('type','email').attr('name','username').attr('id','username').attr('placeholder',Engine.Translate('Username')).appendTo(layout.main.box.theme.form.username);
        $(document.createElement('label')).attr('for','username').html(Engine.Translate('Username')).appendTo(layout.main.box.theme.form.username);
        layout.main.box.theme.form.password = $(document.createElement('div')).addClass('form-floating').appendTo(layout.main.box.theme.form);
        $(document.createElement('input')).addClass('form-control').attr('type','password').attr('name','password').attr('id','password').attr('placeholder',Engine.Translate('Password')).appendTo(layout.main.box.theme.form.password);
        $(document.createElement('label')).attr('for','password').html(Engine.Translate('Password')).appendTo(layout.main.box.theme.form.password);
				layout.main.box.theme.form.remember = $(document.createElement('div')).addClass('form-floating text-start mt-3 mb-4').appendTo(layout.main.box.theme.form);
				Engine.Builder.forms.checkbox('remember',{label:'Remember me'}).appendTo(layout.main.box.theme.form.remember);
				layout.main.box.theme.form.submit = $(document.createElement('div')).addClass('form-floating w-100').appendTo(layout.main.box.theme.form);
        $(document.createElement('button')).addClass('w-100 btn btn-lg btn-primary').attr('type','submit').attr('name','signin').attr('id','signin').html(Engine.Translate('Sign in')).appendTo(layout.main.box.theme.form.submit);
				layout.main.box.theme.form.forgot = $(document.createElement('div')).addClass('form-floating mt-2').appendTo(layout.main.box.theme.form);
				$(document.createElement('a')).addClass('cursor-pointer link-primary text-decoration-none noselect').html(Engine.Translate('Forgot password?')).appendTo(layout.main.box.theme.form.forgot);
        if(callback != null){ callback(layout); }
        return layout;
      },
      forgot:function(options = {}, callback = null){
        if(options instanceof Function){ callback = options; options = {}; }
        var defaults = {
          backgroundPosition: 'start',
          formPosition: 'end',
        };
        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
        Engine.Builder.count++;
        var layout = $(document.createElement('section')).attr('id','login'+Engine.Builder.count);
        layout.id = layout.attr('id');
				layout.title = "Forgot password";
        layout.background = $(document.createElement('div')).addClass('background background-'+defaults.backgroundPosition).appendTo(layout);
        layout.main = $(document.createElement('main')).addClass('box').appendTo(layout);
        layout.main.box = $(document.createElement('div')).addClass('text-center box-'+defaults.formPosition).appendTo(layout.main);
        layout.main.box.theme = $(document.createElement('div')).addClass('form-signin').appendTo(layout.main.box);
        layout.main.box.theme.form = $(document.createElement('form')).attr('method','post').attr('action','./').appendTo(layout.main.box.theme);
        layout.main.box.theme.form.title = $(document.createElement('div')).addClass('form-floating pb-4 title').html(Engine.Storage.get('brand')).appendTo(layout.main.box.theme.form);
        layout.main.box.theme.form.username = $(document.createElement('div')).addClass('form-floating').appendTo(layout.main.box.theme.form);
        $(document.createElement('input')).addClass('form-control').attr('type','text').attr('name','username').attr('id','username').attr('placeholder',Engine.Translate('Username')).appendTo(layout.main.box.theme.form.username);
        $(document.createElement('label')).attr('for','username').html(Engine.Translate('Username')).appendTo(layout.main.box.theme.form.username);
        layout.main.box.theme.form.submit = $(document.createElement('button')).addClass('w-100 btn btn-lg btn-primary').attr('type','submit').attr('name','forgot').attr('id','forgot').html(Engine.Translate('Reset')).appendTo(layout.main.box.theme.form);
        if(callback != null){ callback(layout); }
        return layout;
      },
      reset:function(options = {}, callback = null){
        if(options instanceof Function){ callback = options; options = {}; }
        var defaults = {
          backgroundPosition: 'start',
          formPosition: 'end',
        };
        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
        Engine.Builder.count++;
        var layout = $(document.createElement('section')).attr('id','login'+Engine.Builder.count);
        layout.id = layout.attr('id');
				layout.title = "Reset password";
        layout.background = $(document.createElement('div')).addClass('background background-'+defaults.backgroundPosition).appendTo(layout);
        layout.main = $(document.createElement('main')).addClass('box').appendTo(layout);
        layout.main.box = $(document.createElement('div')).addClass('text-center box-'+defaults.formPosition).appendTo(layout.main);
        layout.main.box.theme = $(document.createElement('div')).addClass('form-signin').appendTo(layout.main.box);
        layout.main.box.theme.form = $(document.createElement('form')).attr('method','post').attr('action','./').appendTo(layout.main.box.theme);
        layout.main.box.theme.form.title = $(document.createElement('div')).addClass('form-floating pb-4 title').html(Engine.Storage.get('brand')).appendTo(layout.main.box.theme.form);
        layout.main.box.theme.form.keyActivation = $(document.createElement('div')).addClass('form-floating').appendTo(layout.main.box.theme.form);
        $(document.createElement('input')).addClass('form-control m-0 border-bottom-0 rounded-0 rounded-top').attr('type','text').attr('name','keyActivation').attr('id','keyActivation').attr('placeholder',Engine.Translate('Key')).appendTo(layout.main.box.theme.form.keyActivation);
        $(document.createElement('label')).attr('for','keyActivation').html(Engine.Translate('Key')).appendTo(layout.main.box.theme.form.keyActivation);
        layout.main.box.theme.form.username = $(document.createElement('div')).addClass('form-floating').appendTo(layout.main.box.theme.form);
        $(document.createElement('input')).addClass('form-control m-0 border-bottom-0 rounded-0').attr('type','text').attr('name','username').attr('id','username').attr('placeholder',Engine.Translate('Username')).appendTo(layout.main.box.theme.form.username);
        $(document.createElement('label')).attr('for','username').html(Engine.Translate('Username')).appendTo(layout.main.box.theme.form.username);
        layout.main.box.theme.form.password = $(document.createElement('div')).addClass('form-floating').appendTo(layout.main.box.theme.form);
        $(document.createElement('input')).addClass('form-control m-0 border-bottom-0 rounded-0').attr('type','password').attr('name','password').attr('id','password').attr('placeholder',Engine.Translate('New Password')).appendTo(layout.main.box.theme.form.password);
        $(document.createElement('label')).attr('for','password').html(Engine.Translate('New Password')).appendTo(layout.main.box.theme.form.password);
        layout.main.box.theme.form.password = $(document.createElement('div')).addClass('form-floating').appendTo(layout.main.box.theme.form);
        $(document.createElement('input')).addClass('form-control rounded-0 rounded-bottom').attr('type','password').attr('name','confirm').attr('id','confirm').attr('placeholder',Engine.Translate('Confirm Password')).appendTo(layout.main.box.theme.form.password);
        $(document.createElement('label')).attr('for','password').html(Engine.Translate('Confirm Password')).appendTo(layout.main.box.theme.form.password);
        layout.main.box.theme.form.submit = $(document.createElement('button')).addClass('w-100 btn btn-lg btn-primary').attr('type','submit').attr('name','reset').attr('id','reset').html(Engine.Translate('Reset')).appendTo(layout.main.box.theme.form);
        if(callback != null){ callback(layout); }
        return layout;
      },
      activate:function(options = {}, callback = null){
        if(options instanceof Function){ callback = options; options = {}; }
        var defaults = {
          backgroundPosition: 'start',
          formPosition: 'end',
        };
        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
        Engine.Builder.count++;
        var layout = $(document.createElement('section')).attr('id','login'+Engine.Builder.count);
        layout.id = layout.attr('id');
				layout.title = "Activation";
        layout.background = $(document.createElement('div')).addClass('background background-'+defaults.backgroundPosition).appendTo(layout);
        layout.main = $(document.createElement('main')).addClass('box').appendTo(layout);
        layout.main.box = $(document.createElement('div')).addClass('text-center box-'+defaults.formPosition).appendTo(layout.main);
        layout.main.box.theme = $(document.createElement('div')).addClass('form-signin').appendTo(layout.main.box);
        layout.main.box.theme.form = $(document.createElement('form')).attr('method','post').attr('action','./').appendTo(layout.main.box.theme);
        layout.main.box.theme.form.title = $(document.createElement('div')).addClass('form-floating pb-4 title').html(Engine.Storage.get('brand')).appendTo(layout.main.box.theme.form);
        layout.main.box.theme.form.keyActivation = $(document.createElement('div')).addClass('form-floating').appendTo(layout.main.box.theme.form);
        $(document.createElement('input')).addClass('form-control').attr('type','text').attr('name','keyActivation').attr('id','keyActivation').attr('placeholder',Engine.Translate('Activation Key')).appendTo(layout.main.box.theme.form.keyActivation);
        $(document.createElement('label')).attr('for','keyActivation').html(Engine.Translate('Activation Key')).appendTo(layout.main.box.theme.form.keyActivation);
        layout.main.box.theme.form.submit = $(document.createElement('button')).addClass('w-100 btn btn-lg btn-primary').attr('type','submit').attr('name','activate').attr('id','activate').html(Engine.Translate('Activate')).appendTo(layout.main.box.theme.form);
        if(callback != null){ callback(layout); }
        return layout;
      },
      reactivate:function(options = {}, callback = null){
        if(options instanceof Function){ callback = options; options = {}; }
        var defaults = {
          backgroundPosition: 'start',
          formPosition: 'end',
        };
        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
        Engine.Builder.count++;
        var layout = $(document.createElement('section')).attr('id','login'+Engine.Builder.count);
        layout.id = layout.attr('id');
				layout.title = "Reactivation";
        layout.background = $(document.createElement('div')).addClass('background background-'+defaults.backgroundPosition).appendTo(layout);
        layout.main = $(document.createElement('main')).addClass('box').appendTo(layout);
        layout.main.box = $(document.createElement('div')).addClass('text-center box-'+defaults.formPosition).appendTo(layout.main);
        layout.main.box.theme = $(document.createElement('div')).addClass('form-signin').appendTo(layout.main.box);
        layout.main.box.theme.form = $(document.createElement('form')).attr('method','post').attr('action','./').appendTo(layout.main.box.theme);
        layout.main.box.theme.form.title = $(document.createElement('div')).addClass('form-floating pb-4 title').html(Engine.Storage.get('brand')).appendTo(layout.main.box.theme.form);
        layout.main.box.theme.form.keyActivation = $(document.createElement('div')).addClass('form-floating').appendTo(layout.main.box.theme.form);
        $(document.createElement('input')).addClass('form-control').attr('type','text').attr('name','keyActivation').attr('id','keyActivation').attr('placeholder',Engine.Translate('Activation Key')).appendTo(layout.main.box.theme.form.keyActivation);
        $(document.createElement('label')).attr('for','keyActivation').html(Engine.Translate('Activation Key')).appendTo(layout.main.box.theme.form.keyActivation);
        layout.main.box.theme.form.submit = $(document.createElement('button')).addClass('w-100 btn btn-lg btn-primary').attr('type','submit').attr('name','activate').attr('id','activate').html(Engine.Translate('Reactivate')).appendTo(layout.main.box.theme.form);
        if(callback != null){ callback(layout); }
        return layout;
      },
      disabled:function(options = {}, callback = null){
        if(options instanceof Function){ callback = options; options = {}; }
        var defaults = {formPosition: 'middle'};
        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
        Engine.Builder.count++;
        var layout = $(document.createElement('section')).attr('id','login'+Engine.Builder.count);
        layout.id = layout.attr('id');
				layout.title = "Account Disabled";
        layout.background = $(document.createElement('div')).addClass('background').appendTo(layout);
        layout.main = $(document.createElement('main')).addClass('box').appendTo(layout);
        layout.main.box = $(document.createElement('div')).addClass('text-center box-'+defaults.formPosition).addClass('p-4').css('max-width','700px').css('min-width','320px').appendTo(layout.main);
				layout.main.box.card = $(document.createElement('div')).addClass('card text-start').appendTo(layout.main.box);
				layout.main.box.card.header = $(document.createElement('div')).addClass('card-header').css('font-size','20px').css('line-height','40px').css('font-weight','200').html(Engine.Translate('Your account has been disabled')).appendTo(layout.main.box.card);
				layout.main.box.card.body = $(document.createElement('div')).addClass('card-body').html(Engine.Translate('For more information, or if you thing your account was disabled by mistake, please contact the support team.')).appendTo(layout.main.box.card);
				layout.main.box.card.footer = $(document.createElement('div')).addClass('card-footer').appendTo(layout.main.box.card);
				layout.main.box.card.footer.button = $(document.createElement('a')).addClass('btn btn-primary float-end').appendTo(layout.main.box.card.footer);
				$(document.createElement('i')).addClass('fas fa-envelope me-2').appendTo(layout.main.box.card.footer.button);
				layout.main.box.card.footer.button.attr('href','mailto:'+Engine.Storage.get('administration')).append(Engine.Translate('Support team'));
        if(callback != null){ callback(layout); }
        return layout;
      },
      application:function(options = {}, callback = null){
        if(options instanceof Function){ callback = options; options = {}; }
        var defaults = {
          disableSidebar: false,
        };
        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
        Engine.Builder.count++;
        var layout = {};
        Engine.Builder.sections.splitX({disableShadow:true},function(splitX){
					layout = splitX;
					layout.background = $(document.createElement('div')).addClass('background background-bottom background-end f-grayscale').prependTo(layout);
					layout.primary.css('z-index','200');
					layout.secondary.css('max-width','calc(100vw - 72px)').css('width','calc(100vw - 72px)');
          if(!defaults.disableSidebar){
            Engine.Builder.components.sidebar(function(sidebar){
              layout.sidebar = sidebar;
            }).appendTo(splitX.primary);
          }
					Engine.Builder.sections.splitY({positionMain:'end',disableShadow:false},function(splitY){
						layout.splitY = splitY;
	          Engine.Builder.components.navbar({disableLogo:true,disableProfile:false},function(navbar){
	            layout.navbar = navbar;
	          }).appendTo(layout.splitY.primary);
						layout.main = layout.splitY.secondary;
						layout.main.addClass('scroll-y').css('z-index','80').css('max-width','calc(100vw - 72px)').css('width','calc(100vw - 72px)').css('max-height','calc(100vh - 55px)').css('height','calc(100vh - 55px)');
					}).appendTo(layout.secondary);
        });
        if(callback != null){ callback(layout); }
        return layout;
      },
			// template:function(options = {}, callback = null){
			// 	if(options instanceof Function){ callback = options; options = {}; }
			// 	var defaults = {};
			// 	for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
			// 	Engine.Builder.count++;
			// 	var layout = $(document.createElement('section')).addClass('m-0 p-0 noselect').attr('id','template'+Engine.Builder.count);
			// 	layout.id = layout.attr('id');
			// 	layout.title = "Template";
			// 	if(callback != null){ callback(layout); }
			// 	return layout;
			// },
			search:{
				render:function(options = {}, callback = null){
					if(options instanceof Function){ callback = options; options = {}; }
					var defaults = {};
					for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
					Engine.request('crud','search',{data:Engine.Layout.navbar.search.val()}).then(function(results){
						Engine.Builder.count++;
						var layout = $(document.createElement('section')).addClass('m-0 px-3 noselect').attr('id','search'+Engine.Builder.count);
						layout.id = layout.attr('id');
						layout.title = "Search Results";
						layout.tables = {};
						var tableOptions = {
							clipboard: false,
							selectableRow: false,
							dblclick: null,
							click: null,
							controls:{},
							actions:{},
						};
						for(var [table, records] of Object.entries(results)){
							tableOptions.title = table;
							if(Engine.Auth.isAllowed('access'+Engine.Helper.ucfirst(table))){
								Engine.Builder.components.table.render(records,tableOptions,function(table){
									table.header.prepend(Object.keys(records).length+' ');
									layout.tables[table] = table.addClass('my-3').appendTo(layout);
								});
							}
						}
						if(callback != null){ callback(layout); }
						return layout;
					});
				},
			},
			dashboard:{
				templates:{
					infoBox:function(options = {}, callback = null){
						if(options instanceof Function){ callback = options; options = {}; }
						var defaults = {
							color: 'light',
							icon: 'far fa-circle',
							iconColor: 'light',
						};
						for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
						Engine.Builder.count++;
						var box = $(document.createElement('div')).attr('id','infoBox'+Engine.Builder.count);
						box.id = box.attr('id');
						box.sizes = {
							lg:'col-lg-3 col-md-6 col-sm-12 col-xs-12',
							md:'col-lg-6 col-md-12 col-sm-12 col-xs-12',
							sm:'col-12',
						};
						for(var [size, classes] of Object.entries(box.sizes)){
							box.attr('data-'+size,classes);
						}
						box.card = $(document.createElement('div')).addClass('card shadow-sm bg-'+defaults.color).appendTo(box);
						box.row = $(document.createElement('div')).addClass('d-flex').appendTo(box.card);
						box.col = {
							icon: $(document.createElement('div')).addClass('sortHandle').css('width','80px').appendTo(box.row),
							info: $(document.createElement('div')).addClass('flex-grow-10').appendTo(box.row),
						};
						box.col.info.body = $(document.createElement('div')).addClass('card-body p-2 ps-2').appendTo(box.col.info);
						box.icon = $(document.createElement('i')).addClass('m-2 p-3 rounded shadow-sm fa-2x').addClass(defaults.icon).addClass('bg-'+defaults.iconColor).appendTo(box.col.icon);
						box.title = $(document.createElement('p')).appendTo(box.col.info.body);
						box.text = $(document.createElement('strong')).addClass('card-text').appendTo(box.col.info.body);
						if(callback != null){ callback(box); }
						return box;
					},
				},
				widgets:{
					newUsers:function(options = {}, callback = null){
						if(options instanceof Function){ callback = options; options = {}; }
						var defaults = {
							color: 'light',
							icon: 'fas fa-users',
							iconColor: 'info',
							count: 0,
						};
						for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
						Engine.Builder.layouts.dashboard.templates.infoBox(defaults,function(box){
							box.title.html(Engine.Translate('New Users'));
							box.text.html(defaults.count);
							if(callback != null){ callback(box); }
							return box;
						});
					},
					totalUsers:function(options = {}, callback = null){
						if(options instanceof Function){ callback = options; options = {}; }
						var defaults = {
							color: 'light',
							icon: 'fas fa-users',
							iconColor: 'primary',
							count: 0,
						};
						for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
						Engine.Builder.layouts.dashboard.templates.infoBox(defaults,function(box){
							box.title.html(Engine.Translate('Total Users'));
							box.text.html(defaults.count);
							if(callback != null){ callback(box); }
							return box;
						});
					},
					activeUsers:function(options = {}, callback = null){
						if(options instanceof Function){ callback = options; options = {}; }
						var defaults = {
							color: 'light',
							icon: 'fas fa-users',
							iconColor: 'success',
							count: 0,
						};
						for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
						Engine.Builder.layouts.dashboard.templates.infoBox(defaults,function(box){
							box.title.html(Engine.Translate('Active Users'));
							box.text.html(defaults.count);
							if(callback != null){ callback(box); }
							return box;
						});
					},
					disabledUsers:function(options = {}, callback = null){
						if(options instanceof Function){ callback = options; options = {}; }
						var defaults = {
							color: 'light',
							icon: 'fas fa-users',
							iconColor: 'danger',
							count: 0,
						};
						for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
						Engine.Builder.layouts.dashboard.templates.infoBox(defaults,function(box){
							box.title.html(Engine.Translate('Disabled Users'));
							box.text.html(defaults.count);
							if(callback != null){ callback(box); }
							return box;
						});
					},
				},
				render:function(options = {}, callback = null){
	        if(options instanceof Function){ callback = options; options = {}; }
	        var defaults = {
						layout:{
							lg:['newUsers','totalUsers','activeUsers','disabledUsers'],
							md:['newUsers','totalUsers'],
							sm:['activeUsers'],
						},
					};
	        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
					Engine.Builder.count++;
					var layout = $(document.createElement('section')).addClass('container m-0 p-2 noselect').attr('id','dashboard'+Engine.Builder.count);
					layout.id = layout.attr('id');
					layout.title = "Dashboard";
					layout.row = $(document.createElement('div')).addClass('row m-0 p-0 g-0').appendTo(layout);
					layout.row.lg = $(document.createElement('div')).addClass('col-12').appendTo(layout.row);
					layout.row.md = $(document.createElement('div')).addClass('col-lg-8 col-md-6 col-sm-12 col-xs-12').appendTo(layout.row);
					layout.row.sm = $(document.createElement('div')).addClass('col-lg-4 col-md-6 col-sm-12 col-xs-12').appendTo(layout.row);
					layout.widgets = {
						lg:$(document.createElement('div')).attr('data-location','lg').addClass('row g-3 m-0').appendTo(layout.row.lg),
						md:$(document.createElement('div')).attr('data-location','md').addClass('row g-3 m-0').appendTo(layout.row.md),
						sm:$(document.createElement('div')).attr('data-location','sm').addClass('row g-3 m-0').appendTo(layout.row.sm),
					};
					for(var [location, widgets] of Object.entries(defaults.layout)){
						for(var [key, widget] of Object.entries(widgets)){
							if(Engine.Helper.isSet(Engine,['Builder','layouts','dashboard','widgets',widget])){
								Engine.Builder.layouts.dashboard.widgets[widget](function(gadget){
									gadget.attr('data-location',location).attr('data-widget',widget);
									gadget.addClass(gadget.sizes[location]).appendTo(layout.widgets[location]);
								});
							}
						}
					}
					if(typeof Engine.Layout.navbar !== 'undefined'){
						Engine.Layout.navbar.container.start.nav.add.group({stick:false},function(group){
							group.add('',{icon:'fas fa-edit',disableRender:true,disableActive:true},function(button){
								button.addClass('rounded');
								button.click(function(){
								  for(var [location, object] of Object.entries(layout.widgets)){
								    object.addClass('sortWith').sortable({
								      placeholder:'sortHighlight',
								      connectWith:'.sortWith',
								      handle:'.sortHandle',
								      forcePlaceholderSize:true,
								      zIndex:999999,
								      disabled:false,
								      start:function(event, ui){
								        var classes = ui.item.attr('class').split(/\s+/);
								        for(var x=0; x<classes.length;x++){
								          if (classes[x].indexOf("col")>-1){
								            ui.placeholder.addClass(classes[x]);
								          }
								        }
								        ui.placeholder.css({
								          width: ui.item.innerWidth() - parseInt(ui.item.css("padding-left")) - parseInt(ui.item.css("padding-right")),
								          height: ui.item.innerHeight() - parseInt(ui.item.css("padding-top")) - parseInt(ui.item.css("padding-bottom")),
								          marginLeft: parseInt(ui.item.css("padding-left")),
								          marginRight: parseInt(ui.item.css("padding-right")),
								        });
								      },
								      stop:function(event, ui){
								        ui.item.attr('data-location',ui.item.parent().attr('data-location')).removeClass().addClass(ui.item.attr('data-'+ui.item.parent().attr('data-location')));
								      },
								    });
								  }
									group.edit.css('display','none');
									group.save.css('display','');
									// group.cancel.css('display','');
								});
								group.edit = button;
							})
							group.add('',{icon:'fas fa-save',disableRender:true,disableActive:true},function(button){
								button.addClass('rounded-start');
								button.css('display','none');
								button.click(function(){
									for(var [location, object] of Object.entries(layout.widgets)){
								    object.sortable("disable").removeClass('sortWith');
									}
									group.edit.css('display','');
									group.save.css('display','none');
									// group.cancel.css('display','none');
								});
								group.save = button;
							})
							// group.add('',{icon:'fas fa-ban',color:'gray-200',outline:false,disableRender:true,disableActive:true},function(button){
							// 	button.css('display','none');
							// 	button.click(function(){
							// 		for(var [location, object] of Object.entries(layout.widgets)){
							// 	    object.sortable("disable").removeClass('sortWith');
							// 		}
							// 		group.edit.css('display','');
							// 		group.save.css('display','none');
							// 		group.cancel.css('display','none');
							// 	});
							// 	group.cancel = button;
							// })
							layout.nav = group;
						});
					}
	        if(callback != null){ callback(layout); }
	        return layout;
				},
			},
			settings:function(options = {}, callback = null){
        if(options instanceof Function){ callback = options; options = {}; }
        var defaults = {};
        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
				Engine.request('api','getSettings').then(function(dataset){
					Engine.Builder.count++;
					Engine.Builder.sections.splitY({positionMain:'start',disableShadow:true},function(splitY){
						splitY.addClass('p-0');
						splitY.primary.addClass('p-2 border-top-1');
						splitY.primary.save = $(document.createElement('button')).addClass("btn btn-success w-100").html('<i class="fas fa-save me-1"></i>'+Engine.Translate('Save')).appendTo(splitY.primary);
						var layout = $(document.createElement('section')).addClass('row m-0 p-0 noselect list-group list-group-flush').attr('id','settings'+Engine.Builder.count).appendTo(splitY.secondary);
		        layout.id = layout.attr('id');
						layout.title = "Settings";
						layout.count = 0;
						layout.sections = {};
						layout.forms = {};
						layout.add = {
							item:function(name, settings = {}, options = {}, callback = null){
				        if(options instanceof Function){ callback = options; options = {}; }
				        var defaults = {
									translate: true,
									ucfirst: true,
								};
				        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
								layout.count++;
				        var item = $(document.createElement('a')).addClass('d-flex align-items-stretch flex-shrink-0 list-group-item border-bottom list-group-item-action py-3 lh-tight bg-transparent').attr('id',layout.id+'item'+layout.count).appendTo(layout);
				        item.id = item.attr('id');
								item.count = 0;
								var original = name.toLowerCase();
								if(defaults.ucfirst){ name = Engine.Helper.ucfirst(name); }
								if(defaults.translate){ name = Engine.Translate(name); }
								item.name = $(document.createElement('a')).addClass('d-flex w-100 align-items-center text-decoration-none justify-content-between link-dark').appendTo(item);
								item.name.data = $(document.createElement('strong')).addClass('mb-1').html(name).appendTo(item.name);
								item.forms = $(document.createElement('div')).addClass('col-10 mb-1 small').appendTo(item);
								Engine.Builder.components.form({header:'Settings',name:'ApplicationSettings'+original},function(form){
									item.forms.form = form;
									var count = Object.keys(settings).length;
									for(var [setting, parameters] of Object.entries(settings)){
										if(parameters.show){
											Engine.Builder.forms[parameters.component](setting,parameters,function(input){
												count--;
												item.forms.form[setting] = input;
												if(count != 0){ input.addClass('mb-2'); };
												if(parameters.component == 'select'){ input.field.select2({theme: "bootstrap-5"}); }
											}).appendTo(item.forms.form);
										} else { count--; }
									}
									layout.forms[original] = form;
								}).appendTo(item.forms);
								layout.sections[original] = item;
								if(callback != null){ callback(item); }
								return item;
							},
						};
						for(var [category, settings] of Object.entries(dataset)){
							layout.add.item(category,settings);
						}
						splitY.primary.save.click(function(){
							Engine.Toast.save(function(){
								var values = {}
								for(var [form, object] of Object.entries(layout.forms)){ values[form] = object.getValues(); }
								Engine.request('api','saveSettings',{data:values},function(){
									for(var [form, object] of Object.entries(layout.forms)){
										object.parents().eq(1).removeClass('bg-danger').addClass('bg-transparent').removeAttr('title data-bs-toggle data-bs-placement').tooltip('disable');
										object.parents().eq(1).find('a.link-light').removeClass('link-light').addClass('link-dark');
									}
								}).then(function(errors){
									for(var [form, error] of Object.entries(errors)){
										layout.forms[form].parents().eq(1).removeClass('bg-transparent').addClass('bg-danger').attr('title',error).attr('data-bs-toggle','tooltip').attr('data-bs-placement','top').tooltip('show');
										layout.forms[form].parents().eq(1).find('a.link-dark').removeClass('link-dark').addClass('link-light');
									}
								});
							});
						});
						splitY.main = layout;
						if(callback != null){ callback(splitY); }
						return splitY;
					});
				});
			},
      profile:function(options = {}, callback = null){
        if(options instanceof Function){ callback = options; options = {}; }
        var defaults = {};
        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
				var profile = {};
				Engine.request('api','getUserRelations').then(function(dataset){
					Engine.Builder.layouts.details.render({icon:'fas fa-user'},function(layout){
						layout.title = 'Profile';
						layout.details.card.body.header.name.html(Engine.Storage.get('user','name'));
						layout.details.card.add.item(function(item){
							item.name = $(document.createElement('b')).html(Engine.Translate('Email')).appendTo(item);
							item.value = $(document.createElement('a')).css('float','right').addClass('text-decoration-none cursor-pointer').html(Engine.Storage.get('user','username')).appendTo(item);
							item.value.click(function(){
								Engine.Helper.copyToClipboard(Engine.Storage.get('user','username'));
							});
							layout.details.card.username = item;
						});
						layout.details.card.add.item(function(item){
							item.name = $(document.createElement('b')).html(Engine.Translate('Registered')).appendTo(item);
							item.value = $(document.createElement('a')).css('float','right').addClass('text-decoration-none cursor-pointer').appendTo(item);
							item.value.time = $(document.createElement('time')).attr('data-bs-toggle','tooltip').attr('data-bs-placement','right').attr('title',Engine.Storage.get('user','dateRegistered')).tooltip().attr('datetime',Engine.Storage.get('user','dateRegistered')).appendTo(item.value);
							item.value.click(function(){
								Engine.Helper.copyToClipboard(Engine.Storage.get('user','dateRegistered'));
							});
						});
						switch(Engine.Storage.get('user','status')){
							case 0: var status = {color:'info',icon:'fas fa-user-plus',name:Engine.Translate('New')};break;
							case 1: var status = {color:'success',icon:'fas fa-user-check',name:Engine.Translate('Active')};break;
							case 2: var status = {color:'danger',icon:'fas fa-user-slash',name:Engine.Translate('Disabled')};break;
							case 3: var status = {color:'danger',icon:'fas fa-exclamation-triangle',name:Engine.Translate('Suspicious')};break;
						}
						layout.details.card.footer.addClass('p-0');
						layout.details.card.footer.status = $(document.createElement('button')).addClass('btn btn-'+status.color+' cursor-default rounded-0 rounded-bottom w-100').appendTo(layout.details.card.footer);
						layout.details.card.footer.status.icon = $(document.createElement('i')).addClass(status.icon+' me-1').appendTo(layout.details.card.footer.status);
						layout.details.card.footer.status.append(Engine.Translate(status.name));
						for(var [tab, object] of Object.entries(Engine.Builder.layouts.details.tabs)){ object(dataset, layout); }
						layout.main.card.add.tab('Settings',{icon:'fas fa-cog'},function(nav,tab){
							Engine.Builder.components.form({header:'Settings',name:'ProfileSettings'},function(form){
								Engine.Builder.forms.input('name',{icon:'fas fa-signature'},function(input){
									input.field.val(Engine.Storage.get('user','name'));
									form.name = input;
								}).addClass('mb-2').appendTo(form);
								Engine.Builder.forms.select('language',Engine.Storage.get('language',['list']),{icon:'fas fa-atlas',translate:false},function(input){
									input.field.val(Engine.Storage.get('user','language')).select2({theme: "bootstrap-5"});
									form.language = input;
								}).addClass('my-2').appendTo(form);
								Engine.Builder.forms.input('username',{icon:'fas fa-at',type:'email',label:'Email'},function(input){
									input.field.val(Engine.Storage.get('user','username'));
									form.username = input;
								}).addClass('my-2').appendTo(form);
								Engine.Builder.forms.input('password',{icon:'fas fa-user-lock',type:'password'},function(input){
									input.field.val('');
									input.field2 = $(document.createElement('input')).addClass("form-control").attr('id',input.id+'field2').attr('name','confirm').attr('type','password').attr('placeholder',Engine.Translate('Confirm')).val('').appendTo(input);
									form.password = input;
								}).addClass('my-2').appendTo(form);
								form.submit = $(document.createElement('button')).addClass("btn btn-success w-100").appendTo(form);
								form.submit.icon = $(document.createElement('i')).addClass('fas fa-save me-1').appendTo(form.submit);
								form.submit.append(Engine.Translate('Save'));
								form.submit.click(function(){
									Engine.Toast.save(function(){
										var values = form.getValues();
										$.post('./hash.php',{string:values.password},function(hash){
											if(!Engine.Form.validate.email(values.username)){ delete values.username; }
											if(values.password != values.confirm || !Engine.Form.validate.password(values.password)){ delete values.password; }
											else { values.password = hash; }
											values.id = Engine.Storage.get('user','id');
											delete values.confirm;
											Engine.request('crud','update',{data:{users:values}}).then(function(dataset){
												if(dataset.users[values.id].username == Engine.Storage.get('user','username')){
													Engine.Storage.set('user',dataset.users[values.id]);
													layout.details.card.body.header.name.html(Engine.Storage.get('user','name'));
													layout.details.card.username.value.html(Engine.Storage.get('user','username'));
													form.password.find('input').val('');
												} else { Engine.Auth.logout(); }
								      });
										}, "json" );
									});
								});
								tab.form = form;
							}).appendTo(tab);
							layout.settings = {nav:nav,tab:tab};
						});
						profile = layout;
					});
	        if(callback != null){ callback(profile); }
	        return profile;
				});
      },
			listing:{
				render:function(dataset, options = {}, callback = null){
					var defaults = {
						striped: true,
						hover: true,
						bordered: false,
						borderless: false,
						compact: false,
						translate: true,
						ucfirst: true,
						clipboard: true,
						dblclick: null,
						disableCard: false,
						click: null,
						title: null,
						headers: [],
						titleListing:"Listing",
						titleDetails:"Details",
					};
					if(dataset instanceof Function){ callback = dataset; dataset = null; }
					if(dataset instanceof Object){
						for(var [option, value] of Object.entries(dataset)){
							if(Engine.Helper.isSet(defaults,[option])){ options = dataset; dataset = null;break; }
						}
					}
					if(dataset == null){ dataset = {}; }
					if(options instanceof Function){ callback = options; options = {}; }
					for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
					Engine.Builder.count++;
					var layout = $(document.createElement('section')).addClass('m-0 p-3 noselect').attr('id','listing'+Engine.Builder.count);
					layout.id = layout.attr('id');
					layout.title = defaults.titleListing;
					Engine.Builder.components.table.render(dataset,defaults,function(table){
						layout.table = table.appendTo(layout);
					});
					if(callback != null){ callback(layout); }
					return layout;
				}
			},
			details:{
				tabs:{
					timeline:function(dataset, layout, options = {}, callback = null){
		        if(options instanceof Function){ callback = options; options = {}; }
						var defaults = {};
		        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
						layout.timeline = {};
						layout.main.card.add.tab('Timeline',{icon:'fas fa-stream'},function(nav,tab){
							nav.link.addClass('active');
							tab.addClass('active');
							Engine.Builder.components.timeline.render(function(timeline){
								timeline.add.item('register',{of:Engine.Storage.get('user')},Engine.Storage.get('user','dateRegistered'));
								for(var [table, relations] of Object.entries(dataset.of)){
									for(var [id, relation] of Object.entries(relations)){
										timeline.add.item(table,{of:Engine.Storage.get('user'),to:relation},relation.on);
									}
								}
								for(var [table, relations] of Object.entries(dataset.to)){
									for(var [id, relation] of Object.entries(relations)){
										timeline.add.item(table,{of:Engine.Storage.get('user'),to:relation},relation.on);
									}
								}
								tab.timeline = timeline.appendTo(tab);
							});
							layout.timeline = {nav:nav,tab:tab};
						});
		        if(callback != null){ callback(layout.timeline); }
		        return layout.timeline;
					},
				},
				render:function(options = {}, callback = null){
	        if(options instanceof Function){ callback = options; options = {}; }
	        var defaults = {
						positionMain: 'end',
						icon:'far fa-circle',
						titleDetails:"Details",
					};
	        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
	        Engine.Builder.count++;
	        var layout = $(document.createElement('section')).addClass('row m-0 p-0 noselect').attr('id','details'+Engine.Builder.count);
	        layout.id = layout.attr('id');
					layout.count = 0;
					layout.title = defaults.titleDetails;
					layout.details = $(document.createElement('div')).addClass('col-3 p-3 pe-2');
					layout.details.card = $(document.createElement('div')).addClass('card').appendTo(layout.details);
					layout.details.card.header = $(document.createElement('div')).addClass('card-header title').css('font-size','24px').css('line-height','40px').html(Engine.Translate("Details")).appendTo(layout.details.card);
					layout.details.card.body = $(document.createElement('ul')).addClass('list-group list-group-flush').appendTo(layout.details.card);
					layout.details.card.footer = $(document.createElement('div')).addClass('card-footer').appendTo(layout.details.card);
					layout.details.card.add = {
						item:function(options = {}, callback = null){
			        if(options instanceof Function){ callback = options; options = {}; }
			        var defaults = {};
			        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
			        layout.count++;
			        var item = $(document.createElement('li')).addClass('list-group-item').attr('id',layout.id+'item'+layout.count).appendTo(layout.details.card.body);
							item.id = item.attr('id');
							if(callback != null){ callback(item); }
			        return item;
						},
					};
					layout.details.card.add.item(function(item){
						item.addClass('text-center');
						item.icon = $(document.createElement('div')).addClass('opacity-50 py-3').appendTo(item);
						item.icon.stack = $(document.createElement('span')).addClass('fa-stack fa-3x').appendTo(item.icon);
						item.icon.stack.circle = $(document.createElement('i')).addClass('far fa-circle fa-stack-2x').appendTo(item.icon.stack);
						item.icon.stack.user = $(document.createElement('i')).addClass(defaults.icon+' fa-stack-1x').appendTo(item.icon.stack);
						item.name = $(document.createElement('div')).addClass('title').css('font-size','24px').appendTo(item);
						layout.details.card.body.header = item;
					});
					layout.main = $(document.createElement('div')).addClass('col-9 p-3 ps-2');
					layout.main.card = $(document.createElement('div')).addClass('card').appendTo(layout.main);
					layout.main.card.header = $(document.createElement('div')).addClass('card-header').appendTo(layout.main.card);
					layout.main.card.header.nav = $(document.createElement('ul')).attr('role','tablist').addClass('nav nav-pills noselect').appendTo(layout.main.card.header);
					layout.main.card.body = $(document.createElement('div')).addClass('card-body tab-content').appendTo(layout.main.card);
					layout.main.card.add = {
						tab:function(name, options = {}, callback = null){
			        if(options instanceof Function){ callback = options; options = {}; }
			        var defaults = {
			          translate: true,
								icon: null,
			        };
			        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
							if(defaults.translate){ name = Engine.Translate(name);}
							if(defaults.icon != null){ name = '<i class="'+defaults.icon+' me-1"></i>'+name; }
			        layout.count++;
			        var tab = $(document.createElement('div')).addClass('tab-pane').css('transition','.4s').attr('id',layout.id+'tab'+layout.count).attr('role','tabpanel').appendTo(layout.main.card.body);
							tab.id = tab.attr('id');
			        var nav = $(document.createElement('li')).addClass('nav-item').appendTo(layout.main.card.header.nav);
							nav.link = $(document.createElement('a')).addClass('nav-link cursor-pointer').attr('id',layout.id+'nav'+layout.count).attr('data-bs-toggle','pill').attr('data-bs-target','#'+tab.id).attr('role','tab').attr('aria-controls',+tab.id).html(name).appendTo(nav);
							nav.id = nav.link.attr('id');
							tab.attr('aria-labelledby',nav.id);
							if(callback != null){ callback(nav,tab); }
			        return {nav:nav,tab:tab};
						},
					};
					if(defaults.positionMain == 'end'){ layout.append(layout.details,layout.main); }
					else { layout.append(layout.main,layout.details); }
	        if(callback != null){ callback(layout); }
	        return layout;
	      },
			},
    },
    sections:{
			splitX:function(options = {}, callback = null){
        if(options instanceof Function){ callback = options; options = {}; }
        var defaults = {
          positionMain: 'end',
          disableShadow: false,
        };
        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
        Engine.Builder.count++;
        if(defaults.positionMain != 'end' && defaults.positionMain != 'start'){
          defaults.positionMain = 'end';
        }
        var section = $(document.createElement('section')).addClass("d-flex vh-100").attr('id','section'+Engine.Builder.count);
        section.id = section.attr('id');
        section.primary = $(document.createElement('div')).addClass('d-flex flex-column').css('z-index','100');
        section.secondary = $(document.createElement('main')).addClass('flex-grow-1 align-self-stretch').css('z-index','100');
        if(!defaults.disableShadow){
          section.secondary.css('box-shadow','inset 0 0.5em 1.5em rgb(0 0 0 / 10%), inset 0 0.125em 0.5em rgb(0 0 0 / 15%)');
        }
        if(defaults.positionMain == 'end'){
          section.append(section.primary,section.secondary);
        } else { section.append(section.secondary,section.primary); }
        if(callback != null){ callback(section); }
        return section;
      },
			splitY:function(options = {}, callback = null){
        if(options instanceof Function){ callback = options; options = {}; }
        var defaults = {
          positionMain: 'end',
          disableShadow: false,
        };
        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
        Engine.Builder.count++;
        if(defaults.positionMain != 'end' && defaults.positionMain != 'start'){
          defaults.positionMain = 'end';
        }
        var section = $(document.createElement('section')).addClass("d-flex flex-column flex-grow-1 align-self-stretch").attr('id','section'+Engine.Builder.count);
        section.id = section.attr('id');
        section.primary = $(document.createElement('div')).addClass('row m-0').css('z-index','100');
        section.secondary = $(document.createElement('main')).addClass('row m-0 flex-grow-1').css('z-index','100');
        if(!defaults.disableShadow){
          section.secondary.css('box-shadow','inset 0 0.5em 1.5em rgb(0 0 0 / 10%), inset 0 0.125em 0.5em rgb(0 0 0 / 15%)');
        }
        if(defaults.positionMain == 'end'){ section.append(section.primary,section.secondary); }
				else { section.append(section.secondary,section.primary); }
        if(callback != null){ callback(section); }
        return section;
      },
    },
    forms:{
      input:function(name, options = {}, callback = null){
        if(options instanceof Function){ callback = options; options = {}; }
        name = name.toLowerCase();
        var defaults = {
          icon: "fas fa-keyboard",
          type: "text",
          label: Engine.Helper.ucfirst(name),
					value: null,
        };
        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
        Engine.Builder.count++;
        var input = $(document.createElement('div')).attr('id','input'+Engine.Builder.count).addClass("input-group");
        input.id = input.attr('id');
        input.label = $(document.createElement('span')).addClass("input-group-text noselect").attr('for',input.id+'field').html('<i class="'+defaults.icon+' me-1"></i>'+Engine.Translate(defaults.label)).appendTo(input);
        input.field = $(document.createElement('input')).addClass("form-control").attr('id',input.id+'field').attr('name',name).attr('type',defaults.type).attr('placeholder',Engine.Translate(Engine.Helper.ucfirst(name))).appendTo(input);
        input.getValue = function(){ return input.field.val(); }
				if(defaults.value != null){ input.field.val(defaults.value); }
        if(callback != null){ callback(input); }
        return input;
      },
      checkbox:function(name, options = {}, callback = null){
        if(options instanceof Function){ callback = options; options = {}; }
        var defaults = {
          translate: true,
          label: Engine.Helper.ucfirst(name),
        };
        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
        name = name.toLowerCase();
				if(defaults.translate){ defaults.label = Engine.Translate(defaults.label);}
        Engine.Builder.count++;
        var input = $(document.createElement('div')).attr('id','checkbox'+Engine.Builder.count).addClass("form-check");
        input.id = input.attr('id');
        input.field = $(document.createElement('input')).addClass("form-check-input").attr('name',name).attr('id',input.id+'field').attr('type','checkbox').appendTo(input);
        input.label = $(document.createElement('label')).addClass("form-check-label noselect").attr('for',input.id+'field').html(defaults.label).appendTo(input);
        input.getValue = function(){
          if(input.field.is(':checked')){ return true; }
          else { return false; }
        }
        if(callback != null){ callback(input); }
        return input;
      },
      select:function(name,list, options = {}, callback = null){
				if(options instanceof Function){ callback = options; options = {}; }
				if(list instanceof Object && Engine.Helper.isSet(list,['list'])){ options = list; list = list.list; }
        var defaults = {
          icon: "fas fa-keyboard",
          translate: true,
          label: Engine.Helper.ucfirst(name),
					value: null,
        };
        name = name.toLowerCase();
        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
        Engine.Builder.count++;
        var input = $(document.createElement('div')).attr('id','select'+Engine.Builder.count).addClass("input-group");
        input.id = input.attr('id');
        input.label = $(document.createElement('span')).addClass("input-group-text noselect").attr('for',input.id+'field').html('<i class="'+defaults.icon+' me-1"></i>'+Engine.Translate(defaults.label)).appendTo(input);
        input.field = $(document.createElement('select')).addClass("form-select").attr('name',name).attr('id',input.id+'field').attr('placeholder',Engine.Translate(Engine.Helper.ucfirst(name))).appendTo(input);
        for(var [key, value] of Object.entries(list)){
          var option = $(document.createElement('option')).html(value).appendTo(input.field);
          if(list instanceof Array){ option.attr('value',value); } else { option.attr('value',key); }
          if(defaults.translate){ option.html(Engine.Translate(value)); }
          else { option.html(Engine.Helper.ucfirst(value)); }
        }
        input.getValue = function(){ return input.field.val(); }
				if(defaults.value != null){ input.field.val(defaults.value); }
        if(callback != null){ callback(input); }
        return input;
      },
    },
    components:{
			table:{
				cells:{
					// status:function(options = {}, callback = null){
					// 	if(options instanceof Function){ callback = options; options = {}; }
					// 	var defaults = {};
					// 	for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
					// 	Engine.Builder.count++;
					// 	var cell = $(document.createElement('section')).addClass('m-0 p-0 noselect').attr('id','cell'+Engine.Builder.count);
					// 	cell.id = cell.attr('id');
					// 	if(callback != null){ callback(cell); }
					// 	return cell;
					// },
				},
				controls:{
					new:{
						always:function(table, options = {}, callback = null){
							if(options instanceof Function){ callback = options; options = {}; }
							var defaults = {};
							for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
							// if(callback != null){ callback(cell); }
							// return cell;
						},
					},
					selectNone:{
						selected:function(table){
							var rows = table.tbody.find('tr'), allrows = table.tbody.find('tr');
							if(table.tbody.find('tr.searchHide').length > 0){ rows = table.tbody.find('tr').not('.searchHide'); }
							rows.filter(".selectedRow").removeClass('selectedRow');
							allrows.filter(".selectedRow").find('div.dropdown a').filter(".link-gray-700").removeClass('link-gray-700').addClass('link-light');
							allrows.not(".selectedRow").find('div.dropdown a').filter(".link-light").removeClass('link-light').addClass('link-gray-700');
							table.render();
						},
					},
					selectAll:{
						always:function(table){
							var rows = table.tbody.find('tr'), allrows = table.tbody.find('tr');
							if(table.tbody.find('tr.searchHide').length > 0){ rows = table.tbody.find('tr').not('.searchHide'); }
							rows.not(".selectedRow").addClass('selectedRow');
							allrows.filter(".selectedRow").find('div.dropdown a').filter(".link-gray-700").removeClass('link-gray-700').addClass('link-light');
							allrows.not(".selectedRow").find('div.dropdown a').filter(".link-light").removeClass('link-light').addClass('link-gray-700');
							table.render();
						},
					},
				},
				actions:{
					edit:function(row, options = {}, callback = null){
						if(options instanceof Function){ callback = options; options = {}; }
						var defaults = {};
						for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
						// if(callback != null){ callback(cell); }
						// return cell;
					},
					delete:function(row, options = {}, callback = null){
						if(options instanceof Function){ callback = options; options = {}; }
						var defaults = {};
						for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
						Engine.Toast.delete(function(){});
						// if(callback != null){ callback(cell); }
						// return cell;
					},
				},
				defaults:{
					striped: true,
					hover: true,
					bordered: false,
					borderless: false,
					compact: false,
					translate: true,
					ucfirst: true,
					clipboard: true,
					selectableRow: true,
					dblclick: null,
					disableCard: false,
					paginationOptions: [10,25,50,100,'all'],
					paginationCount: 10,
					showPagination: true,
					click: null,
					title: null,
					idSelector: 'id',
					headers: [],
					showCounts: true,
					controls: {new:'fas fa-plus',selectAll:'far fa-check-square',selectNone:'far fa-square'},
					actions: {edit:'fas fa-edit',delete:'fas fa-trash-alt'},
				},
				render:function(dataset = null, options = {}, callback = null){
					var defaults = {};
					for(var [option, value] of Object.entries(Engine.Builder.components.table.defaults)){ defaults[option] = value; }
					if(dataset instanceof Function){ callback = dataset; dataset = null; }
					if(dataset instanceof Object){
						for(var [option, value] of Object.entries(dataset)){
							if(Engine.Helper.isSet(defaults,[option])){ options = dataset; dataset = null;break; }
						}
					}
					if(dataset == null){ dataset = {}; }
					if(options instanceof Function){ callback = options; options = {}; }
					for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
					Engine.Builder.count++;
					var tableClass = [], classes = '';
					if(defaults.striped){ tableClass.push('table-striped'); }
					if(defaults.hover){ tableClass.push('table-hover'); }
					if(defaults.bordered){ tableClass.push('table-bordered'); }
					if(defaults.borderless){ tableClass.push('table-borderless'); }
					if(defaults.compact){ tableClass.push('table-sm'); }
					for(var [key, cssClass] of Object.entries(tableClass)){
						if(classes != ''){ classes += ' '; }
						classes += cssClass;
					}
					if(defaults.disableCard){
						var table = $(document.createElement('div')).addClass('table-responsive').attr('id','table'+Engine.Builder.count);
						table.table = $(document.createElement('table')).addClass('table m-0').addClass(classes).appendTo(table);
					} else {
						var table = $(document.createElement('div')).addClass('card').attr('id','table'+Engine.Builder.count);
						table.header = $(document.createElement('div')).addClass('card-header').css('font-size','20px').css('line-height','40px').css('font-weight','200').appendTo(table);
						table.collapsable = $(document.createElement('div')).addClass('collapse show').appendTo(table);
						table.body = $(document.createElement('div')).addClass('card-body p-0 table-responsive').appendTo(table.collapsable);
						table.footer = $(document.createElement('div')).addClass('card-footer d-flex').css('line-height','31px').appendTo(table.collapsable);
						if(Object.keys(defaults.controls).length > 0){
							Engine.Builder.components.dropdown(function(dropdown){
								dropdown.link.addClass('link-gray-700').removeClass('dropdown-toggle').html('<i class="fas fa-ellipsis-v"></i>');
								for(var [control, icon] of Object.entries(defaults.controls)){
									if(Engine.Helper.isSet(Engine,['Builder','components','table','controls',control,'always'])){
										dropdown.nav.add.item(Engine.Translate(Engine.Helper.ucfirst(control)),{icon:icon},function(item){
											item.link.click(function(){
												Engine.Builder.components.table.controls[$(this).parent().attr('data-controls')].always(table);
											});
										}).attr('data-controls',control);
									}
									if(Engine.Helper.isSet(Engine,['Builder','components','table','controls',control,'selected'])){
										dropdown.nav.add.item(Engine.Translate(Engine.Helper.ucfirst(control)),{icon:icon},function(item){
											item.link.click(function(){
												Engine.Builder.components.table.controls[$(this).parent().attr('data-controls')].selected(table);
											});
										}).attr('data-controls',control).css('display', 'none').addClass('onlySelected');
									}
								}
								table.controls = dropdown;
							}).addClass('d-inline float-end ps-2').css('font-size','18px').appendTo(table.header);
						}
						table.collapse = $(document.createElement('a')).addClass('d-inline float-end link-gray-700 px-2').html('<i class="fas fa-chevron-up"></i>').appendTo(table.header);
						table.collapse.click(function(){
							if($(this).find('i').hasClass('fa-chevron-up')){
								table.collapsable.collapse('hide');
								$(this).find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
							} else {
								table.collapsable.collapse('show');
								$(this).find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
							}
						});
						if(defaults.title != null){
							if(defaults.ucfirst){ defaults.title = Engine.Helper.ucfirst(defaults.title); }
							if(defaults.translate){ defaults.title = Engine.Translate(defaults.title); }
							table.header.prepend(defaults.title);
						}
						table.rowCount = $(document.createElement('div')).css('font-weight','200').appendTo(table.footer);
						table.selectedCount = $(document.createElement('div')).addClass('ms-2').css('font-weight','200').appendTo(table.footer);
						table.pagination = $(document.createElement('div')).addClass('ms-auto').appendTo(table.footer);
						table.table = $(document.createElement('table')).addClass('table m-0').addClass(classes).appendTo(table.body);
					}
					table.id = table.attr('id');
					table.count = 0;
					table.headers = [];
					table.rows = 0;
					table.render = function(current = null, page = null){
						if(current != null){ current = parseInt(current); }
						if(page != null){ page = parseInt(page); }
						if(Engine.Helper.isSet(table,['footer'])){
							if(defaults.showCounts){
								var selectedCount = table.tbody.find('tr.selectedRow').length;
								if(selectedCount > 0){ selectedCount += ' '+Engine.Translate('Selected'); }
								else { selectedCount = ''; }
								table.selectedCount.html(selectedCount);
								if(table.tbody.find('tr.searchHide').length > 0){
									var rowCount = Engine.Translate('Filtered')+' '+table.tbody.find('tr').not('.searchHide').length+' '+Engine.Translate('results')+' '+Engine.Translate('of')+' '+table.tbody.find('tr').length+' '+Engine.Translate('entries');
								} else {
									var rowCount = Engine.Translate('Total')+' '+table.tbody.find('tr').length+' '+Engine.Translate('entries');
								}
								table.rowCount.html(rowCount);
							}
							if(defaults.showPagination){
								var rows = table.tbody.find('tr');
								if(table.tbody.find('tr.searchHide').length > 0){ rows = table.tbody.find('tr').not('.searchHide'); }
								table.pagination.counts = {
									pages: Math.ceil(rows.length / defaults.paginationCount),
									first: parseInt(rows.first().attr('data-rowID')),
									last: parseInt(rows.last().attr('data-rowID')),
								};
								table.tbody.find('tr').attr('data-page','');
								if(rows.length >= defaults.paginationCount){
									var pageCount = 1, rowCount = 0;
									rows.each(function(){
										if(page == null){
											if(current == null){ current = parseInt($(this).attr('data-rowID')); }
											if($(this).attr('data-rowID') == current){ page = parseInt(pageCount); }
										} else if(current == null){ current = parseInt(rows.first().attr('data-rowID')); }
										$(this).attr('data-page',pageCount);
										if(rowCount < (defaults.paginationCount - 1)){ rowCount++; }
										else { rowCount = 0; pageCount++; }
									});
									rows.filter('[data-page]').removeClass('paginatedShow').addClass('paginatedHide');
									rows.filter('[data-page="'+page+'"]').removeClass('paginatedHide').addClass('paginatedShow');
									current = parseInt(rows.filter('.paginatedShow').first().attr('data-rowID'));
									table.pagination.counts.next = page + 1;
									table.pagination.counts.previous = page - 1;
									table.pagination.group = $(document.createElement('div')).addClass('btn-group');
									if(table.pagination.counts.previous != 0 && page != 1){
										table.pagination.group.first = $(document.createElement('button')).addClass('btn btn-sm btn-light border').html('<i class="fas fa-angle-double-left"></i>').appendTo(table.pagination.group).click(function(){
											table.render(null,1);
										});
										table.pagination.group.previous = $(document.createElement('button')).addClass('btn btn-sm btn-light border').html('<i class="fas fa-angle-left"></i>').appendTo(table.pagination.group).click(function(){
											table.render(null,table.pagination.counts.previous);
										});
									}
									var paginationCount = 0;
									var paginationMaxCount = 5;
									for(var pageCount=1;pageCount <= table.pagination.counts.pages;pageCount++){
										if(pageCount - page <= 2 && page - pageCount <= 2){
											paginationCount++;
											if(paginationCount == 1 && pageCount != 1){
												$(document.createElement('span')).addClass('btn btn-sm btn-light cursor-default border').html('...').appendTo(table.pagination.group);
											}
											var color = 'btn-light';
											if(pageCount == page){ color = 'btn-primary'; }
											$(document.createElement('button')).addClass('btn btn-sm border').addClass(color).attr('data-page',pageCount).html(pageCount).appendTo(table.pagination.group).click(function(){
												table.render(null,$(this).attr('data-page'));
											});
											if(paginationCount == 1 && pageCount == page){ paginationMaxCount = 3; }
											if(paginationCount == paginationMaxCount && pageCount != table.pagination.counts.pages){
												$(document.createElement('span')).addClass('btn btn-sm btn-light cursor-default border').html('...').appendTo(table.pagination.group);
											}
										}
									}
									if(table.pagination.counts.next != 0 && page != table.pagination.counts.pages){
										table.pagination.group.next = $(document.createElement('button')).addClass('btn btn-sm btn-light border').html('<i class="fas fa-angle-right"></i>').appendTo(table.pagination.group).click(function(){
											table.render(null,table.pagination.counts.next);
										});
										table.pagination.group.last = $(document.createElement('button')).addClass('btn btn-sm btn-light border').html('<i class="fas fa-angle-double-right"></i>').appendTo(table.pagination.group).click(function(){
											table.render(null,table.pagination.counts.pages);
										});
									}
									table.pagination.html(table.pagination.group);
								} else {
									rows.removeClass('paginatedHide').addClass('paginatedShow');
									table.pagination.html('');
								}
							}
						}
						if(Object.keys(defaults.controls).length > 0 && Engine.Helper.isSet(table,['header'])){
							if(table.tbody.find('tr.selectedRow').length > 0){
								table.controls.find('li.onlySelected').css('display','');
							} else {
								table.controls.find('li.onlySelected').css('display','none');
							}
						}
					},
					table.thead = $(document.createElement('thead')).appendTo(table.table);
					table.thead.tr = $(document.createElement('tr')).appendTo(table.thead);
					table.tbody = $(document.createElement('tbody')).appendTo(table.table);
					table.data = {
						selected:function(){
							var rows = {}
							table.tbody.find('tr.selectedRow').each(function(){
								rows[$(this).attr('data-rowID')] = Engine.Helper.json.decode($(this).attr('data-rowData'));
							});
							return rows;
						},
						all:function(){
							var rows = {}
							table.tbody.find('tr').each(function(){
								rows[$(this).attr('data-rowID')] = Engine.Helper.json.decode($(this).attr('data-rowData'));
							});
							return rows;
						},
					};
					table.add = {
						header:function(name, callback = null){
							table.count++;
							table.headers.push(name);
							var key = name;
							if(defaults.ucfirst){ name = Engine.Helper.ucfirst(name); }
							if(defaults.translate){ name = Engine.Translate(name); }
							var header = $(document.createElement('th')).attr('data-key',key).attr('id',table.id+'header'+table.count).html(name).appendTo(table.thead.tr);
							header.id = header.attr('id');
							if(callback != null){ callback(header); }
							return header;
						},
						row:function(record = {}, callback = null){
							if(record instanceof Function){ callback = record; record = {}; }
							table.count++;
							table.rows++;
							var rowID = table.rows;
							if(Engine.Helper.isSet(record,[defaults.idSelector])){ rowID = record[defaults.idSelector]; }
							var row = $(document.createElement('tr')).attr('data-rowID',rowID).attr('id',table.id+'row'+table.count).attr('data-rowData',Engine.Helper.json.encode({})).attr('data-search',Engine.Helper.json.encode({})).appendTo(table.tbody);
							row.id = row.attr('id');
							row.count = 0;
							if(defaults.showPagination){ row.addClass('paginatedShow'); }
							row.data = function(rowData = null){
								if(rowData == null){ return Engine.Helper.json.decode(row.attr('data-rowData')); }
								else { row.attr('data-rowData',Engine.Helper.json.encode(rowData)); }
							}
							row.search = function(){
								row.attr('data-search',Engine.Helper.toCSV(row.data()));
								row.attr('data-search',row.attr('data-search').toString().toUpperCase());
							}
							row.add = {
								cell:function(key, value, optionsCell = {}, callback = null){
									if(optionsCell instanceof Function){ callback = optionsCell; optionsCell = {}; }
									var defaultsCell = {
										isSelectable:true,
									};
									for(var [option, value] of Object.entries(optionsCell)){ if(Engine.Helper.isSet(defaultsCell,[option])){ defaultsCell[option] = value; } }
									row.count++;
									var rowData = row.data();
									rowData[key] = value;
									row.data(rowData);
									row.search();
									if(jQuery.inArray(key, table.headers) !== -1){ var display = ''; } else { var display = 'none'; }
									var cell = $(document.createElement('td')).css('display',display).attr('id',row.id+'cell'+row.count).attr('data-key',key).attr('data-value',value).html(value).appendTo(row);
									cell.id = cell.attr('id');
									if(defaults.clipboard || defaults.click instanceof Function){
										cell.mousedown(function(){ $(this).addClass('bg-primary'); });
										cell.mouseup(function(){ $(this).removeClass('bg-primary'); });
									}
									if(defaults.click instanceof Function){
										cell.click(function(){ defaults.click(cell); });
									}
									if(defaults.clipboard){
										cell.click(function(){ Engine.Helper.copyToClipboard($(this).data('value')); });
									}
									if(defaults.selectableRow && defaultsCell.isSelectable){
										cell.click(function(){
											if(cell.parent().hasClass('selectedRow')){
												cell.parent().removeClass('selectedRow');
												row.actions.link.removeClass('link-light').addClass('link-gray-700');
												table.render();
											} else {
												cell.parent().addClass('selectedRow');
												row.actions.link.removeClass('link-gray-700').addClass('link-light');
												table.render();
											}
										});
									}
									if(callback != null){ callback(cell); }
									return cell;
								},
							}
							if(defaults.dblclick instanceof Function){
								row.dblclick(function(){
									row.find('td').addClass('bg-primary');
									$.when(defaults.dblclick(row)).then(function(){
										row.find('td').removeClass('bg-primary');
									});
								});
							}
							if(Object.keys(record).length > 0){
								for(var [key, value] of Object.entries(record)){ row.add.cell(key,value); }
								if(Object.keys(defaults.actions).length > 0){
									row.add.cell('','',{isSelectable:false},function(cell){
										Engine.Builder.components.dropdown(function(dropdown){
											dropdown.link.attr('data-bs-boundary','window').addClass('link-gray-700').removeClass('dropdown-toggle').html('<i class="fas fa-ellipsis-v px-2"></i>');
											for(var [action, icon] of Object.entries(defaults.actions)){
												if(Engine.Helper.isSet(Engine,['Builder','components','table','actions',action])){
													dropdown.nav.add.item(Engine.Translate(Engine.Helper.ucfirst(action)),{icon:icon},function(item){
														item.link.attr('data-action',action).click(function(){
															Engine.Builder.components.table.actions[$(this).attr('data-action')](row);
														});
													});
												}
											}
											row.actions = dropdown;
										}).css('font-size','18px').appendTo(cell);
									});
								}
							}
							table.render();
							if(callback != null){ callback(row); }
							return row;
						},
					};
					if(defaults.headers.length > 0){
						for(var [key, header] of Object.entries(defaults.headers)){ table.add.header(header); }
						if(Object.keys(defaults.controls).length > 0){ table.add.header('',function(header){ header.addClass('px-2'); }); }
					}
					if(typeof dataset !== 'undefined' && dataset != null && Object.keys(dataset).length > 0){
						if(table.headers.length <= 0){
							for(var [header, value] of Object.entries(dataset[0])){ table.add.header(header); }
							if(Object.keys(defaults.controls).length > 0){ table.add.header('',function(header){ header.addClass('px-2'); }); }
						}
						for(var [key, record] of Object.entries(dataset)){ table.add.row(record,function(row){}); }
					}
					if(Engine.Helper.isSet(Engine,['Layout','navbar','search'])){
						Engine.Layout.navbar.search.on("input", function(){
							table.tbody.find('[data-search]').filter('.searchHide').removeClass('searchHide');
							if($(this).val().toUpperCase() != ''){
								table.tbody.find('[data-search]').not('[data-search*="'+$(this).val().toUpperCase()+'"]').addClass('searchHide');
							} else {
								table.tbody.find('[data-search]').removeClass('searchHide');
							}
							table.render();
						});
						if(Engine.Layout.navbar.search.val() != ''){
							table.tbody.find('[data-search]').filter('.searchHide').removeClass('searchHide');
							table.tbody.find('[data-search]').not('[data-search*="'+Engine.Layout.navbar.search.val().toUpperCase()+'"]').addClass('searchHide');
							table.render();
						}
					}
					if(callback != null){ callback(table); }
					return table;
				},
			},
			timeline:{
				items:{
					register:function(data, datetime, options = {}, callback = null){
						if(options instanceof Function){ callback = options; options = {}; }
						var date = new Date(datetime), defaults = {
							icon: 'fas fa-sign-in-alt',
							color: 'success',
						};
						for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
		        var item = $(document.createElement('div')).attr('data-type','register').attr('data-order',Date.parse(date));
						item.icon = $(document.createElement('i')).addClass(defaults.icon+' bg-'+defaults.color).appendTo(item);
						item.main = $(document.createElement('div')).addClass('timeline-item').appendTo(item);
						item.main.time = $(document.createElement('span')).addClass('time').attr('title',date.toLocaleString()).attr('data-bs-placement','top').appendTo(item.main);
						item.main.time.icon = $(document.createElement('i')).addClass('far fa-clock me-2').appendTo(item.main.time);
						item.main.time.timeago = $(document.createElement('time')).attr('datetime',date.toLocaleString()).appendTo(item.main.time);
						item.main.content = $(document.createElement('h3')).addClass('timeline-header border-0').appendTo(item.main);
						if(Engine.Helper.isSet(data,['of','username'])){
							item.main.content.of = $(document.createElement('a')).addClass('text-decoration-none cursor-default me-1').html(data.of.username).appendTo(item.main.content);
						}
						item.main.content.append(Engine.Translate("has registered"));
						if(callback != null){ callback(item); }
						return item;
					},
					groups:function(data, datetime, options = {}, callback = null){
						if(options instanceof Function){ callback = options; options = {}; }
						var date = new Date(datetime), defaults = {
							icon: 'fas fa-users',
							color: 'info',
						};
						for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
		        var item = $(document.createElement('div')).attr('data-type','groups').attr('data-order',Date.parse(date));
						item.icon = $(document.createElement('i')).addClass(defaults.icon+' bg-'+defaults.color).appendTo(item);
						item.main = $(document.createElement('div')).addClass('timeline-item').appendTo(item);
						item.main.time = $(document.createElement('span')).addClass('time').attr('title',date.toLocaleString()).attr('data-bs-placement','top').appendTo(item.main);
						item.main.time.icon = $(document.createElement('i')).addClass('far fa-clock me-2').appendTo(item.main.time);
						item.main.time.timeago = $(document.createElement('time')).attr('datetime',date.toLocaleString()).appendTo(item.main.time);
						item.main.content = $(document.createElement('h3')).addClass('timeline-header border-0').appendTo(item.main);
						if(Engine.Helper.isSet(data,['of','username'])){
							item.main.content.of = $(document.createElement('a')).addClass('text-decoration-none cursor-default me-1').html(data.of.username).appendTo(item.main.content);
						} else if(Engine.Helper.isSet(data,['of','name'])){
							item.main.content.of = $(document.createElement('a')).addClass('text-decoration-none cursor-default me-1').html(data.of.name).appendTo(item.main.content);
						}
						item.main.content.append(Engine.Translate("is a member of"));
						if(Engine.Helper.isSet(data,['to','name'])){
							item.main.content.to = $(document.createElement('a')).addClass('text-decoration-none cursor-default ms-1').html(data.to.name).appendTo(item.main.content);
						}
						if(callback != null){ callback(item); }
						return item;
					},
					status:function(data, datetime, options = {}, callback = null){
						if(options instanceof Function){ callback = options; options = {}; }
						var date = new Date(datetime), defaults = {
							icon: 'fas fa-info',
							color: 'info',
						};
						for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
		        var item = $(document.createElement('div')).attr('data-type','status').attr('data-order',Date.parse(date));
						item.icon = $(document.createElement('i')).addClass(defaults.icon+' bg-'+defaults.color).appendTo(item);
						item.main = $(document.createElement('div')).addClass('timeline-item').appendTo(item);
						item.main.time = $(document.createElement('span')).addClass('time').attr('title',date.toLocaleString()).attr('data-bs-placement','top').appendTo(item.main);
						item.main.time.icon = $(document.createElement('i')).addClass('far fa-clock me-2').appendTo(item.main.time);
						item.main.time.timeago = $(document.createElement('time')).attr('datetime',date.toLocaleString()).appendTo(item.main.time);
						item.main.content = $(document.createElement('h3')).addClass('timeline-header border-0').appendTo(item.main);
						if(Engine.Helper.isSet(data,['to','name']) && Engine.Helper.isSet(data,['to','icon']) && Engine.Helper.isSet(data,['to','color'])){
							item.main.content.badge = $(document.createElement('span')).addClass('badge bg-'+data.to.color+' me-2').appendTo(item.main.content);
							item.main.content.badge.append(Engine.Translate(data.to.name));
							item.main.content.badge.icon = $(document.createElement('i')).addClass(data.to.icon+' ms-1').appendTo(item.main.content.badge);
						}
						if(Engine.Helper.isSet(data,['to','text'])){
							item.main.content.append(Engine.Translate(data.to.text));
						}
						if(callback != null){ callback(item); }
						return item;
					},
					alert:function(data, datetime, options = {}, callback = null){
						if(options instanceof Function){ callback = options; options = {}; }
						var date = new Date(datetime), defaults = {
							icon: 'fas fa-exclamation-triangle',
							color: 'warning',
						};
						for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
		        var item = $(document.createElement('div')).attr('data-type','alert').attr('data-order',Date.parse(date));
						item.icon = $(document.createElement('i')).addClass(defaults.icon+' bg-'+defaults.color).appendTo(item);
						item.main = $(document.createElement('div')).addClass('timeline-item').appendTo(item);
						item.main.time = $(document.createElement('span')).addClass('time').attr('title',date.toLocaleString()).attr('data-bs-placement','top').appendTo(item.main);
						item.main.time.icon = $(document.createElement('i')).addClass('far fa-clock me-2').appendTo(item.main.time);
						item.main.time.timeago = $(document.createElement('time')).attr('datetime',date.toLocaleString()).appendTo(item.main.time);
						item.main.content = $(document.createElement('h3')).addClass('timeline-header border-0').appendTo(item.main);
						if(Engine.Helper.isSet(data,['to','name']) && Engine.Helper.isSet(data,['to','icon']) && Engine.Helper.isSet(data,['to','color'])){
							item.main.content.badge = $(document.createElement('span')).addClass('badge bg-'+data.to.color+' me-2').appendTo(item.main.content);
							item.main.content.badge.append(Engine.Translate(data.to.name));
							item.main.content.badge.icon = $(document.createElement('i')).addClass(data.to.icon+' ms-1').appendTo(item.main.content.badge);
						}
						if(Engine.Helper.isSet(data,['to','text'])){
							item.main.content.append(Engine.Translate(data.to.text));
						}
						if(callback != null){ callback(item); }
						return item;
					},
					comments:function(data, datetime, options = {}, callback = null){
						if(options instanceof Function){ callback = options; options = {}; }
						var date = new Date(datetime), defaults = {
							icon: 'fas fa-comments',
							color: 'warning',
						};
						for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
		        var item = $(document.createElement('div')).attr('data-type','comments').attr('data-order',Date.parse(date));
						item.icon = $(document.createElement('i')).addClass(defaults.icon+' bg-'+defaults.color).appendTo(item);
						item.main = $(document.createElement('div')).addClass('timeline-item').appendTo(item);
						item.main.time = $(document.createElement('span')).addClass('time').attr('title',date.toLocaleString()).attr('data-bs-placement','top').appendTo(item.main);
						item.main.time.icon = $(document.createElement('i')).addClass('far fa-clock me-2').appendTo(item.main.time);
						item.main.time.timeago = $(document.createElement('time')).attr('datetime',date.toLocaleString()).appendTo(item.main.time);
						item.main.user = $(document.createElement('h3')).addClass('timeline-header').appendTo(item.main);
						if(Engine.Helper.isSet(data,['of','username'])){
							item.main.user.of = $(document.createElement('a')).addClass('text-decoration-none cursor-default me-1').html(data.of.username).appendTo(item.main.user);
						}
						item.main.user.append(Engine.Translate("commented"));
						item.main.comment = $(document.createElement('div')).addClass('timeline-body').html(data.to.comment).appendTo(item.main);
						item.main.footer = $(document.createElement('div')).addClass('timeline-footer').appendTo(item.main);
						item.main.footer.view = $(document.createElement('a')).addClass('btn btn-warning btn-sm text-decoration-none cursor-pointer mx-1').appendTo(item.main.footer);
						item.main.footer.view.append('<i class="fas fa-comment me-1"></i>');
						item.main.footer.view.append(Engine.Translate('View comment'));
						if(callback != null){ callback(item); }
						return item;
					},
					messages:function(data, datetime, options = {}, callback = null){
						if(options instanceof Function){ callback = options; options = {}; }
						var date = new Date(datetime), defaults = {
							icon: 'fas fa-envelope',
							color: 'primary',
						};
						for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
		        var item = $(document.createElement('div')).attr('data-type','messages').attr('data-order',Date.parse(date));
						item.icon = $(document.createElement('i')).addClass(defaults.icon+' bg-'+defaults.color).appendTo(item);
						item.main = $(document.createElement('div')).addClass('timeline-item').appendTo(item);
						item.main.time = $(document.createElement('span')).addClass('time').attr('title',date.toLocaleString()).attr('data-bs-placement','top').appendTo(item.main);
						item.main.time.icon = $(document.createElement('i')).addClass('far fa-clock me-2').appendTo(item.main.time);
						item.main.time.timeago = $(document.createElement('time')).attr('datetime',date.toLocaleString()).appendTo(item.main.time);
						item.main.user = $(document.createElement('h3')).addClass('timeline-header').appendTo(item.main);
						if(Engine.Helper.isSet(data,['of','username'])){
							item.main.user.of = $(document.createElement('a')).addClass('text-decoration-none cursor-default me-1').html(data.of.username).appendTo(item.main.user);
						}
						item.main.user.append(Engine.Translate("sent"));
						item.main.message = $(document.createElement('div')).addClass('timeline-body').html(data.to.body_unquoted).appendTo(item.main);
						item.main.footer = $(document.createElement('div')).addClass('timeline-footer').appendTo(item.main);
						item.main.footer.view = $(document.createElement('a')).addClass('btn btn-primary btn-sm text-decoration-none cursor-pointer mx-1').appendTo(item.main.footer);
						item.main.footer.view.append('<i class="fas fa-envelope-open-text me-1"></i>');
						item.main.footer.view.append(Engine.Translate('View message'));
						item.main.footer.delete = $(document.createElement('a')).addClass('btn btn-danger btn-sm text-decoration-none cursor-pointer mx-1').appendTo(item.main.footer);
						item.main.footer.delete.append('<i class="fas fa-trash-alt me-1"></i>');
						item.main.footer.delete.append(Engine.Translate('Delete'));
						if(callback != null){ callback(item); }
						return item;
					},
					// template:function(data, datetime, options = {}, callback = null){
					// 	if(options instanceof Function){ callback = options; options = {}; }
					// 	var date = new Date(datetime), defaults = {
					// 		icon: 'fas fa-info',
					// 		color: 'info',
					// 	};
					// 	for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
		      //   var item = $(document.createElement('div')).attr('data-type','other').attr('data-order',Date.parse(date));
					// 	item.icon = $(document.createElement('i')).addClass(defaults.icon+' bg-'+defaults.color).appendTo(item);
					// 	if(callback != null){ callback(item); }
					// 	return item;
					// },
				},
				render:function(options = {}, callback = null){
					if(options instanceof Function){ callback = options; options = {}; }
					var defaults = {};
					for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
	        Engine.Builder.count++;
	        var timeline = $(document.createElement('div')).addClass('timeline timeline-inverse').attr('id','timeline'+Engine.Builder.count);
	        timeline.id = timeline.attr('id');
					timeline.start = $(document.createElement('div')).attr('data-order',0000000000000).addClass('text-light').html('<i class="far fa-clock bg-gray"></i>').appendTo(timeline);
					timeline.add = {
						item:function(item, data, datetime, options = {}, callback = null){
							if(options instanceof Function){ callback = options; options = {}; }
							var date = new Date(datetime);
							if(Engine.Helper.isSet(Engine.Builder.components.timeline.items,[item]) && Engine.Auth.isAllowed('timeline'+Engine.Helper.ucfirst(item))){
								if(timeline.find('div.time-label[data-order="'+date.setHours(0,0,0,0)+'"]').length <= 0){
									timeline.add.time(date);
								}
								var timelineItem = Engine.Builder.components.timeline.items[item](data, datetime, options, callback).prependTo(timeline);
								timelineItem.attr('data-search',timelineItem.text().toString().toUpperCase());
								var items = timeline.children('div').detach().get();
								items.sort(function(a, b){
							    return new Date($(b).data('order')) - new Date($(a).data('order'));
							  });
								timeline.append(items);
								timeline.find('time').timeago();
								timeline.find('[data-bs-placement]').tooltip();
								if(Engine.Helper.isSet(Engine,['Layout','navbar','search'])){
									if(Engine.Layout.navbar.search.val().toUpperCase() != ''){
										timeline.find('[data-search]').hide();
										timeline.find('[data-search*="'+Engine.Layout.navbar.search.val().toUpperCase()+'"]').show();
									}
								}
							}
						},
						time:function(datetime, options = {}, callback = null){
							if(options instanceof Function){ callback = options; options = {}; }
							var defaults = {
								color: 'primary',
							};
							for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
							var date = new Date(datetime);
			        var item = $(document.createElement('div')).addClass('time-label').attr('data-order',date.setHours(0,0,0,0)).prependTo(timeline);
							item.time = $(document.createElement('span')).addClass('bg-'+defaults.color).html(date.toLocaleDateString('en-US',{day: 'numeric', month: 'long', year: 'numeric'})).attr('title',date.toLocaleString('en-US')).attr('data-bs-placement','right').appendTo(item);
							if(callback != null){ callback(item); }
							return item;
						},
					};
					if(Engine.Helper.isSet(Engine,['Layout','navbar','search'])){
						Engine.Layout.navbar.search.on("input", function(){
							if($(this).val().toUpperCase() != ''){
								timeline.find('[data-search]').hide();
								timeline.find('[data-search*="'+$(this).val().toUpperCase()+'"]').show();
							} else { timeline.find('[data-search]').show(); }
						});
					}
					if(callback != null){ callback(timeline); }
					return timeline;
				},
			},
			profile:function(options = {}, callback = null){
				if(options instanceof Function){ callback = options; options = {}; }
				var defaults = {};
				for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
				var profile = {}
				Engine.Builder.components.dropdown(function(dropdown){
					dropdown.addClass('profileMenu');
					dropdown.link.addClass('link-dark').removeClass('dropdown-toggle');
					dropdown.link.html('<i class="far fa-user-circle"></i>');
					dropdown.profile = dropdown.nav.add.item('Profile',{icon:'fas fa-user',enableActive:true},function(item){
						item.link.click(function(){
							Engine.Builder.layouts.profile(function(layout){
								Engine.Layout.load(layout);
							});
						});
					});
					if(Engine.Storage.get('permissions','isAdministrator')){
						dropdown.settings = dropdown.nav.add.item('Settings',{icon:'fas fa-cog',enableActive:true},function(item){
							item.link.click(function(){
								Engine.Builder.layouts.settings(function(layout){
									Engine.Layout.load(layout,{title:'Settings'});
								});
							});
						});
					}
					dropdown.nav.add.divider();
					dropdown.signout = dropdown.nav.add.item('Sign out',{icon:'fas fa-sign-out-alt'},function(item){
						item.link.click(function(){ Engine.Auth.logout(); });
					});
					dropdown.nav.find('a').click(function(){
						$('header a').removeClass('active');
						$('sidebar a').removeClass('active');
						$(this).addClass('active');
					});
					profile = dropdown;
				}).addClass('d-flex align-items-center justify-content-center');
				if(callback != null){ callback(profile); }
				return profile;
			},
      navbar:function(options = {}, callback = null){
        if(options instanceof Function){ callback = options; options = {}; }
        var defaults = {
          disableLogo: false,
					disableNav: false,
					disableSearch: false,
					disableProfile: false,
					disableNotifications: false,
        };
        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
        Engine.Builder.count++;
        var navbar = $(document.createElement('navbar')).addClass('border-bottom bg-light noselect').attr('id','navbar'+Engine.Builder.count);
        navbar.id = navbar.attr('id');
        navbar.container = $(document.createElement('div')).addClass('container-fluid px-0 d-flex d-grid align-items-center').appendTo(navbar);
				navbar.container.start = $(document.createElement('div')).addClass('d-flex align-items-justify').appendTo(navbar.container);
				if(!defaults.disableLogo){
					navbar.container.start.brand = $(document.createElement('a')).addClass('d-block text-center py-2 link-dark text-decoration-none').attr('href','./').appendTo(navbar.container.start);
					navbar.container.start.brand.logo = $(document.createElement('img')).css('max-height', '38px').css('max-width', '38px').attr('src','./dist/img/logo.png').appendTo(navbar.container.start.brand);
					if(!defaults.disableNav){ navbar.container.start.brand.addClass('me-3'); }
				}
				if(!defaults.disableNav){
					navbar.container.start.nav = $(document.createElement('ul')).addClass('nav nav-pills justify-content-center').appendTo(navbar.container.start);
					navbar.container.start.nav.count = 0;
					navbar.container.start.nav.add = {
						// dropdown:function(title, options = {}, callback = null){
						// 	if(options instanceof Function){ callback = options; options = {}; }
						// 	var defaults = {
		        //     icon: 'far fa-circle',
		        //     translate: true,
						// linkAction:function(item){
						// 	item.link.click(function(){
						// 		if(typeof Engine.Layout.navbar !== 'undefined'){
						// 			Engine.Layout.navbar.render();
						// 		}
						// 	});
						// },
		        //   };
						// 	for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
		        //   sidebar.nav.count++;
		        //   if(defaults.translate){ title = Engine.Translate(title); }
		        //   var nav = $(document.createElement('li')).addClass("nav-item").attr('id',sidebar.id+'nav'+sidebar.nav.count).appendTo(sidebar.nav);
		        //   nav.id = nav.attr('id');
						// 	Engine.Builder.components.dropdown(defaults,{action:function(item){
						// 		item.link.click(function(){
						// 			if(typeof Engine.Layout.navbar !== 'undefined'){
						// 				navbar.render();
						// 			}
						// 		});
						// 	}},function(dropdown){
						// 		dropdown.link.addClass('p-3 px-4').removeClass('dropdown-toggle').html('<i class="'+defaults.icon+'"></i>');
						// 		nav.dropdown = dropdown;
						// 	}).addClass('border-bottom py-3 dropend').css('z-index','150').appendTo(nav);
		        //   if(callback != null){ callback(nav); }
		        //   return nav;
						// },
						item:function(title, options = {}, callback = null){
		          if(options instanceof Function){ callback = options; options = {}; }
		          var defaults = {
		            icon: null,
		            translate: true,
								stick: true,
								disableRender: false,
								disableActive: false,
		          };
		          for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
		          navbar.container.start.nav.count++;
		          if(defaults.translate){ title = Engine.Translate(title); }
							if(title != ''){ var iconMargin = 'me-1'; } else { var iconMargin = ''; }
							if(defaults.icon == null){ icon = ''; } else { icon = '<i class="'+defaults.icon+' '+iconMargin+'"></i>'; }
		          var nav = $(document.createElement('li')).addClass("nav-item me-2").attr('id',navbar.id+'nav'+navbar.container.start.nav.count).appendTo(navbar.container.start.nav);
							if(defaults.stick){ nav.attr('data-stick',true); }
		          nav.id = nav.attr('id');
		          nav.link = $(document.createElement('a')).addClass('nav-link cursor-pointer').css('margin-bottom','7px').css('margin-top','7px').css('transition','.4s').attr('title',title).attr('data-bs-placement','bottom').html(icon+title).appendTo(nav).tooltip();
		          nav.link.click(function(){
								if(!defaults.disableActive){
			            navbar.container.start.nav.find('a').removeClass('active');
			            if(typeof navbar.container.profile !== 'undefined'){
			              navbar.container.profile.nav.find('a').removeClass('active');
			            }
			            nav.link.addClass('active');
								}
								if(!defaults.disableRender){ navbar.render(); }
		          });
		          if(callback != null){ callback(nav); }
		          return nav;
		        },
						group:function(options = {}, callback = null){
		          if(options instanceof Function){ callback = options; options = {}; }
		          var groupDefaults = {
		            icon: null,
		            translate: true,
								stick: true,
								disableRender: false,
								disableActive: false,
								outline: true,
								linkAction: null,
		          };
		          for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(groupDefaults,[option])){ groupDefaults[option] = value; } }
		          navbar.container.start.nav.count++;
		          var nav = $(document.createElement('li')).addClass("nav-item me-2").attr('id',navbar.id+'nav'+navbar.container.start.nav.count).appendTo(navbar.container.start.nav);
							if(groupDefaults.stick){ nav.attr('data-stick',true); }
		          nav.id = nav.attr('id');
		          nav.group = $(document.createElement('div')).addClass('nav-link btn-group p-0').css('transition','.4s').appendTo(nav);
		          nav.add = function(title, options = {}, callback = null){
								if(options instanceof Function){ callback = options; options = {}; }
								var defaults = {
			            icon: null,
			            translate: true,
									color: 'primary',
									disableRender: false,
									disableActive: false,
									outline: true,
									linkAction: null,
			          };
			          for(var [option, value] of Object.entries(groupDefaults)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
			          for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
			          navbar.container.start.nav.count++;
								if(defaults.translate){ title = Engine.Translate(title); }
								if(title != ''){ var iconMargin = 'me-1'; } else { var iconMargin = ''; }
								if(defaults.icon == null){ icon = ''; } else { icon = '<i class="'+defaults.icon+' '+iconMargin+'"></i>'; }
								if(defaults.outline){ var outline = 'outline-'; } else { var outline = ''; }
			          var item = $(document.createElement('a')).addClass('cursor-pointer btn').addClass('btn-'+outline+defaults.color).css('transition','.4s').attr('title',title).attr('data-bs-placement','bottom').html(icon+title).appendTo(nav.group).tooltip();
			          if(defaults.linkAction != null){ defaults.linkAction(item); }
			          if(callback != null){ callback(item); }
			          return item;
							};
		          if(callback != null){ callback(nav); }
		          return nav;
		        },
					};
				}
				navbar.container.end = $(document.createElement('div')).addClass('d-flex flex-grow-1 align-items-center').appendTo(navbar.container);
				if(!defaults.disableSearch){
					navbar.container.end.search = $(document.createElement('form')).addClass('w-100 my-2').appendTo(navbar.container.end);
					navbar.container.end.search.input = $(document.createElement('input')).addClass('form-control').attr('type','search').attr('aria-label',Engine.Translate('Search')).attr('placeholder',Engine.Translate('Search...')).attr('title',Engine.Translate('Search')).attr('data-bs-placement','bottom').appendTo(navbar.container.end.search).tooltip();
					navbar.search = navbar.container.end.search.input;
					navbar.container.end.search.on('submit', function(e){
				    e.preventDefault();
						if(navbar.search.val() != ''){
							if(Engine.Helper.isSet(Engine,['Layout','sidebar'])){ Engine.Layout.sidebar.nav.find('a').removeClass('active'); }
							if(Engine.Helper.isSet(Engine,['Layout','sidebar','profile'])){ Engine.Layout.sidebar.profile.nav.find('a').removeClass('active'); }
							Engine.Builder.layouts.search.render(function(layout){
								Engine.Layout.load(layout);
							});
						}
				  });
				}
        if(!defaults.disableNotifications){
					Engine.Builder.components.dropdown(function(dropdown){
						dropdown.addClass('notificationMenu');
						dropdown.nav.css('width', 'auto').css('min-width', '300px').css('max-height', '500px').css('overflow', 'auto');
						dropdown.link.addClass('link-dark').removeClass('dropdown-toggle');
						dropdown.icon = $(document.createElement('i')).addClass('far fa-bell').appendTo(dropdown.link);
						dropdown.badge = $(document.createElement('span')).addClass('position-absolute translate-middle badge rounded-pill bg-primary').appendTo(dropdown.icon);
						dropdown.badge.css('font-size','10px').css('font-family','"Helvetica Neue",Helvetica,Arial,sans-serif').css('padding-top','3px').css('padding-bottom','3px').css('padding-left','5px').css('padding-right','5px');
						dropdown.nav.add.item('',function(item){
							item.set = $(document.createElement('span')).addClass('me-1').html(0).appendTo(item.link);
							item.link.append(Engine.Translate('Notifications'));
							item.link.removeClass('cursor-pointer').addClass('cursor-default bg-white link-black text-black').off().click(function(e){e.stopPropagation();});
							dropdown.count = item;
						}).addClass('text-center');
						dropdown.delimiter = dropdown.nav.add.divider();
						dropdown.nav.add.divider();
						dropdown.nav.add.item('Mark all as read',function(item){
							item.link.click(function(e){
								e.stopPropagation();
								dropdown.nav.find('a.notification.bg-primary time').addClass('text-muted');
								dropdown.nav.find('a.notification.bg-primary').removeClass('bg-primary link-light');
								var notifications = [];
								dropdown.nav.find('a.notification').each(function(){
									notifications.push($(this).attr('data-id'));
								}).promise().done(function(){
									Engine.request('api','readNotification',{data:notifications,toast: false,pace: false}).then(function(){ Engine.init(); });
								});
								Engine.Notification.count();
							});
						}).addClass('text-center');
						navbar.notification = dropdown;
					}).addClass('d-flex align-items-center justify-content-center').addClass('flex-shrink-1 px-2').appendTo(navbar.container.end);
					if(defaults.disableSearch){ navbar.notification.addClass('ms-auto'); }
					else { navbar.notification.addClass('ms-2'); }
				}
        if(!defaults.disableProfile){
          var profile = Engine.Builder.components.profile(function(profile){
            navbar.profile = profile;
          }).addClass('flex-shrink-1 px-2').appendTo(navbar.container.end);
					if(defaults.disableSearch && defaults.disableNotifications){ navbar.profile.addClass('ms-auto'); }
					else { navbar.profile.addClass('ms-2'); }
        }
				navbar.render = function(){
					if(typeof navbar.container.start.nav !== 'undefined'){
						navbar.container.start.nav.find('li').not('[data-stick]').remove();
					}
				}
        if(callback != null){ callback(navbar); }
        return navbar;
      },
      sidebar:function(options = {}, callback = null){
        if(options instanceof Function){ callback = options; options = {}; }
        var defaults = {
          disableLogo: false,
					enableProfile: false,
        };
        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
        Engine.Builder.count++;
        var sidebar = $(document.createElement('sidebar')).attr('id','sidebar'+Engine.Builder.count).addClass('d-flex flex-column vh-100 bg-light').css('width','72px').css('overflow-x','hidden');
        sidebar.id = sidebar.attr('id');
				if(!defaults.disableLogo){
	        sidebar.brand = $(document.createElement('a')).addClass('d-block text-center py-2 link-dark text-decoration-none border-bottom').attr('href','./').appendTo(sidebar);
					sidebar.brand.logo = $(document.createElement('img')).css('max-height', '38px').css('max-width', '38px').attr('src','./dist/img/logo.png').appendTo(sidebar.brand);
				}
        sidebar.nav = $(document.createElement('ul')).addClass('nav nav-pills nav-flush flex-column mb-auto text-center').css('z-index','140').appendTo(sidebar);
        sidebar.nav.count = 0;
				sidebar.nav.add = {
					dropdown:function(title, options = {}, callback = null){
						if(options instanceof Function){ callback = options; options = {}; }
						var defaults = {
	            icon: 'far fa-circle',
	            translate: true,
							linkAction:function(nav){
								nav.link.click(function(){
			            sidebar.nav.find('a').removeClass('active');
									$('.profileMenu a').removeClass('active');
			            nav.link.addClass('active');
									if(typeof Engine.Layout.navbar !== 'undefined'){
										Engine.Layout.navbar.render();
									}
			          });
							},
	          };
						for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
	          sidebar.nav.count++;
	          if(defaults.translate){ title = Engine.Translate(title); }
	          var nav = $(document.createElement('li')).addClass("nav-item").attr('id',sidebar.id+'nav'+sidebar.nav.count).appendTo(sidebar.nav);
	          nav.id = nav.attr('id');
						Engine.Builder.components.dropdown(defaults,function(dropdown){
							dropdown.link.addClass('p-3 px-4').removeClass('dropdown-toggle').html('<i class="'+defaults.icon+'"></i>');
							nav.dropdown = dropdown;
						}).addClass('border-bottom py-3 dropend').css('z-index','150').appendTo(nav);
	          if(callback != null){ callback(nav); }
	          return nav;
					},
					item:function(title, options = {}, callback = null){
	          if(options instanceof Function){ callback = options; options = {}; }
	          var defaults = {
	            icon: 'far fa-circle',
	            translate: true,
	          };
	          for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
	          sidebar.nav.count++;
	          if(defaults.translate){ title = Engine.Translate(title); }
	          var nav = $(document.createElement('li')).addClass("nav-item").attr('id',sidebar.id+'nav'+sidebar.nav.count).css('z-index','150').appendTo(sidebar.nav);
	          nav.id = nav.attr('id');
	          nav.link = $(document.createElement('a')).addClass('nav-link cursor-pointer rounded-0 py-3 border-bottom').css('transition','.4s').attr('title',title).attr('data-bs-placement','right').html('<i class="'+defaults.icon+'"></i>').appendTo(nav).tooltip();
	          nav.link.click(function(){
	            sidebar.nav.find('a').removeClass('active');
							$('.profileMenu a').removeClass('active');
	            nav.link.addClass('active');
							if(typeof Engine.Layout.navbar !== 'undefined'){
								Engine.Layout.navbar.render();
							}
	          });
	          if(callback != null){ callback(nav); }
	          return nav;
	        },
				};
        if(defaults.enableProfile){
          Engine.Builder.components.profile(function(profile){
            sidebar.profile = profile;
          }).addClass('border-top p-3').appendTo(sidebar);
        }
        if(callback != null){ callback(sidebar); }
        return sidebar;
      },
      dropdown:function(options = {}, callback = null){
        if(options instanceof Function){ callback = options; options = {}; }
        var defaults = {
					linkAction: null,
				};
        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
        Engine.Builder.count++;
        var dropdown = $(document.createElement('div')).addClass('dropdown noselect').attr('id','dropdown'+Engine.Builder.count);
        dropdown.id = dropdown.attr('id');
				dropdown.linkAction = defaults.linkAction;
        dropdown.link = $(document.createElement('a')).addClass('cursor-pointer text-decoration-none dropdown-toggle').attr('id',dropdown.id+'link').attr('data-bs-toggle','dropdown').attr('data-bs-auto-close',true).attr('aria-expanded',false).appendTo(dropdown);
        dropdown.nav = $(document.createElement('ul')).addClass('dropdown-menu text-small shadow').css('line-height','24px').attr('aria-labelledby',dropdown.id+'link').appendTo(dropdown);
        dropdown.nav.count = 0;
				dropdown.nav.last = {};
        dropdown.nav.add = {
          item:function(title, options = {}, callback = null){
            if(options instanceof Function){ callback = options; options = {}; }
            var defaults = {
              translate: true,
              icon: null,
							enableActive: false,
							after: null,
							before: null,
							linkAction: dropdown.linkAction,
            };
            for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
            dropdown.nav.count++;
            if(defaults.translate){ title = Engine.Translate(title); }
            if(defaults.icon == null){ icon = ''; } else { icon = '<i class="'+defaults.icon+' me-1"></i>'; }
            var item = $(document.createElement('li')).attr('id',dropdown.id+'item'+dropdown.nav.count);
						if(defaults.after != null && defaults.after.length > 0){ item.insertAfter(defaults.after); }
						else if(defaults.before != null && defaults.before.length > 0){ item.insertBefore(defaults.before); }
						else { item.appendTo(dropdown.nav); }
            item.id = item.attr('id');
            item.link = $(document.createElement('a')).addClass('dropdown-item cursor-pointer').css('transition','.4s').attr('title',title).attr('data-bs-placement','right').html(icon+title).appendTo(item).tooltip();
						if(defaults.enableActive){
	            item.link.click(function(){
	              dropdown.nav.find('a').removeClass('active');
	              item.link.addClass('active');
	            });
						}
						dropdown.nav.last = item;
            if(defaults.linkAction != null){ defaults.linkAction(item); }
            if(callback != null){ callback(item); }
            return item;
          },
          divider:function(options = {}, callback = null){
            if(options instanceof Function){ callback = options; options = {}; }
            var defaults = {};
            for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
            dropdown.nav.count++;
            if(defaults.translate){ title = Engine.Translate(title); }
            var divider = $(document.createElement('li')).attr('id',dropdown.id+'divider'+dropdown.nav.count).appendTo(dropdown.nav);
            divider.id = divider.attr('id');
            divider.ruler = $(document.createElement('hr')).addClass('dropdown-divider').appendTo(divider);
						dropdown.nav.last = divider;
            if(callback != null){ callback(divider); }
            return divider;
          }
        };
				dropdown.addClass('data-bs-boundary','viewport').css('position','static');
        if(callback != null){ callback(dropdown); }
        return dropdown;
      },
      progress:function(maxValue = 0, options = {}, callback = null){
        if(maxValue instanceof Function){ callback = maxValue; maxValue = 0; }
        if(maxValue instanceof Object){ options = maxValue; maxValue = 0; }
        if(options instanceof Function){ callback = options; options = {}; }
        var defaults = {};
        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
        Engine.Builder.count++;
        var progress = $(document.createElement('div')).attr('id','progress'+Engine.Builder.count).addClass('progress');
        progress.id = progress.attr('id');
        progress.bar = $(document.createElement('div')).addClass('progress-bar progress-bar-striped progress-bar-animated').attr('role','progressbar').attr('aria-valuenow', 0).attr('aria-valuemin', 0).attr('aria-valuemax', 100).css("width", "0%").html('0%').appendTo(progress);
        progress.maxValue = maxValue;
        progress.currentValue = 0;
        progress.setProgress = function(value){
          if(value > progress.maxValue){ progress.currentValue = progress.maxValue; } else { progress.currentValue = value; }
          value = Math.ceil(progress.currentValue / progress.maxValue * 100);
          progress.bar.css('width',value+'%').html(value+'%');
        };
        if(callback != null){ callback(progress); }
        return progress;
      },
      terminal:function(content = '',options = {}, callback = null){
        if(content instanceof Function){ callback = content; content = ''; }
        if(content instanceof Object){ options = content; content = ''; }
        if(options instanceof Function){ callback = options; options = {}; }
        var defaults = {};
        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
        Engine.Builder.count++;
        var terminal = $(document.createElement('pre')).addClass('terminal noselect').attr('id','terminal'+Engine.Builder.count).html(content.replace(/\n/g, '<br>').replace(/\n/g, '<br>').replace(/ /g, '&nbsp;').replace(/\t/g, '&nbsp;&nbsp;&nbsp;&nbsp;'));
        terminal.id = terminal.attr('id');
        terminal.setContent = function(content){
          terminal.html(content.replace(/\n/g, '<br>').replace(/\n/g, '<br>').replace(/ /g, '&nbsp;').replace(/\t/g, '&nbsp;&nbsp;&nbsp;&nbsp;'));
        };
        if(callback != null){ callback(terminal); }
        return terminal;
      },
      text:function(content = '',options = {}, callback = null){
        if(content instanceof Function){ callback = content; content = ''; }
        if(content instanceof Object){ options = content; content = ''; }
        if(options instanceof Function){ callback = options; options = {}; }
        var defaults = {};
        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
        Engine.Builder.count++;
        var text = $(document.createElement('div')).attr('id','text'+Engine.Builder.count).html('<p>'+content.replace(/\n\n/g, '</p><p>')+'</p>');
        text.id = text.attr('id');
        if(callback != null){ callback(text); }
        return text;
      },
      form:function(options = {}, callback = null){
				Engine.Builder.count++;
        if(options instanceof Function){ callback = options; options = {}; }
        var defaults = {header:'',id:'form'+Engine.Builder.count,name:'form'+Engine.Builder.count,enableSubmit:false};
        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
        var form = $(document.createElement('form')).attr('id',defaults.id).attr('name',defaults.name);
        form.id = form.attr('id');
        form.card = $(document.createElement('div')).addClass('card g-2 px-0');
        if(defaults.header != ''){ form.card.header = $(document.createElement('div')).addClass('card-header').html(Engine.Translate(defaults.header)).appendTo(form.card); }
        form.card.list = $(document.createElement('ul')).addClass('list-group list-group-flush').appendTo(form.card);
        form.item = $(document.createElement('li')).addClass('list-group-item py-1');
        form.item.header = $(document.createElement('strong')).addClass('me-2').appendTo(form.item);
        form.item.value = $(document.createElement('span')).appendTo(form.item);
        form.getCard = function(callback = null){
          var card = form.card.clone();
          card.list = card.find('ul');
          for(var [key, value] of Object.entries(form.getValues())){
            card.list[key] = form.item.clone().appendTo(card.list);
            card.list[key].header = card.list[key].find('strong').html(Engine.Translate(Engine.Helper.ucfirst(key)));
            card.list[key].value = card.list[key].find('span').html(value);
          }
          if(callback != null){ callback(card); }
          return card;
        };
        form.getValues = function(callback = null){
          var values = {};
          form.find('select').each(function(){ values[$(this).attr('name')] = $(this).val(); });
          form.find('input').each(function(){
            switch($(this).attr('type')){
              case'checkbox':
                if($(this).is(':checked')){ values[$(this).attr('name')] = true; }
                else { values[$(this).attr('name')] = false; }
                break;
              default:
                values[$(this).attr('name')] = $(this).val();
                break;
            }
          });
          if(callback != null){ callback(values); }
          return values;
        };
				if(!defaults.enableSubmit){
					form.on('submit', function(e){ e.preventDefault(); });
				}
        if(callback != null){ callback(form); }
        return form;
      },
      stepper:function(options = {}, callback = null){
        if(options instanceof Function){ callback = options; options = {}; }
        var defaults = {
          header: "Wizard",
          color: "light",
          text: "",
        };
        for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
        Engine.Builder.count++;
        var stepper = $(document.createElement('div')).addClass("wizard card bg-"+defaults.color).attr('id','stepper'+Engine.Builder.count).attr('data-stepper',Engine.Builder.count);
        stepper.id = stepper.attr('id');
        stepper.count = 0;
        stepper.index = 0;
        stepper.header = $(document.createElement('div')).addClass("card-header noselect").appendTo(stepper);
        stepper.body = $(document.createElement('div')).addClass("card-body p-4").appendTo(stepper);
        stepper.footer = $(document.createElement('div')).addClass("card-footer").appendTo(stepper);
        $(document.createElement('h5')).html(Engine.Translate(defaults.header)).appendTo(stepper.header);
        $(document.createElement('h6')).addClass('card-subtitle mb-2 text-muted').html(defaults.text).appendTo(stepper.header);
        stepper.accordion = $(document.createElement('div')).addClass("accordion").attr('id',stepper.id).appendTo(stepper.body);
        stepper.steps = $(document.createElement('div')).addClass("stepper").appendTo(stepper.accordion);
        stepper.steps.progress = $(document.createElement('progress')).attr("value",0).attr("max",100).appendTo(stepper.steps);
        // Adding step
        stepper.add = function(title, options = {}, callback = null){
          if(options instanceof Function){ callback = options; options = {}; }
          var defaults = {
            icon: "fas fa-keyboard",
            content:'',
            showNext: true,
            colorNext: 'primary',
            textNext: 'Next',
            showBack: true,
            colorBack: 'light',
            textBack: 'Back',
            disableToggle: false,
            enableSpin: false,
          };
          for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
          stepper.count++;
          var step = $(document.createElement('div')).addClass("step-item").attr('data-step',stepper.count).appendTo(stepper.steps);
          step.id = stepper.count;
          step.button = $(document.createElement('button')).addClass("step-button text-center collapsed").attr('data-bs-toggle','collapse').attr('data-step',step.id).attr('data-bs-target','#'+stepper.id+'Step'+step.id).attr('aria-expanded',false).attr('aria-controls',stepper.id+'Step'+step.id).html('<i class="'+defaults.icon+'"></i>').appendTo(step);
          step.button.disable = function(){
            step.button.attr('data-bs-toggle','disabled').addClass('cursor-default');
          };
          step.button.enable = function(){
            step.button.attr('data-bs-toggle','collapse').removeClass('cursor-default');
          };
          step.title = $(document.createElement('div')).addClass("step-title mt-2 noselect").html(Engine.Translate(title)).appendTo(step);
          step.identifier = title.toLowerCase();
          step.content = $(document.createElement('div')).addClass("collapse").attr('id',stepper.id+'Step'+step.id).attr('data-bs-parent','#'+stepper.id).html(defaults.content).appendTo(stepper.accordion);
          step.next = $(document.createElement('button')).addClass("btn btn-"+defaults.colorNext+" float-end").attr('data-bs-toggle','collapse').attr('data-step',(step.id + 1)).attr('data-bs-target','#'+stepper.id+'Step'+(step.id + 1)).attr('aria-expanded',false).attr('aria-controls',stepper.id+'Step'+(step.id + 1)).html(Engine.Translate(defaults.textNext)+'<i class="fas fa-chevron-right ms-2"></i>');
          step.next.show = function(){
            step.triggers.next = step.next.clone().appendTo(stepper.footer);
            step.triggers.next.off().click(function(){ step.triggers.next.remove(); });
            step.content.on('hide.bs.collapse',function(event){ step.triggers.next.remove(); });
            return step.triggers.next;
          };
          step.back = $(document.createElement('button')).addClass("btn btn-"+defaults.colorBack).attr('data-bs-toggle','collapse').attr('data-step',(step.id - 1)).attr('data-bs-target','#'+stepper.id+'Step'+(step.id - 1)).attr('aria-expanded',false).attr('aria-controls',stepper.id+'Step'+(step.id - 1)).html('<i class="fas fa-chevron-left me-1"></i>'+Engine.Translate(defaults.textBack));
          step.back.show = function(){
            step.triggers.back = step.back.clone().appendTo(stepper.footer);
            step.triggers.back.off().click(function(){ step.triggers.back.remove(); });
            step.content.on('hide.bs.collapse',function(event){ step.triggers.back.remove(); });
            return step.triggers.back;
          };
          step.trigger = $(document.createElement('button')).addClass("btn float-end").attr('data-step',step.id);
          step.triggers = {};
          if(stepper.count <= 1){
            step.button.disable();
            step.content.addClass('show');
            stepper.setProgress(step.id);
          }
          if(defaults.showNext){
            if(step.id == stepper.index){ step.next.show(); }
            step.content.on('show.bs.collapse',function(event){ step.next.show(); });
          }
          if(defaults.showBack){
            if(step.id == stepper.index){ step.back.show(); }
            step.content.on('show.bs.collapse',function(event){ step.back.show(); });
          }
          step.addTrigger = function(options = {}, callback = null){
            if(options instanceof Function){ callback = options; options = {}; }
            var defaults = {
              icon: "fas fa-chevron-right",
              color:'light',
              text: 'Trigger',
            };
            for(var [option, value] of Object.entries(options)){ if(Engine.Helper.isSet(defaults,[option])){ defaults[option] = value; } }
            var trigger = step.trigger.clone().addClass('btn-'+defaults.color).html(Engine.Translate(defaults.text)+'<i class="'+defaults.icon+' ms-2"></i>');
            if(callback != null){ callback(trigger); }
            return trigger;
          };
          if(defaults.disableToggle){ step.button.disable(); }
          if(defaults.enableSpin){
            step.content.on('show.bs.collapse',function(event){ step.button.find('i').addClass('fa-spin'); });
            step.content.on('hide.bs.collapse',function(event){ step.button.find('i').removeClass('fa-spin'); });
          }
          step.content.on('show.bs.collapse',function(event){
            step.button.disable();
            stepper.setProgress(step.id);
          });
          step.content.on('hide.bs.collapse',function(event){
            step.button.enable();
          });
          if(callback != null){ callback(step); }
          return step;
        }
        stepper.setProgress = function(index){
          stepper.index = index;
          stepper.steps.progress.attr('value',(stepper.index - 1) / (stepper.count - 1) * 100 );
          stepper.steps.find('.step-button').each(function(){
            var thisIndex = $(this).attr('data-step');
            if(thisIndex == stepper.index){
              $(this).find('i').addClass('fa-2x');
              $(this).attr('aria-expanded',true).removeClass('collapsed');
            } else {
              $(this).find('i').removeClass('fa-2x');
              $(this).attr('aria-expanded',false).addClass('collapsed');
            }
            if(thisIndex <= stepper.index){ $(this).addClass('done'); }
            if(thisIndex > stepper.index){ $(this).removeClass('done'); }
          });
        };
        if(callback != null){ callback(stepper); }
        return stepper;
      },
    },
  },
	Notification:{
		count:function(){
			if(Engine.Helper.isSet(Engine,['Layout','notification']) && Engine.Layout.notification.length > 0){
				var count = Engine.Layout.notification.nav.find('.notification.bg-primary').length;
				if(count > 0){ Engine.Layout.notification.badge.html(count); }
				else { Engine.Layout.notification.badge.html(''); }
				Engine.Layout.notification.count.set.html(count);
			}
		},
		triggers:{
			debug:function(notification){
				console.info(notification);
			},
		},
		clear:function(){
			Engine.Layout.notification.nav.find('.notification').remove();
		},
		render:function(){
			if(Engine.Helper.isSet(Engine,['Layout','notification']) && Engine.Layout.notification.length > 0){
				// Engine.Notification.clear();
				if(typeof Engine.Storage.get('notification','list') !== "undefined"){
					for(var [id, notification] of Object.entries(Engine.Storage.get('notification','list'))){
						if(Engine.Layout.notification.nav.find('.notification[data-id="'+notification.id+'"]').length <= 0){
							Engine.Notification.add(notification);
						}
					}
				}
			}
		},
		add:function(notification = {}){
			var validate = true;
			for(var [key, property] of Object.entries(['id','created','text','icon','trigger'])){
				if(typeof notification[property] === 'undefined'){ validate = false;break; }
			}
			if(validate){
				Engine.Layout.notification.nav.add.item('',{after:Engine.Layout.notification.delimiter},function(item){
					item.link.addClass('d-flex justify-content-start notification');
					item.link.addClass('bg-primary link-light').css('transition','.4s');
					item.link.attr('data-id',notification.id);
					Engine.Notification.count();
					// item.link.attr('data-bs-toggle','tooltip').attr('data-bs-placement','left').attr('title',notification.text).tooltip();
					$(document.createElement('i')).addClass('pe-2 pt-1').addClass(notification.icon).appendTo(item.link);
					item.link.content = $(document.createElement('div')).addClass('flex-grow-1').appendTo(item.link);
					item.link.text = $(document.createElement('div')).addClass('text-wrap').css('max-width','500px').html(notification.text).appendTo(item.link.content);
					item.link.time = $(document.createElement('time')).addClass('float-end').css('font-size','12px').attr('datetime',notification.created).appendTo(item.link.content).timeago();
					item.link.click(function(){
						item.link.removeClass('bg-primary link-light');
						item.link.time.addClass('text-muted');
						Engine.Notification.count();
						Engine.request('api','readNotification',{data:notification.id,toast: false,pace: false}).then(function(){ Engine.init(); });
						if(Engine.Helper.isSet(Engine,['Notification','triggers',notification.trigger])){
							Engine.Notification.triggers[notification.trigger](notification);
						}
					});
					item.link.hover(function(){
						item.hover = setTimeout(function(){
							item.link.removeClass('bg-primary link-light');
							item.link.time.addClass('text-muted');
							Engine.Notification.count();
							Engine.request('api','readNotification',{data:notification.id,toast: false,pace: false}).then(function(){ Engine.init(); });
							clearTimeout(item.hover);
						}, 2000);
					},function(){ clearTimeout(item.hover); });
				});
			}
		},
	},
  Installer:{
    running:false,
    checkProgress:function(callback = null){
      var checkStart = setInterval(function(){
        $.ajax({
          url : "./tmp/complete.install",
          dataType:"text",
          success:function(complete){
            clearInterval(checkStart);
            Engine.Installer.running = true;
            var checkProgress = setInterval(function(){
              $.ajax({
                url : "./tmp/progress.install",
                dataType:"text",
                success:function(progress){
                  if(progress.replace(/\n/g, '') == 'success' || progress.replace(/\n/g, '') == 'error'){ clearInterval(checkProgress);Engine.Installer.running = false; }
                  $.ajax({
                    url : "./tmp/install.log",
                    dataType:"text",
                    success:function(log){
                      if(callback != null){ callback(complete.replace(/\n/g, ''),progress.replace(/\n/g, ''),log); }
                    }
                  });
                }
              });
            }, 1000);
          }
        });
      }, 400);
    },
  },
  Application:{
    installer:function(){
			Engine.Builder.layouts.installer(function(layout){
				if(typeof Engine.Layout.body !== 'undefined'){ Engine.Layout.main = Engine.Layout.body; }
				Engine.Layout.load(layout);
				Engine.Builder.components.stepper({header:"Installation Wizard",text:Engine.Translate('welcome_message')},function(stepper){
					stepper.forms = {};
		      stepper.card = $(document.createElement('div')).addClass('card g-2 px-0');
		      stepper.card.header = $(document.createElement('div')).addClass('card-header').appendTo(stepper.card);
		      stepper.card.list = $(document.createElement('ul')).addClass('list-group list-group-flush').appendTo(stepper.card);
		      stepper.card.list.item = $(document.createElement('li')).addClass('list-group-item');
		      stepper.card.list.item.header = $(document.createElement('strong')).addClass('me-2').appendTo(stepper.card.list.item);
		      stepper.add('General',{icon:"fas fa-globe-americas"},function(step){
		        Engine.Builder.components.form({header:'General',name:'InstallerGeneral'},function(form){
		          form.administrator = Engine.Builder.forms.input('administrator',{icon:'fas fa-at',type:'email'}).addClass('my-2').appendTo(form);
		          form.password = Engine.Builder.forms.input('password',{icon:'fas fa-user-lock',type:'password'}).addClass('my-2').appendTo(form);
		          form.language = Engine.Builder.forms.select('language',Engine.Storage.get('language',['list']),{icon:'fas fa-atlas',translate:false}).addClass('my-2').appendTo(form);
		          form.language.find('select').val(Engine.Storage.get('language','current')).select2({theme: "bootstrap-5"});
		          form.timezone = Engine.Builder.forms.select('timezone',Engine.Storage.get('timezone',['list']),{icon:'fas fa-globe-americas',translate:false}).addClass('my-2').appendTo(form);
		          form.timezone.find('select').val(Engine.Storage.get('timezone','current')).select2({theme: "bootstrap-5"});
		          form.gkey = Engine.Builder.forms.input('gkey',{icon:'fab fa-google'}).addClass('my-2').appendTo(form);
		          stepper.forms[step.identifier] = form;
		        }).appendTo(step.content);
		      });
		      stepper.add('SQL',{icon:"fas fa-database",showBack:false},function(step){
		        Engine.Builder.components.form({header:'SQL',name:'InstallerSQL'},function(form){
		          form.host = Engine.Builder.forms.input('host',{icon:'fas fa-server'}).addClass('my-2').appendTo(form);
		          form.database = Engine.Builder.forms.input('database',{icon:'fas fa-database'}).addClass('my-2').appendTo(form);
		          form.username = Engine.Builder.forms.input('username',{icon:'fas fa-user'}).addClass('my-2').appendTo(form);
		          form.password = Engine.Builder.forms.input('password',{icon:'fas fa-user-lock',type:'password'}).addClass('my-2').appendTo(form);
		          stepper.forms[step.identifier] = form;
		        }).appendTo(step.content);
		      });
		      stepper.add('IMAP',{icon:"fas fa-inbox"},function(step){
		        Engine.Builder.components.form({header:'IMAP',name:'InstallerIMAP'},function(form){
		          form.host = Engine.Builder.forms.input('host',{icon:'fas fa-server'}).addClass('my-2').appendTo(form);
		          form.encryption = Engine.Builder.forms.select('encryption',{none:'None',ssl:'SSL',starttls:'STARTTLS'},{icon:'fas fa-lock'}).addClass('my-2').appendTo(form);
		          form.encryption.find('select').select2({theme: "bootstrap-5"});
		          form.port = Engine.Builder.forms.input('port',{icon:'fas fa-plug'}).addClass('my-2').appendTo(form);
		          form.username = Engine.Builder.forms.input('username',{icon:'fas fa-at',type:'email'}).addClass('my-2').appendTo(form);
		          form.password = Engine.Builder.forms.input('password',{icon:'fas fa-user-lock',type:'password'}).addClass('my-2').appendTo(form);
		          stepper.forms[step.identifier] = form;
		        }).appendTo(step.content);
		      });
		      stepper.add('SMTP',{icon:"fas fa-paper-plane"},function(step){
		        Engine.Builder.components.form({header:'SMTP',name:'InstallerSMTP'},function(form){
		          form.host = Engine.Builder.forms.input('host',{icon:'fas fa-server'}).addClass('my-2').appendTo(form);
		          form.encryption = Engine.Builder.forms.select('encryption',{none:'None',ssl:'SSL',starttls:'STARTTLS'},{icon:'fas fa-lock'}).addClass('my-2').appendTo(form);
		          form.encryption.find('select').select2({theme: "bootstrap-5"});
		          form.port = Engine.Builder.forms.input('port',{icon:'fas fa-plug'}).addClass('my-2').appendTo(form);
		          form.username = Engine.Builder.forms.input('username',{icon:'fas fa-at',type:'email'}).addClass('my-2').appendTo(form);
		          form.password = Engine.Builder.forms.input('password',{icon:'fas fa-user-lock',type:'password'}).addClass('my-2').appendTo(form);
		          stepper.forms[step.identifier] = form;
		        }).appendTo(step.content);
		      });
		      stepper.add('License',{icon:"fas fa-glasses",showNext:false},function(step){
		        Engine.Builder.components.form({header:'License',name:'InstallerLicense'},function(form){
		          var trigger = {};
		          form.license = Engine.Builder.components.text(Engine.Storage.get('license')).css("overflow-y", "scroll").css("max-height", "300px").appendTo(form);
		          form.license.find('p').css("text-align", "justify");
		          form.license.checkbox = Engine.Builder.forms.checkbox('status',{label:'I have read and agree to this license agreements'}).addClass('my-2').appendTo(form);
		          form.license.checkbox.find("input").change(function() {
		            if(this.checked){ trigger = step.next.show(); } else { trigger.remove(); }
		          });
		          step.content.on('show.bs.collapse',function(event){ if(form.license.checkbox.find("input").is(':checked')){ trigger = step.next.show(); } });
		          stepper.forms[step.identifier] = form;
		        }).appendTo(step.content);
		      });
		      stepper.add('Review',{icon:"fas fa-eye",colorNext:'success',textNext:'Install',disableToggle:true},function(step){
		        var form = $(document.createElement('div')).addClass('row mx-0');
		        form.getValues = function(){
		          var values = {};
		          for(var [key, value] of Object.entries(stepper.forms)){ values[key] = stepper.forms[key].getValues(); }
		          return values;
		        };
		        stepper.review = form;
		        step.content.append(form);
		        step.content.on('show.bs.collapse',function(event){
		          if(!Engine.Installer.running){
		            form.html('');
		            for(var [key, value] of Object.entries(form.getValues())){
		              var col = $(document.createElement('div')).addClass('col-6 p-2').appendTo(form);
		              switch(key){
		                case'general':
		                  stepper.forms[key].getCard(function(card){
		                    card.list.language.value.html(Engine.Helper.ucfirst(Engine.Storage.get('language',['list'])[card.list.language.value.html()]));
		                    card.list.timezone.value.html(Engine.Storage.get('timezone',['list'])[card.list.timezone.value.html()]);
		                  }).appendTo(col);
		                  break;
		                case'license':break;
		                default:
		                  stepper.forms[key].getCard().appendTo(col);
		                  break;
		              }
		            }
		          }
		        });
		      });
		      stepper.add('Details',{icon:"fas fa-cog",showNext:false,disableToggle:true,enableSpin:true},function(step){
		        trigger = step.addTrigger({icon:'fas fa-sign-in-alt',text:'Sign in',color:'primary'});
		        step.content.on('show.bs.collapse',function(event){
		          if(!Engine.Installer.running){
		            console.log(stepper.review.getValues());
		            Engine.request('installer',{toast: false,pace: false,data:stepper.review.getValues()});
		            Engine.Installer.checkProgress(function(totalProgress,currentProgress,log){
		              console.log(totalProgress,currentProgress,log);
		              if(typeof step.content.progress === 'undefined'){
		                Engine.Builder.components.progress(totalProgress,function(progress){
		                  progress.css('height','32px');
		                  step.content.progress = progress;
		                }).appendTo(step.content);
		              }
		              if(typeof step.content.terminal === 'undefined'){
		                Engine.Builder.components.terminal(log,function(terminal){
		                  terminal.addClass('mt-4').css("overflow-y", "scroll").css("max-height", "300px");
		                  step.content.terminal = terminal;
		                }).appendTo(step.content);
		              }
		              step.content.terminal.setContent(log);
		              if(currentProgress != 'success' && currentProgress != 'error'){
		                step.content.progress.bar.removeClass('bg-danger bg-success');
		                step.content.progress.setProgress(currentProgress);
		              } else {
		                step.content.progress.setProgress(totalProgress);
		                if(currentProgress == 'error'){
		                  step.content.progress.bar.addClass('bg-danger').html(Engine.Translate('Error'));
		                }
		                if(currentProgress == 'success'){
		                  step.content.progress.bar.addClass('bg-success');
		                  if(typeof trigger.this === 'undefined'){
		                    trigger.this = trigger.clone().appendTo(stepper.footer);
		                    trigger.this.off().click(function(){ trigger.this.remove();window.location = window.location.origin+window.location.pathname; });
		                    step.content.on('show.bs.collapse',function(event){
		                      trigger.this = trigger.clone().appendTo(stepper.footer);
		                      trigger.this.off().click(function(){ trigger.this.remove();window.location = window.location.origin+window.location.pathname; });
		                    });
		                    step.content.on('hide.bs.collapse',function(event){ trigger.this.remove(); });
		                  }
		                }
		              }
		            });
		          }
		        });
		      });
					layout.stepper = stepper;
				}).appendTo(layout.main.box);
			});
		},
    login:function(){
			var url = Engine.Helper.getUrlVars();
			if(!Engine.Helper.isSet(url,['forgot'])){
				Engine.Builder.layouts.login(function(layout){
					if(typeof Engine.Layout.body !== 'undefined'){ Engine.Layout.main = Engine.Layout.body; }
					Engine.Layout.load(layout);
					layout.main.box.theme.form.forgot.find('a').click(function(){
						Engine.Builder.layouts.forgot(function(layout){
							Engine.Layout.load(layout);
						});
					});
				});
			} else {
				Engine.Builder.layouts.reset(function(layout){
					if(typeof Engine.Layout.body !== 'undefined'){ Engine.Layout.main = Engine.Layout.body; }
					Engine.Layout.load(layout);
					layout.main.box.theme.form.keyActivation.find('input').val(url.forgot);
				});
			}
		},
    activate:function(){
			Engine.Builder.layouts.activate(function(layout){
				if(typeof Engine.Layout.body !== 'undefined'){ Engine.Layout.main = Engine.Layout.body; }
				Engine.Layout.load(layout);
				var url = Engine.Helper.getUrlVars();
				if(Engine.Helper.isSet(url,['key'])){
					layout.main.box.theme.form.keyActivation.find('input').val(url.key);
				}
			});
		},
    reactivate:function(){
			Engine.Builder.layouts.reactivate(function(layout){
				if(typeof Engine.Layout.body !== 'undefined'){ Engine.Layout.main = Engine.Layout.body; }
				Engine.Layout.load(layout);
				var url = Engine.Helper.getUrlVars();
				if(Engine.Helper.isSet(url,['key'])){
					layout.main.box.theme.form.keyActivation.find('input').val(url.key);
				}
			});
		},
    disabled:function(){
			Engine.Builder.layouts.disabled(function(layout){
				if(typeof Engine.Layout.body !== 'undefined'){ Engine.Layout.main = Engine.Layout.body; }
				Engine.Layout.load(layout);
			});
		},
    init:function(){
			Engine.Builder.layouts.application(function(layout){
				if(typeof Engine.Layout.body !== 'undefined'){ Engine.Layout.main = Engine.Layout.body; }
				Engine.Layout.load(layout);
				Engine.Layout.sidebar = layout.sidebar;
				Engine.Layout.navbar = layout.navbar;
				Engine.Layout.notification = layout.navbar.notification;
				Engine.Layout.main = layout.main;
				Engine.Layout.sidebar.nav.add.item('Dashboard',{icon:'fas fa-home'},function(nav){
					nav.link.addClass('active');
					Engine.Builder.layouts.dashboard.render(function(layout){
						Engine.Layout.load(layout);
					});
					nav.link.click(function(){
						Engine.Builder.layouts.dashboard.render(function(layout){
							Engine.Layout.load(layout);
						});
					});
				});
				if(Engine.Auth.isAllowed('isAdministrator')){
					Engine.Layout.sidebar.nav.add.dropdown('CRUD',{icon:'fas fa-table'},function(nav){
						for(var [key, table] of Object.entries(Engine.Storage.get('tables'))){
							if(Engine.Auth.isAllowed('access'+Engine.Helper.ucfirst(table))){
								var header = Engine.Helper.ucfirst(table);
								nav.dropdown.nav.add.item(header,{icon:'fas fa-table'},function(item){
									item.link.attr('data-table',table);
									item.link.click(function(){
										var table = $(this).data('table');
										Engine.request('crud','headers',{data:table}).then(function(headers){
											Engine.request('crud','read',{data:table}).then(function(records){
												Engine.Builder.layouts.listing.render(records,{headers:headers,clipboard:false,title:table},function(layout){
													Engine.Layout.load(layout);
												});
											});
										});
									});
								});
							}
						}
					});
				}
			});
    },
  }
}

// Init API
Engine.init();
