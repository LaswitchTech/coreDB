class Clock {
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

class coreDBNotifications {

  #area = null
  #button = null
  #badge = null
  #banner = null
  #timeline = null
  #token = null
  #api = null
  #clock = null
  #count = 0

  constructor(token = null){
    const self = this
    self.#area = $('#NotificationArea')
    self.#button = $('#NotificationsMenu')
    self.#badge = $('#NotificationsMenu span').html(self.#count)
    self.#banner = $('#NotificationArea ul li').first()
    self.#banner.count = self.#banner.find('strong').html(self.#count)
    self.#timeline = $('#NotificationArea .timeline')
    if(token != null && typeof token === 'string'){
      self.#token = token
      self.#api = new phpAPI('/api.php')
      self.#api.setAuth('BEARER',self.#token)
      self.#retrieve()
      self.#clock = new Clock()
      self.#clock.start()
      self.#clock.add(function(){
        self.#retrieve()
      })
    }
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
    item.date = $(document.createElement('time')).addClass('tl-date timeago text-muted mt-1').appendTo(item.content)
    return item
  }

  #setCount(count){
    const self = this
    self.#count = self.#count + count
    if(self.#count > 0){
      self.#badge.show()
    } else {
      self.#badge.hide()
    }
    self.#badge.html(self.#count)
    self.#banner.count.html(self.#count)
  }

  #read(notification, callback = null){
    const self = this
    if(self.#api != null && !notification.data.isRead){
      self.#api.get('notification/read?id='+notification.data.id,{success:function(result,status,xhr){
        if(notification.find('.b-primary').length > 0){
          notification.dot.remove('b-primary')
          notification.dot.addClass('b-secondary')
        }
        self.#setCount(-1)
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
      if(!record.isRead){
        self.#setCount(1)
      }
      notification.dot.addClass('b-'+record.color.toLowerCase())
      notification.notification.html(record.content)
      notification.date.attr('datetime',record.created).html(record.created)
      notification.prependTo(self.#timeline)
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

const Notifications = new coreDBNotifications(API_TOKEN)
