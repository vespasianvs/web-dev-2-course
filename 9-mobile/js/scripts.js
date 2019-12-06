const COLOUR_KEY = '__BASIC_APP_COLOUR__';
const RX_EMAIL = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

var colourPages = [],
	colour      = localStorage[COLOUR_KEY] || 'teal';

function setupPageColour(page) {
	var $topbar = $(page).find('.app-topbar');
	colourPages.push($topbar);
	if (colour) {
		$topbar.addClass(colour);
	}
	
	$button = $(page).find('.colour-button');
	colourPages.push($button);
	if (colour) {
		$button.addClass(colour);
	}
}


App.controller('home', function (page) {
	setupPageColour(page);
	
	var arrAddress = [];
	var divContacts = $(page).find("#contacts");
	if (localStorage.getItem("addressBook")) arrAddress = JSON.parse(localStorage.getItem("addressBook"));
	
	arrAddress.forEach((value) => {
		var newElement = document.createElement("div");
		newElement.classList.add("app-button");
		newElement.innerHTML = value;
		newElement.setAttribute("data-email", value);
		divContacts.append(newElement);
		newElement.addEventListener('click', function (e) {
			var contact = { toEmail : $(this).data("email") };
			App.load("send_email", contact);
		});
	});
});

App.controller('send_email', function (page, contact) {
	setupPageColour(page)
	
	$(page).on('appLayout', function (e) {
		var pageHeight = $(e.target).offset().height;
		var formHeight = $(e.target).find("#frmEmail").offset().height;

		$(page).find("#txtBody").height(pageHeight-formHeight);
	});
	
	if(localStorage.getItem("fromEmail")) $(page).find("#txtFrom").val(localStorage.getItem("fromEmail"));
	if(contact.toEmail) $(page).find("#txtTo").val(contact.toEmail);
	
	$(page).find("#btnSend").on('click', sendEmail);
});

App.controller('settings', function (page) {
	setupPageColour(page)
	
	$(page).find('ul.colour-picker li')
		.clickable()
		.on('click', function () {
			var oldColour = colour;
			colour = $(this).data('colour');

			if (oldColour === colour) {
				return;
			}
			localStorage[COLOUR_KEY] = colour;

			for (var i=0; i<colourPages.length; i++) {
				if (oldColour) {
					colourPages[i].removeClass(oldColour);
				}
				if (colour) {
					colourPages[i].addClass(colour);
				}
			}
		});
	
	$(page).find("#txtDefaultFrom").on('change', function () {
		var newEmail = $(this).val();
		
		if (newEmail.length > 0 && RX_EMAIL.test(newEmail)) localStorage.setItem("fromEmail", newEmail);
	});
	
	$(page).find("#txtDefaultFrom").val(localStorage.getItem("fromEmail"));
})

function validateForm(frm) {
	var sError = "";
	
	if ($("#txtTo").val().length == 0 || !RX_EMAIL.test($("#txtTo").val())) sError += "Invalid 'To:' email address. ";
	if ($("#txtFrom").val().length == 0 || !RX_EMAIL.test($("#txtFrom").val())) sError += "Invalid 'From:' email address. ";
	if ($("#txtSubject").val().length == 0) sError += "Enter an email subject. ";
	if ($("#txtBody").val().length == 0) sError += "Enter an email message. ";
	
	if (sError.length > 0) {
		
		App.dialog({
			  title        : 'Error',
			  text         : sError,
			  okButton     : 'OK'
		});
		
		return false;
	}
	else return true;
}

function sendEmail() {
	
	if (validateForm()) {
		$.post("http://www.vespasianvs.co.uk/9-mobile/sendEmail.php", $("#frmEmail").serialize(), data => {
			if (JSON.parse(data).success) {
				
				var emailTo = $("#txtTo").val();
				var arrAddress = [];

				if (localStorage.getItem("addressBook")) arrAddress = JSON.parse(localStorage.getItem("addressBook"));
				
				if(!arrAddress.includes(emailTo)) arrAddress.push(emailTo);
				localStorage.setItem("addressBook", JSON.stringify(arrAddress));
				
				App.dialog({
					title        : 'Message Sent',
					text         : 'Your email was sent successfully.',
					okButton     : 'OK'
				}, function (tryAgain) {
					var emailFrom = $("#txtFrom").val();
					
					if (tryAgain) {
						if (!localStorage.getItem("fromEmail")) {
							App.dialog({
								title        : 'Set Default Email?',
								text         : "Set "+emailFrom+" as your default 'From:' address?",
								okButton     : 'Yes',
								cancelButton : 'No'
							}, function (tryAgain) {
								if (tryAgain) {
									localStorage.setItem("fromEmail", emailFrom);
								}
							});
						}
						App.load("home");
					}
				});				
			}
			else {
				App.dialog({
					  title        : 'Error',
					  text         : 'Could not send the email. Try again in a bit',
					  okButton     : 'Try Again',
					  cancelButton : 'Cancel'
				}, function (tryAgain) {
					  if (tryAgain=="ok") {
						sendEmail();
					  }
				});
			}
		 });
	}
}

try {
	App.restore();
} catch (err) {
	App.load('home');
}