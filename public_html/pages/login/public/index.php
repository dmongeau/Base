<form action="/connexion.html?next=<?=rawurlencode(Bob::x('ne',$_REQUEST,'next'))?>" class="login" method="post">
    <h3>Connexion</h3>
    
    <?php if($this->hasErrors()) { ?>
    <div class="error"><?=$this->displayErrors()?></div>
    <?php } ?>
    
    <?php if(isset($_REQUEST['newpwd']) && (int)$_REQUEST['newpwd'] == 1) { ?>
    <div class="success">Un mot de passe temporaire a été envoyé à votre adresse courriel.</div>
    <?php } ?>
    
    <?php if(isset($_REQUEST['closed']) && (int)$_REQUEST['closed'] == 1) { ?>
    <div class="success">Votre compte est maintenant fermé.</div>
    <?php } ?>
	
    <div class="field">
    	<label>Courriel :</label>
        <input type="text" name="email" class="text" style="width:250px;" />
        <div class="clear"></div>
    </div>
	
    <div class="field">
    	<label>Mot de passe :</label>
        <input type="password" name="pwd" class="text" style="width:250px;" />
        <div class="clear"></div>
    </div>
    
    <p class="field submit">
    	<button type="submit">Connexion</button>
        <span><a href="/connexion/mot-de-passe-oublie.html" class="forgot">Mot de passe oublié</a></span>
    </p>
</form>