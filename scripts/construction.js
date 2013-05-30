var localization = new Object();

localization.texts = {
	'delete':'usuń',
	'up':'do góry',
	'down':'na dół',
	'edit':'edytuj',
}

localization.get_text = function(label){
	if(this.texts && this.texts[label]){
		return this.texts[label]
	} else {
		return '[[NO TRANSLATION]]'
	}
}

function makeCategoryLabelBar(text, parentNodeId){
	
	var categoryLabelBar = document.createElement('div')
	categoryLabelBar.setAttribute('class', 'category_label_bar')
	categoryLabelBar.onmouseover = function(){ showButtons(categoryLabelBar) }
	categoryLabelBar.onmouseout = function(){ hideButtons(categoryLabelBar) }

	var categoryLabel = document.createElement('div')
	categoryLabel.setAttribute('class', 'category_label')
	categoryLabel.onclick = function(){ editCategoryLabel(categoryLabelBar, parentNodeId) }
	categoryLabel.textContent = text
	categoryLabelBar.appendChild(categoryLabel)
	
	var buttons = document.createElement('div')
	buttons.setAttribute('class', 'buttons')
	categoryLabelBar.appendChild(buttons)
	
	var buttonDelete = document.createElement('button')
	buttonDelete.setAttribute('class', 'button delete')
	buttonDelete.onclick = function(){ deleteTranslation(translationBar, translationId) }
	buttonDelete.textContent = localization.get_text('delete')
	buttons.appendChild(buttonDelete)
	
	var buttonEdit = document.createElement('button')
	buttonEdit.setAttribute('class', 'button edit')
	buttonEdit.onclick = function(){ editTranslation(translationBar, translationId) }
	buttonEdit.textContent = localization.get_text('edit')
	buttons.appendChild(buttonEdit)
	
	return categoryLabelBar
}

function makeTranslationBar(text, translationId){

	var translationBar = document.createElement('div')
	translationBar.setAttribute('class', 'translation_bar')
	translationBar.onmouseover = function(){ showButtons(translationBar) }
	translationBar.onmouseout = function(){ hideButtons(translationBar) }
	
	var translation = document.createElement('div')
	translation.setAttribute('class', 'translation')
	translation.onclick = function(){ editTranslation(translationBar, translationId) }
	translation.textContent = text
	translationBar.appendChild(translation)
	
	var buttons = document.createElement('div')
	buttons.setAttribute('class', 'buttons')
	translationBar.appendChild(buttons)
	
	var buttonDelete = document.createElement('button')
	buttonDelete.setAttribute('class', 'button delete')
	buttonDelete.onclick = function(){ deleteTranslation(translationBar, translationId) }
	buttonDelete.textContent = localization.get_text('delete')
	buttons.appendChild(buttonDelete)
	
	var buttonUp = document.createElement('button')
	buttonUp.setAttribute('class', 'button move_up')
	buttonUp.onclick = function(){ moveTranslationUp(translationBar, translationId) }
	buttonUp.textContent = localization.get_text('up')
	buttons.appendChild(buttonUp)
	
	var buttonDown = document.createElement('button')
	buttonDown.setAttribute('class', 'button move_down')
	buttonDown.onclick = function(){ moveTranslationDown(translationBar, translationId) }
	buttonDown.textContent = localization.get_text('down')
	buttons.appendChild(buttonDown)
	
	var buttonEdit = document.createElement('button')
	buttonEdit.setAttribute('class', 'button edit')
	buttonEdit.onclick = function(){ editTranslation(translationBar, translationId) }
	buttonEdit.textContent = localization.get_text('edit')
	buttons.appendChild(buttonEdit)
	
	return translationBar
}