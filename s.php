<script>
jQuery(function($) {
$('input[type="checkbox"]').click(function(){
		console.log('a');
			var atual = this;
			var form = $(this).parents('form');
			var total = $(this).parents('form').attr('numb');
			if($('input[type="checkbox"]:checked').length > parseInt(total)){
				$(atual).attr('checked',false);
			}
		});
function setaWidthHeightIframe(){
	$('iframe').attr('style','width:90%;').attr('height','260');
}
setaWidthHeightIframe();
function loginfacebook(){
	$('#login_facebook').click(function(){
		$.ajax({
			url:urlBase+"Ajax/sdk",
			dataType:"JSON",
			type:"POST",
			complete:function(r){
				console.log('Log para sdk');
				token = r.responseJSON['Sdk']['facebook'];
				sdkf(token);
			}
		});
	});
}
loginfacebook();
function submeteCadastroFb(){
	$('#cadastroemailfacebook').submit(function(e){
		e.preventDefault();
		var email = $('.emailcadastro').val();
		$('.emailcadastro').attr('disabled',true);
		$('.submitemailfag').attr("disabled",true);
		$.ajax({
			url:urlBase+"Ajax/emailfacebook",
			type:"POST",
			dataType:"JSON",
			data:{email:email},
			complete:function(r){
				if(r.responseJSON == 'deu'){
					$('#modalemail').modal('hide');
					$('.loginClick a').attr('href',urlBase+"User/index").text(email);
				}
			}
		});
	});
}
submeteCadastroFb();
function cadastraUsuario(nome,email,fbid,picture){
	$.ajax({
		url:urlBase+"Ajax/cadastralogafacebook",
		dataType:"JSON",
		data:{
			nome:nome,
			email:email,
			idfb:fbid,
			picture:picture
		},
		type:"POST",
		complete:function(r){
			if(r.responseJSON[0] == 'email'){
				$('#modalemail').modal({
					show:true,
					backdrop:'static'
				});
			}
			if(r.responseJSON[0] == 'nada'){
				$('#modalemail').modal('hide');
				$('.modal.log').modal('hide');
				var html = '<a href="<?php echo Router::url('/', true); ?>User"><i class="glyphicon glyphicon-plus plusProfile"></i></a>';
				html +='<img src="'+picture+'" class="rounder" style="width:50px;height:50px;margin-top:-2px;margin-right: 10px;" alt="">';
				$('.fotologin').html(html);
			}
		}
	});
}
function sdkf(token){
	console.log('Começou sdk');
	  $.ajaxSetup({ cache: true });
	  $.getScript('//connect.facebook.net/en_US/sdk.js', function(){
	    FB.init({
	      appId: token,
	      version: 'v2.9' // or v2.1, v2.2, v2.3, ...
	    });     
	    $('#loginbutton,#feedbutton').removeAttr('disabled');
	    FB.getLoginStatus(function(response) {
		  if (response.status === 'connected') {
		    console.log('conectado');
		    var userID = response.authResponse.userID;
		  	FB.api('/me',{fields: "id,about,age_range,picture,birthday,context,email,first_name,gender,hometown,link,location,middle_name,name,timezone,website,work"},function(response){
		  		cadastraUsuario(response.name,response.email,response.id,response.picture.data.url);
		  	});
		  	$('.modal').modal('hide');
		  }
		  else {
		  	console.log('Abre moodal login não conectado');
		    FB.login(function(response) {
			    if (response.authResponse) {
			    var userID = response.authResponse.userID;
			     console.log('Logado com sucesso');
			     FB.api('/me',{fields: "id,about,age_range,picture,birthday,context,email,first_name,gender,hometown,link,location,middle_name,name,timezone,website,work"}, function(response) {
		  			cadastraUsuario(response.name,response.email,response.id,response.picture.data.url);
		  			$('.modal').modal('hide');
			     });
			    } else {
			     console.log('Login não autorizado');
			    }
			},{scope:'email,public_profile'});
		  }
		});
	  });
}
function cadastrarLink(votacao){
	$('#cadastrarLink').click(function(r){
	var email = $('#loginemail').val();
	var senha = $('#loginsenha').val();
	if(email.length > 10 && senha.length >0){
		$('#loginsenha').attr('disabled',true);
		$('#loginemail').attr('disabled',true);
		var contador = 0;
			r.preventDefault();
			$.ajax({
				url:urlBase+"Ajax/cadastro",
				dataType:'JSON',
				type:"POST",
				data:{email:email,senha:senha},
				complete:function(r){
					$('#loginemail').val('');
					$('#loginsenha').val('');
					if(r.responseJSON == 'sucesso'){
						$('.loginClick a').text(email).attr("href",urlBase+'User');
						$('.modal').modal('hide');
						enviaVotacao(votacao);
					}else{
						$('#loginemail').val('').attr('disabled',false);
						$('#loginsenha').val('').attr('disabled',false);
						$('.email-cadstradi').fadeIn().delay(2000).fadeOut();
						$(votacao).find('input').attr('disabled',false);
					}
				}
			});
		}else{
			$('.alertalogin').fadeIn(200).delay(2000).fadeOut();
		}
	});
}
	function enviaVotacao(votacao){
		console.log('enviando..');
		var formulario = votacao;
		votacao = $(votacao).serialize();
		$.ajax({
			url:urlBase+"Ajax/enviavotacao",
			type:"POST",
			dataType:"JSON",
			data:votacao,
			complete:function(r){
				if(r.responseJSON == 'sucesso'){
					$(formulario).find('.submit input').fadeOut().delay(400).remove();
					$(formulario).find('input,select,textarea').prop("disabled", true).val('');
					$(formulario).append($("<div class='alert alert-success'>Votado com sucesso!</div>").fadeIn());
				}
			}
		});
	}
	function enviaDados(votacao,dados){
		console.log('envia dados..');
		$.ajax({
				url:urlBase+"Ajax/logar",
				type:"POST",
				dataType:"JSON",
				data:{
					dados:dados
				},
				complete:function(r){
					$('.login2').val('').attr('disabled',false);
					$('.senha').val('').attr('disabled',false);
					$('.submitlogin').val('Login!').attr('disabled',false);
					if(r.responseJSON == 'erro'){
						$('#sucesso').hide();
						$('#erro').fadeIn();
					}else{
						$('#sucesso').fadeIn();
						$('#erro').hide();
						$('.modal.log').modal('hide');
						$('.loginClick a').attr('href',urlBase+'User/index').text(r.responseJSON['User']['email']);
						var html = '<a href="<?php echo Router::url('/', true); ?>User"><i class="glyphicon glyphicon-plus plusProfile"></i></a>';
						html +='<img src="<?php echo Router::url('/', true); ?>img/'+r.responseJSON['Midia']['nome']+'" class="rounder" style="width:50px;height:50px;margin-top:-2px;margin-right: 10px;" alt="">';
						$('.fotologin').html(html);
						enviaVotacao(votacao);
					}
				}
			});
	}
	function formlogin(votacao){
		$('#formlogin').submit(function(e){
			e.preventDefault();
			var dados = $(this).serialize();
			$('.login2').attr('disabled',true);
			$('.senha').attr('disabled',true);
			$('.submitlogin').val('Logando..').attr('disabled',true);
			enviaDados(votacao,dados);
			/*if($('a .loginClick').text() == ''){
				$('#modalemail').modal({
					show:true,
					backdrop:'static'
				});
			}
			*/
		});
	}
	function verificaLogin(votacao){
		var logado = false;
		$.ajax({
			url:urlBase+"Ajax/verificalogin",
			type:"POST",
			dataType:"JSON",
			complete:function(r){
				console.log(r);
				if(r.responseJSON == 'erro'){
					console.log('Nao Logado..');
					//SE DER ERRO ABRE O MODAL PEDINDO O LOGIN
					$('.modal.log').modal();
					formlogin(votacao);
					cadastrarLink(votacao);
				}else{
					//SE TIVER LOGADO
					if($(r.responseJSON).length >0){
						console.log(r.responseJSON);
						console.log('Logado..');
						$('.loginClick a').attr('href',urlBase+'User/index').text(r.responseJSON['email']);
						//ENVIA 
						enviaDados(votacao);
					}
				}			}
		});
	}
	function votar(){
		$('.formvotar').submit(function(e){
			var votacao = this;
			e.preventDefault();
			$(votacao).find('input[type=submit]').attr('disabled',true);
			verificaLogin(votacao);
		});
	}
	votar();
});
</script>
<div class="modal log fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content text-center" style='padding:10px;'>
      	<button class='btn btn-primary' id='login_facebook' style="width:100%;">Login com Facebook</button>
      		<div class="alert alert-warning email-cadstradi" style='display:none;'>E-mail já existente</div>
      		Ou
      		<br />
      		Logue-se normalmente:<br />
      		<form name='login' method="POST" id='formlogin'>
      			<div class='alertalogin alert alert-warning' style='display:none;'>Login e senha incorretos</div>
      			<input type='email' required name='login2' id='loginemail' class='login2 form-control' placeholder='Apelido ou e-mail' />
      			<input type='password' id='loginsenha' required name='senha' class='senha form-control' placeholder='Password' />
      			<br />
      			<a class='btn btn-primary' href='#' id='cadastrarLink'>Cadastrar</a>
      			<input type='submit' class='btn btn-success submitlogin' value="Login!" />
      			<div style='display:none;' id='erro'>
      				<div class='alert alert-danger'>
      					Login não encontrado
      				</div>
      			</div>
      			<div style='display:none;' id='sucesso'>
      				<div class='alert alert-success'>
      					Login feito com sucesso!
      				</div>
      			</div>
      		</form>
    </div>
  </div>
