	
	function changesection() {
		
		function _(id) {
			return document.getElementById(id);
		}
		var xhr = new XMLHttpRequest;
		xhr.previous_text = '';
		_('movesectionprogress').max = 100;
		_('movesectionprogress').value = 0;
		_('movesectionstatus').innerHTML = "";
		_('movesectionexception').innerHTML = "";
		
		xhr.onerror = function() {
			_('movesectionstatus').innerHTML = "AJAX Fatal error.";
			
		};
		
		xhr.onreadystatechange = function() {
			var new_response = "";
			var response = {
				message: "",
				success: false,
				inloop: false,
				completed: false,
				progress: 0
			};
			
			try {
				console.log("readyState Change:",xhr.readyState);
				if ( xhr.readyState > 2 ) {
					console.log("xhr.responseText.length:",xhr.responseText.length);
					console.log("xhr.previous_text.length:",xhr.previous_text.length);
				}
				
				if (xhr.readyState > 2 && xhr.responseText.length > xhr.previous_text.length)
				{
					new_response = xhr.responseText.substring(xhr.previous_text.length);
					new_response = new_response.trim();
					if (new_response.length > 1)
					response = JSON.parse(new_response.trim());
					xhr.previous_text = xhr.responseText;
					
					console.log("readyState " + xhr.readyState + " : " + response.message,"Response");
					console.log("typeof response = ",typeof response);
					
				}
				
				if (xhr.readyState === 4){
					if (typeof response !== 'undefined'){
						if (response.success === true){
							_('movesectionstatus').innerHTML = "Success: " + response.success + "<br/>"
								+ "In Loop: " + response.inloop + "<br/>"
								+ "Completed: " + response.completed + "<br/>"
								+ "Message: " + response.message + "<br/>";
						}
						else {
							_('movesectionstatus').innerHTML = "Success: " + response.success + "<br/>"
								+ "Completed: " + response.completed + "<br/>"
								+ "Message: " + response.message + "<br/>";
						}
					}
					
					return false;
				}
				else if (xhr.readyState > 2 && typeof response !== 'undefined') {
					_('movesectionstatus').innerHTML = "Success: " + response.success + "<br/>"
						+ "Completed: " + response.completed + "<br/>"
						+ "Message: " + response.message + "<br/>";
						
					_('movesectionprogress').value = response.progress;
				}
			}
			catch (e) {
				console.log("onreadystatechange() Exception: " + e + "<br/>"
					+ "xhr.responseText: <" + xhr.responseText.substring(xhr.previous_text.length)+">");
					
				_('movesectionexception').innerHTML = "Fail: " + e + "<br/>\n"
					+ "Response: " + new_response + "<br/>\n"
					+ "Raw Input: " + xhr.responseText + "<br/>\n";
			}
		};
		
		var data = {};
		data.action = 'change-section';
		data.fromsection = _('fromsection').value;
		data.tosection = _('tosection').value;
		data.limit = _('limit').value;
		
		xhr.open('PUT','php/progress-ajax.php',true);
		xhr.setRequestHeader("Content-type", "application/json");
		xhr.send(JSON.stringify(data));
		
		return false;
	}
