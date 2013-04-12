function make_request(url, parameters, handler){
	try {
	http_request = new XMLHttpRequest()
	http_request.onreadystatechange = function(){
		done = false;
		console.log('readyState == ' + http_request.readyState)
				
		// do when response received
		
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

function move_translation_up(translation_bar, id){
	make_request('atomics/move_translation_up.php', 'id=' + id, {
		success: function(response){
			console.log('move_translation_up: ' + response)
			if(response == 'OK'){
				translation_bar.parentNode.insertBefore(translation_bar, translation_bar.previousElementSibling) /* not working in IE<9 */
			}
		}
	})
}

function move_translation_down(translation_bar, id){
	make_request('atomics/move_translation_down.php', 'id=' + id, {
		success: function(response){
			console.log('move_translation_down: ' + response)
			if(response == 'OK'){
				translation_bar.parentNode.insertBefore(translation_bar.nextElementSibling, translation_bar) /* not working in IE<9 */
			}
		}
	})
}

function add_translation(sense_element, sense_id){
	make_request('atomics/add_translation.php', 'id=' + sense_id, {
		success: function(response){
			console.log('add_translation: ' + response)
			if(parseInt(response)){
				translations = sense_element.getElementsByClassName('translations')[0]
				translations.appendChild(make_translation('...'))
			}
		}
	})
}

function update_translation(translation_bar, id, text, doOnSuccess){
	make_request('atomics/update_translation.php', 'id=' + id + '&t=' + text, {
		success: function(response){
			console.log('update_translation: ' + response)
			if(response == 'OK'){
				translation = translation.parentNode.getElementsByClassName('translation')[0]
				translation.textContent = text
				
				if(typeof doOnSuccess != 'undefined'){
					doOnSuccess()
				}
				
				translation.style.display = 'inline-block';
			}
		}
	})

}

function delete_translation(translation_bar, id){
	make_request('atomics/delete_translation.php', 'id=' + id, {
		success: function(response){
			console.log('delete_translation: ' + response)
			if(response == 'OK'){
				translation_bar.parentNode.removeChild(translation_bar)
			}
		}
	})
}

function edit_translation(translation_bar, id){
	translation = translation_bar.getElementsByClassName('translation')[0]
	
	translation_input = document.createElement('input')
	translation_input.setAttribute('type','text')
	translation_input.value = translation.textContent
	translation_input.onkeypress = function(){
		if(event.keyCode == 13){
			if(translation_input.value != translation.textContent){
				translation_input.disabled = true
				update_translation(translation_input.parentNode, id, translation_input.value, function(){
					translation_input.parentNode.removeChild(translation_input);
					translation.style.display = 'inline-block';
				})
			}
		}
	}
	
	translation.style.display = 'none';
	translation_bar.insertBefore(translation_input, translation.nextElementSibling) /* not working in IE<9 */
}

function make_translation(text, id){
	translation_bar = document.createElement('div')
	translation_bar.setAttribute('class', 'translation_bar')
	
	translation = document.createElement('div')
	translation.setAttribute('class', 'translation')
	translation.textContent = text
	translation_bar.appendChild(translation)
	
	buttons = document.createElement('div')
	buttons.setAttribute('class', 'buttons')
	translation_bar.appendChild(buttons)
	
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
	
	return translation_bar
}
