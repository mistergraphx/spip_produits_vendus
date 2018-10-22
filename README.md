**Ce plugin n'est plus maintenu il peut désormait être remplacé par le plugin Stocks  https://contrib.spip.net/Stocks**

# Produits vendus

Ou produits uniques …
ajoute le statut vendu sur l'objet produit,
 dans le cas de sites n'ayant pas de stock sur leurs produits
comme un site d'annonces ou une friperie en ligne.

l'internaute ne peut pas ajouter plusieurs fois le même produit au panier.


## Documentations & exemples :

* https://contrib.spip.net/Gestion-des-Statuts
* http://blog.roxing.net/spip-ajouter-des-statuts-publiable-sur-un-objet


## Utilisation

### Affichage du boutn ajouter au panier si l'article n'est pas vendu

```html
    <BOUCLE_test_produit_vendu(CONDITION){si #STATUT|!={vendu}}>

        [<div class="ajouter_panier">(#BOUTON_ACTION{<:paniers:action_ajouter:>,
            #URL_ACTION_AUTEUR{
                remplir_panier,
                produit-#ID_PRODUIT-1,
                #SELF|ancre_url{popup_panier} }
        })</div>]

    </BOUCLE_test_produit_vendu>

            <div class="btn btn-tertiary vendu"><:produits_vendus:texte_info_vendu:></div>

    <//B_test_produit_vendu>
```


## ToDo


- [] Fatal error: Call to undefined function editer_produit_heritage()
in /spip_produits_vendus/action/editer_produit.php on line 160.  
idem pour produit_insserer : Question pourquoi la fonction produit_editer_dist qui est sensser surcharger la fonction d'origine
ne charge pas les fonctions du plugin produit et oblige a dupliquer tout le code ?  

	* utiliser charger_fonction

- [] vérifier le fonctionnement du passage au statut payé/vendu sur les retours paypal/bank

## Travaux

1.0.6 :

*	on empèche l'ajout produits identiques au panier

1.0.4 :

*	On copie la fonction  la fonction produit_insserer
pour eviter une erreur php, la surcharge de l'action `_dist` ne charge pas la fonction du plugin produit

1.0.2 :

*   Utilisation de la pipeline post_edition pour changer le statut du produit
a vendu quand une commande passe de en_attente à paye.

1.0.1 :

* Ajout a la description de l'objet produit le statut : vendu
* Ajout de la puce identifiant le statut
* Surcharge de la fonction produit_instituer_dist pour invalider le cache quand le statut passe a vendu
