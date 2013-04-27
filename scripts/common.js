function make_request(url, parameters, handler){
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
				console.log(httpRequest.status + ' : ' + httpRequest.statusText)
			}
		}
		
	}
	httpRequest.open('POST', url, true)
	httpRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded'); 
	httpRequest.send(parameters)
}

function showEditorLogIn(editorToolbarContent){
	editorToolbar = editorToolbarContent.parentNode
	
	editorLogIn = document.createElement('div')
	editorLogIn.className = 'editor_log_in'
	editorLogIn.textContent = 'zaloguj';
	editorLogIn.onclick = function(){
		showEditorCredentialsInput(editorLogIn)
	}
	
	//--------------------------------
	// result
	//--------------------------------
	editorToolbar.replaceChild(editorLogIn, editorToolbarContent)
	
}

function showEditorCredentialsInput(editorToolbarContent){
	editorToolbar = editorToolbarContent.parentNode
	
	//--------------------------------
	// credentials input
	//--------------------------------
	editorCredentialsInput = document.createElement('div')
	editorCredentialsInput.className = 'editor_credentials'
	
	//--------------------------------
	// login input
	//--------------------------------
	editorLoginInput = document.createElement('input')
	editorLoginInput.type = 'text'
	editorLoginInput.value = 'login'
	editorLoginInput.onfocus = function(){
		editorLoginInput.value = ''
	}
	editorLoginInput.onblur = function(){
		if(editorLoginInput.value == ''){
			editorLoginInput.value = 'login'
		}
	}
	editorCredentialsInput.appendChild(editorLoginInput)
	
	//--------------------------------
	// password input
	//--------------------------------
	editorPasswordInput = document.createElement('input')
	editorPasswordInput.type = 'text'
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
	editorCredentialsInput.appendChild(editorPasswordInput)
	
	//--------------------------------
	// log in button
	//--------------------------------
	editorLogInButton = document.createElement('button')
	editorLogInButton.textContent = 'zaloguj się'
	editorLogInButton.onclick = function(){
		editorLoginInput.disabled = true
		editorPasswordInput.disabled = true
		login = editorLoginInput.value
		password = editorPasswordInput.value
		make_request('atomics/log_editor_in.php', 'l=' + login + '&p=' + password, {
			success: function(responseText){
				response = JSON.parse(responseText)
				switch(response.status){
					case 'OK' :
						showEditor(editorCredentialsInput, response.editor_name)
						break
					case 'failure' :
						break
				}
			}
		})
	}
	editorCredentialsInput.appendChild(editorLogInButton)
	
	//--------------------------------
	// cancel button
	//--------------------------------
	
	editorCancelButton = document.createElement('button')
	editorCancelButton.textContent = 'anuluj'
	editorCancelButton.onclick = function(){
		showEditorLogIn(editorCredentialsInput)
	}
	editorCredentialsInput.appendChild(editorCancelButton)
	
	//--------------------------------
	// result
	//--------------------------------
	editorToolbar.replaceChild(editorCredentialsInput, editorToolbarContent)
	
}

function showEditor(editorToolbarContent, editorName){
	editorToolbar = editorToolbarContent.parentNode
	
	//--------------------------------
	// credentials input
	//--------------------------------
	editor = document.createElement('div')
	editor.className = 'editor'
	editor.textContent = 'editor: ' + editorName
	
	//--------------------------------
	// result
	//--------------------------------
	editorToolbar.replaceChild(editor, editorToolbarContent)
	
}
