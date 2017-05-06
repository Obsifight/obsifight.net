<?php
return [
  'signup' => "S'inscrire",
  'login' => 'Se connecter',
  'logout' => 'Se déconnecter',
  'login.two_factor_auth' => 'Confirmer la connexion (Double authentification)',

  // FIELDS
  'field.username' => 'Pseudo',
  'field.password' => 'Mot de passe',
  'field.email' => 'Email',
  'field.remember_me' => 'Se souvenir de moi',
  'field.two_factor_auth_code' => 'Code de vérification',
  'field.two_factor_auth_code.placeholder' => "Vous trouvez ce code sur l'application mobile comme Google Authentificator",

  'password.forgot' => 'Mot de passe oublié',
  'password.forgot.subtitle' => 'Retrouvez votre compte depuis votre email',
  'password.forgot.send' => "Envoyer l'email de rénitialisation",
  'password.forgot.user.notfound' => "Aucun utilisateur n'a été trouvé avec cet email",
  'password.forgot.email.subject' => 'Rénitialisation du mot de passe',
  'password.forgot.email.title' => 'Bonjour <strong>:username</strong> !',
  'password.forgot.email.content' => 'Tu viens de demander une rénitialisation de ton mot de passe, pour procéder à celle-ci, il te suffit pour cela de cliquer sur le lien ci-dessous',
  'password.forgot.success' => "L'email de rénitialisation a bien été envoyé ! Clique sur le lien qui est fourni pour procéder au changement de ton mot de passe.",

  'password.reset' => 'Rénitialisation du mot de passe',
  'password.reset.action' => 'Rénitialiser mon mot de passe',
  'password.reset.success' => 'Votre mot de passe a bien été modifié !',

  'password.edit.success' => 'Vous avez bien modifié votre mot de passe !',

  'email.edit.request.already' => "Vous avez déjà une demande de changement d'email en cours.",
  'email.edit.request.success' => 'Votre demande a bien été enregistrée et sera traitée rapidement par notre équipe !',

  // LOGIN
  'login.error.blocked' => 'Vous êtes temporairement bloqué pour avoir tenté trop de fois de vous connecter avec des identifiants incorrects.',
  'login.error.notfound' => "Aucun joueur n'a été trouvé avec ce pseudo.",
  'login.error.credentials' => 'Le mot de passe de ce compte est invalide.',
  'login.error.two_factor_auth' => 'Le code est invalide ou a expiré.',
  'login.success' => 'Vous vous êtes bien connecté !',

  // SIGNUP
  'signup.join_now' => 'Rejoignez-nous dès maintenant !',
  'signup.field.legal' => "J'accepte le <a href=\":link\">réglement</a> d'ObsiFight",
  'signup.error.legal' => 'Vous devez accepter le réglement avant de vous inscrire.',
  'signup.error.captcha' => 'Vous devez valider le captcha pour vous inscrire.',
  'signup.error.username' => 'Le pseudo doit être alpha-numérique entre 2 et 16 charactères.',
  'signup.error.username.taken' => 'Ce pseudo est déjà utilisé par un autre joueur.',
  'signup.error.email' => 'Cet email est invalide.',
  'signup.error.email.taken' => 'Cet email est déjà utilisé par un autre joueur.',
  'signup.error.passwords' => 'Les mots de passe ne sont pas identiques.',
  'signup.success' => 'Vous avez bien été inscrit sur notre serveur !',
  'signup.email.subject' => "Confirmation de l'email",
  'signup.email.confirmed' => 'Votre email a bien été confirmé !',
  'signup.email.confirmation.sended' => "L'email de confirmation a bien été renvoyé !",
  'signup.email.title' => 'Bienvenue à toi <strong>:username</strong> !',
  'signup.email.content' => 'Nous te remercions de rejoindre notre serveur ! Avant de commencer à jouer il est préférable que tu confirmes cet email. Il te suffit pour cela de cliquer sur le lien ci-dessous',

  // PROFILE
  'profile.confirmed.title' => 'Vous venez de vous inscrire',
  'profile.confirmed.description' => "Vous devez confirmer votre email pour pouvoir utiliser complètement votre compte.<a class=\"block-right\" href=\":url\">Renvoyer l'email</a>",
  'profile.created.string' => 'Inscrit :date',
  'profile.menu.infos' => 'Informations',
  'profile.menu.appearence' => 'Apparence',
  'profile.menu.security' => 'Sécurité',
  'profile.menu.spendings' => 'Dépenses',
  'profile.menu.socials' => 'Social',
  'profile.personnals.details' => 'Détails personnels',
  'profile.username.edit' => 'Éditer mon pseudo',
  'profile.email.edit' => 'Éditer mon email',
  'profile.email.edit.reason' => 'Raison du changement',
  'profile.email.edit.subtitle' => 'Soumettez-nous votre demande de changement d\'email',
  'profile.email.edit.send' => 'Soumettre ma demande',
  'profile.password.edit' => 'Éditer mon mot de passe',
  'profile.password.edit.placeholder' => 'Entrez un nouveau de mot de passe',
  'profile.edit.username.error.purchase' => "Vous devez acheter l'article <em>Changement de pseudo</em> pour pouvoir le changer !",
  'profile.edit.username.error.two_weeks' => 'Vous devez attendre 2 semaines entre chaque changement de pseudo !',
  'profile.edit.username.error.two_times' => 'Vous ne pouvez pas modifier votre pseudo plus de 2 fois !',
  'profile.edit.username.error.password' => 'Le mot de passe de votre compte ne correspond pas !',
  'profile.edit.username.success' => 'Votre pseudo a bien été modifié !',
  'profile.edit.username.send' => 'Choississez votre nouveau pseudo',
  'profile.edit.username.subtitle' => 'Changer mon pseudo',
  'profile.edit.username.warning' => 'Vous ne pouvez changer de pseudo que <strong>2 fois</strong> et chaque changement doit être espacé de <strong>deux semaines</strong>.<br>Une fois le changement effectué il vous sera <strong>impossible de récupérer votre ancien pseudo</strong>.<br>Le changement de pseudo doit être <strong>acheté sur la boutique</strong>.',

  'profile.transfer.money' => 'Transférer des points',
  'profile.transfer.money.subtitle' => 'Envoyez vos points à un joueur',
  'profile.transfer.money.error.no_enough' => "Vous n'avez pas assez de points pour pouvoir effectuer ce transfert !",
  'profile.transfer.money.error.unknown_user' => 'Aucun utilisateur ne correspond à ce pseudo.',
  'profile.transfer.money.error.himself' => 'Vous ne pouvez pas effectuer un transfert de points à vous-même.',
  'profile.transfer.money.error.amount' => 'Le montant est invalide.',
  'profile.transfer.money.error.limit.ban' => 'Vous êtes bannis, vous ne pouvez pas transférer vos points.',
  'profile.transfer.money.error.limit.times' => "Vous avez déjà transféré des points plus de 3 fois aujourd'hui.",
  'profile.transfer.money.error.limit.day' => "Vous avez déjà dépassé la limite des 2 250 points par transfert quotidien.",
  'profile.transfer.money.success' => 'Vous avez bien envoyé :money points à :username !',
  'profile.transfer.money.field.to' => 'Pseudo du joueur',
  'profile.transfer.money.field.amount' => 'Montant du transfert',
  'profile.transfer.money.send' => 'Envoyer les points',

  'profile.upload.error.no_file' => "Vous n'avez pas sélectionné de fichier.",
  'profile.upload.error.file.type' => 'Le type de fichier que vous avez envoyé est invalide.',
  'profile.upload.success' => 'Le fichier a bien été envoyé sur nos serveurs !',

  'money' => 'Points',
  'votes' => 'Votes',
  'rewards_waited' => 'Récompenses en attentes',

  // ROLES
  'role.restricted' => 'Compte restreint',
  'role.restricted.description' => "Votre compte est temporairement restreint. Vous avez perdu certaines permissions vous empêchant d'utiliser pleinement votre compte."
];
