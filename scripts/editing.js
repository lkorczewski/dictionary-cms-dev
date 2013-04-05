function make_request(url, parameters, handler){
	try {
	http_request = new XMLHttpRequest()
	http_request.onreadystatechange = function(){
		done = false;
		console.log('readyState == ' + http_request.readyState)
		if(http_request.readyState == 4){
			if(http_request.status == 200){
				if(done == false){
					handler.success(http_request.responseText)
					done = true
				} else {
					console.log('redundant call')
				}
			} else {
				console.log(http_request.status + ' : ' + http_request.statusText)
			}
		}
	}
	http_request.open('POST', url, true)
	http_request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded'); 
	http_request.send(parameters)
	}
	catch(exception){
		console.log(exception.message)
	}
}

/* DOM traversal */
/*
function previousElementSibling(element) {
	if ('previousElementSibling' in element)
		return element.previousElementSibling;
	do
	element= element.previousSibling;
	while (element!==null && element.nodeType!==1);
	return element;
}
*/

/* atomic actions */

/* senses */

function move_sense_up(element, id){
	make_request('atomics/move_sense_up.php', 'id=' + id, {
		success: function(response){
			console.log('move_sense_up: ' + response)
			if(response == 'OK'){
				element_2 = element.previousElementSibling /* not working in IE<9 */
				/* TO DO: replacing label */
				element.parentNode.insertBefore(element, element_2)
			}
		}
	})
}

function move_sense_down(element, id){
	make_request('atomics/move_sense_down.php', 'id=' + id, {
		success: function(response){
			console.log('move_sense_down: ' + response)
			if(response == 'OK'){
				element_2 = element.nextElementSibling /* not working in IE<9 */
				/* TO DO: replacing label */
				element.parentNode.insertBefore(element_2, element)
			}
		}
	})
}

/* translations */

function move_translation_up(element, id){
	make_request('atomics/move_translation_up.php', 'id=' + id, {
		success: function(response){
			console.log('move_translation_up: ' + response)
			if(response == 'OK'){
				element.parentNode.insertBefore(element, element.previousElementSibling) /* not working in IE<9 */
			}
		}
	})
}

function move_translation_down(element, id){
	make_request('atomics/move_translation_down.php', 'id=' + id, {
		success: function(response){
			console.log('move_translation_down: ' + response)
			if(response == 'OK'){
				element.parentNode.insertBefore(element.nextElementSibling, element) /* not working in IE<9 */
			}
		}
	})
}

function add_translation(sense_element, sense_id){
	make_request('atomics/add_translation.php', 'id=' + sense_id, {
		success: function(response){
			console.log('add_translation: ' + response)
			if(parseInt(response)){
				sense_translations = sense_element.getElementsByClassName('translation')
				last_translation = sense_translations[sense_translations.length - 1] // problem inserting first translation
				sense_element.insertBefore(make_translation('...'), last_translation.nextElementSibling)
			}
		}
	})
}

/*
function edit_translation(element, id){
	element.innerHTML='<input type="text" onkeypress="update_translation">' + element + '</input>';
}

function edit_translation_keypressed(event, element, id, text){
	if(event.keyCode == 13){
		update_translation(element, id, text)
	}
}

function update_translation(element, id, text){
	
}
*/

function make_translation(text, id){
	translation = document.createElement('div')
	translation.setAttribute('class', 'translation')
	translation.appendChild(document.createTextNode(text))
	
	buttons = document.createElement('div')
	buttons.setAttribute('class', 'buttons')
	translation.appendChild(buttons)
	
	buttonUp = document.createElement('button')
	buttonUp.setAttribute('class', 'button move_up')
	buttonUp.setAttribute('onclick', 'move_translation_up(' + id + ')')
	buttonUp.textContent = 'do góry'
	buttons.appendChild(buttonUp)
	
	buttonDown = document.createElement('button')
	buttonDown.setAttribute('class', 'button move_down')
	buttonDown.setAttribute('onclick', 'move_translation_down(' + id + ')')
	buttonDown.textContent = 'na dół'
	buttons.appendChild(buttonDown)
	
	return translation
}