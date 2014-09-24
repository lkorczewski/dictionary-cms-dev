//==========================================================
// localization
//==========================================================

var localization = new Object();

localization.getText = function(label){
	if(this.texts && this.texts[label]){
		return this.texts[label]
	} else {
		return '[[NO TRANSLATION]]'
	}
}

localization.get_text = localization.getText

//==========================================================
// making AJAX request
//==========================================================

function makeRequest(url, parameters, handler){
	var httpRequest = new XMLHttpRequest()
	httpRequest.onreadystatechange = function(){
		
		var done = false;
		// console.log('readyState == ' + httpRequest.readyState)
				
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
// making AJAX request for JSON
//==========================================================

function makeJsonRequest(url, parameters, handler){
	var httpRequest = new XMLHttpRequest()
	httpRequest.onreadystatechange = function(){
		
		var done = false;
		if(httpRequest.readyState == 4){
			if(httpRequest.status == 200){
				if(done == false){
					var response = JSON.parse(httpRequest.responseText)
					if(response.status == 'failure' && response.message == 'no authorization'){
						location.reload()
					}
					handler.success(response)
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
	makeJsonRequest('atomics/log_editor_in.php', 'l=' + login + '&p=' + password, {
		success: function(response){
			switch(response.status){
				case 'success' :
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
	makeJsonRequest('atomics/log_editor_out.php', '', {
		success: function(response){
			switch(response.status){
				case 'success' :
					doOnSuccess()
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
// showing editor log in
//==========================================================

function showEditorLogIn(){
	var editorToolbar = document.getElementById('editor_toolbar')
	
	var editorLogIn = document.createElement('div')
	editorLogIn.className = 'editor_log_in'
	editorLogIn.textContent = localization.getText('log in');
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
	// creating credentials form
	//--------------------------------
	
	var editorCredentialsForm = document.createElement('form')
	editorCredentialsForm.className = 'editor_credentials'
		
	//--------------------------------
	// creating login input
	//--------------------------------
	var editorLoginInput = document.createElement('input')
	editorLoginInput.type = 'text'
	editorLoginInput.placeholder = localization.getText('login')
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
	editorCredentialsForm.appendChild(editorLoginInput)
	
	//--------------------------------
	// creating password input
	//--------------------------------
	var editorPasswordInput = document.createElement('input')
	//editorPasswordInput.type = 'text'
	editorPasswordInput.type = 'password'
	editorPasswordInput.placeholder = localization.getText('password')
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
	editorCredentialsForm.appendChild(editorPasswordInput)
	
	//--------------------------------
	// creating log in button
	//--------------------------------
	var editorLogInButton = document.createElement('button')
	editorLogInButton.textContent = localization.getText('log in')
	editorLogInButton.setAttribute('type', 'submit')
	editorLogInButton.onclick = function(event){
		event.preventDefault()
		disableEditorCredentialsInput()
		var login     = editorLoginInput.value
		var password  = editorPasswordInput.value
		logEditorIn(
			login,
			password,
			function(){
				showEditor(response.editor_name)
				location.reload()
			},
			function(){
				showWarning(localization.getText('incorrect credentials'))
				enableEditorCredentialsInput()
			}
		)
	}
	editorCredentialsForm.appendChild(editorLogInButton)
	
	//--------------------------------
	// creating cancel button
	//--------------------------------
	var editorCancelButton = document.createElement('button')
	editorCancelButton.setAttribute('type', 'cancel')
	editorCancelButton.textContent = localization.getText('cancel')
	editorCancelButton.onclick = function(){
		showEditorLogIn()
	}
	editorCredentialsForm.appendChild(editorCancelButton)
	
	//--------------------------------
	// displaying
	//--------------------------------
	editorToolbar.innerHTML = '';
	editorToolbar.appendChild(editorCredentialsForm)
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
	logOutButton.textContent = localization.getText('log out')
	logOutButton.onclick = function(){
		logEditorOut(function(){
			showEditorLogIn()
			location.reload()
		})
	}
	
	//--------------------------------
	// displaying
	//--------------------------------
	editorToolbar.innerHTML = ''
	editorToolbar.appendChild(editor)
	editorToolbar.appendChild(logOutButton)
	
}

//==========================================================
// searching for headwords matching pattern
//==========================================================

function searchHeadwordsLike(headwordMask){
	makeJsonRequest('atomics/get_headwords.php', 'h=' + headwordMask, {
		success: function(response){
			var headwords = response
			var searchResultsContainer = document.getElementById('search_results_container')
			searchResultsContainer.innerHTML = ''; // to be replaced
			var isEditionMode = false
			if(window.location.search.indexOf('m=edition') >= 0){
				isEditionMode = true
			}
			if(headwords.length){
				for(var index in headwords){
					var searchResult = document.createElement('div')
					searchResult.className = 'search_result'
					
					var searchResultAnchor = document.createElement('a')
					searchResultAnchor.textContent = headwords[index]
					var link =
						'?h=' +
						headwords[index] +
						(isEditionMode ? '&m=edition' : '')
					searchResultAnchor.setAttribute('href', link);
					searchResult.appendChild(searchResultAnchor)
					
					searchResultsContainer.appendChild(searchResult)
				}
			} else {
				var searchMessage = document.createElement('div')
				searchMessage.className = 'search_message'
				if(isEditionMode){
					/* TODO: invent some name for div1 */
					var div1 = document.createElement('div')
					div1.innerHTML = // so that <b/> works
						localization.getText('entry not found').replace('{{headword}}', '<b>' + headwordMask + '</b>')
						+ localization.getText('create a new one?')
					searchMessage.appendChild(div1)
					
					var buttonBar = document.createElement('div')
					var createButton = document.createElement('button')
					createButton.className = 'button create'
					createButton.textContent = localization.getText('create')
					createButton.onclick = function(){
						addEntry(document.getElementById('search_mask_input').value)
					}
					buttonBar.appendChild(createButton)
					searchMessage.appendChild(buttonBar)
				} else {
					searchMessage.innerHTML = localization.getText('entry not found').replace('{{headword}}', '<b>' + headwordMask + '</b>')
				}
				
				searchResultsContainer.appendChild(searchMessage)
			}
		}
	})
}

