//==========================================================
// making AJAX request
//==========================================================

function makeRequest(url, parameters, handler){
	httpRequest = new XMLHttpRequest()
	httpRequest.onreadystatechange = function(){
		
		done = false;
		//console.log('readyState == ' + http_request.readyState)
				
		// do when response received
		
		if(httpRequest.readyState == 4){
			if(httpRequest.status == 200){
				if(done == false){
					handler.success(httpRequest.responseText)
					done = true
				} else {
					console.log('redundant call')
				}
			} else {
				if(handler.failure){
					handler.failure()
				}
				console.log(httpRequest.status + ' : ' + httpRequest.statusText)
			}
		}
		
	}
	httpRequest.open('POST', url, true)
	httpRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded'); 
	httpRequest.send(parameters)
}
make_request = makeRequest

//==========================================================
// showing warning
//==========================================================

function showWarning(message){
	
	// creating warning element
	var warningElement = document.createElement('div')
	warningElement.id = 'warning'
	warningElement.className = 'warning'
	
	// creating close button
	var closeButton = document.createElement('img')
	closeButton.className = 'close-button'
	warningElement.onclick = function(event){
		var warningElement = event.target
		warningElement.parentNode.removeChild(warningElement)
	}
	
	// appending button and text
	// to warning element
	warningElement.appendChild(closeButton)
	warningElement.textContent = message
	
	// displaying
	var toolbarElement = document.getElementById('toolbar')
	toolbarElement.parentNode.insertBefore(warningElement, toolbarElement)
	
}


//==========================================================
// logging editor in
//==========================================================

function logEditorIn(login, password, doOnSuccess, doOnFailure){
	make_request('atomics/log_editor_in.php', 'l=' + login + '&p=' + password, {
		success: function(responseText){
			var response = JSON.parse(responseText)
			switch(response.status){
				case 'OK' :
					doOnSuccess(response.editor_name)
					break
				case 'failure' :
					if(doOnFailure){
						doOnFailure()
					}
					break
			}
		}
	})
}

//==========================================================
// logging editor out
//==========================================================

function logEditorOut(doOnSuccess, doOnFailure){
	make_request('atomics/log_editor_out.php', '', {
		success: function(responseText){
			var response = JSON.parse(responseText)
			switch(response.status){
				case 'OK' :
					doOnSuccess()
					break
				case 'failure' :
					if(doOnFailure){ doOnFailure() }
					break
			}
		}
	})
}

//==========================================================
// showing editor log in
//==========================================================

function showEditorLogIn(){
	var editorToolbar = document.getElementById('editor_toolbar')
	
	var editorLogIn = document.createElement('div')
	editorLogIn.className = 'editor_log_in'
	editorLogIn.textContent = 'zaloguj się';
	editorLogIn.onclick = function(){
		showEditorCredentialsInput()
	}
	
	//--------------------------------
	// result
	//--------------------------------
	editorToolbar.innerHTML = '';
	editorToolbar.appendChild(editorLogIn)
	
}

//==========================================================
// showing editor credentials input
//==========================================================

function showEditorCredentialsInput(editorToolbarContent){
	
	function disableEditorCredentialsInput(){
		editorLoginInput.disabled = true
		editorPasswordInput.disabled = true
		editorLogInButton.disabled = true
		editorCancelButton.disabled = true
	}
	
	function enableEditorCredentialsInput(){
		editorLoginInput.disabled = false
		editorPasswordInput.disabled = false
		editorLogInButton.disabled = false
		editorCancelButton.disabled = false
	}
	
	var editorToolbar = document.getElementById('editor_toolbar')
	
	//--------------------------------
	// creating credentials input
	//--------------------------------
	var editorCredentialsInput = document.createElement('div')
	editorCredentialsInput.className = 'editor_credentials'
	
	//--------------------------------
	// creating login input
	//--------------------------------
	var editorLoginInput = document.createElement('input')
	editorLoginInput.type = 'text'
	editorLoginInput.placeholder = 'login'
	/*
	editorLoginInput.value = 'login'
	editorLoginInput.onfocus = function(){
		editorLoginInput.value = ''
	}
	editorLoginInput.onblur = function(){
		if(editorLoginInput.value == ''){
			editorLoginInput.value = 'login'
		}
	}
	*/
	editorCredentialsInput.appendChild(editorLoginInput)
	
	//--------------------------------
	// creating password input
	//--------------------------------
	var editorPasswordInput = document.createElement('input')
	//editorPasswordInput.type = 'text'
	editorPasswordInput.type = 'password'
	editorPasswordInput.placeholder = 'hasło'
	/*
	editorPasswordInput.value = 'password'
	editorPasswordInput.onfocus = function(){
		editorPasswordInput.value = ''
		editorPasswordInput.type = 'password'
	}
	editorPasswordInput.onblur = function(){
		if(editorPasswordInput.value == ''){
			editorPasswordInput.value = ''
			editorPasswordInput.type = 'text'
		}
	}
	*/
	editorCredentialsInput.appendChild(editorPasswordInput)
	
	//--------------------------------
	// creating log in button
	//--------------------------------
	var editorLogInButton = document.createElement('button')
	editorLogInButton.textContent = 'zaloguj się'
	editorLogInButton.onclick = function(){
		disableEditorCredentialsInput()
		login = editorLoginInput.value
		password = editorPasswordInput.value
		make_request('atomics/log_editor_in.php', 'l=' + login + '&p=' + password, {
			success: function(responseText){
				response = JSON.parse(responseText)
				switch(response.status){
					case 'OK' :
						showEditor(response.editor_name)
						break
					case 'failure' :
						showWarning('Incorrect credentials.')
						enableEditorCredentialsInput()
						break
				}
			}
		})
	}
	editorCredentialsInput.appendChild(editorLogInButton)
	
	//--------------------------------
	// creating cancel button
	//--------------------------------
	var editorCancelButton = document.createElement('button')
	editorCancelButton.textContent = 'anuluj'
	editorCancelButton.onclick = function(){
		showEditorLogIn(editorCredentialsInput)
	}
	editorCredentialsInput.appendChild(editorCancelButton)
	
	//--------------------------------
	// displaying
	//--------------------------------
	editorToolbar.innerHTML = '';
	editorToolbar.appendChild(editorCredentialsInput)
	editorLoginInput.focus()
	
}

//==========================================================
// showing editor
//==========================================================

function showEditor(editorName){
	var editorToolbar = document.getElementById('editor_toolbar')
	
	//--------------------------------
	// creating editor name
	//--------------------------------
	var editor = document.createElement('div')
	editor.className = 'editor'
	editor.textContent = 'editor: ' + editorName
	
	//--------------------------------
	// creating logout button
	//--------------------------------
	var logOutButton = document.createElement('button')
	logOutButton.className = 'button log_out'
	logOutButton.textContent = 'wyloguj się'
	logOutButton.onclick = function(){
		logEditorOut(showEditorLogIn)
	}
	
	//--------------------------------
	// displaying
	//--------------------------------
	editorToolbar.innerHTML = ''
	editorToolbar.appendChild(editor)
	editorToolbar.appendChild(logOutButton)
}
