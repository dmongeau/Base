<h2>Changement de mot de passe</h2>

<form action="/connexion/changement-mot-de-passe.html?next=<?=rawurlencode(Bob::x('ne',$_REQUEST,'next'))?>" class="forgot" method="post">
    
    <?php if($this->hasErrors()) { ?>
    <div class="error"><?=$this->displayErrors()?></div>
    <?php } ?>

	<p>Veuillez choisir un nouveau mot de passe</p>
	
    <div class="spacer-small"></div>
    
    <div class="field">
    	<label>Nouveau mot de passe :</label>
        <input type="password" name="pwd" class="text" />
        <div class="clear"></div>
    </div>
    
    <div class="field">
    	<label>Confirmer votre mot de passe :</label>
        <input type="password" name="pwd2" class="text" />
        <div class="clear"></div>
    </div>
    
    <p class="field submit">
    	<button type="submit">Enregistrer</button>
    </p>
</form>