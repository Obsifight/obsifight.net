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
  'profile.menu.appearence' => 'Apparance',
  'profile.menu.security' => 'Sécurité',
  'profile.menu.spendings' => 'Dépenses',
  'profile.menu.socials' => 'Social',
  'profile.personnals.details' => 'Détails personnels',
  'money' => 'Points',
  'votes' => 'Votes',
  'rewards_waited' => 'Récompenses en attentes'
];
