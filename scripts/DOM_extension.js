/* DOM extension */

HTMLElement.prototype.moveUp = function(){
	this.parentNode.insertBefore(this, this.previousElementSibling)
}

HTMLElement.prototype.moveDown = function(){
	this.parentNode.insertBefore(this.nextElementSibling, this)
}

HTMLElement.prototype.remove = function(){
	this.parentNode.removeChild(this)
}

HTMLElement.prototype.delete = HTMLElement.prototype.remove

