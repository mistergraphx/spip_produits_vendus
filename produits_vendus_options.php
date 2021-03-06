<?php
/**
 * Fonctions utiles au plugin Produits Vendus
 *
 * @plugin     Produits Vendus
 * @copyright  2016
 * @author     Mist. GraphX
 * @licence    GNU/GPL
 * @package    SPIP\Produits_vendus\Fonctions
 */

if (!defined('_ECRIRE_INC_VERSION')) return;



/**
 * Remplir un panier avec un objet quelconque
 * surcharge de la fonction _dist du plugin paniers
 * 
 * @param string $arg
 */
function action_remplir_panier($arg=null, $retour = null) {
	if (is_null($arg)){
		$securiser_action = charger_fonction('securiser_action', 'inc');
		$arg = $securiser_action();
	}

	// On récupère les infos de l'argument
	@list($objet, $id_objet, $quantite, $negatif) = explode('-', $arg);

	$paniers_arrondir_quantite = charger_fonction('paniers_arrondir_quantite', 'inc');
	if (!isset($quantite) or is_null($quantite) or !strlen($quantite)) {
		$quantite = 1;
	}

	$quantite = $paniers_arrondir_quantite($quantite, $objet, $id_objet);

	// si la quantite est nulle, on ne fait rien
	if ($quantite<=0) {
		return;
	}

	// retirer un objet du panier
	if(isset($negatif)) {
		$quantite = $paniers_arrondir_quantite(-1 * $quantite, $objet, $id_objet);
	}
		
	// Il faut cherche le panier du visiteur en cours
	include_spip('inc/paniers');
	$id_panier_base = 0;
	if ($id_panier = paniers_id_panier_encours()){
		//est-ce que le panier est bien en base
		$id_panier_base = intval(sql_getfetsel(
				'id_panier',
				'spip_paniers',
				array(
					'id_panier = '.intval($id_panier),
					'statut = '.sql_quote('encours')
				)
		));
	}
	
	// S'il n'y a pas de panier, on le crée
	if (!$id_panier OR !$id_panier_base){
		$id_panier = paniers_creer_panier();
	}

	// On ne fait que s'il y a bien un panier existant et un objet valable
	if ($id_panier > 0 and $objet and $id_objet) {
		// Il faut maintenant chercher si cet objet précis est *déjà* dans le panier
		$quantite_deja = sql_getfetsel(
			'quantite',
			'spip_paniers_liens',
			array(
				'id_panier = '.intval($id_panier),
				'objet = '.sql_quote($objet),
				'id_objet = '.intval($id_objet)
			)
		);

		$quantite_deja = $paniers_arrondir_quantite($quantite_deja, $objet, $id_objet);
		
		// Si on a déjà une quantité, on ne fait rien
		if ($quantite_deja > 0){

			return false;
		}
		// Sinon on crée le lien
		else {
			sql_insertq(
				'spip_paniers_liens',
				array(
					'id_panier' => $id_panier,
					'objet' => $objet,
					'id_objet' => $id_objet,
					'quantite' => $quantite
				)
			);
		}
		
		// Mais dans tous les cas on met la date du panier à jour
		sql_updateq(
			'spip_paniers',
			array('date'=>date('Y-m-d H:i:s')),
			'id_panier = '.intval($id_panier)
		);
	}

	// On vide le cache de l'objet sur lequel on vient de travailler.
	include_spip('inc/invalideur');
	suivre_invalideur("id='$objet/$id_objet'");

}