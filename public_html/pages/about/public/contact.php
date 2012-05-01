<?php

	if($_POST) {
		
		if(!isset($_POST['name']) || empty($_POST['name'])) {
			$this->addError('Vous devez entrer votre nom');
		}
		
		if(!isset($_POST['email']) || !Zend_Validate::is($_POST['email'],'EmailAddress')) {
			$this->addError('Vous devez entrer une adresse courriel valide');
		}
		
		if(!isset($_POST['message']) || empty($_POST['message'])) {
			$this->addError('Vous devez entrer un message');
		}
		
		if(!$this->hasErrors()) {
			$subject = 'Message de '.$_SERVER['HTTP_HOST'];
			$to = $this->getConfig('mail.contact');
			$from = array($_POST['name']=>$_POST['email']);
			
			$message = 'Bonjour,'."\n";
			$message .= 'Un message a été envoyé à partir du formulaire contact du site http://'.$_SERVER['HTTP_HOST'].'.'."\n\n";
			$message .= '---'."\n";
			$message .= 'Nom : '.$_POST['name']."\n";
			$message .= 'Courriel : '.$_POST['email']."\n\n";
			$message .= $_POST['message']."\n";
			$message .= '---'."\n\n";
			$message .= 'Site web'."\n";
			
			$mail = $this->mail->create($subject,$to,$from);
			$mail->setBodyText($message);
			$mail->send();
			
			$success = true;
		}
		
	}


?>
<h2>Contact</h2>

<form action="/a-propos/contact.html" method="post">

	<?php if($this->hasErrors()) { ?>
    <div class="error">
    <p>Votre formulaire contient des erreurs:</p>
	<?=$this->displayErrors()?>
    </div>
    <?php } ?>
    
    <?php if(isset($success)) { ?>
    <div class="success">
    Votre message a été envoyé.
    </div>
    <?php } ?>

	<div class="field">
    	<label>Votre nom :</label>
        <input type="text" name="name" class="text" />
        <div class="clear"></div>
    </div>
    
    <div class="field">
    	<label>Votre adresse courriel :</label>
        <input type="text" name="email" class="text" />
        <div class="clear"></div>
    </div>
    
    <div class="field">
    	<label>Message :</label>
        <textarea name="message" style="height:150px;"></textarea>
        <div class="clear"></div>
    </div>
    
    <p>
    	<button type="submit">Envoyer</button>
    </p>


</form>