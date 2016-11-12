<?php
/**
 * Utilisations de pipelines par Produits Vendus
 *
 * @plugin     Produits Vendus
 * @copyright  2016
 * @author     Mist. GraphX
 * @licence    GNU/GPL
 * @package    SPIP\Produits_vendus\Pipelines
 */

if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * post_edition
 *
 * passe un produit au statut vendu QUAND la commande passe du statut attente a payee
 *
 * @see http://contrib.spip.net/Commandes-4527#forum478302
 * @see http://programmer.spip.net/et-les-autres
 * 
*/
function produits_vendus_post_edition($flux){
    
   
    // Apres COMMANDE :
    // quand la commande passe du statut=attente a statut=paye
    if (
        $flux['args']['action'] == 'instituer'
        AND $flux['args']['table'] == 'spip_commandes'
        AND ($id_commande = intval($flux['args']['id_objet'])) > 0
        AND ($statut_nouveau = $flux['data']['statut']) == 'paye'
        AND ($statut_ancien = $flux['args']['statut_ancien']) == 'attente'
    ){
        // Informations concernant la commande
        $id_auteur= sql_getfetsel('id_auteur', 'spip_commandes', 'id_commande='.intval($id_commande));
        
        // retrouver les objets correspondants a la commande dans spip_commandes_details
        if (
            $objets = sql_allfetsel('objet,id_objet', 'spip_commandes_details', 'id_commande='.intval($id_commande))
            AND is_array($objets)
            AND count($objets)
        ){
            
            include_spip('action/editer_objet');
                
            foreach($objets as $v) {
                // Si un objet produit
                // fait partit des details la commande
                if($v['objet']=='produit'){
                    
                    $objet = $v['objet'];
                    $id_objet = intval($v['id_objet']);
                   
                    
                    
                    objet_modifier($objet, $id_objet, array('statut'=>'vendu'));
                    spip_log(' Objet: '.$objet.' ('.$id_objet.') - Commande : '.$id_commande,'produits');

                }                            
            }
        }
    }
    
    return $flux;
}