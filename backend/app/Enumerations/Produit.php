<?php

namespace App\Enumerations;

class Produit extends Enum
{
    const PRODUIT1 = 'PRODUIT1';

    const PRODUIT2 = 'PRODUIT2';

    const PRODUIT3 = 'PRODUIT3';

    const PRODUIT4 = 'PRODUIT4';


    const PRODUITS = [
        ['produit' => self::PRODUIT1, 'gamme' => Gamme::GAMME1, 'gamme_id' => 1, 'fournisseur' => Fournisseur::FOURNISSEUR1, 'fournisseur_id' => 1],
        ['produit' => self::PRODUIT2, 'gamme' => Gamme::GAMME1, 'gamme_id' => 1, 'fournisseur' => Fournisseur::FOURNISSEUR1, 'fournisseur_id' => 1],

        ['produit' => self::PRODUIT3, 'gamme' => Gamme::GAMME2, 'gamme_id' => 2, 'fournisseur' => Fournisseur::FOURNISSEUR1, 'fournisseur_id' => 1],
        ['produit' => self::PRODUIT4, 'gamme' => Gamme::GAMME2, 'gamme_id' => 2, 'fournisseur' => Fournisseur::FOURNISSEUR2, 'fournisseur_id' => 2],
    ];
}