</div>

<div class="modal fade bs-example-modal-sm" id='modalemail' tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content text-center" style='padding:10px;'>
      		Cadastre um e-mail:<br />
      		<form name='login' method="POST" id='cadastroemailfacebook'>
      			<input type='email' required name='email' class='emailcadastro form-control' placeholder='E-mail' />
      			<br />
      			<input type='submit' class='btn btn-success submitemailfag' value="Cadastrar" />
      			<div style='display:none;' id='erro'>
      				<div class='alert alert-danger'>
      					Login não encontrado
      				</div>
      			</div>
      			<div style='display:none;' id='sucesso'>
      				<div class='alert alert-success'>
      					Login feito com sucesso!
      				</div>
      			</div>
      		</form>
    </div>
  </div>
</div>
<div class='menuOnline'>
	<div class="col-md-4">
		<?php echo $this->Html->image('vota-vota.jpg',array('class'=>'img-responsive imgLogo')); ?>
	</div>
	<div class='loginClick col-md-4'>
	<?php 
	$user = $this->Session->check('Auth.User');
	?>
	<div class="pontosTopo col-md-8 col-md-offset-3">
		Sua pontuação<br />
		<div class="pontosHome">
			<div class='pull-left placardivisa'>
				<div class="Pontos"><?php echo !empty($totalSemanal)?$totalSemanal:"0"; ?></div>
				<div class="tempo">Semanal</div>
			</div>
			<div class='pull-right'>
				<div class="Pontos"><?php echo !empty($totalMensal)?$totalMensal:"0"; ?></div>
				<div class="tempo">Mensal</div>
			</div>
		</div>
	</div>
	
	</div>
	<div class="col-md-4 text-right fotologin">
		<?php if(!empty($foto_perfil)){ ?>
			<?php echo $this->Html->link('<i class="glyphicon glyphicon-plus plusProfile"></i>','/User/index',array('escape'=>false)); ?>
			<?php echo $this->Html->image($foto_perfil['Midia']['nome'],array('class'=>'rounder','style'=>'width:50px;height:50px;margin-top:-2px;margin-right: 10px;')); ?>
		<?php }else{
			echo $this->Html->link('Logar','/Login',array('class'=>'pontosTopo'));
		} ?>
	</div>
