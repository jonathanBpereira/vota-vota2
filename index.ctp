<?php echo $this->element('script'); ?>
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
<?php echo $this->element('menu'); ?>
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
						 <input type="checkbox" name="data[Votacaoe][respostas_id][]" value="<?php echo $v['id']; ?>"> <?php echo $v['resposta']; ?><br>
						<?php
					}
				}
				if(!isset($value['Enquete']['logado'])){
					echo $this->Form->submit('Votar!!!',array('class'=>'btn btn-lg btn-success'));
					echo $this->Form->end();
				}else{
					echo "<div class='alert alert-success'>Votado com sucesso!</div>";
					echo $this->Form->end();
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
					echo $this->Form->end();
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
					echo $this->Form->end();
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
					echo $this->Form->submit('Votar!!!',array('class'=>'btn btn-lg btn-success'));
					echo $this->Form->end();
				}else{
					echo "<div class='alert alert-success'>Votado com sucesso!</div>";
					echo $this->Form->end();
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
	<div class='row ranking vertical-center-row'>
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
					<?php 
					if(!empty($semana[0][0]['total'])){
						foreach($semana as $i=>$v){ ?>
						<div class='col-md-12 linhaRanking'>
							<div class='col-md-2 posicaoRanking'><?php echo $i; ?>°</div>
							<div class='col-md-2 imgRanking'>
								<img src="<?php echo Router::url('/', true); ?>img/<?php echo $v['nome']; ?>" class="imgLogo" alt="">
							</div>
							<div class='descricaoAmarelaRanking col-md-8'>
								<div class="col-md-6 nomeRanking"><?php echo $v['username']; ?></div>
								<div class="col-md-6 pontoRanking"><?php echo $v['total']; ?></div>
							</div>
						</div>
						<?php 
						}
					} ?>
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
		<div class="col-md-4" style="background-color:#51b5c5;border-radius:10px;height: 384px;">
			<div class='text-center tituloRankingMensal'>Ganhadores</div>
				<div style="padding-bottom: 20px;padding-top: 25px;">
					<div style="width: 55%;float: left;">
						<?php if(isset($perfil2)){ ?>
							<?php echo $this->Html->image($perfil[0]['Midia']['nome'],array('style'=>'height: 77px;','class'=>'rounder')); ?>
						<?php }else{
							echo $this->Html->image('no-photo-user.png',array('style'=>'height: 77px;','class'=>'rounder')); 
							} ?>
					</div>
					<div style="text-align: left;width: 45%;float: right;">
						<span class='nome'><?php echo isset($ganhadore[0]['User']['username'])?$ganhadore[0]['User']['username']:""; ?></span>
						<span class='vencedorsemana'>Vencedor do mês de</span>
						<span class='mesNome'><?php echo isset($ganhadore[0]['User']['created'])?$ganhadore[0]['User']['created']:""; ?></span>
						<br />
						<span class='nomePremio'>Prêmio: </span>
					</div>
				</div>	
				<?php if(isset($ganhadore[1]['User']['created'])){ ?>
				<div style="width: 100%;padding-top: 112px;">
					<div style="width: 55%;float: right;">
						<?php if(isset($perfil1[0]['Midia'])){ ?>
							<?php echo $this->Html->image($perfil1[0]['Midia']['nome'],array('style'=>'height: 77px;','class'=>'rounder')); ?>
						<?php }else{
							echo $this->Html->image('no-photo-user.png',array('style'=>'height: 77px;','class'=>'rounder')); 
							} ?>
					</div>
					<div style="text-align: right;width: 45%;float: left;">
						<span class='nome'><?php echo isset($ganhadore[1]['User']['username'])?$ganhadore[1]['User']['username']:""; ?></span>
						<span class='vencedorsemana'>Vencedor do mês de</span>
						<span class='mesNome'><?php echo isset($ganhadore[1]['User']['created'])?$ganhadore[1]['User']['created']:""; ?></span>
						<br />
						<span class="nomePremio">Prêmio: X-box one</span>
					</div>
				</div>
				<?php } ?>
			<?php //echo !empty($ads['Adsense']['public_ultima'])?$ads['Adsense']['public_ultima']:""; ?>
		</div>
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