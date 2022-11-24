const Cookie = new phpAuthCookie()

const API = new phpAPI('/api.php')
API.setAuth('BEARER',API_TOKEN)

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

const Clock = new coreDBClock()
Clock.start()

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
    item.span.icon = $(document.createElement('i')).addClass('bi-clock me-1').appendTo(item.span)
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

const Notifications = new coreDBNotifications()

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
		self.#clear()
		self.#retrieve(type,id)
		self.#object.show()
	}

	#clear(){
    const self = this
		self.#offcanvas.timeline.html('')
	}

  #create(){
    let item = $(document.createElement('li'))
    item.badge = $(document.createElement('div')).addClass('timeline-badge shadow').appendTo(item)
		item.icon = $(document.createElement('i')).appendTo(item.badge)
    item.panel = $(document.createElement('div')).addClass('timeline-panel rounded shadow').appendTo(item)
    item.heading = $(document.createElement('div')).addClass('timeline-heading').appendTo(item.panel)
    item.title = $(document.createElement('h4')).addClass('timeline-title fw-light mb-0').appendTo(item.heading)
    item.time = $(document.createElement('p')).addClass('mb-1').appendTo(item.heading)
    item.small = $(document.createElement('small')).addClass('text-muted').appendTo(item.time)
    item.small.icon = $(document.createElement('i')).addClass('bi-clock me-1').appendTo(item.small)
    item.date = $(document.createElement('time')).addClass('timeago').appendTo(item.small)
    item.body = $(document.createElement('div')).addClass('timeline-body').appendTo(item.panel)
    item.content = $(document.createElement('p')).appendTo(item.body)
    return item
  }

  #add(record){
    const self = this
    if(self.#offcanvas.timeline.find('li[data-id="'+record.id+'"]').length <= 0){
      let activity = self.#create()
      activity.data = record
      activity.attr('data-id',record.id)
      activity.id = activity.attr('data-id')
			activity.title.html(record.title)
			activity.content.html(record.content)
			activity.badge.addClass(record.color.toLowerCase())
			activity.icon.addClass('bi-'+record.icon.toLowerCase())
      activity.date.attr('datetime',record.created).html(record.created)
      activity.appendTo(self.#offcanvas.timeline)
			activity.date.timeago()
			if(record.route != null && typeof record.route === 'string'){
        activity.click(function(){
          window.open(window.location.protocol+"//"+window.location.hostname+activity.data.route,"_self")
        })
      }
    }
  }

	#build(){
		const self = this;
		if(self.#offcanvas == null){
			self.#offcanvas = $(document.createElement('div')).addClass('offcanvas offcanvas-end user-select-none').attr('tabindex','-1').attr('id','profileOffcanvasActivity').attr('aria-labelledby','profileOffcanvasActivityLabel');
			self.#offcanvas.id = self.#offcanvas.attr('id');
			self.#offcanvas.header = $(document.createElement('div')).addClass('offcanvas-header bg-image shadow text-light px-4').appendTo(self.#offcanvas);
			self.#offcanvas.title = $(document.createElement('h5')).addClass('offcanvas-title fs-2 fw-light mt-3 ms-2').attr('id','profileOffcanvasActivityLabel').html('<i class="bi-activity me-2"></i>'+self.#fields['Activity']).appendTo(self.#offcanvas.header);
			self.#offcanvas.body = $(document.createElement('div')).addClass('offcanvas-body').appendTo(self.#offcanvas);
			self.#offcanvas.timeline = $(document.createElement('ul')).addClass('timeline').appendTo(self.#offcanvas.body);
			self.#offcanvas.appendTo('body');
		}
		if(self.#object == null){
			if(typeof bootstrap !== 'undefined'){
				self.#object = new bootstrap.Offcanvas(self.#offcanvas)
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

const Activity = new coreDBActivity()

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
		self.#retrieve(organization)
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
	}

	#save(){
    const self = this
		self.#container.removeClass('edit')
		self.#update()
		console.log(self.#layout())
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

	#clear(){
    const self = this
		self.#container.html('')
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

	#load(widgets){
    const self = this
		self.#clear()
		console.log(widgets)
	}

	#layout(){
    const self = this
		let rows = [], widgets = []
		self.#container.find('.row').each(function(){
			const thisRow = $(this)
			const row = {}
			let classList = thisRow.attr("class")
			let classArr = classList.split(/\s+/)
			for(const [key, className] of Object.entries(classArr)){
				switch(className){
					case"row-cols-1":
					case"row-cols-2":
					case"row-cols-3":
					case"row-cols-4":
						row[className] = []
						thisRow.find('.col').each(function(){
							const thisCol = $(this)
							const col = []
							thisCol.find('[data-widget]').each(function(){
								const thisWidget = $(this)
								col.push(thisWidget.attr('data-widget'))
							})
							row[className].push(col)
						})
						rows.push(row)
						break
				}
			}
		})
		return rows
	}
}

const Dashboard = new coreDBDashboard()

$.holdReady(false)