</div>
<div class='col-md-12'>
	<div class='ads-left'><?php echo !empty($ads['Adsense']['public_esquerda'])?$ads['Adsense']['public_esquerda']:""; ?></div>
	<div class='row text-center'>
	<?php
	foreach($enquetes as $index=>$value){
		$img = $this->Html->image($value['Midia']['nome'],array('class'=>'img-responsive imgfundoenquete'));
		$enqueteTitulo = $value['Enquete']['nome'];
		$enqueteId = $value['Enquete']['id'];
		$enqueteDescricao = $value['Enquete']['descricao'];
		$pergunta = $value['Pergunta']['pergunta'];
		$perguntaId = $value['Pergunta']['id'];
		$tipopergunta = $value['Pergunta']['tipo'];
		$resposta = $value['Resposta'];
		?>
		<div class='col-md-12 enquete'>
			<div style="height: 300px;">
				<?php if(!empty($value['Enquete']['video'])){ ?>
				<div style="z-index:9;width:75%;position:absolute;margin-top: 20px;    margin-left: 11%;"><?php echo $value['Enquete']['video']; ?>
				</div>
			<?php } ?>
			</div>
			<div class="enquete_titulo">
				<p class='col-md-10'>
					<strong><?php echo $enqueteTitulo; ?></strong><br />
					<?php echo $enqueteDescricao; ?>
				</p>
				<p class='col-md-2 pontuacao'>
					Vale até<br />
					<span class="pontos">5</span><br />
					pontos
				</p>
			</div>
			<div>
				<?php echo $img; ?>
			</div>
			<div class="enquete_pergunta">
			<h1><?php echo $pergunta; ?></h1>
			<br />
			<?php
			if($tipopergunta == 'fechada' || $tipopergunta == 'menosvotada' || $tipopergunta == 'maisvotada' || $tipopergunta == 'combinacaocerta' || $tipopergunta == 'opcaounica'){
				$combinacaoNumber = 0;
				if($tipopergunta == 'combinacaocerta'){
					foreach($resposta as $i=>$v){
						if($v['nota'] >0 and $v['nota'] != null and $v['nota'] != ''){
							$combinacaoNumber++;
						}
					}
				}
				if($tipopergunta != 'combinacaocerta'){
					echo "<form method='POST' name='formvotar' class='formvotar'>";
				}else{
					echo "<form method='POST' name='formvotar' numb='".$combinacaoNumber."' class='formvotar'>";
				}
				echo $this->Form->hidden('Votacoes.tipo',array('value'=>$tipopergunta));
				echo $this->Form->hidden('Votacoes.enquetes_id',array('class'=>'input_enquete_id','value'=>$enqueteId));
				echo $this->Form->hidden('Votacoes.perguntas_id',array('class'=>'input_perguntas_id','value'=>$perguntaId));
				?>
				<div class='text-left'>
				<?php
				if($tipopergunta != 'combinacaocerta'){
					foreach($resposta as $i=>$v){
						?>
						 <input type="radio" name="data[Votacaoe][respostas_id]" value="<?php echo $v['id']; ?>"> <?php echo $v['resposta']; ?><br>
						<?php
					}
				}else{
					foreach($resposta as $i=>$v){
						?>
						 <input type="checkbox" name="data[Votacaoe][respostas_id]" value="<?php echo $v['id']; ?>"> <?php echo $v['resposta']; ?><br>
						<?php
					}
				}
				if(!isset($value['Enquete']['logado'])){
					echo $this->Form->submit('Votar!!!',array('class'=>'btn btn-lg btn-success'));
					echo $this->Form->end();
				}else{
					echo "<div class='alert alert-success'>Votado com sucesso!</div>";
				}
				?>
				</div>
				<?php
			}else if($tipopergunta == 'botao'){
				echo "<form method='POST' name='formvotar' class='formvotar'>";
				echo $this->Form->hidden('Votacoes.tipo',array('value'=>$tipopergunta));
				echo $this->Form->hidden('Votacoes.enquetes_id',array('class'=>'input_enquete_id','value'=>$enqueteId));
				echo $this->Form->hidden('Votacoes.perguntas_id',array('class'=>'input_perguntas_id','value'=>$perguntaId));
				if(!isset($value['Enquete']['logado'])){
					foreach($value['Botoe'] as $i=>$v){
						echo $this->Form->hidden('Votacoes.botao_id',array('value'=>$v['id']));
						echo $this->Form->submit($v['label'],array('class'=>'btn btn-lg btn-success'));
					}

					echo $this->Form->end();
				}else{
					echo "<div class='alert alert-success'>Votado com sucesso!</div>";
				}
			}else if($tipopergunta == 'palavras'){
				echo "<form method='POST' name='formvotar' class='formvotar'>";
				echo $this->Form->hidden('Votacoes.enquetes_id',array('class'=>'input_enquete_id','value'=>$enqueteId));
				echo $this->Form->hidden('Votacoes.tipo',array('value'=>$tipopergunta));
				echo $this->Form->hidden('Votacoes.perguntas_id',array('class'=>'input_perguntas_id','value'=>$perguntaId));
				echo $this->Form->input('Votacoes.resposta_aberta',array('type'=>'text','class'=>'form-control'));
				echo '<br />';
				if(!isset($value['Enquete']['logado'])){
					echo $this->Form->submit('Votar!!!',array('class'=>'btn btn-lg btn-success'));
					echo $this->Form->end();
				}else{
					echo "<div class='alert alert-success'>Votado com sucesso!</div>";
				}
			}else if($tipopergunta == 'aberta'){
				echo "<form method='POST' name='formvotar' class='formvotar'>";
				echo $this->Form->hidden('Votacoes.enquetes_id',array('class'=>'input_enquete_id','value'=>$enqueteId));
				echo $this->Form->hidden('Votacoes.respostas_id',array('value'=>$resposta[0]['id']));
				echo $this->Form->hidden('Votacoes.pontos',array('value'=>$resposta[0]['nota']));
				echo $this->Form->hidden('Votacoes.tipo',array('value'=>$tipopergunta));
				echo $this->Form->hidden('Votacoes.perguntas_id',array('class'=>'input_perguntas_id','value'=>$perguntaId));
				echo $this->Form->input('Votacoes.resposta_aberta',array('type'=>'textarea','class'=>'form-control'));
				echo '<br />';
				if(!isset($value['Enquete']['logado'])){
					?>
					<div class="submit">
						<input class="btn btn-lg btn-success" type="submit" value="Votar!!!">
					</div>
					</form>
					<?php
				}
			}
			?>
			</div>
			<hr />
		</div>
		<?php
	}
	?>
	</div>
	<div class='ads-right'><?php echo !empty($ads['Adsense']['public_direita'])?$ads['Adsense']['public_direita']:""; ?></div>
	<div class='row ads-center'>
		<div>
			<?php echo !empty($ads['Adsense']['public_baixo_enquete'])?$ads['Adsense']['public_baixo_enquete']:""; ?>
		</div>
	</div>
	<div class='row ranking'>
		<div class='col-md-4 borderleft'>
			<?php echo isset($ranking[0]['Midia']['nome'])?$this->Html->image($ranking[0]['Midia']['nome'],array('class'=>'img-responsive')):""; ?>
		</div>
		<div class='col-md-4 borderleft'>
			<?php echo isset($ranking[0]['Midia']['nome'])?$this->Html->image($ranking[1]['Midia']['nome'],array('class'=>'img-responsive')):""; ?>
		</div>
		<div class='col-md-4 borderleft'>
			<?php echo isset($ranking[0]['Midia']['nome'])?$this->Html->image($ranking[2]['Midia']['nome'],array('class'=>'img-responsive')):""; ?>
		</div>
	</div>
	<div class='row ranking'>
		<div class="col-md-8">
		<?php echo !empty($enquete_passada['Midia']['nome'])?$this->Html->link($this->Html->image($enquete_passada['Midia']['nome'],array('class'=>'img-responsive')),'enquete_antigas',array('escape'=>false)):""; ?>
		</div>
		<div class="col-md-4"><?php echo !empty($ads['Adsense']['public_ultima'])?$ads['Adsense']['public_ultima']:""; ?></div>
	</div>
	<div class='row ranking'>
		<div class="col-md-8">
			<div class="col-md-6 rankings">
				<div class="quadradoRanking" style="background-color:#ffad05;">
					<div class='text-center tituloRankingSemanal'>Ranking Semanal</div>
				</div>
				<div style="background-color:#f4de64;" class="quadradoMaiorRanking">
				</div>
			</div>
			<div class="col-md-6 rankings">
				<div class="quadradoRanking" style="background-color:#b80000;">
					<div class='text-center tituloRankingMensal'>Ranking Mensal</div>
				</div>
				<div style="background-color:#e04e27;" class="quadradoMaiorRanking">
				</div>
			</div>
		</div>
		<div class="col-md-4" style="background-color:#51b5c5;border-radius:10px;">
		<?php echo !empty($ads['Adsense']['public_ultima'])?$ads['Adsense']['public_ultima']:""; ?></div>
	</div>
</div>
<?php 
$email = $this->Session->read('Auth.User');
if(empty($email['email']) and isset($email)){
	?>
	<script>
		$('#modalemail').modal({
		show:true,
		backdrop:'static'
		});
	</script>
	<?php	                
}
?>