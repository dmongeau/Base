<h2>Mot de passe oublié</h2>

<form action="/connexion/mot-de-passe-oublie.html" class="forgot" method="post">
    
    <?php if($this->hasErrors()) { ?>
    <div class="error"><?=$this->displayErrors()?></div>
    <?php } ?>

	<p>Veuillez entrer l'adresse courriel de votre compte et nous vous enverrons un lien qui vous permet de définir un nouveau mot de passe.</p>
	
    <div class="spacer-small"></div>
    
    <div class="field">
    	<label>Courriel :</label>
        <input type="text" name="email" class="text" />
        <div class="clear"></div>
    </div>
    
    <p class="field submit">
    	<button type="submit">Envoyer</button>
    </p>
</form>