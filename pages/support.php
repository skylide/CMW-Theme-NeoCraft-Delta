<div class="neo-content-headline">
	<div class="neo-line-headline" style="width: 100%"><h2><em><strong><span style="font-size:40px"><i class="fa fa-user-md"></i> Support communautaire</span></strong></em></h2></div>
</div>

	<div class="neo-container neo-responsive neo-radius neo-padding-16">
		<h2 class="header-bloc">Gestion des signalements</h2>
			<div class="corp-bloc">

				<table class="neo-table neo-striped neo-bordered neo-hoverable">

					<thead>
						<tr class="neo-light-grey">
							<?php if($_Joueur_['rang'] == 1 OR $_PGrades_['PermsDefault']['support']['displayTicket'] == true) { echo '<th style="text-align: center;">Visuel</th>'; } ?>
							<th style="text-align: center;">Pseudo</th>
							<th style="text-align: center;">Titre</th>
							<th style="text-align: center;">Date</th>
							<th style="text-align: center;">Action</th>
                            <th style="text-align: center;">Status </th>
							<?php if($_Joueur_['rang'] == 1 OR $_PGrades_['PermsDefault']['support']['closeTicket'] == true) { echo '<th style="text-align: center;">Modification</th>'; } ?>
						</tr>
					</thead>
					<tbody>
					<?php $j = 0;
					while($tickets = $ticketReq->fetch()) { ?>
						<tr class="neo-white">
						    <?php if($tickets['ticketDisplay'] == 0 OR $tickets['auteur'] == $_Joueur_['pseudo'] OR $_Joueur_['rang'] == 1 OR $_PGrades_['PermsDefault']['support']['displayTicket'] == true) {
						    if($_Joueur_['rang'] == 1 OR $_PGrades_['PermsDefault']['support']['displayTicket'] == true) { ?>
						    <td class="neo-center-simple">
						        <?php if($tickets['ticketDisplay'] == "0") {
						                echo '<span>Public</span>';
						            } else {
								        echo '<span >Privé</span>';
								} ?>
							</td>
							<?php } ?>

							<td class="neo-center-simple">
								<a href="index.php?&page=profil&profil=<?php echo $tickets['auteur'] ?>"><img class="icon-player-topbar" src="https://cravatar.eu/head/<?php echo $tickets['auteur']; ?>/32" /> <?php echo $tickets['auteur'] ?></a>
							</td>
						
							<td class="neo-center-simple">
								<?php echo $tickets['titre'] ?>​
							</td>
						
							<td class="neo-center-simple">
								<?php echo $tickets['jour']. '/' .$tickets['mois']. ' à ' .$tickets['heure']. ':' .$tickets['minute']; ?>
							</td>
						
							<td class="neo-center-simple">
								 <a class="neo-button neo-green" onclick="document.getElementById('<?php echo $tickets['id']; ?>Slide').style.display='block'">
									Voir <i class="fa fa-eye"></i>
								</a>
							</td>
                            
                            <td class="neo-center-simple">
                                <?php
                                    $ticketstatus = $tickets['etat'];
                                    if($ticketstatus == "1"){
                                        echo '<button class="neo-button neo-green">Résolu <i class="fas fa-check"></i></button>';
                                    } else {
                                        echo '<button class="neo-button neo-red">Non Résolu <i class="fas fa-times"></i></button>';
                                    }
                                ?>
                            </td>

							<?php if($_Joueur_['rang'] == 1 OR $_PGrades_['PermsDefault']['support']['closeTicket'] == true) { ?>
								<td class="neo-center-simple">
									<form class="form-horizontal default-form" method="post" action="?&action=ticketEtat&id=<?php echo $tickets['id']; ?>">
										<?php if($tickets['etat'] == 0){ 
											echo '<button type="submit" name="etat" class="neo-button neo-red" value="1" />Fermer le ticket</button>';
										}else{
											echo '<button type="submit" name="etat" class="neo-button neo-red" value="0" />Ouvrir le ticket</button>';
										} ?>
									</form>
								</td>
							<?php }
							} ?>
						</tr>
						
					<?php if($tickets['ticketDisplay'] == "0" OR $tickets['auteur'] == $_Joueur_['pseudo'] OR $_Joueur_['rang'] == 1 OR $_PGrades_['PermsDefault']['support']['displayTicket'] == true) { ?>
					<!-- Modal -->
					
					<div class="neo-modal " id="<?php echo $tickets['id']; ?>Slide" style="z-index:53;">
						<div class="neo-modal-content neo-animate-zoom"  style="background-color: rgba(51, 51, 51, 0.5);color:#FFF;width:40%">
							<div class="neo-container">
								<span  onclick="document.getElementById('<?php echo $tickets['id']; ?>Slide').style.display='none'" class="neo-button neo-display-topright">&times;</span>
																
									<div class="neo-panel ">
										<h2 style="text-align:center;;">  <?php echo $tickets['titre']; ?>  <?php $ticketstatus = $tickets['etat']; if($ticketstatus == "1"){ echo ' | Résolu !'; } else { echo ''; } ?></h2>
											<div class="neo-container"  >
												<p class="neo-center-simple neo-padding-16">
													<ul class="neo-ul" style="width:100%;">
														<?php 
														unset($message);
														$message = espacement($tickets['message']);
														$message = BBCode($message, $bddConnection);
														echo $message; ?>
														<p class="text-right">Ticket de : <img src="https://cravatar.eu/avatar/<?php echo $tickets['auteur']; ?>/16" alt="none" /> <?php echo $tickets['auteur']; ?></p>
														</br>
														<hr>
														<?php
														$commentaires = 0;
														if(isset($ticketCommentaires[$tickets['id']]))
														{
															echo '<h3 class="ticket-commentaire-titre"><center>' .count($ticketCommentaires[$tickets['id']]). ' Commentaires</center></h3>';
															for($i = 0; $i < count($ticketCommentaires[$tickets['id']]); $i++)
															{
																$get_idComm = $bddConnection->prepare('SELECT id FROM cmw_support_commentaires WHERE auteur LIKE :auteur AND id_ticket LIKE :id_ticket');
																$get_idComm->bindParam(':auteur', $ticketCommentaires[$tickets['id']][$i]['auteur']);
																$get_idComm->bindParam(':id_ticket', $tickets['id']);
																$get_idComm->execute();
																$req_idComm = $get_idComm->fetch();
														?>
														<div style="margin-bottom:10px; margin-top:10px;" class="panel panel-default">
															<div class="panel-body">
																<div class="ticket-commentaire">
																<div class="left-ticket-commentaire">
																	<span class="img-ticket-commentaire"><img src="https://cravatar.eu/head/<?php echo $ticketCommentaires[$tickets['id']][$i]['auteur']; ?>/32" alt="none" /></span>
																	<span class="desc-ticket-commentaire">
																		<span class="ticket-commentaire-auteur"><?php echo $ticketCommentaires[$tickets['id']][$i]['auteur']; ?></span>
																		<span class="ticket-commentaire-date"><?php echo 'Le ' .$ticketCommentaires[$tickets['id']][$i]['jour']. '/' .$ticketCommentaires[$tickets['id']][$i]['mois']. ' à ' .$ticketCommentaires[$tickets['id']][$i]['heure']. ':' .$ticketCommentaires[$tickets['id']][$i]['minute']; ?></span>
																		<?php if(isset($_Joueur_) && (($ticketCommentaires[$tickets['id']][$i]['auteur'] == $_Joueur_['pseudo'] OR $_Joueur_['rang'] == 1 OR $_PGrades_['PermsDefault']['support']['deleteMemberComm'] == true) OR ($ticketCommentaires[$tickets['id']][$i]['auteur'] == $_Joueur_['pseudo'] OR $_Joueur_['rang'] == 1 OR $_PGrades_['PermsDefault']['support']['editMemberComm'] == true))) { ?>
																			 <span class="dropdown" style="padding-left: 40%">
																				<div  style="float:right;" class="neo-margin-left-1 neo-float-right neo-dropdown-hover">
																					 <button class="neo-transforme-1 neo-button neo-green hvr-bounce-in" ">Action <b class="caret"></b></button>
																					  <div class="neo-dropdown-content neo-bar-block neo-border">
																						<?php if($ticketCommentaires[$tickets['id']][$i]['auteur'] == $_Joueur_['pseudo'] OR $_Joueur_['rang'] == 1 OR $_PGrades_['PermsDefault']['support']['deleteMemberComm'] == true) {
																							echo '<a class="neo-bar-item hvr-forward neo-button" href="?&action=delete_support_commentaire&id_comm='.$req_idComm['id'].'&id_ticket='.$tickets['id'].'&auteur='.$ticketCommentaires[$tickets['id']][$i]['auteur'].'">Supprimer</a>';
																						} if($ticketCommentaires[$tickets['id']][$i]['auteur'] == $_Joueur_['pseudo'] OR $_Joueur_['rang'] == 1 OR $_PGrades_['PermsDefault']['support']['editMemberComm'] == true) {
																							echo '<a class="hvr-forward neo-bar-item neo-button" href="#editComm-'.$req_idComm['id'].'" onclick="document.getElementById(\'editComm-'.$req_idComm['id'].'\').style.display=\'block\'" >Editer</a>';
																						}?>
																						</div>
																				</div>
																					
																			 </span>
																		<?php } ?>
																	</span>
																	
																</div>
																<div class="right-ticket-commentaire"><div style="text-overflow: clip; word-wrap: break-word;">
																	<?php unset($message);
																	$message = espacement($ticketCommentaires[$tickets['id']][$i]['message']);
																	$message = BBCode($message, $bddConnection);
																	echo $message;  ?></div>
																</div>
															</div>
															</div>
														</div>
														
														

														<?php
															}
														}		
														else
															echo '<h3 class="ticket-commentaire-titre">0 Commentaire</h3>';
														?>
														<?php
														if($tickets['etat'] == "0"){
															echo '<form action="?&action=post_ticket_commentaire" method="post"><div class="modal-footer">
																		<input type="hidden" name="id" value="'.$tickets['id'].'" /><div class="row">
																		<div class="col-md-12 text-center">';
																		$smileys = getDonnees($bddConnection);
																		for($y = 0; $y < count($smileys['symbole']); $y++)
																		{
																			echo '<a href="javascript:insertAtCaret(\'ticket'.$tickets['id'].'\',\' '.$smileys['symbole'][$y].' \')"><img src="'.$smileys['image'][$y].'" alt="'.$smileys['symbole'][$y].'" title="'.$smileys['symbole'][$y].'" /></a>';
																		}
																		?>
																		<a href="javascript:ajout_text('ticket<?=$tickets['id'];?>', 'Ecrivez ici ce que vous voulez mettre en gras', 'ce texte sera en gras', 'b')" style="text-decoration: none;" title="gras"><i class="fas fa-bold" aria-hidden="true"></i></a>
																		<a href="javascript:ajout_text('ticket<?=$tickets['id'];?>', 'Ecrivez ici ce que vous voulez mettre en italique', 'ce texte sera en italique', 'i')" style="text-decoration: none;" title="italique"><i class="fas fa-italic"></i></a>
																		<a href="javascript:ajout_text('ticket<?=$tickets['id'];?>', 'Ecrivez ici ce que vous voulez mettre en souligné', 'ce texte sera en souligné', 'u')" style="text-decoration: none;" title="souligné"><i class="fas fa-underline"></i></a>
																		<a href="javascript:ajout_text('ticket<?=$tickets['id'];?>', 'Ecrivez ici ce que vous voulez mettre en barré', 'ce texte sera barré', 's')" style="text-decoration: none;" title="barré"><i class="fas fa-strikethrough"></i></a>
																		<a href="javascript:ajout_text('ticket<?=$tickets['id'];?>', 'Ecrivez ici ce que vous voulez mettre en aligné à gauche', 'ce texte sera aligné à gauche', 'left')" style="text-decoration: none" title="aligné à gauche"><i class="fas fa-align-left"></i></a>
																		<a href="javascript:ajout_text('ticket<?=$tickets['id'];?>', 'Ecrivez ici ce que vous voulez mettre en centré', 'ce texte sera centré', 'center')" style="text-decoration: none" title="centré"><i class="fas fa-align-center"></i></a>
																		<a href="javascript:ajout_text('ticket<?=$tickets['id'];?>', 'Ecrivez ici ce que vous voulez mettre en aligné à droite', 'ce texte sera aligné à droite', 'right')" style="text-decoration: none" title="aligné à droite"><i class="fas fa-align-right"></i></a>
																		<a href="javascript:ajout_text('ticket<?=$tickets['id'];?>', 'Ecrivez ici ce que vous voulez mettre en justifié', 'ce texte sera justifié', 'justify')" style="text-decoration: none" title="justifié"><i class="fas fa-align-justify"></i></a>
																		<a href="javascript:ajout_text_complement('ticket<?=$tickets['id'];?>', 'Ecrivez ici l\'adresse de votre lien', 'https://craftmywebsite.fr/forum', 'url', 'Entrez le titre de votre lien', 'CraftMyWebsite')" style="text-decoration: none" title="lien"><i class="fas fa-link"></i></a>
																		<a href="javascript:ajout_text_complement('ticket<?=$tickets['id'];?>', 'Ecrivez ici l\'adresse de votre image', 'https://craftmywebsite.fr/img/cat6.png', 'img', 'Entrez ici le titre de votre image (laisser vide si vous ne voulez pas compléter)', 'Titre')" style="text-decoration: none" title="image"><i class="fas fa-image"></i></a>
																		<a href="javascript:ajout_text_complement('ticket<?=$tickets['id'];?>', 'Ecrivez ici votre texte en couleur', 'Ce texte sera coloré', 'color', 'Entrer le nom de la couleur en anglais ou en hexaécimal avec le  # : http://www.code-couleur.com/', 'red ou #40A497')" style="text-decoration: none" title="couleur"><i class="fas fa-font"></i></a>
																		<a href="javascript:ajout_text_complement('ticket<?=$tickets['id'];?>', 'Ecrivez ici votre message caché', 'contenue du spoiler', 'spoiler', 'Entrer le titre du message caché (si la case est vide le titre sera \'Spoiler\'', 'Spoiler')" style="text-decoration: none" title="spoiler"><i class="fas fa-flag"></i></a>
																		<div class="dropdown">
																			<a href="#" role="button" id="font" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																			 <i class="fas fa-text-height"></i>
																			</a>
																			<div class="dropdown-menu" aria-labelledby="font">
																				<a class="dropdown-item" href="javascript:ajout_text('ticket<?=$tickets['id'];?>', 'Ecrivez ici ce que vous voulez mettre en taille 2', 'ce texte sera en taille 2', 'font=2')"><span style="font-size: 2em;">2</span></a>
																				<a class="dropdown-item" href="javascript:ajout_text('ticket<?=$tickets['id'];?>', 'Ecrivez ici ce que vous voulez mettre en taille 5', 'ce texte sera en taille 5', 'font=5')"><span style="font-size: 5em;">5</span></a>
																			</div>
																		</div>
																	</div><div class="col-md-12">
																	
																		<textarea name="message" id="ticket<?=$tickets['id'];?>" class="form-control" rows="3" cols="60"></textarea>
																		</br></div></div>
																  </div>
																  <button type="submit" class="neo-button neo-green">Commenter</button>
																	</form>
																	<?php 
														} else {
															echo '<div class="modal-footer">
																	<form action="" method="post">
																		<textarea style="text-align: center;"name="message" class="form-control" rows="2" placeholder="Ticket résolu ! Merci de contacter un administrateur pour réouvrir votre ticket." disabled></textarea>
																	</form>
																  </div>';
														}
														?>
													</ul>
												</p>
										</div>
									</div>	 
								</div>
							</div>
					</div>
					<?php if($ticketCommentaires[$tickets['id']][$i]['auteur'] == $_Joueur_['pseudo'] OR $_Joueur_['rang'] == 1 OR $_PGrades_['PermsDefault']['support']['editMemberComm'] == true) {
						for($i = 0; $i < count($ticketCommentaires[$tickets['id']]); $i++) {
							$get_idComm = $bddConnection->prepare('SELECT id FROM cmw_support_commentaires WHERE auteur LIKE :auteur AND id_ticket LIKE :id_ticket');
							$get_idComm->bindParam(':auteur', $ticketCommentaires[$tickets['id']][$i]['auteur']);
							$get_idComm->bindParam(':id_ticket', $tickets['id']);
							$get_idComm->execute();
							$req_idComm = $get_idComm->fetch(); ?>
					<div class="neo-modal " id="editComm-<?php echo $req_idComm['id']; ?>" style="z-index:53;">
						<div class="neo-modal-content neo-animate-zoom"  style="background-color: rgba(51, 51, 51, 0.5);color:#FFF;width:40%">
							<div class="neo-container">
								<span  onclick="document.getElementById('editComm-<?php echo $req_idComm['id']; ?>').style.display='none'" class="neo-button neo-display-topright">&times;</span>
																
									<div class="neo-panel ">
										<h2 style="text-align:center;;">  Edition du commentaire </h2>
											<div class="neo-container"  >
											 <form method="POST" action="?&action=edit_support_commentaire&id_comm=<?php echo $req_idComm['id']; ?>&id_ticket=<?php echo $tickets['id']; ?>&auteur=<?php echo $ticketCommentaires[$tickets['id']][$i]['auteur']; ?>">
											<p>
												<ul class="neo-ul" style="width:100%;">
													<textarea name="editMessage" class="form-control" rows="3" style="resize: none;"><?php echo $ticketCommentaires[$tickets['id']][$i]['message']; ?></textarea>
													 <button type="submit" class="neo-button neo-green">Valider !</button>
												</ul>
											</p>
											</form>
										</div>
									</div>	 
								</div>
							</div>
					</div>

				    <?php }
				       }
				    }
					$j++; } ?>
					</tbody>
				</table>
			</div>
			<div class="card-footer">
				<?php
					if(!isset($_Joueur_)) 
						echo '<a data-toggle="modal" data-target="#ConnectionSlide" class="btn btn-warning btn-block" ><span class="glyphicon glyphicon-user"></span> Se connecter pour ouvrir un ticket</a>'; 
					else 
					{
				?>
				<a data-toggle="collapse" data-parent="#ticketCree" href="#ticketCree" class="neo-button neo-green"><i class="fa fa-pencil-square-o"></i> Poster un ticket !</a>
				</div>
		  </div>

				<div class="collapse neo-center" id="ticketCree">
					<div class="card">
						<form action="" method="post" onSubmit="envoie_ticket();">
							<div class="card-block">
								<div class="row">
									<div class="col-sm-8">
										<div class="form-group">
											<label class="control-label">Sujet</label>
											<div class="form-group">
												<div class="input-group">
													<div class="input-group-addon"><i class="fas fa-eye"></i></div>
													<input type="text" id="titre_ticket" class="form-control" name="titre" placeholder="Sujet">
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="exampleSelect1">Visibilité</label>
											<select class="form-control" id="vu_ticket" name="ticketDisplay">
												<option value="0">Publique</option>
												<option value="1">Privée</option>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-12 text-center">
									<?php 
										$smileys = getDonnees($bddConnection);
										for($i = 0; $i < count($smileys['symbole']); $i++)
										{
											echo '<a href="javascript:insertAtCaret(\'message\',\' '.$smileys['symbole'][$i].' \')"><img src="'.$smileys['image'][$i].'" alt="'.$smileys['symbole'][$i].'" title="'.$smileys['symbole'][$i].'" /></a>';
										}
									?>
									<a href="javascript:ajout_text('message', 'Ecrivez ici ce que vous voulez mettre en gras', 'ce texte sera en gras', 'b')" style="text-decoration: none;" title="gras"><i class="fas fa-bold" aria-hidden="true"></i></a>
									<a href="javascript:ajout_text('message', 'Ecrivez ici ce que vous voulez mettre en italique', 'ce texte sera en italique', 'i')" style="text-decoration: none;" title="italique"><i class="fas fa-italic"></i></a>
									<a href="javascript:ajout_text('message', 'Ecrivez ici ce que vous voulez mettre en souligné', 'ce texte sera en souligné', 'u')" style="text-decoration: none;" title="souligné"><i class="fas fa-underline"></i></a>
									<a href="javascript:ajout_text('message', 'Ecrivez ici ce que vous voulez mettre en barré', 'ce texte sera barré', 's')" style="text-decoration: none;" title="barré"><i class="fas fa-strikethrough"></i></a>
									<a href="javascript:ajout_text('message', 'Ecrivez ici ce que vous voulez mettre en aligné à gauche', 'ce texte sera aligné à gauche', 'left')" style="text-decoration: none" title="aligné à gauche"><i class="fas fa-align-left"></i></a>
									<a href="javascript:ajout_text('message', 'Ecrivez ici ce que vous voulez mettre en centré', 'ce texte sera centré', 'center')" style="text-decoration: none" title="centré"><i class="fas fa-align-center"></i></a>
									<a href="javascript:ajout_text('message', 'Ecrivez ici ce que vous voulez mettre en aligné à droite', 'ce texte sera aligné à droite', 'right')" style="text-decoration: none" title="aligné à droite"><i class="fas fa-align-right"></i></a>
									<a href="javascript:ajout_text('message', 'Ecrivez ici ce que vous voulez mettre en justifié', 'ce texte sera justifié', 'justify')" style="text-decoration: none" title="justifié"><i class="fas fa-align-justify"></i></a>
									<a href="javascript:ajout_text_complement('contenue', 'Ecrivez ici l\'adresse de votre lien', 'https://craftmywebsite.fr/forum', 'url', 'Entrez le titre de votre lien', 'CraftMyWebsite')" style="text-decoration: none" title="lien"><i class="fas fa-link"></i></a>
									<a href="javascript:ajout_text_complement('contenue', 'Ecrivez ici l\'adresse de votre image', 'https://craftmywebsite.fr/img/cat6.png', 'img', 'Entrez ici le titre de votre image (laisser vide si vous ne voulez pas compléter', 'Titre')" style="text-decoration: none" title="image"><i class="fas fa-image"></i></a>
									<a href="javascript:ajout_text_complement('contenue', 'Ecrivez ici votre texte en couleur', 'Ce texte sera coloré', 'color', 'Entrer le nom de la couleur en anglais ou en hexaécimal avec le  # : http://www.code-couleur.com/', 'red ou #40A497')" style="text-decoration: none" title="couleur"><i class="fas fa-font"></i></a>
									<a href="javascript:ajout_text_complement('contenue', 'Ecrivez ici votre message caché', 'contenue du spoiler', 'spoiler', 'Entrer le titre du message caché (si la case est vide le titre sera \'Spoiler\'', 'Spoiler')" style="text-decoration: none" title="spoiler"><i class="fas fa-flag"></i></a>
									<div class="neo-dropdown-hover neo-margin-right-1">
					 <button class="neo-button fadeInDown"><i class="fas fa-text-height"></i></button>
						<div class="neo-dropdown-content neo-bar-block neo-border">
							<a class="neo-bar-item hvr-forward neo-button" href="javascript:ajout_text('contenue', 'Ecrivez ici ce que vous voulez mettre en taille 2', 'ce texte sera en taille 2', 'font=2')"><span style="font-size: 2em;">2</span></a>
							<a class="neo-bar-item hvr-forward neo-button" href="javascript:ajout_text('contenue', 'Ecrivez ici ce que vous voulez mettre en taille 5', 'ce texte sera en taille 5', 'font=5')"><span style="font-size: 5em;">5</span></a>
							
					</div>
				</div>
									<!--<a href="javascript:ajout_text('message', 'Ecrivez ici ce que vous voulez mettre en rouge', 'ce texte sera en rouge', 'color=red')" class="redactor_color_link" style="background-color: rgb(255, 0, 0);"></a>-->
								</div>
									<label for="message_ticket">Description détaillée</label>
									<textarea class="form-control" id="message_ticket" name="message" placeholder="Description détaillée de votre problème" rows="3"></textarea>
								</div>
							</div>
							<div class="card-footer">
								<button type="submit" class="neo-button neo-green champ valider pull-right">Envoyer</button>
							</div>
						</form>
					</div>
				</div>
				<?php } ?>
<script>
var nbEnvoie = 0
	function envoie_ticket()
	{
		if(nbEnvoie>0)
			return false;
		else
		{
			var data_titre = document.getElementById("titre_ticket").value;
			var data_message = document.getElementById("message_ticket").value;
			var data_vu = document.getElementById("vu_ticket").value;
			$.ajax({
				url  : 'index.php?action=post_ticket',
				type : 'POST',
				data : 'titre=' + data_titre + '&message=' + data_message + '&ticketDisplay=' + data_vu,
				dataType: 'html'
			});
			nbEnvoie++;
			return true;
		}
	}
</script>
	</div>
</div>
