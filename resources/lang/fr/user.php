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

  // PROFILE
  'profile.confirmed.title' => 'Vous venez de vous inscrire',
  'profile.confirmed.description' => "Vous devez confirmer votre email pour pouvoir utiliser complètement votre compte.<a class=\"block-right\" href=\":url\">Renvoyer l'email</a>"
];
