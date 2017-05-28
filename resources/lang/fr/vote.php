<?php
return [
  'title' => 'Voter',

  'step.one.title' => 'Connexion',
  'step.one.content' => 'Entrez votre pseudo',
  'step.two.title' => 'Voter',
  'step.two.content' => 'Votez sur RPG-Paradize',
  'step.three.title' => 'Vérification',
  'step.three.content' => 'Prouvez que vous avez voté',
  'step.four.title' => 'Récompenses',
  'step.four.content' => 'Récupérez votre gain',

  'step.one.content.input.label' => 'Pseudo',
  'step.one.content.input.btn' => "Passer à l'étape suivante",
  'step.one.error.user' => 'Aucun utilisateur ne correspond à ce pseudo.',
  'step.one.error.already' => 'Vous avez déjà voté ! Vous devez encore attendre :hours heures, :minutes minutes et :seconds secondes.',
  'step.one.success' => 'Vous vous êtes bien connecté !',

  'step.two.content.link' => 'Voter sur RPG-Paradize',

  'step.three.content.input.label' => 'OUT',
  'step.three.content.input.placeholder' => 'Nombre de clics sortants',
  'step.three.content.help' => "Le nombre de clics sortant doit être récupéré sur RPG-Paradize après votre vote. Si vous avez besoin d'aide vous pouvez voir le tutoriel disponible <a href=\":help_link\">ici</a>.",
  'step.three.content.input.btn' => "Passer à l'étape suivante",
  'step.three.error.out' => 'Le nombre de clics sortant est invalide.',
  'step.three.success' => 'Vous avez bien validé votre vote !',

  'step.four.content.btn.now' => 'Recevoir mes récompenses maintenant',
  'step.four.content.btn.now.hover' => 'Les recevoir sur le serveur',
  'step.four.content.btn.after' => 'Recevoir mes récompenses plus tard',
  'step.four.content.btn.after.hover' => 'Les stocker',
  'step.four.success.now' => 'Vous avez bien voté ! Vous avez reçu :money_earned points et la récompense "<em>:reward</em>" vous a été donnée en jeu !',
  'step.four.success.after' => 'Vous avez bien voté ! Vous avez reçu :money_earned points et la récompense "<em>:reward</em>" a été stockée sur votre profil !',

  'rewards.get.success' => 'Vous avez bien reçu la récompense "<em>:reward</em>" en jeu !',

  'step.error.unauthorized' => 'Vous devez être connecté pour procéder aux étapes suivantes.',
  'step.error.valid' => 'Vous devez avoir validé le vote avec de récupérer vos récompenses.',

  'reset.kit.get' => 'Vous avez été parmis les meilleurs voteurs le mois dernier <em>(position: :position)</em> ! Vous pouvez donc recevoir un kit pour vous récompenser en <a href=":url">cliquant ici</a>.',
  'reset.kit.get.success' => 'Vous avez bien reçu votre kit voteur ! Merci et bon jeu !'
];
