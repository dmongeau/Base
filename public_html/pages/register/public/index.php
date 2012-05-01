<form action="/inscription.html?next=<?=rawurlencode(Bob::x('ne',$_REQUEST,'next'))?>" class="login" method="post">
    <h3>Inscription</h3>
    
    <?php if($this->hasErrors()) { ?>
    <div class="error">
    <p>Votre formulaire contient des erreurs:</p>
	<?=$this->displayErrors(true,array('alwaysList'=>true))?>
    </div>
    <?php } ?>
    
    <div class="field">
    	<label>Courriel :</label>
        <input type="text" name="email" class="text" value="<?=Bob::x('ne',$_POST,'email')?>" />
        <div class="clear"></div>
    </div>
	
    <div class="field">
    	<label>Nom d'utilisateur :</label>
        <input type="text" name="username" class="text" value="<?=Bob::x('ne',$_POST,'username')?>" />
        <div class="clear"></div>
    </div>
	
    <div class="field">
    	<label>Mot de passe :</label>
        <input type="password" name="pwd" class="text" value="<?=Bob::x('ne',$_POST,'pwd')?>" />
        <div class="clear"></div>
    </div>
	
    <div class="field">
    	<label>Confirmez votre mot de passe :</label>
        <input type="password" name="pwd2" class="text" value="<?=Bob::x('ne',$_POST,'pwd2')?>" />
        <div class="clear"></div>
    </div>
    
    
    <div class="field">
        <label>Choisir une photo :</label>
        <input type="file" name="photo" />
        <div class="note">Format jpeg, png, gif. Maximum 10 mo.</div>
        <div class="clear"></div>
    </div>
    
    <div class="spacer-small"></div>
    
    <p class="field">
    	<button type="submit">Inscription</button>
    </p>
</form>