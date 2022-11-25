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
		self.#widget()
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
				self.#sortable(col,false)
			}
		})
		self.#container.find('.placeholder').remove()
		self.#container.find('.handle').remove()
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

	#sortable(object, status = true){
		let options = {
			cursor: "move",
			// activate: function(event, ui){
			// 	console.log('activate',event, ui)
			// },
			beforeStop: function(event, ui){
				// console.log('beforeStop',event, ui)
				if(ui.item.hasClass('delete')){
					ui.item.remove()
				}
			},
			// change: function(event, ui){
			// 	console.log('change',event, ui)
			// },
			// create: function(event, ui){
			// 	console.log('create',event, ui)
			// },
			// deactivate: function(event, ui){
			// 	console.log('deactivate',event, ui)
			// },
			out: function(event, ui){
				// console.log('out',event, ui)
				ui.item.addClass('delete')
			},
			over: function(event, ui){
				// console.log('over',event, ui)
				ui.item.removeClass('delete')
			},
			// receive: function(event, ui){
			// 	console.log('receive',event, ui)
			// },
			// remove: function(event, ui){
			// 	console.log('remove',event, ui)
			// },
			// sort: function(event, ui){
			// 	console.log('sort',event, ui)
			// },
			// start: function(event, ui){
			// 	console.log('start',event, ui)
			// },
			stop: function(event, ui){
				console.log('stop',event, ui)
				if(ui.item.hasClass('delete')){
					ui.item.removeClass('delete')
				}
				if(ui.item.hasClass('col')){
					ui.item.sortable("enable")
					// self.#sortable(ui.item)
				}
			},
			// update: function(event, ui){
			// 	console.log('update',event, ui)
			// },
		}
		if(object.find('.handle').length > 0){
			if(!object.hasClass('col')){
				if(object.hasClass('row')){
					options.handle = object.find('.handle').eq(1)
					object.find('.col.ui-sortable').sortable("disable")
				} else {
					if(object.hasClass('edit')){
						options.handle = object.find('.handle').first()
						object.find('.col.ui-sortable').sortable("disable")
					}
				}
			}
		}
		if(status){
			object.sortable(options).sortable("enable")
		} else {
			object.sortable("disable")
		}
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

	#modal(){
    const self = this
		let modal = $(document.createElement('div')).addClass('modal fade').attr('tabindex',-1)
		modal.dialog = $(document.createElement('div')).addClass('modal-dialog modal-lg').appendTo(modal)
		modal.content = $(document.createElement('div')).addClass('modal-content').appendTo(modal.dialog)
		modal.header = $(document.createElement('div')).addClass('modal-header shadow-sm').appendTo(modal.content)
		modal.header.title = $(document.createElement('h5')).addClass('modal-title fw-light').appendTo(modal.header)
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

	#handle(){
    const self = this
		let item = $(document.createElement('div')).addClass('card handle fs-4 fw-light')
		item.icon = $(document.createElement('i')).addClass('bi-arrows-move mx-2 my-1').appendTo(item)
		item.mousedown(function(){
			console.log(item.parent().parent())
			self.#sortable(item.parent().parent())
		})
		// item.mouseout(function(){
		// 	self.#sortable(item.parent(),false)
		// })
		return item
	}

	#placeholder(type = null){
    const self = this
		let item = $(document.createElement('div')).addClass('card placeholder')
		item.div = $(document.createElement('div')).appendTo(item)
		switch(type){
			case"row":
				item.icon = $(document.createElement('i')).addClass('bi-columns').appendTo(item.div)
				item.addClass(type)
				item.click(function(){
					let modal = self.#modal()
					let bsModal = new bootstrap.Modal(modal)
					modal.header.title.html('Row')
					modal.header.title.icon = $(document.createElement('i')).addClass('bi-columns me-2').prependTo(modal.header.title)
					modal.body.row = $(document.createElement('div')).addClass('row row-cols-2').appendTo(modal.body)
					let col = $(document.createElement('div')).addClass('col my-2')
					let radio = $(document.createElement('input')).addClass('btn-check').attr('type','radio')
					let label = $(document.createElement('label')).addClass('btn btn-outline-primary border w-100 shadow-sm')
					label.row = $(document.createElement('div')).addClass('row h-100 px-2')
					label.col = $(document.createElement('div')).addClass('col bg-secondary py-2 rounded m-2')
					for(var id=1;id <= 4;id++){
						const option = col.clone().appendTo(modal.body.row)
						option.option = radio.clone().attr('data-value','row-cols-'+id).attr('id','option'+id).attr('name','row').appendTo(option)
						option.label = label.clone().attr('for','option'+id).appendTo(option)
						option.label.row = label.row.clone().appendTo(option.label)
						for(var count=1;count <= id;count++){
							label.col.clone().appendTo(option.label.row)
						}
					}
					modal.footer.group.primary.click(function(){
						let row = $(document.createElement('div')).addClass('row').append(self.#placeholder('col'))
						modal.body.row.find('input[type="radio"]:checked').each(function(){
							row.addClass($(this).attr('data-value'))
						})
						item.before(row)
						bsModal.hide()
					})
					bsModal.show()
				})
				break
			case"col":
				item.icon = $(document.createElement('i')).addClass('bi-collection').appendTo(item.div)
				item.addClass(type)
				item.click(function(){
					let modal = self.#modal()
					let bsModal = new bootstrap.Modal(modal)
					modal.header.title.html('Col')
					modal.header.title.icon = $(document.createElement('i')).addClass('bi-collection me-2').prependTo(modal.header.title)
					modal.body.row = $(document.createElement('div')).addClass('row row-cols-2').appendTo(modal.body)
					let col = $(document.createElement('div')).addClass('col my-2')
					let radio = $(document.createElement('input')).addClass('btn-check').attr('type','radio')
					let label = $(document.createElement('label')).addClass('btn btn-outline-primary border w-100 shadow-sm')
					label.row = $(document.createElement('div')).addClass('row h-100 px-2')
					label.col = $(document.createElement('div')).addClass('col bg-secondary py-2 rounded m-2')
					if($(this).parent().hasClass('row-cols-4')){
						let option = col.clone().appendTo(modal.body.row)
						option.option = radio.clone().attr('data-value','col').attr('id','option-3').attr('name','row').appendTo(option)
						option.label = label.clone().attr('for','option-3').appendTo(option)
						option.label.row = label.row.clone().appendTo(option.label)
						label.col.clone().addClass('col-3').appendTo(option.label.row)
						option = col.clone().appendTo(modal.body.row)
						option.option = radio.clone().attr('data-value','col-6').attr('id','option-6').attr('name','row').appendTo(option)
						option.label = label.clone().attr('for','option-6').appendTo(option)
						option.label.row = label.row.clone().appendTo(option.label)
						label.col.clone().addClass('col-6').appendTo(option.label.row)
						option = col.clone().appendTo(modal.body.row)
						option.option = radio.clone().attr('data-value','col-9').attr('id','option-9').attr('name','row').appendTo(option)
						option.label = label.clone().attr('for','option-9').appendTo(option)
						option.label.row = label.row.clone().appendTo(option.label)
						label.col.clone().addClass('col-9').appendTo(option.label.row)
						option = col.clone().appendTo(modal.body.row)
						option.option = radio.clone().attr('data-value','col-12').attr('id','option-12').attr('name','row').appendTo(option)
						option.label = label.clone().attr('for','option-12').appendTo(option)
						option.label.row = label.row.clone().appendTo(option.label)
						label.col.clone().removeClass('m-2').addClass('my-2 col-12').appendTo(option.label.row)
					}
					if($(this).parent().hasClass('row-cols-3')){
						let option = col.clone().appendTo(modal.body.row)
						option.option = radio.clone().attr('data-value','col').attr('id','option-4').attr('name','row').appendTo(option)
						option.label = label.clone().attr('for','option-4').appendTo(option)
						option.label.row = label.row.clone().appendTo(option.label)
						label.col.clone().addClass('col-4').appendTo(option.label.row)
						option = col.clone().appendTo(modal.body.row)
						option.option = radio.clone().attr('data-value','col-8').attr('id','option-8').attr('name','row').appendTo(option)
						option.label = label.clone().attr('for','option-8').appendTo(option)
						option.label.row = label.row.clone().appendTo(option.label)
						label.col.clone().addClass('col-8').appendTo(option.label.row)
						option = col.clone().appendTo(modal.body.row)
						option.option = radio.clone().attr('data-value','col-12').attr('id','option-12').attr('name','row').appendTo(option)
						option.label = label.clone().attr('for','option-12').appendTo(option)
						option.label.row = label.row.clone().appendTo(option.label)
						label.col.clone().removeClass('m-2').addClass('my-2 col-12').appendTo(option.label.row)
					}
					if($(this).parent().hasClass('row-cols-2')){
						let option = col.clone().appendTo(modal.body.row)
						option.option = radio.clone().attr('data-value','col').attr('id','option-6').attr('name','row').appendTo(option)
						option.label = label.clone().attr('for','option-6').appendTo(option)
						option.label.row = label.row.clone().appendTo(option.label)
						label.col.clone().addClass('col-6').appendTo(option.label.row)
						option = col.clone().appendTo(modal.body.row)
						option.option = radio.clone().attr('data-value','col-12').attr('id','option-12').attr('name','row').appendTo(option)
						option.label = label.clone().attr('for','option-12').appendTo(option)
						option.label.row = label.row.clone().appendTo(option.label)
						label.col.clone().removeClass('m-2').addClass('my-2 col-12').appendTo(option.label.row)
					}
					if($(this).parent().hasClass('row-cols-1')){
						let option = col.clone().appendTo(modal.body.row)
						option.option = radio.clone().attr('data-value','col').attr('id','option-12').attr('name','row').appendTo(option)
						option.label = label.clone().attr('for','option-12').appendTo(option)
						option.label.row = label.row.clone().appendTo(option.label)
						label.col.clone().removeClass('m-2').addClass('my-2 col-12').appendTo(option.label.row)
					}
					modal.footer.group.primary.click(function(){
						let col = $(document.createElement('div')).addClass('col').append(self.#placeholder())
						modal.body.row.find('input[type="radio"]:checked').each(function(){
							col.addClass($(this).attr('data-value'))
						})
						item.before(col)
						self.#sortable(col)
						bsModal.hide()
					})
					bsModal.show()
				})
				break
			default:
				item.icon = $(document.createElement('i')).addClass('bi-rocket-takeoff').appendTo(item.div)
				item.click(function(){
					let modal = self.#modal()
					let bsModal = new bootstrap.Modal(modal)
					modal.widget = null
					modal.header.title.html('Widget')
					modal.header.title.icon = $(document.createElement('i')).addClass('bi-rocket-takeoff me-2').prependTo(modal.header.title)
					modal.body.preview = $(document.createElement('div')).addClass('card shadow').appendTo(modal.body)
					modal.body.preview.header = $(document.createElement('div')).addClass('card-header').appendTo(modal.body.preview)
					modal.body.preview.header.title = $(document.createElement('h5')).addClass('card-title fw-light').html('Preview').appendTo(modal.body.preview.header)
					modal.body.preview.header.title.icon = $(document.createElement('i')).addClass('bi-eye me-2').prependTo(modal.body.preview.header.title)
					modal.body.preview.body = $(document.createElement('div')).addClass('card-body p-4').appendTo(modal.body.preview)
					modal.body.selector = $(document.createElement('div')).addClass('mt-3').appendTo(modal.body)
					modal.body.select = $(document.createElement('select')).addClass('form-select shadow').attr('aria-label','Select a Widget').appendTo(modal.body.selector)
					$(document.createElement('option')).html('Select a Widget').appendTo(modal.body.select)
					for(var [name, widget] of Object.entries(self.#widget())){
						$(document.createElement('option')).attr('value',name).html(name).appendTo(modal.body.select)
					}
					modal.body.select.change(function(){
						modal.widget = $(self.#widgets[$(this).val()].element)
						modal.widget.attr('data-widget',$(this).val())
						modal.body.preview.body.html(modal.widget)
					});
					modal.footer.group.primary.click(function(){
						if(modal.widget != null){
							item.before(modal.widget)
							bsModal.hide()
						}
					})
					bsModal.show()
				})
				break
		}
		return item
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
