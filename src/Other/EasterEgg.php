<?php

namespace App\Other;

class EasterEgg{
    public function __construct() {
        
    }
    
    public function getPhrase() {
        $phrases = [
            "Un cadavre bien caché est un problème en moins. Un cadavre jamais découvert ? C’est de l’art.",
            "Ne te demande pas si tu peux le faire. Demande-toi juste combien de sacs-poubelle il te faut.",
            "Le silence est d’or. Surtout quand il vient d’un témoin gênant.",
            "Enterrer un secret, c'est bien. Mais lester un corps, c'est mieux.",
            "La vraie question n’est pas ‘Pourquoi ?’, mais ‘Où cacher les preuves ?’",
            "On dit que le crime parfait n’existe pas… Mais ceux qui l’ont réussi ne sont plus là pour témoigner.",
            "Ne laisse jamais un problème en suspens… à moins que ce soit avec une corde.",
            "Si quelqu’un t’énerve, souviens-toi : la patience est une vertu… mais l’accident domestique est une solution.",
            "Un bon plan a toujours une issue de secours. Un excellent plan s’assure que personne d’autre ne sorte.",
            "Les empreintes digitales, c’est surfait. Un bon bain d’acide, c’est mieux.",
            "Pourquoi courir quand on peut s’arranger pour que quelqu’un d’autre soit suspecté à ta place ?",
            "On dit que le crime ne paie pas… mais un héritage bien placé, ça change la donne.",
            "Il y a des millions de façons de mourir… Et toi, tu n’as besoin d’en choisir qu’une.",
            "La confiance est essentielle… jusqu’à ce que ton complice devienne un témoin.",
            "Si personne ne te voit commettre le crime, l’as-tu vraiment commis ?",
            "Les détectives sont perspicaces… mais rarement aussi persévérants qu’un bon feu de cheminée.",
            "Un problème enterré reste un problème. À moins de creuser assez profond.",
            "Chaque meurtre a son mobile… mais les plus élégants n’en laissent aucun.",
            "N’oublie jamais : une scène de crime impeccable, c’est comme une œuvre d’art… Ça demande du talent.",
            "L’erreur de débutant ? Laisser un témoin. L’erreur d’expert ? En sous-estimer un."
        ];
    
        return $phrases[array_rand($phrases)];
    }
}