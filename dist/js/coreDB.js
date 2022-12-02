Date.prototype.today = function () {
	return this.getFullYear() + "-" +(((this.getMonth()+1) < 10)?"0":"") + (this.getMonth()+1) + "-" + ((this.getDate() < 10)?"0":"") + this.getDate();
}

Date.prototype.timeNow = function () {
	return ((this.getHours() < 10)?"0":"") + this.getHours() + ":" + ((this.getMinutes() < 10)?"0":"") + this.getMinutes() + ":" + ((this.getSeconds() < 10)?"0":"") + this.getSeconds();
}

function inArray(needle, haystack) {
	var length = haystack.length;
	for(var i = 0; i < length; i++) {
    if(haystack[i] == needle) return true;
	}
	return false;
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

class coreDBIcon {

	#api = null
	#icons = []

	constructor(){
		const self = this
		self.#api = API
		self.#retrieve()
	}

  #retrieve(){
    const self = this
    if(self.#api != null){
      self.#api.get('icon/list',{success:function(result,status,xhr){
				self.#icons = result
      }})
    }
  }

	create(name, html = false){
		const self = this
		let icon = $(document.createElement('i'))
		// if(inArray(name,self.#icons)){
			icon.addClass('bi-'+name)
		// }
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
		}
		let modal = $(document.createElement('div')).addClass('modal fade').attr('tabindex',-1)
		modal.options = options
		modal.dialog = $(document.createElement('div')).addClass('modal-dialog modal-lg').appendTo(modal)
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
			modal.content.addClass('text-bg-'+modal.options.color)
			modal.footer.group.primary.removeClass('btn-primary').addClass('btn-'+modal.options.color)
		}
		if(modal.options.close == null || typeof modal.options.close !== 'boolean' || !modal.options.close){
			modal.header.close.remove()
			delete modal.header.close
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

class coreDBActionBTN {

	constructor(){}

	create(actions = {}, html = false){
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
			if(typeof properties.callback === "function"){
				object.menu[action].btn.click(function(){
					properties.callback(object)
				})
			}
		}
		if(html){
			return object.get(0).outerHTML
		}
		return object
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
		let object = $(document.createElement('div')).appendTo(timeline)
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
		object.attr('data-type',object.options.type).attr('data-order',order)
		object.icon = Icon.create(object.options.icon).addClass('text-bg-'+object.options.color).addClass('shadow').appendTo(object)
		object.item = $(document.createElement('div')).addClass('timeline-item shadow').appendTo(object)
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
		let objects = timeline.children('div').detach().get();
		objects.sort(function(a, b){
			return new Date($(b).data('order')) - new Date($(a).data('order'));
		});
		timeline.append(objects);
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
		self.#setCount()
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
      self.#api.get('notification/list',{success:function(result,status,xhr){
        for(var [key, notification] of Object.entries(result)){
          self.#add(notification)
        }
      }})
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
      self.#api.get('notification/read?id='+notification.data.id,{success:function(result,status,xhr){
        if(notification.find('.b-primary').length > 0){
          notification.dot.remove('b-primary')
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
		console.log(record)
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
			let url = 'dashboard/save/?current'
      self.#api.post(url,{layout:JSON.stringify(self.#layout())},{success:function(result,status,xhr){
				Toast.create({title:'Saved!',icon:'check-lg',color:'success',close:false})
			}})
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
					Modal.create({title:'Row',icon:'columns',body:body},function(modal){
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
					Modal.create({title:'Col',icon:'collection',body:body},function(modal){
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
					Modal.create({title:'Widget',icon:'rocket-takeoff',body:body},function(modal){
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
		// console.log("Widgets: ",widgets)
		for(const [rowKey, row] of Object.entries(widgets)){
			// console.log("rowKey: "+rowKey, row)
			const rowClass = Object.keys(row)[0];
			const rowCols = row[rowClass];
			// console.log("rowClass: "+rowClass,rowCols)
			const rowObj = $(document.createElement('div')).addClass('row').addClass(rowClass);
			// console.log("rowObj: ",rowObj)
			rowObj.appendTo(self.#container)
			for(const [colKey, col] of Object.entries(rowCols)){
				// console.log("colKey: "+colKey, col)
				const colClass = Object.keys(col)[0];
				const colWidgets = col[colClass];
				// console.log("colClass: "+colClass,colWidgets)
				const colObj = $(document.createElement('div')).addClass('col').addClass(colClass);
				// console.log("colObj: ",colObj)
				colObj.appendTo(rowObj)
				for(const [widgetKey, widget] of Object.entries(colWidgets)){
					// console.log("widgetKey: "+widgetKey, widget)
					const widgetObj = self.#widget(widget);
					// console.log("widgetObj: ",widgetObj)
					// console.log("widgetElement: ",widgetObj.element)
					widgetObj.obj = $(widgetObj.element)
					// console.log("obj: ",widgetObj.obj)
					widgetObj.obj.attr('data-widget',widget).appendTo(colObj)
					if(widgetObj.callback != null){
						// console.log("widgetCallback: ",widgetObj.callback)
						const callback = eval('('+widgetObj.callback+')')
						// console.log("callback: ",callback);
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

// Core
const API = new phpAPI('/api.php')
const Cookie = new phpAuthCookie()
const Clock = new coreDBClock()
// Components
const Icon = new coreDBIcon()
const Modal = new coreDBModal()
const Toast = new coreDBToast()
const ActionDropdown = new coreDBActionBTN()
const Timeline = new coreDBTimeline()
// Objects
const Notifications = new coreDBNotifications()
const Activity = new coreDBActivity()
const Dashboard = new coreDBDashboard()

Clock.start()

$.holdReady(false)
