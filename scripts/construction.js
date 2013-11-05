function makeButton(name, action){
	var button = document.createElement('button')
	button.setAttribute('class', 'button ' + name)
	button.onclick = action
	button.textContent = localization.getText(name)
	return button
}

function makeButtons(buttonsArray){
	var buttons = document.createElement('div')
	buttons.setAttribute('class', 'buttons')
	
	for(var buttonElement in buttonsArray){
		buttons.appendChild(makeButton(buttonElement, buttonsArray[buttonElement]))
	}
	
	return buttons
}

function makeValueBar(name, text, id){
	var elementBar = document.createElement('div')
	elementBar.setAttribute('class', 'bar ' + name + '_bar')
	elementBar.onmouseover = function(){ showButtons(elementBar) }
	elementBar.onmouseout = function(){ hideButtons(elementBar) }
	
	var element = document.createElement('div')
	element.setAttribute('class', 'bar_element ' + name)
	element.onclick = function(){ editValue(name, elmentBar, id) }
	element.textContent = text
	elementBar.appendChild(element)
	
	var buttons = makeButtons({
		'delete'  : function(){ deleteValue(name, elementBar, id) },
		'up'      : function(){ moveValueUp(name, elementBar, id) },
		'down'    : function(){ moveValueDown(name, elementBar, id) },
		'edit'    : function(){ editValue(name, elementBar, id) }
	})
	
	elementBar.appendChild(buttons)
	
	return elementBar
}

function makeCategoryLabelBar(text, parentNodeId){
	var categoryLabelBar = document.createElement('div')
	categoryLabelBar.setAttribute('class', 'bar category_label_bar')
	categoryLabelBar.onmouseover = function(){ showButtons(categoryLabelBar) }
	categoryLabelBar.onmouseout = function(){ hideButtons(categoryLabelBar) }

	var categoryLabel = document.createElement('div')
	categoryLabel.setAttribute('class', 'bar_element category_label')
	categoryLabel.onclick = function(){ editCategoryLabel(categoryLabelBar, parentNodeId) }
	categoryLabel.textContent = text
	categoryLabelBar.appendChild(categoryLabel)
	
	var buttons = makeButtons({
		'delete' : function(){ deleteCategoryLabel(categoryLabelBar, parentNodeId) },
		'edit'   : function(){ editCategoryLabel(categoryLabelBar, parentNodeId) }
	})
	categoryLabelBar.appendChild(buttons)
	
	return categoryLabelBar
}

function makeContextBar(text, parentNodeId){
	var contextBar = document.createElement('div')
	contextBar.setAttribute('class', 'bar context_bar')
	contextBar.onmouseover = function(){ showButtons(contextBar) }
	contextBar.onmouseover = function(){ hideButtons(contextBar) }
	
	var context = document.createElement('div')
	context.setAttribute('class', 'bar_element context')
	context.onclick = function(){ editContext(contextBar, parentNodeId) }
	context.textContent = text
	contextBar.appendChild(context)
	
	var buttons = makeButtons({
		'delete' : function(){ deleteContext(categoryLabelBar, parentNodeId) },
		'edit'   : function(){ editContext(categoryLabelBar, parentNodeId) }
	})
	contextBar.appendChild(buttons)
	
	return contextBar
}

function makePronunciationBar(pronunciationText, pronunciationId){
	return makeValueBar('pronunciation', pronunciationText, pronunciationId)
	
	/*
	var pronunciationBar = document.createElement('div')
	pronunciationBar.setAttribute('class', 'bar pronunciation_bar')
	pronunciationBar.onmouseover = function(){ showButtons(pronunciationBar) }
	pronunciationBar.onmouseout = function(){ hideButtons(pronunciationBar) }
	
	var pronunciation = document.createElement('div')
	pronunciation.setAttribute('class', 'bar_element pronunciation')
	pronunciation.onclick = function(){ editPronunciation(pronunciationBar, pronunciationId) }
	pronunciation.textContent = text
	pronunciationBar.appendChild(pronunciation)
	
	var buttons = makeButtons({
		'delete'  : function(){ deletePronunciation(pronunciationBar, pronunciationId) },
		'up'      : function(){ movePronunciationUp(pronunciationBar, pronunciationId) },
		'down'    : function(){ movePronunciationDown(pronunciationBar, pronunciationId) },
		'edit'    : function(){ editPronunciation(pronunciationBar, pronunciationId) }
	})
	
	pronunciationBar.appendChild(buttons)
	
	return pronunciationBar
	*/
}

function makeTranslationBar(translationText, translationId){
	return makeValueBar('translation', translationText, translationId)
	
	/*
	var translationBar = document.createElement('div')
	translationBar.setAttribute('class', 'bar translation_bar')
	translationBar.onmouseover = function(){ showButtons(translationBar) }
	translationBar.onmouseout = function(){ hideButtons(translationBar) }
	
	var translation = document.createElement('div')
	translation.setAttribute('class', 'bar_element translation')
	translation.onclick = function(){ editTranslation(translationBar, translationId) }
	translation.textContent = text
	translationBar.appendChild(translation)
	
	var buttons = makeButtons({
		'delete'  : function(){ deleteTranslation(translationBar, translationId) },
		'up'      : function(){ moveTranslationUp(translationBar, translationId) },
		'down'    : function(){ moveTranslationDown(translationBar, translationId) },
		'edit'    : function(){ editTranslation(translationBar, translationId) }
	})
	
	translationBar.appendChild(buttons)
	
	return translationBar
	*/
}
